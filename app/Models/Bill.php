<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'payment_date',
        "state"
    ];

    protected $casts = [
        "state" => "string",
        "payment_date" => "date",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];
}
