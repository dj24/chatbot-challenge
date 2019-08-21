<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  public $name;
  protected $primaryKey = 'name';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = [
      'name',
  ];

  /**
   * A user can have many messages
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function messages()
  {
    return $this->hasMany(Message::class);
  }

  /**
   * A user can have many bookings
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
}
