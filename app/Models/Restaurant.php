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
            Category::class,          // Related model
            'RestaurantCategories',   // Pivot table name
            'RestaurantId',           // Foreign key on pivot table related to current model (Restaurant)
            'CategoryId'            // Foreign key on pivot table related to related model (Category)
        );
        // If you want to access extra pivot table columns (if any): ->withPivot('column1', 'column2');
        // If your pivot table has timestamps and you want them: ->withTimestamps();
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'Favorites', 'ProductId', 'UserId')
                    ->using(Favorite::class); // Specify the custom pivot model
                    
    }

    // We'll add RestaurantCategories relationship later
    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'RestaurantCategories', 'RestaurantId', 'CategoryId');
    // }
}
