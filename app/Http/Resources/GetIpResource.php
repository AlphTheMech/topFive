<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetIpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fio = $this->personalData->first();
        return [
            'ip_address' => $this->ip_address,
            'first_name' => $fio->first_name,
            'middle_name' => $fio->middle_name,
            'last_name' => $fio->last_name,
            'email' => $this->responsible->first()->email,
            'user_id' => $this->user_id,
        ];
    }
}
