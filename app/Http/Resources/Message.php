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

        $countUnreadMessages = ModelsMessage::where('status', '=', false)->where('job_list_msg_id',$this->job_list_msg_id)->count();

        return [
            "id"=>(string)$this->id,
            "text_mesg"=>$this->text_msg,
            "sender_id"=>$this->sender_id,
            "recver_id"=>$this->recever_id,
            "status"=> $state = ($this->status == false) ? 'unread' : 'readed',
            "job_list_msg_id"=>(string)$this->job_list_msg_id,
            "Created_at"=>$this->created_at,
            "count_unread_messages" => $countUnreadMessages
        ];
    }
}
