<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketServicio extends Pivot
{
    use HasFactory;

    public $table = "ticket_servicio";

    protected $fillable = [
        'id',
        'servicio_id',
        'user_id',
        'discount',
        'price'
    ];

    protected $attributtes = [
        'id',
        'servicio_id',
        'user_id',
        'discount',
        'created_at',
        'ticket_id',
        'price'
    ];



    protected $casts = [
        'discount' => 'float:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
