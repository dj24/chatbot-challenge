<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
//Chatbotapp1!


class ChatsController extends Controller
{
  private $book = array(
    "message" => "What time?"
  );
  public $flow = array(
    "message" => "Welcome to Meeting Bot. What would you like to do?",
    "options" => array(
      "Book a room" => array(
        "message" => "Which room?",
        "options" => array("Boardroom","Meeting Room A","Meeting Room B"),
        "next" => array(
          "message" => "What time between 9am and 5pm?",
          "next" => "For how many hours?"
        )
      ),
      "View my bookings" => ""
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
      if($key == $message){
        //if options exist for this node, trigger options event
        if(isset($value['options'])){
          event(new OptionsEvent($value['options']));
        }
        return $value['message'];
      }
      else if($key == "next" && $value = getLastBotMessage($user)){
        return "Processing Input HERE";
      }
    }
    //2nd level traversal
    foreach($current_options as $option){
      if(isset($option['options'])){
        foreach($option['options'] as $suboption){
          if($suboption == $message && isset($option['next'])){
            if(isset($option['next']['message'])){
              return $option['next']['message'];
            }
            else if(isset($option['next'])){
              return $option['next'];
            }
          }
        }
      }
    }
    //3rd level traversal
    foreach($current_options as $option){
      error_log("LAST MSG: " . $this->getLastBotMessage($user));
      //error_log("LAST MSG: " . $this->getLastBotMessage($user));
      if(isset($option['next']['next']) && $this->getLastBotMessage($user) == $option['next']){
        return $option['next']['next'];
      }
    }
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
