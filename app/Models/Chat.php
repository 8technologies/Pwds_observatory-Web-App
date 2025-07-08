<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    use HasFactory;


    static public function getChat($receiverId,$senderId){
        $query = self::select('chats.*')
                      ->where(function($q) use ($receiverId,$senderId){
                        $q->where(function($q) use ($receiverId,$senderId){
                            $q->where('receiver_id',$senderId)
                                ->where('sender_id',$receiverId)
                                ->where('status','>','-1');
                        })->orWhere(function($q) use ($receiverId,$senderId){
                            $q->where('receiver_id',$receiverId)
                                ->where('sender_id',$senderId);
                                
                        });
                      })->where('message','!=','')
                        ->orderBy('id','asc')
                        ->get();
        return $query;                
    }


    public function getSender(){
        return $this->belongsTo(User::class,'sender_id');
    }

    public function getConnectUser(){
        return $this->belongsTo(User::class,'connect_user_id');
    }

    


    static public function getChatUser($senderId){
        $getuserchat = self::select('chats.*',DB::raw('(CASE WHEN chats.sender_id = "'.$senderId.'" THEN
                        chats.receiver_id ELSE chats.sender_id END) AS connect_user_id'))
                        ->join('users as sender','sender.id','=','chats.sender_id')
                        ->join('users as receiver','receiver.id','=','chats.receiver_id');

        $getuserchat = $getuserchat->whereIn('chats.id',function($query) use($senderId){
            $query->selectRaw('max(chats.id)')->from('chats')
            ->where('chats.status','<',2)
            ->where(function($query) use($senderId){
                $query->where('chats.sender_id','=',$senderId);
                   $query->orWhere(function($query) use($senderId){
                           $query->where('chats.receiver_id','=',$senderId)
                                 ->where('chats.status','>','-1');
                   });
            })->groupBy(DB::raw('CASE WHEN chats.sender_id = "'.$senderId.'" 
            THEN chats.receiver_id ELSE chats.sender_id END'));
        })->orderBy('chats.id','desc')->get();

        //dd($getuserchat);

        $result = array();

        foreach($getuserchat as $value)
        {
            $data = array();
            $data['id'] = $value->id;
            $data['message'] = $value->message;
            $data['created_date'] = $value->created_date;
            $data['user_id'] = $value->connect_user_id;
            // $data['is_online'] = $value->getConnectUser->OnlineUser();
            $data['name'] = $value->getConnectUser->name;
            $data['profile_photo'] = $value->getConnectUser->getProfilePic();
            $data['messagecount'] = $value->CountMessage($value->connect_user_id,$senderId);
            $data['organisation_id'] = $value->getConnectUser->organisation_id;
            $result[] = $data;
        }

        return $result;
    }

    static public function CountMessage($connect_user_id,$senderId){

        return self::where('sender_id','=',$connect_user_id)->where('receiver_id','=',$senderId)
                   ->where('status','=',0)->count();

    }

    static public function updateCount($senderId,$receiverId){
           
        self::where('sender_id','=',$receiverId)->where('receiver_id','=',$senderId)
                                                 ->where('status','=','0')
                                                 ->update(['status' => '1']);
    }
}
