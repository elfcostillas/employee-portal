
<div>
    <style>
        .error {
            font-size:8pt;
            color :red
        }
    </style>

    <form class="max-w-sm mx-auto">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Success alert!</span>  {{ session()->get('success') }}
            </div>
            <a href="{{ url('/leave/leave-request') }}" type="button" class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
            </svg> &emsp;
                Back
            </a>
        @else
        <a href="{{ url('/leave/leave-request') }}" type="button" class="mb-4 px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
        </svg> &emsp;
            Back
        </a>

        @if(session('error'))
            <div id="error_banner" tabindex="1" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Error alert!</span>  {{ session()->get('error') }}
            </div>
        @endif
       
        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date From</label>
        @error('form.date_from') <span style="display:block" class="error">{{ $message }}</span> @enderror 
        <div class="relative max-w-sm">
           <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
               <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
               </svg>
           </div>
           <div class="mb-5">
               <input id="date_from" name="date_from" wire:model='form.date_from' type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select Date">
           </div>
       </div>
      
       <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date To</label>
       @error('form.date_to') <span style="display:block" class="error">{{ $message }}</span> @enderror 
       <div class="relative max-w-sm">
            
           <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
               <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
               </svg>
           </div>
           <div class="mb-5">
               <input id="date_to" name="date_to" wire:model='form.date_to' type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select Date">
           </div>
       </div>

        <div class="mb-5">
            <label for="leave_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Leave Type</label>
            @error('form.leave_type') <span style="display:block" class="error">{{ $message }}</span> @enderror 
            <select id="leave_type" name="leave_type" wire:model='form.leave_type' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Select Type...</option>
                @foreach($leave_types as $type)
                    <option value={{ $type->id }} > {{ $type->leave_type_desc }} </option>
                @endforeach
            </select>
        </div>

        <div class="mb-5">
            <label for="reliever_bio_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reliever</label>
            @error('form.reliever_bio_id') <span style="display:block" class="error">{{ $message }}</span> @enderror 
            <select id="reliever_bio" name="reliever_bio" wire:model='form.reliever_bio_id' class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="0">N/A</option>
                @foreach( $reliver_list as $emp)
                    <option value={{ $emp->biometric_id }} > {{ $emp->empname }} </option>
                @endforeach
            </select>
        </div>


        <div class="mb-5">   
            <label for="leave_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Leave Remarks</label>
            @error('form.leave_reason') <span style="display:block" class="error">{{ $message }}</span> @enderror 
            <textarea id="leave_reason" wire:model='form.leave_reason' name="leave_reason" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a reason..."></textarea>
        </div>
        @if(!$isAccepted)
        <div class="mt-5">
                <button wire:click="submitForm" type="button" class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
            </svg>
            Delete and Modify Dates
            </button>
        </div>
        @endif
        <div class="mb-5 mt-5">   
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Day
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            W/ Pay (Hrs)
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            W/O Pay (hrs)
                        </th>
                       
                    </tr>
                </thead>
                <tbody>
                    @if($dates)
                        @foreach($dates as $key => $date)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <td class="px-3 py-3 text-center"> {{ $date['dayname'] }} </td>
                                <td class="px-3 py-3 text-center"> {{ $date['date'] }}</td>
                                <td class="px-3 py-3 text-center"> 
                                    <select onchange="hide()" wire:change="updateDates($event.target.value,'w_pay',{{$key}})" id="w_pay|{{$key}}" name="w_pay|{{$key}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0" {{ ($date['w_pay'] == 0 ) ? 'selected=selected' : '' }}>0</option>
                                        <option value="4" {{ ($date['w_pay'] == 4 ) ? 'selected=selected' : '' }}>4</option>
                                        <option value="8" {{ ($date['w_pay'] == 8 ) ? 'selected=selected' : '' }}>8</option>
                                    </select>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <select onchange="hide()" wire:change="updateDates($event.target.value,'wo_pay',{{$key}})" id="w_pay|{{$key}}" name="wo_pay|{{$key}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="0" {{ ($date['wo_pay'] == 0 ) ? 'selected=selected' : '' }}>0</option>
                                        <option value="4" {{ ($date['wo_pay'] == 4 ) ? 'selected=selected' : '' }}>4</option>
                                        <option value="8" {{ ($date['wo_pay'] == 8 ) ? 'selected=selected' : '' }}>8</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <td colspan="4"  class="px-6 py-4 text-center">
                            No selected dates.
                        </td>
                    </tr>
                    @endif
                    
                    
                </tbody>
            </table>
            
        </div>
       
        @if(!$isAccepted)
        <button id="submitButton" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button" class="{{ ($dates) ? '' :'hidden'}} px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
            </svg>
            Submit
        </button>
        @endif
        <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to submit leave request?</h3>
                        <button wire:click="submitRequest"  data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>
<script>
     window.addEventListener('contentChanged', (e) => {
        setTimeout(function(){
            var element = document.getElementById("error_banner");
            element.focus();
        },1000);
    });

    function hide()
    {
        var element = document.getElementById("submitButton");
        element.classList.add("hidden");
        
        // setTimeout(element.classList.remove("hidden"), 2000);
       
    }
</script>