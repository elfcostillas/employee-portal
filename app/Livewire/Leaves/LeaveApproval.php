<?php

namespace App\Livewire\Leaves;
use Livewire\Attributes\Layout;
use App\Repository\LeavesRepository;
use Livewire\Component;

#[Layout('custom-layout.app')]

class LeaveApproval extends Component
{

    private $repo;

    public function mount(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        $pending = $this->repo->getPendingLeavesforApproval()->paginate(10);
        return view('livewire.leaves.leave-approval',['leaves' => $pending]);
    }
}
