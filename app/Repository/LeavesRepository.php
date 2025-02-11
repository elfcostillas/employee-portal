<?php

namespace App\Repository;

use App\Models\Me;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeavesRepository
{
    //

    public function mainQuery(){
        return DB::table("leave_headers")
                ->join("leave_request_type","leave_headers.leave_type","=","leave_request_type.id");
    }

    // public function myLeaves()
    // {
    //     return $this->mainQuery()
    //     ->select(DB::raw("leave_headers.*,leave_type_desc"))
    //     ->where('requested_by',Auth::user()->id)
    //     ->orderBy('id','DESC');
    // }

    public function myLeaves()
    {
        return $this->mainQuery()
            ->leftJoin('leave_details','leave_headers.id','=','header_id')
            ->select(DB::raw("leave_headers.*,leave_type_desc,round(sum(ifnull(with_pay,0))/8,2) as with_pay,round(sum(ifnull(without_pay,0))/8,2) as without_pay"))
            ->where('requested_by',Auth::user()->id)
            ->groupBy(
                'leave_headers.id',
                'biometric_id',
                'requested_by',
                'leave_headers.requested_on',
                'leave_request_type.leave_type_desc',
                'sup_apporval_by',
                'sup_apporval_on',
                'sup_approval_resp',
                'leave_status',
                'remarks',
                'hr_received',
                'hr_received_by',
                'hr_received_on',
                'is_canceled',
                'is_deleted',
                'manager_approval_by',
                'manager_approval_on',
                'manager_approval_resp',
                'div_manager_approval_by',
                'div_manager_approval_on',
                'div_manager_approval_resp',
                'date_from',
                'date_to',
                'leave_reason',
                'leave_type',
                'sup_approval_remarks',
                'manager_approval_remarks',
                'div_manager_approval_remarks'
            )
            ->orderBy('id','DESC');
    }

    public function myLeaveTypes()
    {
        return DB::table('leave_request_type')
            ->select(DB::raw("id,leave_type_desc"))
            ->where('id','!=',3)
            ->get();
    }

    public function getLeavesHeader($id)
    {
        $result = DB::table('leave_headers')
                ->select(DB::raw('leave_headers.*,users.name'))
                ->join('users','users.id','requested_by')
                ->where('leave_headers.id',$id)
                ->first();

        return $result;
    }

    public function getLeaveDetails($id)
    {
        $result = DB::table('leave_details')
                ->where('header_id',$id)
                ->get();

        return $result;
    }

    public function getPendingLeavesforApproval()
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
            ->leftJoin('leave_details','leave_headers.id','=','header_id')
            ->leftJoin('users','users.id','=','requested_by')
            ->select(DB::raw("users.name,leave_headers.*,leave_type_desc,round(sum(ifnull(with_pay,0))/8,2) as with_pay,round(sum(ifnull(without_pay,0))/8,2) as without_pay"))
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

        return $result->groupBy(
            'leave_headers.id',
            'biometric_id',
            'requested_by',
            'leave_headers.requested_on',
            'leave_request_type.leave_type_desc',
            'sup_apporval_by',
            'sup_apporval_on',
            'sup_approval_resp',
            'leave_status',
            'remarks',
            'hr_received',
            'hr_received_by',
            'hr_received_on',
            'is_canceled',
            'is_deleted',
            'manager_approval_by',
            'manager_approval_on',
            'manager_approval_resp',
            'div_manager_approval_by',
            'div_manager_approval_on',
            'div_manager_approval_resp',
            'date_from',
            'date_to',
            'leave_reason',
            'leave_type',
            'users.name',
            'sup_approval_remarks',
            'manager_approval_remarks',
            'div_manager_approval_remarks'
        )->orderBy('id','DESC');
    }

    public function approveLeave($id,$remarks)
    {
        // $result = DB::table('h')

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

        $result = DB::table('leave_headers')->where('id',$id)->update($array);

        return $result;

    }

    public function denyLeave($id,$remarks)
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

        $result = DB::table('leave_headers')->where('id',$id)->update($array);

        return $result;
    }
}
