

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
<!-- <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Request FTP</button> -->
<button onclick="window.location.href='ftp-request-create'"  type="button" class="mb-2 px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
    <svg class="w-3.5 h-3.5 text-white me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
    <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
    <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
    </svg>
    Request FTP
</button>
<?php

use Carbon\Carbon;

?>
    @foreach($ftps as $ftp)
        <?php
            $ftp_date = Carbon::createFromFormat('Y-m-d', $ftp->ftp_date);

        
        ?>
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h3 class="mb-3 font-bold tracking-tight text-gray-900 dark:text-white">{{ $ftp_date->shortEnglishDayOfWeek }} - {{ $ftp_date->format('M d, Y') }} </h3>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Type : </b> {{ $ftp->ftp_type }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Time In : </b> {{ $ftp->time_in }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Time Out : </b> {{ $ftp->time_out }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>O.T. In : </b> {{ $ftp->overtime_in }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>O.T.Out : </b> {{ $ftp->overtime_out }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Remarks : </b> {{ $ftp->ftp_remarks }}</p>
         
             @if($this->my_level() >=5)
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Supervisor Approval : </b> {{ ($ftp->sup_approval_resp) ? $ftp->sup_approval_resp : 'Pending' }}</p>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Department Manager Approval : </b> {{ ($ftp->manager_approval_resp) ? $ftp->manager_approval_resp : 'Pending' }}</p>
                <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Division Manager Approval : </b> {{ ($ftp->div_manager_approval_resp) ? $ftp->div_manager_approval_resp : 'Pending' }}</p>
            @elseif($this->my_level() == 4)
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Department Manager Approval : </b> {{ ($ftp->manager_approval_resp) ? $ftp->manager_approval_resp : 'Pending' }}</p>
            @else
                <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Division Manager Approval : </b> {{ ($ftp->div_manager_approval_resp) ? $ftp->div_manager_approval_resp : 'Pending' }}</p>
            @endif
            <butto onclick="window.location.href='ftp-request-edit/{{ $ftp->id }}'" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
        </div>
    @endforeach
    {{ $ftps->links()  }}
</div>

<?php

/*
time_in
time_out
overtime_in
overtime_out
ftp_type
ftp_remarks
ftp_state
*/
?>