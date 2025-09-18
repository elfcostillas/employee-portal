<?php

namespace App\Livewire\DTR\FTP;

use App\Repository\FTPRepository;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('custom-layout.app')]

class MainComponent extends Component
{
    protected $repo;

    public function mount(FTPRepository $repo)
    {
        $this->repo = $repo;
    }
    public function render()
    {

        $result = $this->repo->myFTP()->paginate(10);

        return view('livewire.d-t-r.f-t-p.main-component',['ftps' => $result]);
    }

    public function my_level()
    {
        return $this->repo->my_level();
    }
}
