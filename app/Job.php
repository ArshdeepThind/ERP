<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Job extends Model
{
    use Notifiable;

    protected $table = 'jobs';
    protected $primaryKey = 'job_id';

    protected $fillable = [
        'job_title','job_description','experience_required','no_of_vacancies','department_id','yearly_leaves'
    ];

    public function is_deleted()
    {
        $this->is_deleted = 1;
        $this->save();
    }

    public function employees(){
        return $this->belongsToMany('App\Employee')->withPivot('start_date','end_date');
    }
}
