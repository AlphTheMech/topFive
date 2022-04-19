<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                'json_data' => $this->json_data
            ];
        } else {
            return [
                'id' => $this->id,
                'name_test' => $this->name_test,
                'json_data' => $this->json_data
            ];
        }
    }
}
