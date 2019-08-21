<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Booking;
//Chatbotapp1!


class ChatsController extends Controller
{
  private $book = array(
    "message" => "What time?"
  );
  public $flow = array(
    "message" => "What would you like to do?",
    "options" => array(
      "Book a room" => array(
        "message" => "Which room?",
        "options" => array("Boardroom","Meeting Room A","Meeting Room B"),
        "next" => "What time?"
      ),
      "View bookings" => array(
        "message" => "Here are your bookings: "
      )
    ),
    "error" => "I'm not sure what you would like me to do"
  );
  /**
  * Show chats
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return view('chat');
  }
  /**
  * Generate root options event
  */
  public function rootOptions(){
    $options = [];
    foreach ($this->flow['options'] as $key => $value){
      $options[] = $key;
    }
    event(new OptionsEvent($options));
  }
  /**
  * Fetch all messages
  *
  * @param  Request $request
  * @return Response
  */
  public function fetchMessages($name)
  {
    $user = \App\User::firstOrCreate(['name' => $name]);

    $message_count = count($user->messages);
    if($message_count == 0){
      $this->flow['message'] = "Hi " . $name .", welcome to Meeting Bot. What would you like to do?";
      event(new MessageEvent($this->flow['message'],$name));
    }
    $this->rootOptions();
    return $user->messages;
  }
  /**
  * Persist message to database
  *
  * @param  Request $request
  */
  private function saveMessage(Request $request){
    $message = $request->input('message');
    $name = $request->input('name');
    $user = \App\User::firstOrCreate(['name' => $name]);
    $output = array(
      'message' => $message,
      'type' => 'user'
    );
    $user->messages()->create($output);
  }
  /**
  * Gets the last message sent to the user
  *
  * @param  User $user
  * @return string
  */
  private function getLastBotMessage($user){
    return $user->messages->where('user_name',$user->getKey())->last()->message;
  }
  /**
  * Gets the last message sent from the user
  *
  * @param  User $user
  * @return string
  */
  private function getLastUserMessage($user){
    return $user->messages
        ->where('user_name',$user->getKey())
        ->where('type','user')
        ->last()['message'];
  }

  /**
  * Interpret time string
  *
  * @param  $message
  * @return string
  */
  private function interpretTime($user,$message){
    $hour = date_parse($message)['hour'];
    if(!empty($hour)){
      //hour succesfully parsed
      //FIXME: If incorrect room is entered, this will break
      //also  needs to not use hard coded value
      $rooms = [];
      $room = $user->messages
        ->where('message','Boardroom')
        ->last();
      if ($room !== null){
        $room = $room->message;
      }
      $room2 = $user->messages
        ->where('message','Meeting Room A')
        ->last();
      if ($room2 !== null){
        if($room == null ){
          $room = $room2->message;
        }
      }
      $room3 = $user->messages
        ->where('message','Meeting Room B')
        ->last();
      if ($room3 !== null){
        if($room == null ){
          $room = $room3->message;
        }
      }
      //create time string
      if($hour < 13){
        $suffix = "am";
      }
      else{
        $hour -= 12;
        $suffix = "pm";
      }
      $time_str = $hour . $suffix;
      $bookingCheck = Booking::where('time',$time_str)
        ->where('room',$room)
        ->first();
      if ($bookingCheck === null) {
         //no booking found
         $booking = array(
           'time' => $time_str,
           'room' => $room
         );
         $user->bookings()->create($booking);
         $output = "Congrats! You have booked " . $room . " for " . $time_str;
         $output .= ". Is there anything else?";
         $this->rootOptions();
         return $output;
      }
      else{
        //booking found, show error
        return "Sorry, " .
         $bookingCheck['user_name'] .
          " has already booked " . $room .
          " for " . $time_str .
          ". Try another time";
      }
    }
    else{
      //hour cannot be parsed
      return "Sorry, please try a different format";
    }
  }

  /**
  * Interpret user message and return
  *
  * @param  Request $request
  * @return string
  */
  private function parseMessage(Request $request){
    $message = $request->input('message');
    $name = $request->input('name');
    $user = \App\User::firstOrCreate(['name' => $name]);
    $last = $this->getLastUserMessage($user);
    $current_options = $this->flow['options'];

    //1st level traversal
    foreach($current_options as $key => $value){
      if(strtolower($key) == strtolower($message)){
        //if options exist for this node, trigger options event
        if(isset($value['options'])){
          event(new OptionsEvent($value['options']));
        }
        if(strtolower($message) == "view bookings"){
          //build string displaying all bookings
          $output = $value['message'];
          foreach($user->bookings as $booking){
            $output .= $booking['room'] . " at " . $booking['time'] . ", ";
          }
          return $output;
        }
        return $value['message'];
      }
    }
    //2nd level traversal
    foreach($current_options as $option){
      if(isset($option['options'])){
        foreach($option['options'] as $suboption){
          if(strtolower($suboption) == strtolower($message) && isset($option['next'])){
            if(isset($option['next'])){
              return $option['next'];
            }
          }
          else if($this->getLastBotMessage($user) == $option['next'] || strpos($this->getLastBotMessage($user), 'Sorry') !== false ){
            return $this->interpretTime($user,$message);
          }
        }
      }
    }
    if(strtolower($message) == "what is love"){
      return "Baby don't hurt me";
    }
    else if(strtolower($message) == "clear db"){
      Booking::query()->delete();
      Message::query()->delete();
      return "DB CLEARED";
    }
    $this->rootOptions();
    return $this->flow['error'];
  }

  /**
  * Process incoming user message
  *
  * @param  Request $request
  * @return Response
  */
  public function sendMessage(Request $request)
  {
    $name = $request->input('name');
    sleep(1);
    $response = $this->parseMessage($request);
    $this->saveMessage($request);
    event(new MessageEvent($response,$name));
    return $response;
  }
}
