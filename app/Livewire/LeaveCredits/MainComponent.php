<?php

namespace App\Livewire\LeaveCredits;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{
    public function render()
    {
        return view('livewire.leave-credits.main-component');
    }
}
