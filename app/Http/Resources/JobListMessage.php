<?php

namespace App\Http\Resources;

use App\Http\Resources\JobList\Buyer;
use App\Http\Resources\JobList\Job;
use App\Http\Resources\JobList\Seller;
use App\Models\Message as ModelsMessage;
use Illuminate\Http\Resources\Json\JsonResource;

class JobListMessage extends JsonResource
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
            'id'=>(string)$this->id,
            'ftc_code' => $this->ftc_code,
            // 'user_id'=>new Seller($this->seller),
            'friend'=>new Buyer($this->user),
            // 'count_unread_messages' => "0",
            'unread_messages' => ModelsMessage::where('status', '=', false)->where('ftm_code',$this->ftc_code)->where('recever_id',$this->user_id)->count(),
            'timestamp'=>$this->created_at
        ];
    }
}
