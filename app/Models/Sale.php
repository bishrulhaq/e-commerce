<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', // Added Sale Title
        'saleable_id',
        'saleable_type',
        'discount_type', // 'fixed' or 'percentage'
        'discount_value',
        'start_date',
        'end_date',
    ];

    /**
     * Get the related model (Product, Category, etc.) for the sale.
     */
    public function saleable(): MorphTo
    {
        return $this->morphTo();
    }
}
