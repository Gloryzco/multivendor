<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'status', 'image_path'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
