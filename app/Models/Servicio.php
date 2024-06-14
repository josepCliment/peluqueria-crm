<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'price' => 'float:2'
    ];

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', '') . '€'; // Format with 2 decimals, no thousands separator, and euro symbol
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class);
    }
}
