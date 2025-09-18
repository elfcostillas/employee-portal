<?php

namespace App\Livewire\DTR\FTPApproval;

use App\Repository\FTPRepository;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{

    protected $repo;

    public function boot(FTPRepository $repo)
    {
        $this->repo = $repo;
    }

    public function render()
    {
        $result = $this->repo->getPendingFTPforApproval()->paginate(10);

        return view('livewire.d-t-r.f-t-p-approval.main-component',['ftps' => $result]);
    }
}
