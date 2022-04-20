<?php

namespace App\Http\Resources;

use App\Models\PersonalData;
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
            'user'=>$this->FIO($this->userResult->id),
            'subject' => $this->subjectResult->name,
            'name_test' => $this->testResult->name_test,
            'mark' => $this->mark
        ];
    }
    protected function FIO($id)
    {
       return PersonalData::where('user_id', $id)->first();
    }
}
