<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Employees extends Model
{

    use HasFactory;
    protected $table = 'employees';
    protected $fillable = ['name','active'];

    public function getEmployees(){
        
        //$employees = DB::select('select id,name,active,created_at,t.emp_id,t.leave_days from employees left join (select emp_id,group_concat(leave_day) as leave_days from  emp_attendance group by emp_id) as t on employees.id = t.emp_id');
         $employees = DB::table('employees')->where('active', '1')->get();
        //select id,name,t.emp_id,t.leave_days from employees left join (select emp_id,group_concat(leave_day) as leave_days from  emp_attendance group by emp_id) as t on employees.id = t.emp_id;
        return $employees;
        
    }

    public function submitLeave($data){
        if(!$data) return false;
        
        DB::table('emp_attendance')->upsert(
            [
                ['leave_date' => $data['leaveDate'], 'leave_day' => $data['leaveDay'], 'emp_id' => $data['empId'],'created_at' => Carbon::now()]
            ],
            ['leave_date', 'emp_id'],
            ['updated_at' => Carbon::now()]
        );
    }
   
}
