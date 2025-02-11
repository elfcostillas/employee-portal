<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Me
{
    //
    public $att;
    
    public function __construct()
    {
        $this->att = $this->get_201();
    }

    public function get_201()
    {
      
        $result = DB::connection('hris')->table('employees')
        ->where('biometric_id',Auth::user()->biometric_id)
        ->first();

        return $result;
    }

    public function my_empLevel()
    {
        return $this->att->emp_level;
    }

    public function my_dept()
    {
        return $this->att->dept_id;
    }

    public function my_division()
    {
        return $this->att->division_id;
    }


}

/*
emp_level
dept_id
division_id
*/
