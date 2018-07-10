<?php

use Illuminate\Database\Seeder;

use App\Role;
use App\Permission;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$permission =Permission::first();
		$role       =Role::first();
		$role->givePermissionTo($permission);
    }
}
