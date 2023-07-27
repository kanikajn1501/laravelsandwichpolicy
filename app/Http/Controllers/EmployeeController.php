<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\Employees;
use App\Models\EmployeeAttendance;
use Exception;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\ModelNotFoundException;  

class EmployeeController extends Controller
{
    public function __construct() {
        $this->emp_model = new Employees();
        $this->emp_attendance_model = new EmployeeAttendance();
    }
    public function index(){
        try{
            //$employees = DB::select("select id,name from employees");
            $employees = $this->emp_model->getEmployees();
            if(!empty($employees)){
                foreach($employees as $employee){
                    $employee_attendance = $this->emp_attendance_model->getEmployeesAttendance($employee->id);
                    $employee->attendance = $employee_attendance;
                    //dd($employee_attendance);
                    $today = Carbon::createFromFormat('Y-m-d H:i:s',Carbon::now());
                    if(!empty($employee_attendance)){
                        $minDate = '';
                        $count=0;
                        $days = [];
                        $attendanceId = '';
                        foreach($employee_attendance as $attendance){
                           
                            if($count == 0){
                                $count++;
                                $minDate = $attendance->leave_date; 
                                if($minDate<$today)
                                    $attendanceId .= $attendance->id.',';
                            }else{
                                $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $minDate.' 00:00:00');
                                $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->leave_date.' 00:00:00');
                                $minDate = $attendance->leave_date; 
                                $calculatedDate =  $date2->diffInDays($date1);
                                
                                if($calculatedDate==1){
                                    $count++;
                                    if($date1<$today && $date2<$today){
                                        $attendanceId .= $attendance->id.',';
                                    }
                                    
                                }else{
                                    $count = 1; // in case if we have working day in between
                                    //now we will update all related emp_id old leaves marked as inactive so that in next scenario we'll not get it
                                    //we can do this by cron job as well as a second option
                                    $attendanceId = trim($attendanceId,',');
                                    $this->emp_attendance_model->updateLeaveInactive(array('emp_id'=> $attendance->emp_id,'attendance_id' => $attendanceId,'active' =>'1'));
                                }
                            }
                            $days[] = $attendance->leave_day.' ('.$attendance->leave_date.')';
                            
                            
                        }
                        
                        $employee->sandwichStatus = ($count<=10)?(($count == 0)?'No Leave Taken':'Sandwich Not Applied'):'Sandwich Applied';
                        $employee->bgColor = ($count<=10)?(($count == 0)?'yellow':'pink'):'lightgreen';
                        $employee->leaveDays =$days;
                    }
                }
            }
           
            return view('welcome',['employees' => $employees]);
        }catch(ModelNotFoundException $exception){
            return view('modelnotfound');
        }
        catch(InvalidFormatException $exception){
            return view('invalidFormat');
        }
        catch(Exception $exception){
           dd(get_class($exception));
            return view('error');
        }
          
          
    }

    public function apply_leave(Request $request){
        
        return view('create',array('empId' => $request->id));
    }

  public function approvedLeave(Request $request) {
   
    $validated = $request->validate([
        'startDate' => 'required|date_format:Y-m-d|before_or_equal:endDate',
        'endDate' => 'required|date_format:Y-m-d|after_or_equal:startDate',
    ]);
   
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $today = Carbon::today();

    $startDate = new Carbon($startDate); 
    $endDate = new Carbon($endDate); 
    
    if($startDate < $today){
        return redirect('/')->with('error','Previous Start date can not be selected');
    }
    if($endDate < $today){
        return redirect('/')->with('error','Previous End date can not be selected');
    }

    $allDates = array(); 
    while ($startDate->lte($endDate)){
         $leaveDay = $startDate->format('l');
         $leaveDate = $startDate->toDateString(); 
         $startDate->addDay(); 
         $checkAlready = $this->emp_attendance_model->checkAlreadyExist(array('emp_id'=>$request->empId,'leave_date' => $leaveDate));
         //if($request->empId==3){print_r(JSON_decode($checkAlready));die;}
         if(!empty(JSON_decode($checkAlready))){
            return redirect('/')->with('error','You can not apply for the dates alreday applied.');
            }
         $this->emp_model->submitLeave(array('leaveDate'=> $leaveDate,'leaveDay' => $leaveDay,'empId' =>$request->empId));
         
        
    } 
    return redirect('/')->with('success', 'Leave Applied Successfully!');
 }
}
