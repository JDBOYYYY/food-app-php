<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// For models representing pivot tables, extend Illuminate\Database\Eloquent\Relations\Pivot
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Pivot // Extend Pivot for pivot models
{
    use HasFactory;

    protected $table = 'Favorites'; // Explicitly set table name

    // Since this is a pivot model, Laravel expects foreign keys to be defined
    // for the belongsToMany relationship. If you query this model directly,
    // these relationships help.

    public $incrementing = false; // No auto-incrementing ID for this pivot model
    protected $primaryKey = ['UserId', 'ProductId']; // Composite primary key
                                                    // Note: Eloquent doesn't fully support composite PKs out of the box
                                                    // for find() etc. Usually, you query by the individual keys.

    // If your pivot table does not have created_at and updated_at timestamps
    // (our migration for Favorites doesn't add them, only DateAdded)
    public $timestamps = false; // Set to true if you add $table->timestamps() to the migration

    protected $fillable = [
        'UserId',
        'ProductId',
        'DateAdded',
    ];

    protected $casts = [
        'DateAdded' => 'datetime',
    ];

    // Relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    // Relationship to Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}