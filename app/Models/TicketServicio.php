<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketServicio extends Pivot
{
    use HasFactory;

    public $table = "ticket_servicio";

    protected $fillable = [
        'discount',
        'price',
    ];

    protected $attributtes = [
        'ticket_id',
        'servicio_id',
        'user_id',
        'discount',
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function servicios()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
