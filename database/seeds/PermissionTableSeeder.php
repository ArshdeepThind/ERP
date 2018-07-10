<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([

                [
                    'name'     => "developerOnly",
                    'label'    => "developerOnly"
                ],
                [
                    'name'     => "admin.create",
                    'label'    => "Admin Create"
                ],
                [
                    'name'     => "admin.list",
                    'label'    => "Admin List"
                ],
                [
                    'name'     => "admin.remove",
                    'label'    => "Admin Remove"
                ],
                [
                    'name'     => "admin.update",
                    'label'    => "Admin Update"
                ],
                [
                    'name'     => "conversion",
                    'label'    => "Conversion"
                ],
                [
                    'name'     => "currency",
                    'label'    => "Currency"
                ],
                [
                    'name'     => "pages",
                    'label'    => "pages"
                ],
                [
                    'name'     => "role",
                    'label'    => "Role"
                ],
                [
                    'name'     => "user.create",
                    'label'    => "User Create"
                ],
                [
                    'name'     => "user.delete",
                    'label'    => "User Delete"
                ],
                [
                    'name'     => "user.edit",
                    'label'    => "User Edit"
                ],
                [
                    'name'     => "user.list",
                    'label'    => "User List"
                ],
                [
                    'name'     => "user.update",
                    'label'    => "User Update"
                ],
                [
                    'name'=>'taruwaAccount.update',
                    'label'=>" Taruwa Account Update"
                ],
                [
                    'name'=>'taruwaAccount.delete',
                    'label'=>" Taruwa Account Remove"
                ],
                [
                    'name'=>'taruwaAccount.list',
                    'label'=>" Taruwa Account List"
                ],
                [
                    'name'=>'taruwaAccount.create',
                    'label'=>" Taruwa Account Create"
                ],

                [
                    'name'=>'transaction.list',
                    'label'=>"Transaction List"
                ],
                [
                    'name'=>'transactions.edit',
                    'label'=>"Transaction Edit"
                ],
                [
                    'name'=>'transactions.update',
                    'label'=>"Transaction Update"
                ],
                [
                    'name'=>'transaction.status',
                    'label'=>"Transaction Stutus"
                ],
            ]
        );
    }
}
