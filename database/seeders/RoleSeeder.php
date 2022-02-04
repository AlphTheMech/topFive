<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new Role();
        $user->name = 'Пользователь';
        $user->slug = 'user';
        $user->save();
        $expert = new Role();
        $expert->name = 'Эксперт';
        $expert->slug = 'expert';
        $expert->save();
        $teacher = new Role();
        $teacher->name = 'Учитель';
        $teacher->slug = 'teacher';
        $teacher->save();
        $admin = new Role();
        $admin->name = 'Админ';
        $admin->slug = 'admin';
        $admin->save();
    }
}
