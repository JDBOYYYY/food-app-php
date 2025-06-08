<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
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

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'OrderId', 'Id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}