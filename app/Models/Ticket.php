<?php

namespace App\Models;

use App\Enums\Ticket\TicketState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'total' => 'float:2',
        'total_dto' => 'float:2',
    ];

    public function is_paid()
    {
        return $this->status === TicketState::PAGADO->value;
    }

    public function calcularTotal()
    {
        $total = 0;

        // Itera sobre los servicios asociados al ticket
        foreach ($this->servicios as $servicio) {
            // Suma el precio del servicio menos el descuento aplicado
            $total += ($servicio->pivot->cprice * $servicio->pivot->quantity) - $servicio->pivot->discount;
        }
        $this->total = $total;
        $this->save();
    }
    public function getTotal()
    {
        $total = 0;
        // Itera sobre los servicios asociados al ticket
        foreach ($this->servicios as $servicio) {
            // Suma el precio del servicio menos el descuento aplicado
            $total += ($servicio->pivot->cprice * $servicio->pivot->quantity) - $servicio->pivot->discount;
        }
        return $total;
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            TicketServicio::class,
        )->withPivot(['pivot_id', 'ticket_id', 'discount', 'cprice', 'quantity', 'user_id'])->using(TicketServicio::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, TicketServicio::class)
            ->withPivot([['pivot_id', 'discount', 'servicio_id', 'cprice', 'quantity', 'ticket_id']])->using(TicketServicio::class);
    }
}
