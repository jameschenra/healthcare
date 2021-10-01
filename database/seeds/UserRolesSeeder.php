<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin'
            ],
            [
                'name' => 'Admin'
            ],
            [
                'name' => 'User'
            ],
        ]);
    }
}
