<?php

namespace App\Http\Resources;

use App\Models\Tests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TeacherExpertsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $test = $this->testDataStatistic($this->expert->test_id)->first();
        $personalData = $this->personalDataExpert;
        return [
            'statistics_score' => $this->expert->statistics_score,
            'first_name' => $personalData->first_name ?? null,
            'middle_name' => $personalData->middle_name ?? null,
            'last_name' => $personalData->last_name ?? null,
            'abbreviation' => Str::upper(mb_substr($personalData->last_name ?? null, 0, 1)) .
                mb_substr($personalData->last_name ?? null, 1) . ' ' .
                Str::upper(mb_substr($personalData->first_name ?? null, 0, 1)) . '.' .
                Str::upper(mb_substr($personalData->middle_name ?? null, 0, 1)) . '.',
            'email' => $this->emailExpert->email,
            'test_name' => $test->name_test,
            'subject' => $test->subjectTests->first()->name,
        ];
    }

    protected function testDataStatistic($id)
    {
        return Tests::with('subjectTests')->get()->where('id', $id);
    }
}
