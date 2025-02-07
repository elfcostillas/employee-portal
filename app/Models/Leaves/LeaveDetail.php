<?php

namespace App\Models\Leaves;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveDetail //extends Model
{
    // use HasFactory;

    public $carbon_date;
    
    public $formated_date;

    public $day_name;

    public function __construct()
    {
        // $this->carbon_date = $date;
        //$this->formated_date =(string) $date->format('m/d/Y');
        //$this->day_name = (string) $date->format('D');
    }

    public function dayname()
    {
        return $this->day_name;
    } 
    
    public function formatedDate()
    {
        return $this->formated_date;
    }


}
