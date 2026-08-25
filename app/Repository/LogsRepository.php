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

    public function get_dtr_table($period_id)
    {
        $user = Auth::user();

        // dd($user->biometric_id);

        $result = DB::connection('hris')->table('edtr_detailed')->join('payroll_period',function($join){
            $join->whereBetweenColumns('edtr_detailed.dtr_date',['payroll_period.date_from','payroll_period.date_to']);
        })
        ->select(DB::raw("DATE_FORMAT(dtr_date,'%a') AS day_name,DATE_FORMAT(dtr_date,'%b %d') AS dtr_date ,time_in,time_out,late,under_time,awol,ndays"))
        ->where('biometric_id','=',$user->biometric_id)
        ->where('payroll_period.id','=',$period_id)
        ->orderBy('dtr_date','ASC')
        ->get();

        return $result;
    }


}


/*

SELECT * FROM edtr_raw INNER JOIN payroll_period ON edtr_raw.punch_date 
BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE biometric_id = 847 AND payroll_period.id = 67


SELECT DATE_FORMAT(dtr_date,'%a') AS day_name,DATE_FORMAT(dtr_date,'%b %d') AS dtr_date ,time_in,time_out,late,under_time,awol 
FROM edtr_detailed INNER JOIN payroll_period ON edtr_detailed.dtr_date BETWEEN payroll_period.date_from AND payroll_period.date_to
WHERE payroll_period.id = 89 AND edtr_detailed.biometric_id = 847
ORDER BY dtr_date ASC;

*/