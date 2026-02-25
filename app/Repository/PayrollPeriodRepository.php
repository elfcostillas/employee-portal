<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\Me;

class PayrollPeriodRepository
{
    //

    public function mainQuery(){
        return DB::connection('hris')->table("payroll_period");
    }

    public function get_payroll_period()
    {
        return $this->mainQuery()
                ->where('date_from','>=','2025-01-01')
                ->select(DB::raw("id,CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS period_label"))
                ->orderBy('id','desc')
                ->get();
    }

    public function get_current_period()
    {
       $result = $this->mainQuery()
                // ->whereBetweenColumns(now()->format('Y-m-d'),['date_from','date_to'])
                ->whereRaw("now() between date_from and date_to")
                ->first();
        return $result;
    }

    public function get_last_posted()
    {
        $me = new Me();

        if($me->att->emp_level < 5){
            $result = DB::connection('hris')->table('posting_info')
                        ->where('trans_type','=','confi')
                        ->select('period_id')
                        ->first();
        }else{

            $result = DB::connection('hris')->table('posting_info')
                    ->where('trans_type','=','non-confi')
                    ->select('period_id')
                    ->first();
        }

        return $result;
    }

}
