<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'price', 'quantity', 'discount']; // Add discount to fillable

    // Method to calculate the discounted price
    public function discountedPrice()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }
}
