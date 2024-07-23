<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Superadmin = 'superadmin';
    case Empleado = 'user';
}
