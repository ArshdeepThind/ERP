<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('admins')->insert([
            'name'     => "Openxcell",
            'email'    => "abc@gmail.com",
            'password' => bcrypt('password)
        ]);
    }
}
