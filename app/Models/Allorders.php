<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allorders extends Model
{
    protected $fillable = ['name', 'price', 'quantity', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
