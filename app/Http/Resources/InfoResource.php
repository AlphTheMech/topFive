<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource
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
            'email'=>$this->email, 
            'avatar'=>$this->avatar, 
            'first_name' => PersonalData::where('user_id', $this->id)->first()->first_name,
            'middle_name' => PersonalData::where('user_id', $this->id)->first()->middle_name,
            'last_name' =>PersonalData::where('user_id', $this->id)->first()->last_name
        ];
    }
}
