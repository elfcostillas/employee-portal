<?php

namespace App\Livewire\DTR\Logs;


use App\Repository\LogsRepository;
use App\Repository\PayrollPeriodRepository;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]
class MainDTRTableComponent extends Component
{

    private $repo;
    private $logs_repo;

    public $payroll_period;
    public $period_id;
    public $logs;

    public $carbonDate = 'App\Livewire\DTR\Logs\MainComponent::carbonDateFN';

    public function boot(PayrollPeriodRepository $repo,LogsRepository $logs_repo)
    {
        $this->repo = $repo;
        $this->logs_repo = $logs_repo;
    }

    public function mount()
    {
      

        $default = $this->repo->get_current_period();
        
        if(!is_null($default)){
            $this->period_id = $default->id;

            $this->logs = $this->logs_repo->get_dtr_table($this->period_id);
        }
        
    }

    public function updated($property)
    {
       
        if($property == 'period_id')
        {
            $this->logs = $this->logs_repo->get_dtr_table($this->period_id);
        }
    }

    public function render()
    {
        $this->payroll_period = $this->repo->get_payroll_period();
        return view('livewire.d-t-r.logs.main-d-t-r-table-component');
    }

    static function carbonDateFN($date)
    {
        return Carbon::createFromFormat('Y-m-d',$date);
    }

}
