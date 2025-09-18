<?php

namespace App\Livewire\DTR\FTP;

use App\Livewire\Forms\FTP\EditRequestForm;
use App\Models\DTR\FTP;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use App\Repository\FTPRepository;

use Illuminate\Support\Facades\Auth;

#[Layout('custom-layout.app')]
class EditFTPRequest extends Component
{
    private $id;

    public EditRequestForm $form;

    // public FTPRepository $repo;

    public function mount($id)
    {
        $this->id = $id;

        $repo = new FTPRepository();

        $ftp = $repo->find($this->id);

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
            $this->form->remarks = $ftp->remarks;

        }
    }

    public function render()
    {
        return view('livewire.d-t-r.f-t-p.edit-f-t-p-request',['ftp' => $this->form]);
    }

    public function submitForm()
    {
        $this->form->validate();
       
        $result = FTP::where('id',$this->form->id)->update($this->form->all());
       
        session()->flash('success','FTP Request submitted.');
        
    }
}

/*
 $repo = new FTPRepository();

        $ftp = $repo->find($this->id);
       
        // $this->form->ftp_date = Carbon::createFromFormat('Y-m-d', $ftp->ftp_date)->format('m/d/Y');

        if($ftp){
            $this->form->ftp_date = $ftp->ftp_date;
            $this->form->time_in = $ftp->time_in;
            $this->form->time_out = $ftp->time_out;
            $this->form->overtime_in = $ftp->overtime_in;
            $this->form->overtime_out = $ftp->overtime_out;
            $this->form->ftp_remarks = $ftp->ftp_remarks;
            $this->form->ftp_type = $ftp->ftp_type;
        }else{
            dd($ftp,$this->id);
        }
            */