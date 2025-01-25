<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // Add fillable, relationships, or other logic as needed
    protected $fillable = ['user_id', 'total_price', 'status'];
}
