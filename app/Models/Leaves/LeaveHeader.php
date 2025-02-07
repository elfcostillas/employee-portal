<?php

namespace App\Models\Leaves;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveHeader extends Model
{
    use HasFactory;

    protected $table = 'leave_headers';
    
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'requested_by',
        'requested_on',
        'sup_apporval_by',
        'sup_apporval_on',
        'sup_approval_resp',
        // 'leave_status',
        // 'remarks',
        'hr_received',
        'hr_received_by',
        'hr_received_on',
        'is_canceled',
        'is_deleted',
        'manager_approval_by',
        'manager_approval_on',
        'manager_approval_resp',
        'div_manager_approval_by',
        'div_manager_approval_on',
        'div_manager_approval_resp',
        'date_from',
        'date_to',
        'leave_reason',
        'leave_type',
    ];
}
