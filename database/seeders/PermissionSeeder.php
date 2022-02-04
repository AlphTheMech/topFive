<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new Permission();
        $user->name = 'Пользователь';
        $user->slug = 'standard-user';
        $user->save();
        $work_with_users = new Permission();
        $work_with_users->name = 'Эксперт';
        $work_with_users->slug = 'work-with-users';
        $work_with_users->save();
        $work_with_experts = new Permission();
        $work_with_experts->name = 'Учитель';
        $work_with_experts->slug = 'work-with-experts';
        $work_with_experts->save();
        $administration = new Permission();
        $administration->name = 'Админ';
        $administration->slug = 'administration';
        $administration->save();
    }
}
