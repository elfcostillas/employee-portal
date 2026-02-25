<?php

namespace App\Livewire\Payroll;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Repository\PayrollPeriodRepository;
use App\Repository\PayslipRepository;
use Carbon\Carbon;

#[Layout('custom-layout.app')]

class Payslip extends Component
{
    private $repo;
    private $payslip;

    public $payroll_period;
    public $period_id;


    public function boot(PayrollPeriodRepository $repo,PayslipRepository $payslip)
    {
        $this->repo = $repo;
        $this->payslip = $payslip;
    }

    public function mount()
    {
        $default = $this->repo->get_last_posted();

        if(!is_null($default)){
            // $this->period_id = $default->id;
            $this->period_id = 77;

        }

    }

    public function render()
    {
        $this->payroll_period = $this->repo->get_payroll_period();
        // return view('livewire.payroll.payslip');
        $period_label = $this->payslip->getPeriodLabel($this->period_id);
        $data = $this->payslip->getData($this->period_id);

        return view('livewire.payroll.payslip',[
                'e' => $data,
                'period_label' => $period_label
        ]);
    }

    static function carbonDateFN($date)
    {
        return Carbon::createFromFormat('Y-m-d',$date);
    }

    public function updated($property)
    {
        if($property == 'period_id'){
           $data = $this->payslip->getData($this->period_id);
           $period_label = $this->payslip->getPeriodLabel($this->period_id);

            return view('livewire.payroll.payslip',[
                    'e' => $data,
                    'period_label' => $period_label
            ]);
        }

        
    }
}
