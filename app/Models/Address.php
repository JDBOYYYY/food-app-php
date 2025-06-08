<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $table = 'Addresses';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'Street',
        'Apartment',
        'City',
        'PostalCode',
        'Country',
        'UserId',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }
}