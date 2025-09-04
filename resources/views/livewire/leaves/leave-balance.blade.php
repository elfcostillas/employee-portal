
<div>
    @if($emp_level < 5)
    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Leave Balance</label>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">

                    </th>
                    <th scope="col" class="px-6 py-3">
                        VL
                    </th>
                    <th scope="col" class="px-6 py-3">
                        SL
                    </th>
                    <th scope="col" class="px-6 py-3">
                        SVL
                    </th>
                 
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="col" class="px-6 py-3">Credits</th>
                    <td class="px-6 py-4">{{ round($credits['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($credits['sick_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($credits['summer_vacation_leave'],2) }}</td>
                    
                </tr>

                <th scope="col" class="px-6 py-3">Consumed</th>
                    <td class="px-6 py-4">{{ round($consumed['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($consumed['sick_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($consumed['summer_vacation_leave'],2) }}</td>
                    
                </tr>
                <th scope="col" class="px-6 py-3">Approved (Upcoming)</th>
                    <td class="px-6 py-4">{{ round($upcoming['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($upcoming['sick_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($upcoming['summer_vacation_leave'],2) }}</td>
                    
                </tr>

                <th scope="col" class="px-6 py-3">Pending Approval</th>
                    <td class="px-6 py-4">{{ round($pending['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($pending['sick_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($pending['summer_vacation_leave'],2) }}</td>
                    
                </tr>
                <th scope="col" class="px-6 py-3">Remaining (Assumed) </th>
                    <td class="px-6 py-4">{{ round($remaining['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($remaining['sick_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($remaining['summer_vacation_leave'],2) }}</td>
                    
                </tr>
             
            </tbody>
        </table>
    </div>

    @else
    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Leave Balance</label>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">

                    </th>
                    <th scope="col" class="px-6 py-3">
                        VL
                    </th>
                    <th scope="col" class="px-6 py-3">
                        SL
                    </th>
                    
                 
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th scope="col" class="px-6 py-3">Credits</th>
                    <td class="px-6 py-4">{{ round($credits['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($credits['sick_leave'],2) }}</td>
                  
                    
                </tr>

                <th scope="col" class="px-6 py-3">Consumed</th>
                    <td class="px-6 py-4">{{ round($consumed['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($consumed['sick_leave'],2) }}</td>
                   
                    
                </tr>
                <th scope="col" class="px-6 py-3">Approved (Upcoming)</th>
                    <td class="px-6 py-4">{{ round($upcoming['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($upcoming['sick_leave'],2) }}</td>
                  
                    
                </tr>

                <th scope="col" class="px-6 py-3">Pending Approval</th>
                    <td class="px-6 py-4">{{ round($pending['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($pending['sick_leave'],2) }}</td>
                   
                    
                </tr>
                <th scope="col" class="px-6 py-3">Remaining (Assumed) </th>
                    <td class="px-6 py-4">{{ round($remaining['vacation_leave'],2) }}</td>
                    <td class="px-6 py-4">{{ round($remaining['sick_leave'],2) }}</td>
                  
                    
                </tr>
             
            </tbody>
        </table>
    </div>
    @endif
</div>