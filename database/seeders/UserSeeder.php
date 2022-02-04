<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Role::where('slug','user')->first();
        $expert = Role::where('slug', 'expert')->first();
        $teacher = Role::where('slug','teacher')->first();
        $admin = Role::where('slug', 'admin')->first();
        $users = Permission::where('slug','standard-user')->first();
        $experts = Permission::where('slug','work-with-users')->first();
        $teachers = Permission::where('slug','work-with-experts')->first();
        $admins = Permission::where('slug','administration')->first();


        $user1 = new User();
        $user1->name = 'Смуглый';
        $user1->email = 'example@example.com';
        $user1->password = bcrypt('secret');
        $user1->save();
        $user1->roles()->attach($user);
        $user1->permissions()->attach($users);


        $user2 = new User();
        $user2->name = 'Смуглый1';
        $user2->email = 'example1@example.com';
        $user2->password = bcrypt('secret');
        $user2->save();
        $user2->roles()->attach($expert);
        $user2->permissions()->attach($experts);


        $user3 = new User();
        $user3->name = 'Смуглый3';
        $user3->email = 'example3@example.com';
        $user3->password = bcrypt('secret');
        $user3->save();
        $user3->roles()->attach($teacher);
        $user3->permissions()->attach($teachers);


        $user4 = new User();
        $user4->name = 'Смуглый4';
        $user4->email = 'example4@example.com';
        $user4->password = bcrypt('secret');
        $user4->save();
        $user4->roles()->attach($admin);
        $user4->permissions()->attach($admins);
    }
}