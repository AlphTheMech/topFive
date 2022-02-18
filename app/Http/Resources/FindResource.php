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
        return [
            'email'=>$this->email, 
            'first_name' => PersonalData::where('user_id', $this->id)->first()->first_name,
            'middle_name' => PersonalData::where('user_id', $this->id)->first()->middle_name,
            'last_name' =>PersonalData::where('user_id', $this->id)->first()->last_name,
            'role_slug'=>Role::where('id',UsersRoles::where('user_id', $this->id)->first()->role_id)->first()->slug,
            // 'role_name'=>Role::where('id',UsersRoles::where('user_id', $this->id)->first()->role_id)->first()->name,
            // 'permission_name'=>Permission::where('id',UsersPermissions::where('user_id', $this->id)->first()->permission_id)->first()->name,
            'permission_slug'=>Permission::where('id',UsersPermissions::where('user_id', $this->id)->first()->permission_id)->first()->slug,
        ];
    }
}
