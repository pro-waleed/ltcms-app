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

    public static function nextEmployeeNumber(): string
    {
        $lastNumber = static::query()
            ->where('employee_no', 'like', 'EMP-%')
            ->orderByDesc('employee_no')
            ->value('employee_no');

        $next = 1;

        if ($lastNumber) {
            $suffix = (int) preg_replace('/\D+/', '', $lastNumber);
            $next = max(1, $suffix + 1);
        }

        return 'EMP-' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }

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

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
