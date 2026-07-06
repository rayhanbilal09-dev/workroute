<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'avatar',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function assignedIssues()
    {
        return $this->hasMany(Issue::class, 'assigned_to');
    }

    public function createdIssues()
    {
        return $this->hasMany(Issue::class, 'creator_id'); // Wait, we don't have creator_id on issues, wait!
        // Ah, did we have creator_id on issues table? 
        // Let's check: the TechDoc.md says Client has "dashboard berisi issue yang mereka buat."
        // Wait, how do we know which client created an issue?
        // Ah! We need to associate the issue with its creator!
        // The TechDoc.md table schema listed:
        // "Assigned To: Foreign Id (Relasi ke tabel users)."
        // But it did not list a creator/user_id column.
        // Wait, if it didn't list user_id/creator_id, how do we know which issue is created by which client?
        // Of course, we must add a `creator_id` or `user_id` column to the `issues` table to associate it with the creator!
        // Let's modify the migrations to add `creator_id` to the `issues` table, referencing `users.id`.
        // Let's edit `c:\xampp\htdocs\workroute\database\migrations\2026_07_06_000000_create_workroute_tables.php` first!
    }
}
