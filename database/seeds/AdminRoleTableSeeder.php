<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\Admin;

class AdminRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$admin= Admin::first();       
		$admin->assignRole('developer');
    }
}
