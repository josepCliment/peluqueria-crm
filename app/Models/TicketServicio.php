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
        'ticket_id',
        'servicio_id',
        'user_id',
        'discount',
    ];

    protected $attributtes = [
        'id',
        'ticket_id',
        'servicio_id',
        'user_id',
        'discount',
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
