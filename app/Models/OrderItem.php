<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Or Relations\Pivot if it were a simple pivot
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model // Extending Model is fine as it has its own ID and attributes
{
    use HasFactory;

    protected $table = 'OrderItems';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'OrderId',
        'ProductId',
        'Quantity',
        'UnitPrice',
    ];

    protected $casts = [
        'UnitPrice' => 'decimal:2',
        'Quantity' => 'integer',
    ];

    /**
     * Get the order that this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'OrderId', 'Id');
    }

    /**
     * Get the product associated with this order item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}