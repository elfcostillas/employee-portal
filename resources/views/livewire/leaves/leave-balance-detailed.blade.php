<div>
    @if($emp_level < 5)

    @else
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-3 py-3">
                            DATE
                        </th>
                        <th scope="col" class="px-3 py-3">
                            TYPE
                        </th>
                        <th scope="col" class="px-3 py-3">
                            REASONS
                        </th>
                        <th scope="col" class="px-3 py-3">
                            W/ PAY
                        </th>
                        <th scope="col" class="px-3 py-3">
                            W/O PAY
                        </th>
                    
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-3 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                           
                        </th>
                        <td class="px-3 py-4">
                            Silver
                        </td>
                        <td class="px-3 py-4">
                            Laptop
                        </td>
                        <td class="px-3 py-4">
                            $2999
                        </td>
                    </tr>
                    
                   
                </tbody>
            </table>
        </div>
    @endif
</div>