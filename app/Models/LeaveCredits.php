<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveCredits
{
    //

    public $current_year;
    public $me;

    public function __construct()
    {
        $this->me = new Me();
        $this->current_year = now()->format('Y');
    }   

    public function getLeaveCredits()
    {
        // dd( DB::connection('hris'));
        $credits = DB::connection('hris')->table('leave_credits')
                ->where('fy_year',now()->format('Y'))
                ->select(DB::raw('vacation_leave,sick_leave,summer_vacation_leave,paternity_leave'))
                ->where('biometric_id',$this->me->att->biometric_id)
                ->first();

        $result =  [
            'vacation_leave' => ($credits) ? $credits->vacation_leave : 0,
            'sick_leave' => ($credits) ? $credits->sick_leave : 0,
            'summer_vacation_leave' => ($credits) ? $credits->summer_vacation_leave : 0,
            'paternity_leave' => ($credits) ? $credits->paternity_leave : 0,
        ];

        return $result;

        // return ($credits) ? $credits : [
        //     'vacation_leave' => 0,
        //     'sick_leave' => 0,
        //     'summer_vacation_leave' => 0,
        //     'paternity_leave' => 0,
        // ];
    }

    public function getConsumedLeaves()
    {
        $current_date = now()->format('Y-m-d');
        $start_year = $this->current_year.'-01-01';

        $consumed = DB::connection('hris')->table('leave_request_header')
                ->join('leave_request_detail','leave_request_detail.header_id','=','leave_request_header.id')
                ->select(DB::raw("leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) as consumed"))
                ->where('biometric_id','=',$this->me->att->biometric_id)
                ->where('leave_type','!=','BL')
                ->where('is_canceled','N')
                ->whereBetween("leave_request_detail.leave_date",[$start_year,$current_date])
                ->groupBy(
                    'id',
                    'biometric_id',
                    'encoded_on',
                    'encoded_by',
                    'request_date',
                    'leave_type',
                    'date_from',
                    'date_to',
                    'remarks',
                    'acknowledge_status',
                    'acknowledge_time',
                    'acknowledge_by',
                    'received_by',
                    'received_time',
                    'dept_id',
                    'division_id',
                    'job_title_id',
                    'document_status',
                    'reliever_id',
                    'ack_by_reliver',
                    'deny_reason',
                    // 'leave_request_detail.line_id',
                    // 'leave_request_detail.header_id'
                )
                ->get();

        $result = array(
            'vacation_leave' => 0,
            'sick_leave' => 0,
            'summer_vacation_leave' => 0,
            'paternity_leave' => 0,
        );
       
        if($consumed){
            foreach($consumed as $key => $value)
            {
                switch($value->leave_type)
                {
                    case 'VL' :
                        $result['vacation_leave'] += (float) $value->consumed;
                        break;

                    case 'SL' :
                        $result['sick_leave'] += (float) $value->consumed;
                        break;

                    case 'SVL' :
                        $result['summer_vacation_leave'] += (float)  $value->consumed;
                        break;
                    default :

                        break;
                }
            }
        }

        return $result;
    }

    public function getUpcomingLeave()
    {
        $tomorrow = now()->addDay()->format('Y-m-d');
        $end_year = $this->current_year.'-12-31';

        $upcoming = DB::connection('hris')->table('leave_request_header')
                ->join('leave_request_detail','leave_request_detail.header_id','=','leave_request_header.id')
                ->select(DB::raw("leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) as consumed"))
                ->where('biometric_id','=',$this->me->att->biometric_id)
                ->where('leave_type','!=','BL')
                ->where('is_canceled','N')
                ->whereBetween("leave_request_detail.leave_date",[$tomorrow,$end_year])
                ->groupBy(
                    'id',
                    'biometric_id',
                    'encoded_on',
                    'encoded_by',
                    'request_date',
                    'leave_type',
                    'date_from',
                    'date_to',
                    'remarks',
                    'acknowledge_status',
                    'acknowledge_time',
                    'acknowledge_by',
                    'received_by',
                    'received_time',
                    'dept_id',
                    'division_id',
                    'job_title_id',
                    'document_status',
                    'reliever_id',
                    'ack_by_reliver',
                    'deny_reason',
                    // 'leave_request_detail.line_id',
                    // 'leave_request_detail.header_id'
                )
                ->get();

        $result = array(
            'vacation_leave' => 0,
            'sick_leave' => 0,
            'summer_vacation_leave' => 0,
            'paternity_leave' => 0,
        );
       
        if($upcoming){
            foreach($upcoming as $key => $value)
            {
                switch($value->leave_type)
                {
                    case 'VL' :
                        $result['vacation_leave'] += (float) $value->consumed;
                        break;

                    case 'SL' :
                        $result['sick_leave'] += (float) $value->consumed;
                        break;

                    case 'SVL' :
                        $result['summer_vacation_leave'] += (float)  $value->consumed;
                        break;
                    default :

                        break;
                }
            }
        }

        return $result;
    }

    public function getPendingLeaves()
    {
        $pending = DB::table('leave_headers')
            ->join('leave_details','leave_headers.id','=','leave_details.header_id')
            ->select(DB::raw("leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) AS days"))
            ->where('requested_by',Auth::user()->id)
            ->where('hr_received','=','N')
   
            ->where(function($query){
                $query->whereNull('sup_approval_resp')
                ->orWhere('sup_approval_resp','=','Approved')
                ->whereNull('manager_approval_resp')
                ->orWhere('manager_approval_resp','=','Approved')
                ->whereNull('div_manager_approval_resp')
                ->orWhere('div_manager_approval_resp','=','Approved');
            })
            ->groupBy('leave_headers.id',
                'biometric_id',
                'requested_by',
                'requested_on',
                'sup_apporval_by',
                'sup_apporval_on',
                'sup_approval_resp',
                'sup_approval_remarks',
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
                'manager_approval_remarks',
                'div_manager_approval_by',
                'div_manager_approval_on',
                'div_manager_approval_resp',
                'div_manager_approval_remarks',
                'date_from',
                'date_to',
                'leave_reason',
                'leave_type'
                )
            ->get();

        $result = array(
            'vacation_leave' => 0,
            'sick_leave' => 0,
            'summer_vacation_leave' => 0,
            'paternity_leave' => 0,
        );

        if($pending){
            foreach($pending as $ltype)
            {
                switch($ltype->leave_type)
                {
                    case 1 :
                        $result['vacation_leave'] += (float) $ltype->days;
                        break;
    
                    case 2 :
                        $result['sick_leave'] += (float) $ltype->days;
                        break;
    
                    case 8 : 
                        $result['summer_vacation_leave'] += (float) $ltype->days;
                        break;
    
                    default : break;
                }
            }
        }
        
        return $result;
        
    }

    public function getPendingLeavesUpdate($id)
    {
        $pending = DB::table('leave_headers')
            ->join('leave_details','leave_headers.id','=','leave_details.header_id')
            ->select(DB::raw("leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) AS days"))
            ->where('requested_by',Auth::user()->id)
            ->where('hr_received','=','N')
            ->where('leave_headers.id','!=',$id)
            ->where(function($query){
                $query->whereNull('sup_approval_resp')
                ->orWhere('sup_approval_resp','=','Approved')
                ->whereNull('manager_approval_resp')
                ->orWhere('manager_approval_resp','=','Approved')
                ->whereNull('div_manager_approval_resp')
                ->orWhere('div_manager_approval_resp','=','Approved');
            })
            ->groupBy('leave_headers.id',
                'biometric_id',
                'requested_by',
                'requested_on',
                'sup_apporval_by',
                'sup_apporval_on',
                'sup_approval_resp',
                'sup_approval_remarks',
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
                'manager_approval_remarks',
                'div_manager_approval_by',
                'div_manager_approval_on',
                'div_manager_approval_resp',
                'div_manager_approval_remarks',
                'date_from',
                'date_to',
                'leave_reason',
                'leave_type'
                )
            ->get();

        $result = array(
            'vacation_leave' => 0,
            'sick_leave' => 0,
            'summer_vacation_leave' => 0,
            'paternity_leave' => 0,
        );

        if($pending){
            foreach($pending as $ltype)
            {
                switch($ltype->leave_type)
                {
                    case 1 :
                        $result['vacation_leave'] += (float) $ltype->days;
                        break;
    
                    case 2 :
                        $result['sick_leave'] += (float) $ltype->days;
                        break;
    
                    case 8 : 
                        $result['summer_vacation_leave'] += (float) $ltype->days;
                        break;
    
                    default : break;
                }
            }
        }
        
        return $result;
        
    }

    public function getAssumedRemaining()
    {
        $credits = $this->getLeaveCredits();
        $consumed = $this->getConsumedLeaves();
        $upcoming = $this->getUpcomingLeave();
        $pending = $this->getPendingLeaves();

        return array(
            'vacation_leave' => $credits['vacation_leave']  - $consumed['vacation_leave'] - $upcoming['vacation_leave'] - $pending['vacation_leave'],
            'sick_leave' => $credits['sick_leave'] - $consumed['sick_leave'] - $upcoming['sick_leave'] - $pending['sick_leave'],
            'summer_vacation_leave' => $credits['summer_vacation_leave'] - $consumed['summer_vacation_leave'] - $upcoming['summer_vacation_leave'] - $pending['summer_vacation_leave'],
            'paternity_leave' => 0,
        );
    }

    public function getAssumedRemainingUpdate($id)
    {
        $credits = $this->getLeaveCredits();
        $consumed = $this->getConsumedLeaves();
        $upcoming = $this->getUpcomingLeave();
        $pending = $this->getPendingLeavesUpdate($id);

        return array(
            'vacation_leave' => $credits['vacation_leave'] - $consumed['vacation_leave'] - $upcoming['vacation_leave'] - $pending['vacation_leave'],
            'sick_leave' => $credits['sick_leave'] - $consumed['sick_leave'] - $upcoming['sick_leave'] - $pending['sick_leave'],
            'summer_vacation_leave' => $credits['summer_vacation_leave'] - $consumed['summer_vacation_leave'] - $upcoming['summer_vacation_leave'] - $pending['summer_vacation_leave'],
            'paternity_leave' => 0,
        );
    }

    



}

