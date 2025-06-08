<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Orders';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PROCESSING = 'Processing';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_SHIPPED = 'Shipped';
    public const STATUS_DELIVERED = 'Delivered';
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_FAILED = 'Failed';

    protected $fillable = [
        'UserId',
        'OrderDate',
        'TotalAmount',
        'Status',
        'ShippingAddressId',
        'BillingAddressId',
    ];

    protected $casts = [
        'OrderDate' => 'datetime',
        'TotalAmount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'ShippingAddressId', 'Id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'BillingAddressId', 'Id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'OrderId', 'Id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'OrderId', 'Id');
    }
}