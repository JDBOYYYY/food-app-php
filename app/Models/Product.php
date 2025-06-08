<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Products';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Name',
        'Description',
        'Price',
        'ImageUrl',
        'RestaurantId',
        'CategoryId',
    ];

    protected $casts = [
        'Price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'Id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'RestaurantId', 'Id');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'ProductId', 'Id');
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_products');
    }
}