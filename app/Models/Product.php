<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'cost_price',
        'regular_price',
        'status',
        'stock'
    ];

    // Relationship with Sales (Polymorphic)
    public function sale(): MorphOne
    {
        return $this->morphOne(Sale::class, 'saleable');
    }

    // Get final price logic
    public function getFinalPriceAttribute()
    {
        if ($this->sale && now()->between($this->sale->start_date, $this->sale->end_date)) {
            return $this->sale->discount_price ?? ($this->regular_price * (1 - $this->sale->discount_percentage / 100));
        }

        return $this->regular_price;
    }

    // Define the relationship with categories
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

}
