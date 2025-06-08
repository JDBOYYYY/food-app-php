<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'Categories';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['Name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'CategoryId', 'Id');
    }

    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(
            Restaurant::class,
            'RestaurantCategories',
            'CategoryId',
            'RestaurantId'
        );
    }
}