<?php

namespace App\Listeners;

use App\Events\BesoinEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BesoinEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BesoinEvent  $event
     * @return void
     */
    public function handle(BesoinEvent $event)
    {
        //
    }
}
