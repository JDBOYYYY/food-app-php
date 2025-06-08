<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'Restaurants';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Name',
        'ImageUrl',
        'AverageRating',
        'DeliveryTime',
        'Distance',
        'PriceRange',
    ];

    protected $casts = [
        'AverageRating' => 'float',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'RestaurantId', 'Id');
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'RestaurantCategories',
            'RestaurantId',
            'CategoryId'
        );
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_restaurants');
    }
}