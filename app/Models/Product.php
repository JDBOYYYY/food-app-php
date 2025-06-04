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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'Price' => 'decimal:2', // Ensure Price is treated as a decimal
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'Id');
    }

    /**
     * Get the restaurant that owns the product.
     * We'll create the Restaurant model next.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'RestaurantId', 'Id');
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'ProductId', 'Id');
    }

    // We'll add other relationships (Favorites, OrderItems, Reviews) later
    // when those models are created.

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'Favorites', 'ProductId', 'UserId')
                    ->using(Favorite::class)       // Specify the custom pivot model
                    ->withPivot('DateAdded');     // Include the extra pivot column
                    
    }
}
