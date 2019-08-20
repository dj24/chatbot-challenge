<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;


class ChatsController extends Controller
{
  private $book = array(
    "message" => "What time?"
  );
  public $flow = array(
    "message" => "Welcome to Meeting Bot. What would you like to do?",
    "options" => array(
      "Book" => "",
      "View" => ""
    )
  );

  public function __construct()
  {
    //$this->middleware('auth');
  }

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
      $message = "Hi " . $name .", welcome to Meeting Bot. What would you like to do?";
      event(new MessageEvent($message,$name));
    }
    $options = [];
    foreach ($this->flow as $key => $value){
      $options[] = $key;
    }
    event(new OptionsEvent($options));
    return $user->messages;
    // return array(
    //   'message' => "hello world",
    //   'type' => 'bot'
    // );
  }

  /**
  * Persist message to database
  *
  * @param  Request $request
  * @return Response
  */
  public function sendMessage(Request $request)
  {

    $message = $request->input('message');
    $name = $request->input('name');
    $user = \App\User::firstOrCreate(['name' => $name]);
    $output = array(
      'message' => $message,
      'type' => 'user'
    );
    $user->messages()->create($output);
    event(new OptionsEvent(["Option 1","Option 2","Option 3"]));
    event(new MessageEvent($message,$name));
    return $output;
  }
}
