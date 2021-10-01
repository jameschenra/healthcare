<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456'),
            'role_id' => 1
        ]);
    }
}
