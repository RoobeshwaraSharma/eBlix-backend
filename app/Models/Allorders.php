<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allorders extends Model
{
    protected $fillable = ['name',  'price', 'quantity'];

}
