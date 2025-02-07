<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <button onclick="window.location.href='leave-request-create'"  type="button" class="mb-2 px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-3.5 h-3.5 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
        <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
        <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
        </svg>
        Request Leave
    </button>

    <?php
        use Carbon\Carbon;
    ?>

    @foreach($leaves as $leave)
        <?php
            $date_from = Carbon::createFromFormat('Y-m-d', $leave->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d', $leave->date_to);

        ?>
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Leave Date : </b> {{ $date_from->format('M d, Y') }} - {{ $date_to->format('M d, Y') }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Type : </b> {{ $leave->leave_type_desc }}</p>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Reason : </b> {{ $leave->leave_reason }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Supervisor Approval : </b> {{ ($leave->sup_approval_resp) ? $leave->sup_approval_resp : 'Pending' }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Department Manager Approval : </b> {{ ($leave->manager_approval_resp) ? $leave->manager_approval_resp : 'Pending' }}</p>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Division Manager Approval : </b> {{ ($leave->div_manager_approval_resp) ? $leave->div_manager_approval_resp : 'Pending' }}</p>
            <butto onclick="window.location.href='leave-request-edit/{{ $leave->id }}'" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
        </div> 
    @endforeach

    {{ $leaves->links()  }}
</div>