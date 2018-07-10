<?php

Route::group(['middleware' => 'prevent-back-history'],function(){
Route::get('/home', function (App\User $user) {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('admin')->user();

    //dd($users);
    $pass['usercount'] = 2;
    
    return view('admin.home')->with($pass);
})->name('home');


//Administrator
Route::get('/profile',"Admin\AdminController@profileEdit");
Route::put('/profile/{admin}',"Admin\AdminController@profileUpdate");
Route::put('/changepassword/{admin}',"Admin\AdminController@changePassword");
Route::put('/administrator/status',"Admin\AdminController@switchStatus");
Route::post('/administrator/removeBulk',"Admin\AdminController@destroyBulk");
Route::put('/administrator/statusBulk',"Admin\AdminController@switchStatusBulk");
Route::resource('/administrator',"Admin\AdminController");

//users

Route::put('/users/verify',"Admin\UsersController@switchVerification");
Route::put('/users/status',"Admin\UsersController@switchStatus");
Route::get('/users/fetchData/{id}',"Admin\UsersController@fetchData");
Route::post('/users/removeBulk',"Admin\UsersController@destroyBulk");
Route::put('/users/statusBulk',"Admin\UsersController@switchStatusBulk");
Route::resource('/users',"Admin\UsersController");

//Pages

Route::put('/pages/status',"Admin\PagesController@switchStatus");
Route::post('/pages/removeBulk',"Admin\PagesController@destroyBulk");
Route::put('/pages/statusBulk',"Admin\PagesController@switchStatusBulk");
Route::resource('/pages',"Admin\PagesController");

/**
 * ROLES
 */
Route::get('/role/{role}/permissions',"Admin\RoleController@permissions");
Route::get('/rolePermissions',"Admin\RoleController@rolePermissions")->name('myrolepermission');
Route::get('/roles/all',"Admin\RoleController@all");
Route::post('/assignPermission','Admin\RoleController@attachPermission');
Route::post('/detachPermission','Admin\RoleController@detachPermission');
Route::resource('/roles',"Admin\RoleController");

/**
 * PERMISSIONs
 */
Route::get('/permissions/all',"Admin\PermissionController@all");
Route::resource('/permissions',"Admin\PermissionController");

});