<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketProducto extends Pivot
{
    use HasFactory;

    public $table = "ticket_producto";



    protected $fillable = [
        'discount',
        'price',
    ];

    protected $attributtes = [
        'ticket_id',
        'producto_id',
        'user_id',
        'discount',
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
