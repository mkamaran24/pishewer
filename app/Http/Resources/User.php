<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email'=>$this->email,
            'fullname'=>$this->fullname,
            'username'=>$this->username,
            'fastpay_acc_num'=>$this->fastpay_acc_num,
            'phone_number'=>$this->phone_number,
            'profile'=>$this->profile
        ];
    }
}
