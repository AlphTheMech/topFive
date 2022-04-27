<?php

namespace App\Http\Resources;

use App\Models\Tests;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $test=$this->testDataStatistic($this->expert->test_id)->first();
        $personalData=$this->personalDataExpert;
        return [
            'statistics_score' => $this->expert->statistics_score,
            'first_name' => $personalData->first_name,
            'middle_name' => $personalData->middle_name,
            'last_name' => $personalData->last_name,
            'email' => $this->emailExpert->email,
            'test_name'=>$test->name_test,
            'subject'=>$test->subjectTests->first()->name,
        ];
    }
    protected function testDataStatistic($id)
    {
        return Tests::with('subjectTests')->get()->where('id', $id);
    }
}
