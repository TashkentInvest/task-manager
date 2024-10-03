<?php
namespace App\Listeners;

use App\Events\DemoProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessDemo implements ShouldQueue
{
    public function handle(DemoProcessed $event)
{
    $data = $event->data;

    \Log::info('Event data count: ' . $data);
}
}