<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;


class NewLabOrder implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    // Channel yang akan digunakan
    public function broadcastOn()
    {
        return new Channel('lab-orders');
    }

    // Nama event yang muncul di frontend
    public function broadcastAs()
    {
        return 'new-order';
    }
}
