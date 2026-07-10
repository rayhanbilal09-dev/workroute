<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'type',
        'status',
        'priority',
        'subject',
        'title',
        'description',
        'deadline',
        'assigned_to',
        'creator_id',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($issue) {
            if (empty($issue->id)) {
                $latestIssue = static::orderByRaw('CAST(SUBSTRING(id, 5) AS UNSIGNED) DESC')->first();
                $nextNumber = 1;
                if ($latestIssue) {
                    $lastNumber = intval(substr($latestIssue->id, 4));
                    $nextNumber = $lastNumber + 1;
                }
                $issue->id = sprintf("ISS-%03d", $nextNumber);
            }
        });
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(IssueAttachment::class, 'issue_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(IssueHistory::class, 'issue_id');
    }
}
