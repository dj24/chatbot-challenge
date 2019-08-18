<?php

namespace App\Http\Controllers;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $message;
  public $time;
  public $type;

  public function __construct($message)
  {
      $this->message = "BOT HAS RECIEVED: " . $message;
      $this->time = time();
      $this->type = "bot";
  }

  public function broadcastOn()
  {
      return ['chat'];
  }

  public function broadcastAs()
  {
      return 'message';
  }
}
