<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;

class EmployeeLeave extends Model
{
    use Notifiable;

    protected $table = 'employee_leave';

    protected $fillable = [
        'leave_id','employee_id','deducted_leaves','reason','is_approved','non_paid_leaves','start_date','end_date'
    ];

    public function is_approved()
    {
        $this->is_approved = 1;
        $this->save();
    }


}
