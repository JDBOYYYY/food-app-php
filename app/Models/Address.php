<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'Addresses'; // Matches your migration

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'Id'; // Matches your migration

    /**
     * Indicates if the model's ID is auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     */
    protected $keyType = 'int'; // Matches your migration

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'Street',
        'Apartment',
        'City',
        'PostalCode',
        'Country',
        'UserId', // Foreign key column
    ];

    /**
     * Get the user that owns the address.
     * This defines the inverse of a one-to-many relationship.
     * An Address belongs to a User.
     */
    public function user(): BelongsTo
    {
        // First argument: Related model (User::class)
        // Second argument (optional): Foreign key on the current model's table ('Addresses' table -> 'UserId' column)
        // Third argument (optional): Owner key on the parent model's table ('users' table -> 'id' column)
        return $this->belongsTo(User::class, 'UserId', 'id');
    }
}
