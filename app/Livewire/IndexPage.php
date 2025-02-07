<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class IndexPage extends Component
{
    public function render()
    {
        return view('livewire.index-page');
    }
}
