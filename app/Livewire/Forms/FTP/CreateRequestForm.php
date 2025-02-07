<?php

namespace App\Livewire\Forms\FTP;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateRequestForm extends Form
{
    //
    #[Rule('required')]
    public $ftp_date;

    #[Rule('required_without_all:time_out,overtime_in,overtime_out')]
    public $time_in;

    #[Rule('required_without_all:time_in,overtime_in,overtime_out')]
    public $time_out;

    #[Rule('required_without_all:time_out,time_in,overtime_out')]
    public $overtime_in;

    #[Rule('required_without_all:time_out,overtime_in,time_in')]
    public $overtime_out;

    #[Rule('required|min:4|max:255')]
    public $ftp_remarks;

    #[Rule('required')]
    public $ftp_type;

    public function store()
    {
        $this->validate();

        return $this;
    }

}
