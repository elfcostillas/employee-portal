<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use App\Models\Me;
use Illuminate\Support\Facades\Auth;

class FTPRepository
{
    //

    public function __construct()
    {

    }

    public function mainQuery(){
        return DB::table("ftp")->join('users','ftp.requested_by','=','users.id')
         ->select(DB::raw("users.biometric_id,users.name,
                ftp.id,
                ftp.ftp_date,
                ftp.time_in,
                ftp.time_out,
                ftp.overtime_in,
                ftp.overtime_out,
                ftp.ftp_state,
                ftp.ftp_remarks,
                ftp.requested_by,
                ftp.requested_on,
                ftp.sup_apporval_by,
                ftp.sup_apporval_on,
                ftp.sup_approval_resp,
                ftp.ftp_type,
                ftp.ftp_status,
                ftp.remarks,
                ftp.hr_received,
                ftp.hr_received_by,
                ftp.hr_received_on,
                ftp.is_canceled,
                ftp.is_deleted,
                ftp.manager_approval_by,
                ftp.manager_approval_on,
                ftp.manager_approval_resp,
                ftp.div_manager_approval_by,
                ftp.div_manager_approval_on,
                ftp.div_manager_approval_resp
            "));
    }

    public function myFTP()
    {
        
        return $this->mainQuery()
            ->where('requested_by',Auth::user()->id);
    }

    public function find($id)
    {
      
        return $this->mainQuery()
           
            ->where('ftp.id',$id)->first();
    }

    public function my_level()
    {
        $me = new Me();

        return $me->my_empLevel();

        // division_id,emp_level
    }

    public function getPendingFTPforApproval()
    {
        $me = new Me();

        $emp_level = $me->my_empLevel();

        // 4 sup 3 dept man 2 div man
        
        $co_dept_lower_than_me = DB::connection('hris')->table('employees')
        ->where('emp_level','>',$emp_level)
        ->where('exit_status',1)
        ->select('biometric_id');
        
        switch($emp_level){
            case 4 :  case '4' :
                $co_dept_lower_than_me = $co_dept_lower_than_me ->where('dept_id',$me->my_dept())->get();
             
                break;
            
            case 3 :  case '3' :
               
                $co_dept_lower_than_me = $co_dept_lower_than_me->where('dept_id',$me->my_dept())->get();
              
                break;

            case 2 :  case '2' :
                $co_dept_lower_than_me = $co_dept_lower_than_me->where('division_id',$me->my_division())->get();
              
                break;
                
            case 1 :  case '1' :
                // $co_dept_lower_than_me = $co_dept_lower_than_me->where('dept_id',$me->my_dept());
                break;
            
            default : 
                break;
        }

        $biometric_ids = [];
        $users_id = [];

        foreach($co_dept_lower_than_me as $employee)
        {
            array_push($biometric_ids,$employee->biometric_id);
        }

        $users = DB::table('users')->whereIn('biometric_id',$biometric_ids)->select('id')->get();

        foreach($users as $user)
        {
            array_push($users_id,$user->id);
        }

        $result = $this->pendingQuery($users_id,$me);


        return $result;

    }

    public function pendingQuery($users_id,$me)
    {
        $result = $this->mainQuery()
          
            ->select(DB::raw("users.biometric_id,users.name,
                ftp.id,
                ftp.ftp_date,
                ftp.time_in,
                ftp.time_out,
                ftp.overtime_in,
                ftp.overtime_out,
                ftp.ftp_state,
                ftp.ftp_remarks,
                ftp.requested_by,
                ftp.requested_on,
                ftp.sup_apporval_by,
                ftp.sup_apporval_on,
                ftp.sup_approval_resp,
                ftp.ftp_type,
                ftp.ftp_status,
                ftp.remarks,
                ftp.hr_received,
                ftp.hr_received_by,
                ftp.hr_received_on,
                ftp.is_canceled,
                ftp.is_deleted,
                ftp.manager_approval_by,
                ftp.manager_approval_on,
                ftp.manager_approval_resp,
                ftp.div_manager_approval_by,
                ftp.div_manager_approval_on,
                ftp.div_manager_approval_resp
            "))
            ->whereIn('requested_by',$users_id);
      
        switch($me->my_empLevel()){
            case 4 :  case '4' :
                $result = $result
                    ->whereNull('sup_apporval_by')
                    ->whereNull('manager_approval_by')
                    ->whereNull('div_manager_approval_by');
                break;
            
            case 3 :  case '3' :
                $result = $result
                    ->where(function($query){
                        $query->whereNull('sup_apporval_by');
                        $query->orWhere('sup_approval_resp','Approved');
                    })
                    ->whereNull('manager_approval_by')
                    ->whereNull('div_manager_approval_by');
            break;

            case 2 :  case '2' :
                $result = $result
                    ->where(function($query){
                        $query->whereNull('sup_apporval_by');
                        $query->orWhere('sup_approval_resp','Approved');
                    })
                    ->where(function($query){
                        $query->whereNull('manager_approval_by');
                        $query->orWhere('manager_approval_resp','Approved');
                    })
                    ->whereNull('div_manager_approval_by');
                break;
                
            case 1 :  case '1' :
                // $co_dept_lower_than_me = $co_dept_lower_than_me->where('dept_id',$me->my_dept());
                break;
            
            default : 
                break;
        }

        return $result;

    }

    public function approveFTP($id,$remarks)
    {

   
        $me = new Me();
       
        switch($me->my_empLevel()){
            case 4 : case '4':
                $array = [
                    'sup_apporval_by' => Auth::user()->id,
                    'sup_apporval_on' => now(),
                    'sup_approval_resp' => 'Approved',
                    'sup_approval_remarks' => trim($remarks)
                ];
                break;

            case 3 : case '3':
                $array = [
                    'manager_approval_by' => Auth::user()->id,
                    'manager_approval_on' => now(),
                    'manager_approval_resp' => 'Approved',
                    'manager_approval_remarks' => trim($remarks)
                ];
                break;

            case 2 : case '2':
                $array = [
                    'div_manager_approval_by' => Auth::user()->id,
                    'div_manager_approval_on' => now(),
                    'div_manager_approval_resp' => 'Approved',
                    'div_manager_approval_remarks' => trim($remarks)
                ];
                break;
        }

        $result = DB::table('ftp')->where('id',$id)->update($array);

       

        return $result;
    }

    public function denyFTP($id,$remarks)
    {
        $me = new Me();
       
        switch($me->my_empLevel()){
            case 4 : case '4':
                $array = [
                    'sup_apporval_by' => Auth::user()->id,
                    'sup_apporval_on' => now(),
                    'sup_approval_resp' => 'Denied',
                    'sup_approval_remarks' => trim($remarks)
                ];
                break;

            case 3 : case '3':
                $array = [
                    'manager_approval_by' => Auth::user()->id,
                    'manager_approval_on' => now(),
                    'manager_approval_resp' => 'Denied',
                    'manager_approval_remarks' => trim($remarks)
                ];
                break;

            case 2 : case '2':
                $array = [
                    'div_manager_approval_by' => Auth::user()->id,
                    'div_manager_approval_on' => now(),
                    'div_manager_approval_resp' => 'Denied',
                    'div_manager_approval_remarks' => trim($remarks)
                ];
                break;
        }

        $result = DB::table('ftp')->where('id',$id)->update($array);

        return $result;
    }

}
