<div>
<?php
    use Illuminate\Support\Str;

    if(Str::contains(request()->path(), 'gov-loans')){
        $url = 'payroll/gov-loans';
    }

    if(Str::contains(request()->path(), 'company-loans')){
        $url = 'payroll/company-loans';
    }

    $total = 0;

    $total_payment = 0;

    foreach($loan_details as $loan_detail){
        $total_payment += $loan_detail->amount;
    }

    $balance = $loan_header->total_amount - $total_payment;



?>

    <div class="mt-5 mb-5">
        <button onclick="window.location.href='{{ url($url) }}'" type="button" class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-4 h-4 mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Return
        </button> 
    </div>

    <div>
  
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h3 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white"> {{ $loan_header->description }} </h3>
            <!-- <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Remarks : </b> {{ $loan_header->remarks }}</p> -->
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Start of Deduction : </b> {{ $loan_header->payperiod_label }}</p>
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Loan Amount : </b> {{ number_format($loan_header->total_amount,2)}}</p>
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Ammortization : </b> {{ number_format($loan_header->ammortization,2) }}</p>
            <p class=" font-normal text-gray-700 dark:text-gray-400"> <b>Balance : </b> {{ number_format($balance,2) }}</p>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Payroll Period
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Posted Amount
                    </th>
                
                </tr>
            </thead>
            <tbody>
            
                @if($loan_details->count() > 0)
                    @foreach($loan_details as $loan_detail)
                        @php 
                            $total += $loan_detail->amount;
                        @endphp

                        <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4">
                                {{  $loan_detail->period_label }}
                            </td>
                            <td style="padding-right: 28%;" class=" py-4 text-right">
                                {{ number_format($loan_detail->amount) }}
                            </td>
                        </tr>
                    @endforeach
                            <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4 font-bold">
                            Total
                            </td>
                            <td style="padding-right: 28%;" class="font-bold py-4 text-right">
                                {{ number_format($total,2) }}
                            </td>
                        </tr>
                @else
                    <tr class="bg-neutral-primary border-b border-default">
                        <td class="px-6 py-4" colspan="2"> No posted payments. </td>
                    </tr>

                @endif
            </tbody>
        </table>
    </div>
</div>
