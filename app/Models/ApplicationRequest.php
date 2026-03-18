<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'employee_id',
        'request_date',
        'status',
        'decision_reason',
        'notes',
    ];

    protected $casts = [
        'request_date' => 'date',
    ];

    public static function statusLabels(): array
    {
        return [
            'submitted' => 'مقدم',
            'under_review' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
            'withdrawn' => 'منسحب',
        ];
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function nomination()
    {
        return $this->hasOne(Nomination::class, 'application_request_id');
    }
}
