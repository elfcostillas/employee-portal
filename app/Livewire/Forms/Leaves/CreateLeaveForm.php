<?php

namespace App\Livewire\Forms\Leaves;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\Attributes\Rule;

class CreateLeaveForm extends Form
{
    //
    // #[Rule('required')]
    // public $leave_reason;

    #[Rule('required')]
    public $leave_type;

    #[Rule('required')]
    public $date_to;

    #[Rule('required')]
    public $date_from;

    #[Rule('required|min:4')]
    public $leave_reason;

    #[Rule('required|min:0')]
    public $reliever_bio_id;

    public function store()
    {
        $this->validate();

        return $this;
    }
}
