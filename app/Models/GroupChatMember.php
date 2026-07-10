<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupChatMember extends Model
{
    protected $fillable = [
        'group_chat_id',
        'user_id',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(GroupChat::class, 'group_chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
