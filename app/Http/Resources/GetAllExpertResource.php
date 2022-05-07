<?php

namespace App\Http\Resources;

use App\Models\SubjectOfStudies;
use App\Models\Tests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class GetAllExpertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $personalData = $this->personalData;

        return [
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
            ],
            'personal_data' => [
                'first_name' => $personalData->first_name ?? null,
                'middle_name' => $personalData->middle_name ?? null,
                'last_name' => $personalData->last_name ?? null,
                'abbreviation' => Str::upper(mb_substr($personalData->last_name ?? null, 0, 1)) .
                    mb_substr($personalData->last_name ?? null, 1) . ' ' .
                    Str::upper(mb_substr($personalData->first_name ?? null, 0, 1)) . '.' .
                    Str::upper(mb_substr($personalData->middle_name ?? null, 0, 1)) . '.',
            ],
            'tests' => $this->testArray($this->testPermission),
        ];
    }

    protected function testArray($data)
    {
        return TestExpertResource::collection($data);
    }
}
