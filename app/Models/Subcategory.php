<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug'];

    public function sale(): MorphOne
    {
        return $this->morphOne(Sale::class, 'saleable');
    }

    // Define the relationship with the parent category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
