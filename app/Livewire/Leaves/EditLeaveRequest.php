<?php

namespace App\Livewire\Leaves;

use App\Models\Leaves\LeaveDetail;
use App\Models\Leaves\LeaveHeader;
use App\Models\LeaveCredits;
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

class EditLeaveRequest extends Component
{
    public EditLeaveForm $form;
    private $repo;
    public $dates = [];
    public $isAccepted;

    public function mount(Request $request,LeavesRepository $repo)
    {
        // dd($request->id);
        $this->repo = $repo;

        /* Headers */
        $header = $this->repo->getLeavesHeader($request->id);
        
        $this->form->id = $header->id;
        $this->form->date_from = $header->date_from;
        $this->form->date_to = $header->date_to;
        $this->form->leave_reason = $header->leave_reason;
        $this->form->leave_type = $header->leave_type;
        $this->form->reliever_bio_id = $header->reliever_bio_id;

        if($header->sup_apporval_by || $header->manager_approval_by || $header->div_manager_approval_by)
        {
            $this->isAccepted = true;
        }else{
            $this->isAccepted = false;
        }
        
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

    public function hydrate(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        $leave_type = $this->repo->myLeaveTypes();
        $reliver_list = $this->repo->releiver_list();
        return view('livewire.leaves.edit-leave-request',['leave_types' => $leave_type,'isAccepted' => $this->isAccepted,'reliver_list' => $reliver_list]);
    }

    function updateDates($value,$type,$key)
    {
        $this->dates[$key][$type] = $value;
    }
    public function submitForm()
    {   
        $this->form->validate();
        $this->build_detail();
       
    }

    public function build_detail()
    {   
        $this->dates= [];

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
    
    public function submitRequest()
    {

        $header_data = $this->form->validate();

        $array = [
            // 'requested_by' => Auth::user()->id,
            // 'requested_on' => now(),
            'date_from' => $header_data['date_from'],
            'date_to' =>$header_data['date_to'],
            'leave_reason' => trim($header_data['leave_reason']),
            'leave_type' => trim($header_data['leave_type']),
        ];

        $error = false;

        DB::beginTransaction();

        // $header = LeaveHeader::create($array);

        DB::table('leave_details')->where('header_id',$header_data['id'])->delete();

        $header = DB::table('leave_headers')->where('id',$header_data['id'])->update($array);
      
        $details_data = [];
        $total_wpay = 0;
        $exceed_limit = false;

        foreach($this->dates as $leave)
        {
            $key = [  
                'header_id' => $header_data['id'],
                'leave_date' => $leave['db_date']
            ];

            $details = array(
                'with_pay' => $leave['w_pay'],
                'without_pay' => $leave['wo_pay'],
            );

            // array_push( $details_data,$details);
            $total_wpay += $leave['w_pay'];

            $creation_result = DB::table('leave_details')->updateOrInsert($key,$details);

        }

        $credits = new LeaveCredits();
        $remaining = $credits->getAssumedRemainingUpdate($header_data['id']);

        switch($array['leave_type'])
        {
            case 1 : 
                    if( ($total_wpay/8) > $remaining['vacation_leave'])
                    {
                      $exceed_limit = true;
                    }
              break;
            case 2 : 
              if( ($total_wpay/8) > $remaining['sick_leave'])
                    {
                      $exceed_limit = true;
                    }
              break;
            case 8 :  
              if( ($total_wpay/8) > $remaining['summer_vacation_leave'])
                    {
                      $exceed_limit = true;
                    }
              break;
  
            default : 
            break;
        }

        if(!$exceed_limit){
            DB::commit();
            session()->flash('success','Leave Request submitted.');
            $this->form->reset();
            $this->dates = [];

        }else{
            DB::rollBack();
            session()->flash('error', 'Requested leave with pay exceeds remaining balance.');
            $this->dispatch('contentChanged', ['error' => 'error']);
        }

    }

    function carb_wtime($date){
        return Carbon::createFromFormat('Y-m-d H:i:s',$date);
    }
}
