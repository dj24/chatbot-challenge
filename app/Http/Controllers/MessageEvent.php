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

  public function __construct($message,$name)
  {
      $this->message = $message;
      $this->time = time();
      $this->type = "bot";
      $user = \App\User::firstOrCreate(['name' => $name]);
      $user->messages()->create([
         'message' => $this->message,
         'type' => 'bot'
      ]);
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
