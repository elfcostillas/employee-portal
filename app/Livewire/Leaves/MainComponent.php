<?php

namespace App\Livewire\Leaves;

use App\Repository\LeavesRepository;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{
    private $repo;

    public function mount(LeavesRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        $result = $this->repo->myLeaves()->paginate(10);
        return view('livewire.leaves.main-component',['leaves' => $result]);
    }
}
