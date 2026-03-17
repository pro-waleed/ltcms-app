<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_no',
        'full_name',
        'department_id',
        'mission_id',
        'job_title',
        'job_grade',
        'education_level',
        'specialization',
        'languages',
        'language_level',
        'years_of_service',
        'work_location',
        'employment_status',
        'previous_opportunities_count',
        'last_training_date',
        'notes',
    ];

    protected $casts = [
        'last_training_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function nominations()
    {
        return $this->hasMany(Nomination::class);
    }

    public function trainingHistory()
    {
        return $this->hasMany(TrainingHistory::class);
    }

    public function applicationRequests()
    {
        return $this->hasMany(ApplicationRequest::class);
    }
}
