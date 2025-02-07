<?php

namespace App\Livewire;

use App\Repository\Auth\UserRights;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    private $repo;

    public function __construct()
    {
        $this->repo = new UserRights;
    }

    public function render()
    {
        $rights = $this->repo->getRights();

        return view('livewire.navbar',['rights' => $rights]);
    }
}
