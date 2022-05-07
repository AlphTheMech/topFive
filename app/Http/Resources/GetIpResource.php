<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'abbreviation' => Str::upper(mb_substr($fio->last_name ?? null, 0, 1)) .
                mb_substr($fio->last_name ?? null, 1) . ' ' .
                Str::upper(mb_substr($fio->first_name ?? null, 0, 1)) . '.' .
                Str::upper(mb_substr($fio->middle_name ?? null, 0, 1)) . '.',
            'email' => $this->responsible->first()->email,
            'user_id' => $this->user_id,
        ];
    }
}
