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
            'seller'=>new Seller($this->seller),
            'buyer'=>new Buyer($this->buyer),
            'job'=>new Job($this->job),
            'count_unread_messages' => ModelsMessage::where('status', '=', false)->where('job_list_msg_id',$this->id)->count(),
            'timestamp'=>$this->created_at
        ];
    }
}
