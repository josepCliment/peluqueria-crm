<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case SUPERADMIN = 'superadmin';
    case USER = 'user';
}
