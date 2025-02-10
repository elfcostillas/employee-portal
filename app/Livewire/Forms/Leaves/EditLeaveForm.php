<?php

namespace App\Livewire\Forms\Leaves;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\Attributes\Rule;

class EditLeaveForm extends Form
{
    //
    #[Rule('required')]
    public $id;

    #[Rule('required')]
    public $leave_type;

    #[Rule('required')]
    public $date_to;

    #[Rule('required')]
    public $date_from;

    #[Rule('required|min:4')]
    public $leave_reason;

    public function store()
    {
        $this->validate();

        return $this;
    }
}
