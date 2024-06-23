<?php

namespace App\Enums\Ticket;

enum TicketState: string
{
    case PAGADO = 'paid';
    case DEUDA = 'debt';
}
