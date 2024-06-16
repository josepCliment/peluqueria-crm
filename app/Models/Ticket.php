<?php

namespace App\Models;

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

    protected $casts = [
        'total' => 'float:2',
        'total_dto' => 'float:2',
    ];

    public function calcularTotal()
    {
        $total = 0;

        // Itera sobre los servicios asociados al ticket
        foreach ($this->servicios as $servicio) {
            // Suma el precio del servicio menos el descuento aplicado
            $total += $servicio->pivot->price - $servicio->pivot->discount;
        }
        $this->total = $total;
        $this->save();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'ticket_servicio',
        )->withPivot(['discount', 'user_id', 'price']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'ticket_servicio',)
            ->withPivot([['discount', 'servicio_id', 'price']]);
    }
}
