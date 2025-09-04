<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class LogsRepository
{
    //

    public function __construct()
    {
        
    }

    public function get_logs($period_id)
    {
        $user = Auth::user();

        // dd($user->biometric_id);

        $result = DB::connection('hris')->table('edtr_raw')->join('payroll_period',function($join){
            $join->whereBetweenColumns('edtr_raw.punch_date',['payroll_period.date_from','payroll_period.date_to']);
        })
        ->where('biometric_id','=',$user->biometric_id)
        ->where('payroll_period.id','=',$period_id)
        ->orderBy('punch_date','ASC')
        ->orderBy('punch_time','ASC')
        ->get();

        return $result;
    }
}


/*

SELECT * FROM edtr_raw INNER JOIN payroll_period ON edtr_raw.punch_date 
BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE biometric_id = 847 AND payroll_period.id = 67
*/