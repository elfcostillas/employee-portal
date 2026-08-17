<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use App\Models\Me;
use Illuminate\Support\Facades\Auth;

class LoansRepository
{
    //

    public function mainGovLoanQuery()
    {
        return DB::connection('hris')->table("deduction_gov_loans")
            ->join('employee_names_vw','deduction_gov_loans.biometric_id','=','employee_names_vw.biometric_id')
            ->join('loan_types','deduction_gov_loans.deduction_type','=','loan_types.id')
            ->join('payroll_period','deduction_gov_loans.period_id','=','payroll_period.id')
            ->select(DB::raw("loan_types.description,DATE_FORMAT(ADDDATE(date_to,INTERVAL 5 DAY),'%m/%d/%Y') AS payperiod_label,deduction_gov_loans.*"))
            ->where('hidden','=','N');
    }

    public function mainCompanyLoanQuery()
    {
        return DB::connection('hris')->table("deduction_installments")
            ->join('employee_names_vw','deduction_installments.biometric_id','=','employee_names_vw.biometric_id')
            ->join('deduction_types','deduction_installments.deduction_type','=','deduction_types.id')
            ->join('payroll_period','deduction_installments.period_id','=','payroll_period.id')
            ->select(DB::raw("COALESCE(deduction_types.complete_description,deduction_types.description) AS description,DATE_FORMAT(ADDDATE(date_to,INTERVAL 5 DAY),'%m/%d/%Y') AS payperiod_label,deduction_installments.*"))
            ->where('hidden','=','N');
    }

    public function myGovLoans()
    {
      
        return $this->mainGovLoanQuery()
            ->where('deduction_gov_loans.biometric_id',Auth::user()->biometric_id)->get();
    }

    public function myCompanyLoans()
    {
      
        return $this->mainCompanyLoanQuery()
            ->where('deduction_installments.biometric_id',Auth::user()->biometric_id)->get();
    }

    public function myGovLoan(int $id)
    {
      
        return $this->mainGovLoanQuery()
            ->where('deduction_gov_loans.biometric_id',Auth::user()->biometric_id)
            ->where('deduction_gov_loans.id',$id)->first();
    }

    public function myCompanyLoan(int $id)
    {
      
        return $this->mainCompanyLoanQuery()
            ->where('deduction_installments.biometric_id',Auth::user()->biometric_id)
            ->where('deduction_installments.id',$id)->first();
    }

    public function myPostedPayments($type,$loan_id)
    {
        $posted_payments = null;

        if($type == 'gov-loans'){
            $posted_payments = DB::connection('hris')->table('posted_loans')
                ->leftJoin('payroll_period','posted_loans.period_id','=','payroll_period.id')
                ->select(DB::raw("date_format(date_add(payroll_period.date_to, interval 5 day),'%b %m %Y') as period_label,amount"))
                ->where('deduction_id',$loan_id)
                ->orderBy('period_id','asc');
          
        }

        if($type == 'company-loans'){
            $posted_payments = DB::connection('hris')->table('posted_installments')
                ->leftJoin('payroll_period','posted_installments.period_id','=','payroll_period.id')
                ->select(DB::raw("date_format(date_add(payroll_period.date_to, interval 5 day),'%b %d, %Y') as period_label,amount"))
                ->where('deduction_id',$loan_id)
                ->orderBy('period_id','asc');
        }

        return (is_null($posted_payments)) ? null : $posted_payments->get();
      
    }

}


  /*
        SELECT date_add(payroll_period.date_to, interval 5 day) as period_label,amount FROM posted_installments 
        left join payroll_period on posted_installments.period_id = payroll_period.id 
        WHERE deduction_id = ? 
        order by period_id;  
        */