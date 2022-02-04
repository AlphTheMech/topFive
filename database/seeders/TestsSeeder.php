<?php

namespace Database\Seeders;

use App\Models\Tests;
use Illuminate\Database\Seeder;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tests = new Tests();
        $tests->name_test = 'Я хочу питсы';
        $tests->json_data = 'я_хочу_питсы.json';
        $tests1 = new Tests();
        $tests1->name_test = 'Я хочу питсы2';
        $tests1->json_data = 'я_хочу_питсы2.json';
        $tests2 = new Tests();
        $tests2->name_test = 'Я хочу питсы3';
        $tests2->json_data = 'я_хочу_питсы3.json';
    }
}
