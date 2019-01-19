<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatEvent;
use App\Events\ReadMessageEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ChatController extends Controller
{
    public function index(){
        $chats = Chat::where([['contact_id' ,'=', Auth::user()->id ],['read' ,'=',0],])->get();
        return $chats;
    }
    public function show($userid ,$contactid){
        $chats = Chat::where([['user_id' ,'=', $contactid ],['contact_id' ,'=',$userid],])
            ->orWhere([['user_id' ,'=', $userid ],['contact_id' ,'=', $contactid] ,])
            ->get();

        return $chats;
    }

    public function store(Request $request){
        $chat = new Chat();
        $user = User::find($request->user_id);
        $chat->user_id = $request->user_id;
        $chat->contact_id = $request->contact_id;
        $chat->message = $request->message;
        $chat->save();
        event(new ChatEvent($chat,$user));
        return Response()->json(['etat' => true]);
    }

    public function storeicon(Request $request){
        $chat = new Chat();
        $user = User::find($request->user_id);
        $chat->user_id = $request->user_id;
        $chat->contact_id = $request->contact_id;
        $chat->message = $request->message;
        $chat->save();
        event(new ChatEvent($chat,$user));
        return Response()->json(['etat' => true]);
    }

    public function storeimage(Request $request){
        $chat = new Chat();
        $user = User::find($request->user_id);

        $chat->user_id = $request->user_id;
        $chat->contact_id = $request->contact_id;
        $chat->message = '';

        $file = $request->get('image');
        $fileDB =  time().'.' . explode('/', explode(':', substr($file, 0,
                strpos($file, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('images/chat/').$fileDB);

        $chat->image = $fileDB;
        $chat->save();
        event(new ChatEvent($chat,$user));
        return Response()->json(['etat' => true]);
    }
    public function update($userid,$contactid){
        $chat = Chat::where([['user_id' ,'=', $contactid ],['contact_id' ,'=',$userid],])
                    ->update(array('read' => 1));
        event(new ReadMessageEvent($userid,$contactid));

    }

}
