<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        // Notify the admin (for example, by sending an email)
        // You might want to create a new Mailable class for this
        Mail::to('admin@example.com')->send(new OrderNotification($event->order));

        // Log the order placement for debugging or monitoring purposes
        Log::info('New order placed:', ['order_id' => $event->order->id]);
    }
}
