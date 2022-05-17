<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'email' => $this->email,
            'avatar' => $this->avatar,
            'token' => $this->token,
            'email_verified' => $this->email_verified_at,
            'first_name' => $this->personalData ? $this->personalData->first_name : null,
            'middle_name' => $this->personalData ? $this->personalData->middle_name : null,
            'last_name' => $this->personalData ? $this->personalData->last_name : null,
            'abbreviation' => Str::upper(mb_substr($this->personalData->last_name, 0, 1)) .
                mb_substr($this->personalData->last_name, 1) . ' ' .
                Str::upper(mb_substr($this->personalData->first_name, 0, 1)) . '.' .
                Str::upper(mb_substr($this->personalData->middle_name, 0, 1)) . '.',
        ];
    }
}
