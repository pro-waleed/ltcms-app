<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'title',
        'opportunity_type_id',
        'summary',
        'provider_entity',
        'organizer_entity',
        'delivery_mode',
        'location_country',
        'location_city',
        'location_platform',
        'language',
        'start_date',
        'end_date',
        'duration_days',
        'seats',
        'nomination_deadline',
        'target_group',
        'eligibility_requirements',
        'admin_notes',
        'status',
        'partner_id',
        'funding_detail_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'nomination_deadline' => 'date',
    ];

    public function type()
    {
        return $this->belongsTo(OpportunityType::class, 'opportunity_type_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function fundingDetail()
    {
        return $this->belongsTo(FundingDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nominations()
    {
        return $this->hasMany(Nomination::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class);
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
