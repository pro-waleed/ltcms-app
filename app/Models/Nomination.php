<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomination_no',
        'opportunity_id',
        'employee_id',
        'external_entity_id',
        'nominated_by_department_id',
        'nomination_date',
        'nomination_type',
        'status',
        'accepted',
        'declined',
        'attended',
        'certificate_received',
        'notes',
        'nomination_reason',
    ];

    protected $casts = [
        'nomination_date' => 'date',
        'accepted' => 'boolean',
        'declined' => 'boolean',
        'attended' => 'boolean',
        'certificate_received' => 'boolean',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function externalEntity()
    {
        return $this->belongsTo(ExternalEntity::class);
    }

    public function nominatedByDepartment()
    {
        return $this->belongsTo(Department::class, 'nominated_by_department_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
