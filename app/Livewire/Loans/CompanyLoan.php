<?php

namespace App\Livewire\Loans;

use App\Repository\LoansRepository;
use Livewire\Attributes\Layout;

use Livewire\Component;

#[Layout('custom-layout.app')]
class CompanyLoan extends Component
{

    private $loansRepository;

    public function boot(LoansRepository $loansRepository)
    {
        $this->loansRepository = $loansRepository;
    }

    public function render()
    {
        $myLoans = $this->loansRepository->myCompanyLoans();

        return view('livewire.loans.company-loan',['myLoans' => $myLoans]);
    }
}


