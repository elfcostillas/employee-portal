<div>
    <h3 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white"> Company Loans </h3>
          
    @foreach($myLoans as $myLoan)
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h3 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white"> {{ $myLoan->description }} </h3>
            <!-- <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Remarks : </b> {{ $myLoan->remarks }}</p> -->
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Start of Deduction : </b> {{ $myLoan->payperiod_label }}</p>
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Loan Amount : </b> {{ number_format($myLoan->total_amount,2)}}</p>
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Ammortization : </b> {{ number_format($myLoan->ammortization,2) }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Active Loan : </b> {{ ($myLoan->is_stopped == 'Y') ? 'No' : 'Yes'}}</p>

            <butto onclick="window.location.href='company-loans/view/{{ $myLoan->id }}'" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
        </div>
    @endforeach
</div>
