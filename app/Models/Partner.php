<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'partner_type',
        'geographic_level',
        'strategic_importance',
        'sector',
        'country',
        'partnership_nature',
        'cooperation_areas',
        'contact_name',
        'contact_email',
        'contact_phone',
        'typical_opportunities',
        'typical_funding',
        'evaluation_notes',
        'status',
    ];

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }
}
