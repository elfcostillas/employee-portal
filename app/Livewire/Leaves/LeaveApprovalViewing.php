<?php

namespace App\Livewire\Leaves;

use App\Models\Leaves\LeaveDetail;
use App\Models\Leaves\LeaveHeader;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Repository\LeavesRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\Leaves\EditLeaveForm;
use Illuminate\Http\Request;

#[Layout('custom-layout.app')]

class LeaveApprovalViewing extends Component
{
    public $requestor;

    public EditLeaveForm $form;
    private $repo;
    public $dates = [];

    public $remarks;

    public function mount(Request $request,LeavesRepository $repo)
    {
        // dd($request->id);
        $this->repo = $repo;
        $this->remarks = '';

        /* Headers */
        $header = $this->repo->getLeavesHeader($request->id);
        $this->requestor = $header->name;
        $this->form->id = $header->id;
        $this->form->date_from = $header->date_from;
        $this->form->date_to = $header->date_to;
        $this->form->leave_reason = $header->leave_reason;
        $this->form->leave_type = $header->leave_type;
        $this->form->reliever_bio_id = $header->reliever_bio_id;

        /* Details */

        $details = $this->repo->getLeaveDetails($request->id);

        foreach($details as $row){
           
            $index_date = Carbon::createFromFormat('Y-m-d',$row->leave_date);

            array_push($this->dates,[
                'dayname' => $index_date->format('D'),
                'date' => $index_date->format('m/d/Y'),
                'db_date' => $index_date->format('Y-m-d'),
                'w_pay' => $row->with_pay,
                'wo_pay' => $row->without_pay,
            ]);
        }

    }

    function approveRequest()
    {
        // dd($this->form->id);
        $result = $this->repo->approveLeave($this->form->id,$this->remarks);
        session()->flash('success','Leave Request approved.');
    }

    function denyRequest()
    {
        $result = $this->repo->denyLeave($this->form->id,$this->remarks);
        session()->flash('success','Leave Request denied.');
    }

    public function hydrate(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        $leave_type = $this->repo->myLeaveTypes();
        $reliver_list = $this->repo->releiver_list_approval();
        return view('livewire.leaves.leave-approval-viewing',['leave_types' => $leave_type,'reliver_list' => $reliver_list]);
    }
}
