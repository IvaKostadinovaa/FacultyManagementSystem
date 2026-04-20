<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use LogsActivity;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'faculty_id',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

    ];


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProfessor()
    {
        return $this->role === 'professor';
    }

    public function isAssistant()
    {
        return $this->role === 'assistant';
    }

    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }
    public function subjectsAsProfessor() {
        return $this->hasMany(Subject::class, 'professor_id');
    }
    public function subjectsAsAssistant() {
        return $this->hasMany(Subject::class, 'assistant_id');
    }
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'professor', 'assistant']);
    }


}
