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
  * @return Message
  */
  public function fetchMessages()
  {
    return Message::with('user')->get();
  }

  /**
  * Persist message to database
  *
  * @param  Request $request
  * @return Response
  */
  public function sendMessage(Request $request)
  {
    // $user = Auth::user();
    //
    // $message = $user->messages()->create([
    //   'message' => $request->input('message')
    // ]);
    $message = $request->input('message');
    $obj = array(
      'time' => time(),
      'message' => $message,
      'type' => 'user'
    );
    event(new MessageEvent($message));
    return $obj;
  }
}
