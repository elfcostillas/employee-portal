<?php

namespace App\Repository\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRights
{
    //
    private $user;

    function __construct()
    {
        $this->user = Auth::user();
    }

    function mainQuery()
    {
        return  DB::table('menu_subs')->select()
        ->join('menu_mains','menu_subs.main_id','=','menu_mains.id') 
        ->join('user_group_rights','menu_subs.id','=','user_group_rights.menu_sub_id')
        ->where('user_group_rights.group_id',$this->user->user_group_id);
    }

    function getRights()
    {
        // dd($this->user->id);
        
        $mains = $this->mainQuery()
            ->select('menu_mains.id','menu_mains.label','menu_mains.id','menu_mains.icon')
            ->distinct()
            ->get();

        foreach($mains as $main)
        {
            $subs = $this->mainQuery()
                    ->select(DB::raw("menu_subs.id,menu_subs.label,menu_subs.icon,menu_subs.path"))
                    ->where('main_id',$main->id)
                    ->get();

            $main->subs = $subs;
        }
       
        return $mains;
        
    }

    /*

    select * from menu_subs 
    inner join menu_mains on menu_subs.main_id = menu_mains.id
    INNER JOIN user_group_rights  on menu_sub_id = menu_subs.id
    where user_group_rights.group_id = 5


    function getRights()
    {
        // dd($this->user->id);
        
        $mains = $this->mainQuery()
            ->select('menu_mains.id','menu_mains.label','menu_mains.id','menu_mains.icon')
            ->distinct()
            ->get();

        foreach($mains as $main)
        {
            $subs = $this->mainQuery()
                    ->select(DB::raw("menu_subs.id,menu_subs.label,menu_subs.icon,menu_subs.path"))
                    ->where('main_id',$main->id)
                    ->get();

            $main->subs = $subs;
        }
       
        return $mains;
        
    }

    function mainQuery()
    {
        return  DB::table('menu_subs')->select()
        ->join('menu_mains','menu_subs.main_id','=','menu_mains.id') 
        ->join('menu_rights','menu_subs.id','=','menu_rights.menu_sub_id')
        ->where('menu_rights.user_id',$this->user->id);
    }
    */
    
}

/*
select * from menu_subs 
inner join menu_mains on menu_subs.main_id = menu_mains.id
inner join menu_rights on menu_subs.id = menu_rights.menu_sub_id
where menu_rights.user_id = 1
*/