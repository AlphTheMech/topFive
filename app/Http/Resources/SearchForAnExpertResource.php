<?php

namespace App\Http\Resources;

use App\Models\ResultTests;
use App\Models\Tests as ModelsTests;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NumberAttemptResource;

class SearchForAnExpertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $explode = explode('@', $this->name_test);
        if (array_key_exists(1, $explode)) {
            return [
                'id' => $this->id,
                'name_test' => $explode[0],
                'author' => $explode[1],
                'full_name_test' => $this->name_test,
                'subject' => $this->subjectName($this->id),
                'attempt' => $this->accessAttempt($this->id)->first(),
                'json_data' => $this->json_data,
            ];
        } else {
            return [
                'id' => $this->id,
                'name_test' => $this->name_test,
                'subject' => $this->subjectName($this->id),
                'attempt' => $this->accessAttempt($this->id)->first(),
                'json_data' => $this->json_data,
            ];
        }
    }

    protected function subjectName($id)
    {
        $tests =  ModelsTests::with('subjectTests')->get()->where('id', $id)->first();
        return new SubjectArrayResource($tests);
    }

    protected function accessAttempt($id)
    {
        $tests = ResultTests::where('tests_id', $id)->get()->where('user_id', auth('sanctum')->user()->id);
        return NumberAttemptResource::collection($tests);
    }
}
