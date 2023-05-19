<?php

namespace App\Http\Resources;

use App\Models\Message as ModelsMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id"=>(string)$this->id,
            "text_mesg"=>$this->text_msg,
            "sender_id"=>$this->sender_id,
            "recver_id"=>$this->recever_id,
            // "status"=> $state = ($this->status == false) ? 'unread' : 'readed',
            // "friend_list_msg_id"=>(string)$this->friend_list_id,
            "Created_at"=>$this->created_at,
        ];
    }
}
