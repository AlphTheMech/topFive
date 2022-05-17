<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'total' => $this->total(),
            // 'per_page' => $this->perPage(),
            // 'current_page' => $this->currentPage(),
            // 'last_page' => $this->lastPage(),
            // 'from' => $this->firstItem(),
            // 'to' => $this->lastItem(),
            // 'count' => $this->count(),
            // 'total_pages' => $this->lastPage()
        ];
    }
}
