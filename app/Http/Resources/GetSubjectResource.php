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
        return [
            'subject_name' => $this->subjectTests->first()->name,
            'name_test' => $this->name_test,
            'subject_id' => $this->subjectTests->first()->id,
            'test_id' => $this->id

        ];
    }
}
