<?php

namespace App\Livewire\Loans;

use App\Repository\LoansRepository;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;

use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('custom-layout.app')]
class ViewLoan extends Component
{

    private $loansRepository;

    public function boot(LoansRepository $loansRepository)
    {
        $this->loansRepository = $loansRepository;
    }

    public function mount(Request $request)
    {
        
    }

    public function render(Request $request)
    {
        $loan_header = null; 
        $loan_details = null; 

        if(Str::contains(request()->path(), 'gov-loans')){
            $loan_header = $this->loansRepository->myGovLoan($request->id);
            $loan_details = $this->loansRepository->myPostedPayments('gov-loans',$request->id);

        }

        if(Str::contains(request()->path(), 'company-loans')){
            $loan_header = $this->loansRepository->myCompanyLoan($request->id);
            $loan_details = $this->loansRepository->myPostedPayments('company-loans',$request->id);
        }

        return view('livewire.loans.view-loan',['loan_header' => $loan_header,'loan_details' => $loan_details]);
    }
}
