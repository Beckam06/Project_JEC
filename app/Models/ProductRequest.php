<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity_requested',
        'receptor',
        'requester_name', 
        'purpose',
        'status',
        'notes',
        'processed_by',
        'processed_at',
         'is_new_product',
    'new_product_name',
    'new_product_description'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación con usuario que procesó
    public function processor() 
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}