<?php

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
        //
        DB::table('users')->insert([
            'name' => 'vbh',
            'email' => 'vbh@email.com',
            'password' => bcrypt('vbh2020now'),
        ]);
    }
}
