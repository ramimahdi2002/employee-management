<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'email', 'phone', 'date_of_birth',
        'job_title', 'department', 'salary', 'start_date', 'end_date',
        'photo', 'documents','identities'
    ];

    protected $casts = [
        'documents' => 'array',
        'identities' => 'array',
        'date_of_birth' => 'date',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
