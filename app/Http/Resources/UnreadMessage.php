<?php

namespace App\Http\Resources;

use App\Models\Message as ModelsMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class UnreadMessage extends JsonResource
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
            "count_unread_messages" => $countUnreadMessages
        ];
    }
}
