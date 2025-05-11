<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'discount', // Added discount field
    ];

    // Optional: Method to calculate the discounted price
    public function discountedPrice()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }
}
