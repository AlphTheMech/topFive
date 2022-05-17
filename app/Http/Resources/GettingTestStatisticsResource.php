<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'abbreviation' => Str::upper(mb_substr($fio->last_name ?? null, 0, 1)) .
                mb_substr($fio->last_name ?? null, 1) . ' ' .
                Str::upper(mb_substr($fio->first_name ?? null, 0, 1)) . '.' .
                Str::upper(mb_substr($fio->middle_name ?? null, 0, 1)) . '.',
            'email' => $this->userEmail($this->expert_id),
            'statistics_score' => $this->statistics_score,
        ];
    }
    protected function userEmail($id)
    {
        return User::where('id', $id)->first()->email;
    }
    protected function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
}
