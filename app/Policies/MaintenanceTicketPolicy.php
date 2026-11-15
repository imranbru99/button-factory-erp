<?php

namespace App\Policies;

use App\Models\MaintenanceTicket;
use App\Models\User;

class MaintenanceTicketPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'maintenance';
    }
}
