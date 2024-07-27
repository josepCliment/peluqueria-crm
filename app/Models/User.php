<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRoleEnum;
use Filament\Forms\Components\Builder;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    public function canAccessPanel(Panel $panel): bool
    {
        // if ($panel->getId() === 'admin') {
        //     return $this->isAdmin();
        // }

        return true;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];


    protected $attributtes = [
        'name',
        'email',
        'role'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRoleEnum::Superadmin->value;
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
    public function servicios()
    {
        return $this->hasMany(TicketServicio::class, 'user_id', 'id');
    }
}
