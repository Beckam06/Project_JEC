<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'notes',
        'receptor'
    ];

    protected $casts = [
        'receptor' => 'string'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}