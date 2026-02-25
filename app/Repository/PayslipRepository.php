<?php

namespace App\Repository;

use App\Models\Me;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PayslipRepository
{
    //

    public function getPeriodLabel($period_id)
    {
        $result = DB::connection('hris')->table('payroll_period')
            ->select(DB::raw("CONCAT(DATE_FORMAT(date_from,'%m/%d/%Y'),' - ',DATE_FORMAT(date_to,'%m/%d/%Y')) AS date_range"))
            ->where('id',$period_id);
        
        return $result->first();
    }

    public function getData($period_id){
        $me = new Me();

        // dd($me->att,$period_id);

        $data = DB::connection('hris')->table('payrollregister_posted_s')
            ->join('employees','employees.biometric_id','=','payrollregister_posted_s.biometric_id')
            ->leftJoin('departments','departments.id','=','dept_id')
            ->where('employees.biometric_id','=',$me->att->biometric_id)
            ->where('period_id',$period_id)
            ->select(DB::raw("payrollregister_posted_s.*,dept_id,division_id,concat(lastname,', ',firstname) as employee_name,suffixname,dept_name"))
            ->first();


            $data->basic = $this->basic($data);
            $data->gov_loan = $this->paySlipGovLoan($period_id,$me->att->biometric_id);
            $data->reg_earnings = $this->regEarnings($data);
            $data->restday = $this->restDay($data);
            $data->legalHol = $this->legalHol($data);
            $data->specialHol = $this->specialHol($data);
            $data->dblLegHol = $this->dblLegHol($data);
            $data->allowances = $this->allowances($data);
            $data->otherEearnings = $this->otherEearnings($period_id,$me->att->biometric_id);
            $data->slvl = $this->slvl($data);
            $data->fixedDeduction = $this->fixedDeduction($period_id,$me->att->biometric_id);
            $data->installments = $this->installments($period_id,$me->att->biometric_id);

        return $data;
    }

    public function installments($period_id,$biometric_id)
    {
        $loantotal = 0;

        $paid = DB::connection('hris')->table('posted_installments')->where('biometric_id','=',$biometric_id)
                ->select(DB::raw("deduction_id,biometric_id,SUM(amount) AS paid_amount"))
                ->where('period_id','<=',$period_id)
                ->groupBy('deduction_id')
                ->groupBy('biometric_id');

        $result = DB::connection('hris')->table('deduction_installments')->select(DB::raw("deduction_types.description,posted_installments.amount,total_amount-paid.paid_amount AS balance"))
                ->join('posted_installments',function($join){
                    $join->on('deduction_installments.id','=','posted_installments.deduction_id');
                    $join->on('deduction_installments.biometric_id','=','posted_installments.biometric_id');
                    $join->on('deduction_installments.deduction_type','=','posted_installments.deduction_type');
                })
                ->leftJoinSub($paid,'paid',function($join){
                    $join->on('paid.deduction_id','=','deduction_installments.id');
                })
                ->join('deduction_types','deduction_types.id','=','deduction_installments.deduction_type')
                ->where('posted_installments.biometric_id',$biometric_id)
                ->where('posted_installments.period_id','=',$period_id)
                ->get();


        foreach($result as $loan){
            $loantotal += $loan->amount;
        }

        return array(
            'total' => $loantotal,
            'list' => $result
        );
        
    }

    public function fixedDeduction($period_id,$biometric_id)
    {
        $total = 0;

        $query = "SELECT description,amount FROM posted_fixed_deductions 
        INNER JOIN deduction_types ON deduction_type = deduction_types.id
        WHERE biometric_id = $biometric_id AND period_id = $period_id
        UNION ALL
        SELECT description,amount FROM posted_onetime_deductions 
        INNER JOIN deduction_types ON deduction_type = deduction_types.id
        WHERE biometric_id = $biometric_id AND period_id = $period_id";

        $result = DB::connection('hris')->select($query); 

        foreach($result as $earn)
        {
            $total += $earn->amount;
        }
        
        return array(
            'total' => $total,
            'list' => $result
        );

    }

    public function slvl($e){
        $earnings = [];
        array_push($earnings, (object) [
            'name' => 'Vacation Leave',
            'days'=> $e->vl_wpay,
            'amount' => $e->vl_wpay_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Sick Leave',
            'days'=> $e->sl_wpay,
            'amount' => $e->sl_wpay_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Birthday Leave',
            'days'=> $e->bl_wpay,
            'amount' => $e->bl_wpay_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'SVL',
            'days'=> $e->svl,
            'amount' => $e->svl_amount
        ]);
        
        return collect($earnings);
    }

    
    public function otherEearnings($period_id,$biometric_id)
    {
        $total = 0;
        $query = "SELECT description,amount FROM posted_fixed_compensations 
        INNER JOIN compensation_types ON posted_fixed_compensations.compensation_type = compensation_types.id
        WHERE posted_fixed_compensations.biometric_id = $biometric_id AND period_id =  $period_id
        UNION ALL
        SELECT description,amount FROM posted_other_compensations 
        INNER JOIN compensation_types ON posted_other_compensations.compensation_type = compensation_types.id
        WHERE posted_other_compensations.biometric_id = $biometric_id AND period_id = $period_id";

        $result = DB::connection('hris')->select($query); 

        foreach($result as $earn)
        {
            $total += $earn->amount;
        }
        
        return array(
            'total' => $total,
            'list' => $result
        );
    }


    public function allowances($e)
    {
        $earnings = [];
        
        
        array_push($earnings, (object) [
            'name' => 'Daily Allowance',
            'days'=> '',
            'amount' => $e->daily_allowance
        ]);

        array_push($earnings, (object) [
            'name' => 'Semi Monthly Allowance',
            'days'=> '',
            'amount' => $e->semi_monthly_allowance
        ]);

        return array('list'=>collect($earnings),'total'=> $e->daily_allowance + $e->semi_monthly_allowance );
    }

    public function dblLegHol($e)
    {
        $earnings = [];
        array_push($earnings, (object) [
            'name' => 'Double Legal Holiday Pay',
            'days'=> $e->dblhol_count,
            'amount' => $e->dblhol_count_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. (Hrs)',
            'days'=> $e->dblhol_hrs,
            'amount' => $e->dblhol_hrs_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. O.T. (Hrs)',
            'days'=> $e->dblhol_ot,
            'amount' => $e->dblhol_ot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Night Diff. (Hrs)',
            'days'=> $e->dblhol_nd,
            'amount' => $e->dblhol_nd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Rest Day (Hrs)',
            'days'=> $e->dblhol_rd,
            'amount' => $e->dblhol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Rest Day O.T. (Hrs)',
            'days'=> $e->dblhol_rdot,
            'amount' => $e->dblhol_rdot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. Night Diff O.T. (Hrs)',
            'days'=> $e->dblhol_ndot,
            'amount' => $e->dblhol_ndot_amount
        ]);
        
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. R.D. N.D. (Hrs)',
            'days'=> $e->dblhol_rdnd,
            'amount' => $e->dblhol_rdnd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Double Legal Hol. R.D. N.D. O.T. (Hrs)',
            'days'=> $e->dblhol_rdndot,
            'amount' => $e->dblhol_rdndot_amount
        ]);
        
        $total = $e->dblhol_count_amount + $e->dblhol_hrs_amount + $e->dblhol_ot_amount + $e->dblhol_nd_amount + $e->dblhol_rd_amount + $e->dblhol_rdot_amount + $e->dblhol_ndot_amount +$e->dblhol_rdnd_amount +$e->dblhol_rdndot_amount;
        
        return array('list'=>collect($earnings),'total'=> $total );
    }

    public function specialHol($e)
    {
        $earnings=[];

        array_push($earnings, (object) [
            'name' => 'Special Holiday Pay',
            'days'=> $e->sphol_count,
            'amount' => $e->sphol_count_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday (Hrs)',
            'days'=> $e->sphol_hrs,
            'amount' => $e->sphol_hrs_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday O.T. (Hrs)',
            'days'=> $e->sphol_ot,
            'amount' => $e->sphol_ot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Night Diff. (Hrs)',
            'days'=> $e->sphol_nd,
            'amount' => $e->sphol_nd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Rest Day (Hrs)',
            'days'=> $e->sphol_rd,
            'amount' => $e->sphol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Rest Day O.T. (Hrs)',
            'days'=> $e->sphol_rdot,
            'amount' => $e->sphol_rdot_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday Night Diff O.T. (Hrs)',
            'days'=> $e->sphol_ndot,
            'amount' => $e->sphol_ndot_amount
        ]);
        
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday R.D. N.D. (Hrs)',
            'days'=> $e->sphol_rdnd,
            'amount' => $e->sphol_rdnd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Special Holiday R.D. N.D. O.T. (Hrs)',
            'days'=> $e->sphol_rdndot,
            'amount' => $e->sphol_rdndot_amount
        ]);
        
        $total = $e->sphol_count_amount + $e->sphol_hrs_amount + $e->sphol_ot_amount + $e->sphol_nd_amount + $e->sphol_rd_amount + $e->sphol_rdot_amount + $e->sphol_ndot_amount +$e->sphol_rdnd_amount +$e->sphol_rdndot_amount;
        
        return array('list'=>collect($earnings),'total'=> $total );
    }

    public function legalHol($e)
    {
        $earnings=[];

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Pay',
            'days'=> $e->leghol_count,
            'amount' => $e->leghol_count_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday (Hrs)',
            'days'=> $e->leghol_hrs,
            'amount' => $e->leghol_hrs_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday O.T. (Hrs)',
            'days'=> $e->leghol_ot,
            'amount' => $e->leghol_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Night Diff. (Hrs)',
            'days'=> $e->leghol_nd,
            'amount' => $e->leghol_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Rest Day (Hrs)',
            'days'=> $e->leghol_rd,
            'amount' => $e->leghol_rd_amount
        ]);
        
        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Rest Day O.T. (Hrs)',
            'days'=> $e->leghol_rdot,
            'amount' => $e->leghol_rdot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday Night Diff O.T. (Hrs)',
            'days'=> $e->leghol_ndot,
            'amount' => $e->leghol_ndot_amount
        ]);


        array_push($earnings, (object) [
            'name' => 'Leg. Holiday R.D. N.D. (Hrs)',
            'days'=> $e->leghol_rdnd,
            'amount' => $e->leghol_rdnd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Leg. Holiday R.D. N.D. O.T. (Hrs)',
            'days'=> $e->leghol_rdndot,
            'amount' => $e->leghol_rdndot_amount
        ]);

        $total = $e->leghol_count_amount + $e->leghol_hrs_amount + $e->leghol_ot_amount + $e->leghol_nd_amount + $e->leghol_rd_amount + $e->leghol_rdot_amount + $e->leghol_ndot_amount +$e->leghol_rdnd_amount +$e->leghol_rdndot_amount;

        return array('list'=>collect($earnings),'total'=> $total );
    }


    public function restDay($e){
      
        $earnings=[];
        
        array_push($earnings, (object) [
            'name' => 'Rest Day (Hrs)',
            'days'=> $e->rd_hrs,
            'amount' => $e->rd_hrs_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day O.T. (Hrs)',
            'days'=> $e->rd_ot,
            'amount' => $e->rd_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day Night Diff. (Hrs)',
            'days'=> $e->rd_nd,
            'amount' => $e->rd_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Rest Day Night Diff. O.T. (Hrs)',
            'days'=> $e->rd_ndot,
            'amount' => $e->rd_ndot_amount
        ]);

        return array('list'=>collect($earnings),'total'=> $e->rd_hrs_amount + $e->rd_ot_amount + $e->rd_nd_amount + $e->rd_ndot_amount );
    }

    
    public function regEarnings($e){
        $earnings = [];

        array_push($earnings, (object) [
            'name' => 'Overtime (Hrs)',
            'days'=> $e->reg_ot,
            'amount' => $e->reg_ot_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Night Diff (Hrs)',
            'days'=> $e->reg_nd,
            'amount' => $e->reg_nd_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Night Diff O.T.',
            'days'=> $e->reg_ndot,
            'amount' => $e->reg_ndot_amount
        ]);

        return collect($earnings);
    }

    public function paySlipGovLoan($period_id,$biometric_id)
    {
        $loantotal = 0;
  
        $paid = DB::connection('hris')->table('posted_loans')->where('biometric_id','=',$biometric_id)
                ->select(DB::raw("deduction_id,biometric_id,SUM(amount) AS paid_amount"))
                ->where('period_id','<=',$period_id)
                ->groupBy('deduction_id')
                ->groupBy('biometric_id');

        $result = DB::connection('hris')
                ->table('deduction_gov_loans')
                ->select(DB::raw("loan_types.description,posted_loans.amount,total_amount-paid.paid_amount AS balance"))
                ->join('posted_loans',function($join){
                    $join->on('deduction_gov_loans.id','=','posted_loans.deduction_id');
                    $join->on('deduction_gov_loans.biometric_id','=','posted_loans.biometric_id');
                    $join->on('deduction_gov_loans.deduction_type','=','posted_loans.deduction_type');
                })
                ->leftJoinSub($paid,'paid',function($join){
                    $join->on('paid.deduction_id','=','deduction_gov_loans.id');
                })
                ->join('loan_types','loan_types.id','=','deduction_gov_loans.deduction_type')
                ->where('posted_loans.biometric_id',$biometric_id)
                ->where('posted_loans.period_id','=',$period_id)
                ->get();

        foreach($result as $loan){
            $loantotal += $loan->amount;
        }

        return array(
            'total' => $loantotal,
            'list' => $result
        );
        
    }

    public function basic($e)
    {
        $earnings=[];
        
        array_push($earnings, (object) [
            'name' => 'Basic Pay (Days)',
            'days'=> $e->ndays,
            'amount' => $e->basic_pay
        ]);

        array_push($earnings, (object) [
            'name' => 'Late (Hrs)',
            'days'=> $e->late_eq,
            'amount' => $e->late_eq_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Undertime (Hrs)',
            'days'=> $e->under_time,
            'amount' => $e->under_time_amount
        ]);

        array_push($earnings, (object) [
            'name' => 'Absent (Hrs)',
            'days'=> $e->absences,
            'amount' => $e->absences_amount
        ]);
        
        return collect($earnings);
    }
}
