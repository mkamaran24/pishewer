<?php

namespace App\Http\Resources;

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
            "job_list_msg_id"=>(string)$this->job_list_msg_id,
            "Created_at"=>$this->created_at
        ];
    }
}
