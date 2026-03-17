<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'city',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
