<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $attributtes = [
        'status',
        'payment_method',
        'total',
        'total_dto',
        'description'
    ];

    protected $casts = [
        'total' => 'float:2',
        'total_dto' => 'float:2',
    ];
    public function getTotalAttribute($value)
    {
        return number_format($value, 2, '.', '') . '€'; // Format with 2 decimals, no thousands separator, and euro symbol
    }
    public function getTotal_dtoAttribute($value)
    {
        return number_format($value, 2, '.', '') . '€'; // Format with 2 decimals, no thousands separator, and euro symbol
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class);
    }
}
