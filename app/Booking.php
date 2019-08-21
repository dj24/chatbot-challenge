<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

  public $name;
  public $time;
  
  protected $fillable = [
      'room','time'
  ];

  /**
 * A booking belong to a user
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
