<?php
namespace App\Admin\Controllers;

use App\Models\Chat;
use App\Models\Organisation;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChatController extends AdminController
{
    public function index(Content $content)
    {
        $senderId      = Auth::id();
        $getChatUser = Chat::getChatUser($senderId);
        $receiverParam = request()->query('receiver_id');

        // if no ?receiver_id → show inbox
          if (! $receiverParam) {
                return $content
                    ->header('My Chats')
                    ->description('Select a conversation')
                    // pass getChatUser here too!
                    ->body(view('vendor.admin.partials.chat.list', [
                        'getChatUser' => $getChatUser,
                    ]));
            }

        // find the org
        $org = Organisation::findOrFail($receiverParam);

        // try to resolve that organisation's admin user
        if ($org->user_id) {
            $getReceiver = User::find($org->user_id);
        } else {
            $getReceiver = null;
        }

        // fallback: look up by organisation_id on users table
        if (empty($getReceiver)) {
            $getReceiver = User::where('organisation_id', $org->id)->first();
        }

        if (empty($getReceiver)) {
            throw ValidationException::withMessages([
                'receiver_id' => 'No admin user found for this Organization.',
            ]);
        }

        $receiverId = $getReceiver->id;
       
        if ($senderId === $receiverId) {
            throw ValidationException::withMessages([
                'receiver_id' => 'You cannot send a message to yourself.',
            ]);
        }

        Chat::updateCount($senderId,$receiverId);
        $getchat = Chat::getChat($receiverId,$senderId);
        

        //dd($getChatUser);

        return $content
            ->header('Chat with ' . $org->name)
            ->description('Conversations')
            ->body(view('vendor.admin.partials.chat.list', compact(
                'receiverId','getReceiver','getchat','getChatUser'
            )));
    }




    public function submit_message(Request $request){

         //dd($request->all());

        $chat = new Chat();
        $chat->sender_id = Auth::user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->created_date = now();
        $chat->save();
        

        $getchat = Chat::where('id','=',$chat->id)->get();

        return response()->json([

            "status" => true,
            "success" => view('vendor.admin.partials.chat._single',[

                "getchat" => $getchat

            ])->render(),

        ],200);
        
    }


    public function get_chat_windows(Request $request)
            {
                $senderId      = Auth::id();
                $orgId         = $request->receiver_id;

                // bump your unread-count logic
                Chat::updateCount($senderId, $orgId);

                // 1) Find the Org, then resolve its admin **user** (same as index())
                $org = Organisation::findOrFail($orgId);

                if ($org->user_id) {
                    $getReceiver = User::find($org->user_id);
                } else {
                    $getReceiver = User::where('organisation_id', $org->id)->firstOrFail();
                }

                $receiverId = $getReceiver->id;

                // 2) Now fetch the chats between **two users**
                $getchat = Chat::getChat($receiverId, $senderId);

                // 3) Render the same partial _message, passing the **user** and the chat array
                $html = view('vendor.admin.partials.chat._message', [
                    'getReceiver' => $getReceiver,
                    'getchat'     => $getchat,
                    'receiverId'  => $receiverId,
                   
                ])->render();

                return response()->json([
                    'status'  => true,
                    'org_id'  => $orgId,      // <-- add this
                    'success' => $html,
                ], 200);
            }

}
