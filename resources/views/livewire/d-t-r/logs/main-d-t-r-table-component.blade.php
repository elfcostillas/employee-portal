<?php
    function format_num($num)
    {
        if($num == 0)
        {
            return '';
        }else{
            return round($num,2);
        }
    }
?>
<style>
    .responsive-table {
    font-size: 8pt !important;
   
}

@media (min-width: 768px) {
    .responsive-table {
        font-size: 10pt !important;
       
    }

   
}
</style>
<div>


    <label for="period_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
    <select wire:model.live="period_id" name="period_id" id="period_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="" >Choose a period</option>
        @foreach($payroll_period as $period)
            <option value="{{$period->id}}"> {{ $period->period_label }} </option>
        @endforeach
    </select>

    @if($logs)
        <div class="mt-2 relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400 responsive-table">
                <thead class=" text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-2 py-2">
                            Day
                        </th>
                        <th scope="col" class="px-2 py-2">
                            Date
                        </th>   
                        <th scope="col" class="px-2 py-2">
                            Time In
                        </th>
                        <th scope="col" class="px-2 py-2">
                            Time Out
                        </th>
                        <th scope="col" class="px-2 py-2">
                            Day
                        </th>
                        
                        <th scope="col" class="px-2 py-2">
                            Late
                        </th>
                           <th scope="col" class="px-2 py-2">
                            UT
                        </th>
                        <th scope="col" class="px-2 py-2">
                            AWOL
                        </th>
                       
                    </tr>
                </thead>
                <tbody>

                    @foreach($logs as $log)
                  
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-4 py-4"> {{ $log->day_name }} </td>
                        <td class="px-4 py-4"> {{ $log->dtr_date }} </td>
                        <td class="px-4 py-4"> {{ $log->time_in }} </td>
                        <td class="px-4 py-4"> {{ $log->time_out }} </td>

                        <td class="px-4 py-4"> {{ format_num($log->ndays) }} </td>
                        <td class="px-4 py-4 text-orange-400"> {{ format_num($log->late) }} </td>
                        <td class="px-4 py-4 text-orange-800"> {{ format_num($log->under_time) }} </td>
                        <td class="px-4 py-4 text-red-500"> {{ format_num($log->awol) }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        No logs found.
    @endif

</div>
