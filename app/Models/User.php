<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // We'll add this back when doing auth
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Temporarily just HasFactory, Notifiable
    use HasFactory, Notifiable;


    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Get the addresses for the user.
     * A User has many Addresses.
     */
    public function addresses(): HasMany
    {
        // First argument: Related model (Address::class)
        // Second argument (optional): Foreign key on the related model's table ('Addresses' table -> 'UserId' column)
        // Third argument (optional): Local key on the current model's table ('users' table -> 'id' column)
        return $this->hasMany(Address::class, 'UserId', 'id');
    }

    public function favoriteProducts(): BelongsToMany
    {
    return $this->belongsToMany(Product::class, 'Favorites', 'UserId', 'ProductId')
                ->using(Favorite::class)       // Specify the custom pivot model
                ->withPivot('DateAdded');     // Include the extra pivot column
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'UserId', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'UserId', 'id');
    }
}