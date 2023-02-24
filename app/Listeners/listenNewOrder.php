<?php

namespace App\Listeners;

use App\Events\NewOrder;
use App\Mail\MailOrder;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class listenNewOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewOrder  $event
     * @return void
     */
    public function handle(NewOrder $event)
    {
        $order = $event->order;
        $data['message'] = 'Có một đơn hàng mới';
        $data["order_id"] = $order->id;
        notification("my-channel",'my-event',$data);
        Mail::to($order->email)->send(new MailOrder($order));
        session()->forget("cart");
    }
}
