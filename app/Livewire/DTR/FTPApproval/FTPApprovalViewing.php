<?php

namespace App\Livewire\DTR\FTPApproval;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repository\FTPRepository;
use App\Livewire\Forms\FTP\EditRequestForm;

#[Layout('custom-layout.app')]
class FTPApprovalViewing extends Component
{
    private $id;
    protected $repo;
    public $remarks;

    public EditRequestForm $form;

    public function boot()
    {
        $repo = new FTPRepository();
        $this->repo = $repo;

    }

    public function mount($id)
    {
        $this->id = $id;

        
        $ftp = $this->repo->find($this->id);
     
        if($ftp){
            $this->form->id = $ftp->id;
            $this->form->ftp_date = $ftp->ftp_date;
            $this->form->time_in = $ftp->time_in;
            $this->form->time_out = $ftp->time_out;
            $this->form->overtime_in = $ftp->overtime_in;
            $this->form->overtime_out = $ftp->overtime_out;
            $this->form->ftp_remarks = $ftp->ftp_remarks;
            $this->form->ftp_type = $ftp->ftp_type;
            $this->form->ftp_state = $ftp->ftp_state;
            $this->form->name = $ftp->name;

        }
    }
    public function render()
    {
        return view('livewire.d-t-r.f-t-p-approval.f-t-p-approval-viewing',['ftp' => $this->form]);
    }

    function approveRequest()
    {
        // dd($this->form->id);
        $result = $this->repo->approveFTP($this->form->id,$this->remarks);
        session()->flash('success','FTP Request approved.');
    }

    function denyRequest()
    {
        $result = $this->repo->denyFTP($this->form->id,$this->remarks);
        session()->flash('success','FTP Request denied.');
    }
}
