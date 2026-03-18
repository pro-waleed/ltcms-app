<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'training_history';

    protected $fillable = [
        'employee_id',
        'opportunity_id',
        'nomination_id',
        'completion_status',
        'certificate_received',
        'completion_date',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'created_at' => 'datetime',
        'certificate_received' => 'boolean',
    ];

    public static function completionStatusLabels(): array
    {
        return [
            'completed' => 'مكتمل',
            'not_completed' => 'غير مكتمل',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function nomination()
    {
        return $this->belongsTo(Nomination::class);
    }
}
