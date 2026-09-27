<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['owner_id', 'title', 'description', 'status', 'deadline', 'roles', 'milestones', 'documents'];
    protected $casts = ['deadline' => 'date', 'roles' => 'array', 'milestones' => 'array', 'documents' => 'array'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
