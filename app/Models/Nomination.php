<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomination_no',
        'opportunity_id',
        'employee_id',
        'application_request_id',
        'external_entity_id',
        'nominated_by_department_id',
        'nomination_date',
        'nomination_type',
        'status',
        'selection_category',
        'rank_order',
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

    public static function statusLabels(): array
    {
        return [
            'nominated' => 'مرشح',
            'under_review' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'reserve' => 'احتياطي',
            'rejected' => 'مرفوض',
            'declined' => 'معتذر',
            'attended' => 'شارك',
            'not_attended' => 'لم يشارك',
            'completed' => 'مكتمل',
            'closed' => 'مغلق',
        ];
    }

    public static function selectionLabels(): array
    {
        return [
            'primary' => 'أساسي',
            'reserve' => 'احتياطي',
        ];
    }

    public static function nextNumber(): string
    {
        $year = date('Y');
        $prefix = "NOM-$year-";
        $last = static::where('nomination_no', 'like', $prefix . '%')
            ->orderByDesc('nomination_no')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $suffix = Str::after($last->nomination_no, $prefix);
            $nextNumber = max(1, intval($suffix) + 1);
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function applicationRequest()
    {
        return $this->belongsTo(ApplicationRequest::class, 'application_request_id');
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
