
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <?php
        use Carbon\Carbon;
    ?>

    @foreach($leaves as $leave)
        <?php
            $date_from = Carbon::createFromFormat('Y-m-d', $leave->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d', $leave->date_to);

        ?>
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Name :</b> {{ $leave->name }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Leave Date : </b> {{ $date_from->format('M d, Y') }} - {{ $date_to->format('M d, Y') }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Type : </b> {{ $leave->leave_type_desc }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Reason : </b> {{ $leave->leave_reason }}</p>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>With Pay : </b> {{ round($leave->with_pay,1) }} Day(s) &emsp;&emsp;<b> Without Pay : </b> {{ round($leave->without_pay,1) }} Day(s)</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Supervisor Approval : </b> {{ ($leave->sup_approval_resp) ? $leave->sup_approval_resp : 'Pending' }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Department Manager Approval : </b> {{ ($leave->manager_approval_resp) ? $leave->manager_approval_resp : 'Pending' }}</p>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Division Manager Approval : </b> {{ ($leave->div_manager_approval_resp) ? $leave->div_manager_approval_resp : 'Pending' }}</p>
            <butto onclick="window.location.href='leave-approval-view/{{ $leave->id }}'" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
        </div> 
    @endforeach

    {{ $leaves->links()  }}
</div>