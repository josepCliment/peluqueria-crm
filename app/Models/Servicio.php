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
    protected $attributtes = [
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
        'created_at',
        'id'
    ];


    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, TicketServicio::class, 'servicio_id')
            ->withPivot(['pivot_id', 'discount', 'user_id', 'cprice', 'quantity', 'ticket_id'])->using(TicketServicio::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, TicketServicio::class)
            ->withPivot(['pivot_id', 'discount', 'ticket_id', 'cprice', 'quantity'])->using(TicketServicio::class);
    }
}
