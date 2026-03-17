<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'label',
        'is_active',
    ];
}
