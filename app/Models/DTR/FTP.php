<?php

namespace App\Models\DTR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FTP extends Model
{
    use HasFactory;

    protected $table = 'ftp';
    
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'ftp_date',
        'time_in',
        'time_out',
        'overtime_in',
        'overtime_out',
        'ftp_state',
        'ftp_remarks',
        'requested_by',
        'requested_on',
        'ftp_type'
    ];


}
