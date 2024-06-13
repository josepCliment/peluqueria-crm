<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;

    protected $fillable = ['name', 'role'];
    protected $hidden = ['deleted_at'];
}
