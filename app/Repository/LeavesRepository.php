<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeavesRepository
{
    //

    public function mainQuery(){
        return DB::table("leave_headers")
                ->join("leave_request_type","leave_headers.leave_type","=","leave_request_type.id");
    }

    public function myLeaves()
    {
        return $this->mainQuery()
        ->select(DB::raw("leave_headers.*,leave_type_desc"))
        ->where('requested_by',Auth::user()->id)
        ->orderBy('id','DESC');
    }

    public function myLeaveTypes()
    {
        return DB::table('leave_request_type')
            ->select(DB::raw("id,leave_type_desc"))
            ->where('id','!=',3)
            ->get();
    }
}
