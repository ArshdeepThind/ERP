<?php

Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('employee')->user();

    //dd($users);
    $pass['usercount'] = 2;

    return view('employee.home')->with($pass);
})->name('home');



//Administrator
Route::get('/profile',"Employee\AdminController@profileEdit");
Route::put('/profile/{employee}',"Employee\AdminController@profileUpdate");
Route::put('/changepassword/{employee}',"Employee\AdminController@changePassword");
Route::put('/administrator/status',"Employee\AdminController@switchStatus");
Route::post('/administrator/removeBulk',"Employee\AdminController@destroyBulk");
Route::put('/administrator/statusBulk',"Employee\AdminController@switchStatusBulk");
Route::resource('/administrator',"Employee\AdminController");

//users

Route::put('/users/verify',"Employee\UsersController@switchVerification");
Route::put('/users/status',"Employee\UsersController@switchStatus");
Route::get('/users/fetchData/{id}',"Employee\UsersController@fetchData");
Route::post('/users/removeBulk',"Employee\UsersController@destroyBulk");
Route::put('/users/statusBulk',"Employee\UsersController@switchStatusBulk");
Route::resource('/users',"Employee\UsersController");

//Pages

Route::put('/pages/status',"Employee\PagesController@switchStatus");
Route::post('/pages/removeBulk',"Employee\PagesController@destroyBulk");
Route::put('/pages/statusBulk',"Employee\PagesController@switchStatusBulk");
Route::resource('/pages',"Employee\PagesController");

/**
 * ROLES
 */
Route::get('/role/{role}/permissions',"Employee\RoleController@permissions");
Route::get('/rolePermissions',"Employee\RoleController@rolePermissions")->name('myrolepermission');
Route::get('/roles/all',"Employee\RoleController@all");
Route::post('/assignPermission','Employee\RoleController@attachPermission');
Route::post('/detachPermission','Employee\RoleController@detachPermission');
Route::resource('/roles',"Employee\RoleController");

/**
 * PERMISSIONs
 */
Route::get('/permissions/all',"Employee\PermissionController@all");
Route::resource('/permissions',"Employee\PermissionController");
