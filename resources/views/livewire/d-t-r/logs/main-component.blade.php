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
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            Day
                        </th>
                        <th scope="col" class="px-4 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-4 py-3">
                            Time
                        </th>
                        <th scope="col" class="px-4 py-3">
                            State
                        </th>
                       
                    </tr>
                </thead>
                <tbody>

                    @foreach($logs as $log)
                    <?php
                        $punch_date = $carbonDate($log->punch_date);
                    ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $punch_date->englishDayOfWeek }}
                        </th>
                        <td class="px-4 py-4">
                            {{ $punch_date->format('m/d/Y') }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $log->punch_time }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $log->cstate }}
                        </td>
                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        No logs found.
    @endif
</div>
