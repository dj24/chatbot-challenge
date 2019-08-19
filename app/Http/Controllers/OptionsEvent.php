<?php

namespace App\Http\Controllers;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OptionsEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $options;

  public function __construct($options)
  {
      $this->options = $options;
  }

  public function broadcastOn()
  {
      return ['chat'];
  }

  public function broadcastAs()
  {
      return 'options';
  }
}
