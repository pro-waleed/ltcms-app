<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'funding_type',
        'training_fees',
        'international_tickets',
        'domestic_tickets',
        'accommodation',
        'meals',
        'local_transport',
        'health_insurance',
        'visa_fees',
        'per_diem',
        'training_materials',
        'tech_support',
        'ministry_obligations',
        'notes',
    ];

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }
}
