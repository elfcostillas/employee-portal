<?php

namespace App\Livewire\UserManagement;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{
    public function render()
    {
        return view('livewire.user-management.main-component');
    }
}
