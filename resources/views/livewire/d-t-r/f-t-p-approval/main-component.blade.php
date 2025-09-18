

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

<?php

use Carbon\Carbon;

?>
    @foreach($ftps as $ftp)
        <?php
            $ftp_date = Carbon::createFromFormat('Y-m-d', $ftp->ftp_date);
        
        ?>
        <div class="mb-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <h3 class="mb-3 font-bold tracking-tight text-gray-900 dark:text-white">{{ $ftp_date->shortEnglishDayOfWeek }} - {{ $ftp_date->format('M d, Y') }} </h3>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Name : </b> {{ $ftp->name }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Type : </b> {{ $ftp->ftp_type }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Time In : </b> {{ $ftp->time_in }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Time Out : </b> {{ $ftp->time_out }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>O.T. In : </b> {{ $ftp->overtime_in }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>O.T.Out : </b> {{ $ftp->overtime_out }}</p>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"> <b>Remarks : </b> {{ $ftp->ftp_remarks }}</p>
            <p class="mb-4 font-normal text-gray-700 dark:text-gray-400"> <b>Status : </b> {{ $ftp->ftp_state }}</p>

            <butto onclick="window.location.href='ftp-approval-view/{{ $ftp->id }}'" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
        </div>
    @endforeach
    {{ $ftps->links()  }}
</div>
