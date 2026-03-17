<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'entity_type',
        'contact_name',
        'contact_phone',
        'seats',
        'notes',
    ];

    public function nominations()
    {
        return $this->hasMany(Nomination::class);
    }
}
