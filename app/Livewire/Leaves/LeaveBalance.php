<?php

namespace App\Livewire\Leaves;

use App\Models\LeaveCredits;
use App\Models\Me;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('custom-layout.app')]

class LeaveBalance extends Component
{

    public $credits;
    public $consumed;
    public $upcoming;
    public $pending;

    public $remaining;

    public $me;
    public $emp_level;

    public function boot()
    {
        $me = new Me();

        $this->emp_level = $me->my_empLevel();
    }

    public function mount()
    {
        $credits = new LeaveCredits();

        $this->credits = $credits->getLeaveCredits();
        $this->consumed = $credits->getConsumedLeaves();
        $this->upcoming = $credits->getUpcomingLeave();
        $this->pending = $credits->getPendingLeaves();
        
        $this->remaining = $credits->getAssumedRemaining();

       
    }

    public function render()
    {
        return view('livewire.leaves.leave-balance');
    }
}
