<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
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
                'name'     => "developer",
                'label'    => "Developer"
            ],
            [
                'name'     => "User",
                'label'    => "User"
            ],
            [
                'name'     => "Agent",
                'label'    => "Agent"
            ],
            [
                'name'     => "Admin",
                'label'    => "Admin"
            ],
            [
                'name'     => "Super",
                'label'    => "Super"
            ],
        ]);
    }
}
