<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'float:2'
    ];

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', '') . '€'; // Format with 2 decimals, no thousands separator, and euro symbol
    }
}
