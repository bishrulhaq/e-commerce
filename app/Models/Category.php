<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function sale(): MorphOne
    {
        return $this->morphOne(Sale::class, 'saleable');
    }

    // Define the relationship with subcategories
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    // Define the relationship with products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
