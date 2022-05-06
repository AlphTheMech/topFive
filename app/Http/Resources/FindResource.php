<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PersonalData;
use App\Models\Role;
use App\Models\UsersPermissions;
use App\Models\UsersRoles;

class FindResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fio = $this->FIO($this->id);
        $user = auth('sanctum')->user();
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $fio->first_name,
            'middle_name' => $fio->middle_name,
            'last_name' => $fio->last_name,
            'role_slug' => $user->roles->first()->slug,
            'permission_slug' => $user->permissions->first()->slug,
        ];
    }    
    /**
     * FIO
     *
     * @param  mixed $id
     * @return void
     */
    protected function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
}
