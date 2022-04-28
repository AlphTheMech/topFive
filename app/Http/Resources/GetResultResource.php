<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetResultResource extends JsonResource
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
            // 'user' =>$this->FIO($this->userResult->first()->id) ,
            'user' => $this->FIO($this->userResult->id),
            'email' => $this->userEmail($this->userResult->id)->email,
            'subject' => $this->subjectResult->name,
            'name_test' => $this->testResult->name_test,
            'mark' => $this->mark,
            'id'=>$this->id,
        ];
    }
    protected function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
    protected function userEmail($id)
    {
        return User::where('id', $id)->first();
    }
}
