<?php

namespace App\Livewire\DTR\FTP;

use Livewire\Attributes\Layout;
use App\Livewire\Forms\FTP\CreateRequestForm;
use App\Models\DTR\FTP;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

#[Layout('custom-layout.app')]
class CreateFTPRequest extends Component
{
    public CreateRequestForm $form;

    public function render()
    {
        return view('livewire.d-t-r.f-t-p.create-f-t-p-request');
    }

    public function submitForm()
    {

        // $this->form->store();

        // dd($this->form->store());
        $this->form->validate();

        $data = [
            'ftp_date' => Carbon::createFromFormat('Y-m-d',$this->form->ftp_date),
            'time_in' =>  $this->form->time_in,
            'time_out' =>  $this->form->time_out,
            'overtime_in' =>  $this->form->overtime_in,
            'overtime_out' =>  $this->form->overtime_out,
            'ftp_type' => $this->form->ftp_type,
            'ftp_state' => 'PENDING',
            'ftp_remarks' =>  $this->form->ftp_remarks,
            'requested_by' => Auth::user()->id,
            'requested_on' => now(),
        ];

        FTP::create($data);
        // FTP::create();        
        session()->flash('success','FTP Request submitted.');

        $this->form->reset();
    }
}
