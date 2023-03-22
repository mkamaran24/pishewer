<?php

namespace App\Http\Resources\ViewJob;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $total_rev_collection = array();
        for ($i=0; $i < count($this->collection); $i++) 
        { 
            $total_rev = ($this->collection[$i]->service_quality + $this->collection[$i]->commun_followup + $this->collection[$i]->panctual_delevery)/3;
            $total_rev_collection[$i] = floor($total_rev);
        }
        
        return [
            'avg_of_total_stars' => round(array_sum($total_rev_collection)/count($total_rev_collection)),
            'data' => $this->collection
        ];
    }
}