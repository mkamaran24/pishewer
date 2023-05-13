<?php

namespace App\Http\Resources\Translation;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $delimiters = ['-', ',', '٬','،']; // Array of delimiters
        $escapedDelimiters = array_map('preg_quote', $delimiters);
        $pattern = implode('|', $escapedDelimiters);

        return [
            'title' => $this->title,
            'description' => $this->description,
            'skills' => preg_split("/$pattern/u", $this->skills),
            'langs' => preg_split("/$pattern/u", $this->langs),
            'certification' => $this->certification,
            'age' => $this->age,
            'gender' => $this->gender
        ];
    }
}
