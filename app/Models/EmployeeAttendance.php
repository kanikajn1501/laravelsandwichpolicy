<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class employeeAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['leave_date','leave_day','emp_id','active','created_at'];
    public function getEmployeesAttendance($id){
        
        $employee_attendance = DB::table('emp_attendance')->where([['emp_id' ,'=', $id],['active','=','1']])->get();
        return $employee_attendance;
        
    }

    public function checkAlreadyExist($data){
        if(!$data) return false;
         $checkAlready = DB::table('emp_attendance')->where('emp_id', '=', $data['emp_id'])->where('leave_date', $data['leave_date'])->get();
        return $checkAlready;
        
    }
    public function updateLeaveInactive($data){
        if(!$data) return false;
        $attendanceArray = explode(',',$data['attendance_id']);
        
        DB::table('emp_attendance')->
        where('emp_id', '=', $data['emp_id'])
        ->where('active', '=', $data['active'])
        ->whereIN('id',$attendanceArray)->update(array('active' => '0')); 
    }
}
