<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Project;
use App\Models\TeamRequest;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'study_program',
        'semester',
        'university',
        'team_status',
        'skills',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'skills' => 'array',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('role');
    }

    public function sentTeamRequests()
    {
        return $this->hasMany(TeamRequest::class, 'sender_id');
    }

    public function receivedTeamRequests()
    {
        return $this->hasMany(TeamRequest::class, 'recipient_id');
    }
}