<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GettingTestStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fio = $this->FIO($this->expert_id);
        return [
            'first_name' => $fio->first_name,
            'middle_name' => $fio->middle_name,
            'last_name' => $fio->last_name,
            'email' => $this->userEmail($this->expert_id),
            'statistics_score' => $this->statistics_score,
        ];
    }    
    /**
     * userEmail
     *
     * @param  mixed $id
     * @return void
     */
    protected function userEmail($id)
    {
        return User::where('id', $id)->first()->email;
    }    
    /**
     * FIO
     *
     * @param  mixed $id
     * @return void
     */
    protected function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
}