//SELECT * FROM leave_credits WHERE fy_year = 2025 AND biometric_id = 847
/*

 $query->whereNotNull('sup_approval_resp')
                ->orWhere('sup_approval_resp','=','Approved');
            })
            ->orWhere(function($query){
                $query->whereNotNull('manager_approval_resp')
                ->orWhere('manager_approval_resp','=','Approved');
            })
            ->orWhere(function($query){
                $query->whereNotNull('div_manager_approval_resp')
                ->orWhere('div_manager_approval_resp','=','Approved');


SELECT leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) AS days FROM leave_headers INNER JOIN 
leave_details ON leave_headers.id = leave_details.header_id
WHERE requested_by = 8
AND (sup_approval_resp IS NULL OR sup_approval_resp = 'Approved')
AND (manager_approval_resp IS NULL OR manager_approval_resp = 'Approved')
AND (div_manager_approval_resp IS NULL OR div_manager_approval_resp = 'Approved')
AND hr_received = 'N'

  +"line_id": 569
  +"fy_year": 2025
  +"biometric_id": 42
  +"vacation_leave": "10.00"
  +"sick_leave": "5.00"
  +"summer_vacation_leave": "0.00"
  +"paternity_leave": "0.00"

    SELECT leave_type,ROUND(SUM(IFNULL(with_pay,0))/8,2) as consumed FROM  leave_request_header 
    INNER JOIN leave_request_detail ON leave_request_detail.header_id = leave_request_header.id 
    WHERE biometric_id = 847
    AND leave_type != 'BL'
    AND leave_request_detail.leave_date BETWEEN '2025-01-01' AND '2025-12-31'
    GROUP BY leave_type




}*/
