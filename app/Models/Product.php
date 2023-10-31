<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'is_featured',
        'store_id',
        'category_id',
        'brand',
        'availabile',
        'start_date',
        'end_date',
        'attributes'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product_image(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
