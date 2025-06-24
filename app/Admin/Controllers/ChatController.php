<?php
namespace App\Admin\Controllers;

use App\Models\Organisation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ChatController extends AdminController
{
    public function index(Content $content)
    {
        $senderId      = Auth::id();
        $receiverParam = request()->query('receiver_id');

        // No receiver_id? Show the “inbox” view instead of crashing.
        if (! $receiverParam) {
            return $content
                ->header('My Chats')
                ->description('Select a conversation')
                ->body(view('vendor.admin.partials.chat.list'));
        }

        // Load whichever Organisation (DU or OPD) you clicked on
        $org        = Organisation::findOrFail($receiverParam);
        $receiverId = $org->user_id;   // this must be set on both DU and OPD records

        if ($senderId === $receiverId) {
            throw ValidationException::withMessages([
                'receiver_id' => 'You cannot send a message to yourself.',
            ]);
        }

        return $content
            ->header('Chat with ' . $org->name)
            ->description('Conversations')
            ->body(view('vendor.admin.partials.chat.list', [
                'receiverId' => $receiverId,
            ]));
    }
}
