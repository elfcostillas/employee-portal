<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class FTPRepository
{
    //

    public function __construct()
    {

    }

    public function mainQuery(){
        return DB::table("ftp");
    }

    public function myFTP()
    {
        
        return $this->mainQuery()
            ->where('requested_by',Auth::user()->id);
    }

    public function find($id)
    {
        return $this->mainQuery()
            ->select()
            ->where('id',$id)->first();
    }
}
