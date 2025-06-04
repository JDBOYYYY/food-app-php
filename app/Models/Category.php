<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // For relationships
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Categories'; // To match the migration table name

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'Id'; // To match your 'Id' column

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Name',
    ];

    /**
     * Define the relationship to Products.
     * We'll assume a Product model will exist and have a 'CategoryId' foreign key.
     */
    public function products(): HasMany
    {
        // Assumes Product model will have 'CategoryId' foreign key
        return $this->hasMany(Product::class, 'CategoryId', 'Id');
    }

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(
            Restaurant::class,        // Related model
            'RestaurantCategories',   // Pivot table name
            'CategoryId',             // Foreign key on pivot table related to current model (Category)
            'RestaurantId'            // Foreign key on pivot table related to related model (Restaurant)
        );
    }

    /**
     * Define the relationship to RestaurantCategories.
     * This is a many-to-many relationship through a pivot table.
     * We'll define Restaurant and RestaurantCategory models later.
     */
    // public function restaurants()
    // {
    //     return $this->belongsToMany(Restaurant::class, 'RestaurantCategories', 'CategoryId', 'RestaurantId');
    // }
}
