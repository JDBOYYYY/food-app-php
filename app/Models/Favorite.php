<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Change from Pivot to Model
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model // <-- Change from Pivot to Model
{
    use HasFactory;

    protected $table = 'Favorites';

    // We now have a standard 'id' primary key, so we don't need these lines:
    // public $incrementing = false;
    // protected $primaryKey = ['UserId', 'ProductId'];

    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'ProductId',
        'RestaurantId', // <-- Add RestaurantId
        'DateAdded',
    ];

    protected $casts = [
        'DateAdded' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'RestaurantId', 'Id');
    }
}