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

    // Define order statuses as constants for consistency
    public const STATUS_PENDING = 'Pending';
    public const STATUS_PROCESSING = 'Processing';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_SHIPPED = 'Shipped'; // If applicable
    public const STATUS_DELIVERED = 'Delivered'; // If applicable
    public const STATUS_CANCELLED = 'Cancelled';
    public const STATUS_FAILED = 'Failed';


    protected $fillable = [
        'UserId',
        'OrderDate',
        'TotalAmount',
        'Status',
        'ShippingAddressId',
        'BillingAddressId',
        // 'Notes',
    ];

    protected $casts = [
        'OrderDate' => 'datetime',
        'TotalAmount' => 'decimal:2',
    ];

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'ShippingAddressId', 'Id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'BillingAddressId', 'Id');
    }

    /**
     * Get the items for the order.
     * We'll create the OrderItem model next.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'OrderId', 'Id');
    }

    /**
     * Get the payment associated with the order.
     * (Assuming one-to-one or one-to-many if multiple payment attempts)
     * We'll create the Payment model later.
     */
    // public function payment(): HasOne or HasMany
    // {
    //    return $this->hasOne(Payment::class, 'OrderId', 'Id');
    // }

    public function payment(): HasOne
    {
       return $this->hasOne(Payment::class, 'OrderId', 'Id');
    }
}