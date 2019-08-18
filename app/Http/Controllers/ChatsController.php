<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;


class ChatsController extends Controller
{


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
  * @return Response
  */
  public function fetchMessages(Request $request)
  {
    $name = $request->input('name');
    $user = \App\User::firstOrCreate(['name' => $name]);
    //return Message::with(['user_name' => $name])->get();
    //return Message::all()->get();
    return array('name' => 'Steve', 'state' => 'CA');
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
    event(new MessageEvent($message,$name));
    return $output;
  }
}
