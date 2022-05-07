<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fio = $this->FIO($this->causer_id);
        $info = $this->userInfo($this->causer_id);
        return [
            'properties' => $this->properties,
            'log_name' => $this->log_name,
            'batch_uuid' => $this->batch_uuid,
            'created_at' => $this->created_at->format('d.m.Y  H:i:s'),
            'updated_at' => $this->updated_at->format('d.m.Y  H:i:s'),
            'first_name' => $fio->first_name ?? null,
            'middle_name' => $fio->middle_name ?? null,
            'last_name' => $fio->last_name ?? null,
            'user' => $info->email ?? null,
            'ip_address' => $info->ip_address ?? null,
        ];
    }
    public function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
    public function userInfo($id)
    {
        return User::where('id', $id)->first();
    }
}
