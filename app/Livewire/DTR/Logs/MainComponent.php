<?php

namespace App\Livewire\DTR\Logs;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{
    public function render()
    {
        return view('livewire.d-t-r.logs.main-component');
    }
}
