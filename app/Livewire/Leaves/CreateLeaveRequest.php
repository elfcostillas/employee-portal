<?php

namespace App\Livewire\Leaves;

use App\Livewire\Forms\Leaves\CreateLeaveForm;
use App\Models\Leaves\LeaveDetail;
use App\Models\Leaves\LeaveHeader;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Repository\LeavesRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

#[Layout('custom-layout.app')]

class CreateLeaveRequest extends Component
{
    public CreateLeaveForm $form;
    private $repo;
    public $dates = [];

    public $sampledates = [];

    public function mount(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function hydrate(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        // $leave_type = [];
        $leave_type = $this->repo->myLeaveTypes();
        return view('livewire.leaves.create-leave-request',['leave_types' => $leave_type]);
    }

    public function submitForm()
    {   
        /*
        $flag = $this->form->validate();

        $array = [
            'requested_by' => Auth::user()->id,
            'requested_on' => now(),
            'date_from' => $flag['date_to'],
            'date_to' =>$flag['date_from'],
            'leave_reason' => trim($flag['leave_reason']),
            'leave_type' => trim($flag['leave_type']),
        ];

        LeaveHeader::create($array);

        session()->flash('success','Leave Request saved.');
        */

        // $this->form->reset();

        $this->form->validate();
        $this->build_detail();
       
    }

    public function build_detail()
    {
        $range = CarbonPeriod::create($this->form->date_from,$this->form->date_to);

        foreach($range as $date)
        {
            $index_date =  $this->carb_wtime($date);

            $detail_model = array(
                'dayname' => $index_date->format('D'),
                'date' => $index_date->format('m/d/Y'),
                'db_date' => $index_date->format('Y-m-d'),
                'w_pay' => 0,
                'wo_pay' => 0
            );
            if($index_date->format('D')!='Sun')
            {
                array_push($this->dates,$detail_model);
            }
            
        }

    }

    function carb($date)
    {
        return Carbon::createFromFormat('Y-m-d',$date);
    }

    function carb_wtime($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$date);
    }

    function updateDates($value,$type,$key)
    {
        $this->dates[$key][$type] = $value;
    }

    public function submitRequest()
    {
        
    }
}


/*
App\Livewire\Forms\Leaves\CreateLeaveForm {#542 ▼ // app\Livewire\Leaves\CreateLeaveRequest.php:108
  #component: 
App\Livewire\Leaves
\
CreateLeaveRequest {#537 ▶}
  #propertyName: "form"
  #withValidatorCallback: null
  #rulesFromOutside: array:4 [▶]
  #messagesFromOutside: []
  #validationAttributesFromOutside: []
  +leave_type: "2"
  +date_to: "2025-02-05"
  +date_from: "2025-02-01"
  +leave_reason: "teast 123"
}
array:4 [▼ // app\Livewire\Leaves\CreateLeaveRequest.php:108
  0 => array:4 [▼
    "dayname" => "Sat"
    "date" => "02/01/2025"
    "w_pay" => "4"
    "wo_pay" => "4"
  ]
  1 => array:4 [▼
    "dayname" => "Mon"
    "date" => "02/03/2025"
    "w_pay" => 0
    "wo_pay" => "8"
  ]
  2 => array:4 [▼
    "dayname" => "Tue"
    "date" => "02/04/2025"
    "w_pay" => "8"
    "wo_pay" => 0
  ]
  3 => array:4 [▼
    "dayname" => "Wed"
    "date" => "02/05/2025"
    "w_pay" => 0
    "wo_pay" => "8"
  ]
]
*/