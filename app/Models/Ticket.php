<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'payment_method',
        'total',
        'total_dto',
        'description',
        'cliente_id'
    ];

    protected $attributtes = [
        'status',
        'payment_method',
        'total',
        'total_dto',
        'description',
        'cliente_id',
        'servicio_id'
    ];

    protected $casts = [
        'total' => 'float:2',
        'total_dto' => 'float:2',
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, TicketServicio::class, 'ticket_id')->withPivot(['discount']);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, TicketServicio::class, 'ticket_id');
    }
}
