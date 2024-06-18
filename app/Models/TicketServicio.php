<?php

namespace App\Models;

use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketServicio extends Pivot
{
    protected $table = 'ticket_servicio';

    protected $primaryKey = 'pivot_id';
    public $incrementing = true;
    protected $guarded = [
        'pivot_id',
        'created_at',
        'updated_at',
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function user()
    {

        return $this->belongsTo(User::class);
    }
}
