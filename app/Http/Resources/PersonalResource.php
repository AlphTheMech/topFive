<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PersonalResource extends JsonResource
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
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'abbreviation' => Str::upper(mb_substr($this->last_name ?? null, 0, 1)) .
                mb_substr($this->last_name ?? null, 1) . ' ' .
                Str::upper(mb_substr($this->first_name ?? null, 0, 1)) . '.' .
                Str::upper(mb_substr($this->middle_name ?? null, 0, 1)) . '.',
        ];
    }
}
