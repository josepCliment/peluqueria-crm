<?php

namespace App\Enums\Bills;

enum BillState: string
{
    case DEVUELTA = 'returned';
    case PAGADO_PARCIAL = 'partial_paid';
    case PAGADO = 'paid';
}
