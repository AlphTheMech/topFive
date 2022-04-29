<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $subject = $this->subjectTests->first();
        return [
            'subject_name' =>  $subject->name,
            'name_test' => $this->name_test,
            'subject_id' =>  $subject->id,
            'test_id' => $this->id

        ];
    }
}
