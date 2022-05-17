<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PersonalData;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersPermissions;
use App\Models\UsersRoles;
use Illuminate\Support\Str;

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
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $fio->first_name ?? null,
            'middle_name' => $fio->middle_name ?? null,
            'last_name' => $fio->last_name ?? null,
            'abbreviation' => Str::upper(mb_substr($fio->last_name ?? null, 0, 1)) .
                mb_substr($fio->last_name ?? null, 1) . ' ' .
                Str::upper(mb_substr($fio->first_name ?? null, 0, 1)) . '.' .
                Str::upper(mb_substr($fio->middle_name ?? null, 0, 1)) . '.',
            'role_slug' => $this->roles->first()->slug,
            'permission_slug' => $this->permissions->first()->slug,
        ];
    }
    protected function FIO($id)
    {
        return PersonalData::where('user_id', $id)->first();
    }
}
