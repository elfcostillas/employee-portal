<?php

namespace App\Livewire\Leaves;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Me;
use App\Models\LeaveCredits;

#[Layout('custom-layout.app')]

class LeaveBalanceDetailed extends Component
{

    public $emp_level;
    public $credits;


    public function boot()
    {
        $me = new Me();
        $credits = new LeaveCredits();
        $this->credits = $credits->getLeaveCredits();

        $this->emp_level = $me->my_empLevel();
    }

    public function mount()
    {
        
    }
    
    public function render()
    {
        return view('livewire.leaves.leave-balance-detailed');
    }
}
