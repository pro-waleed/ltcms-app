<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'username',
        'full_name',
        'email',
        'password',
        'is_active',
        'approval_status',
        'approved_at',
        'approved_by',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function isEmployeeAccount(): bool
    {
        return !is_null($this->employee_id);
    }

    public function isApprovedForOpportunities(): bool
    {
        if (!$this->isEmployeeAccount()) {
            return true;
        }

        return $this->approval_status === 'approved';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole(string $name): bool
    {
        return $this->roles()->where('name', $name)->exists();
    }

    public function createdOpportunities()
    {
        return $this->hasMany(Opportunity::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class, 'changed_by');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
