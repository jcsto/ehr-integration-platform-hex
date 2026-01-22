<?php

namespace App\Ehr\Application\Port;

interface EventBus
{
    public function publish(object $event): void;
}
