<?php

namespace App\Livewire\Loans;

use App\Repository\LoansRepository;
use Livewire\Attributes\Layout;

use Livewire\Component;

#[Layout('custom-layout.app')]
class GovernmentLoan extends Component
{
    private $loansRepository;

    public function boot(LoansRepository $loansRepository)
    {
        $this->loansRepository = $loansRepository;
    }
    public function render(LoansRepository $loansRepository)
    {
        $myLoans = $this->loansRepository->myGovLoans();

        return view('livewire.loans.government-loan',['myLoans' => $myLoans]);
    }
}
