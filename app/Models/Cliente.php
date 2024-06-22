<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'description'
    ];

    protected $attributtes = [
        'name',
        'phone',
        'description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];


    public function totalDebt()
    {
        return $this->tickets()->where('status', '=', 'debt')->sum('total');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
