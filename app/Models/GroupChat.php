<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupChat extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'created_by',
        'is_main',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(GroupChatMember::class, 'group_chat_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_chat_members', 'group_chat_id', 'user_id')->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Chat::class, 'group_chat_id');
    }
}
