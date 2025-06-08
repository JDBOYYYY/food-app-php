<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'Payments';
    protected $primaryKey = 'Id';
    public $incrementing = true;
    protected $keyType = 'int';

    public const STATUS_PENDING = 'Pending';
    public const STATUS_SUCCEEDED = 'Succeeded';
    public const STATUS_FAILED = 'Failed';
    public const STATUS_REFUNDED = 'Refunded';

    protected $fillable = [
        'OrderId',
        'PaymentDate',
        'Amount',
        'PaymentMethod',
        'TransactionId',
        'Status',
    ];

    protected $casts = [
        'PaymentDate' => 'datetime',
        'Amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'OrderId', 'Id');
    }
}