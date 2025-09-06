<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'sku'
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ProductRequest::class);
    }
}