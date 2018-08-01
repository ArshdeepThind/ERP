<?php



Route::get('/home', "Employee\DashController@index")->name('home');

//Employee
Route::get('/profile',"Employee\EmployeeController@profileEdit");
Route::put('/profile/{employee}',"Employee\EmployeeController@profileUpdate");
Route::put('/changepassword/{employee}',"Employee\EmployeeController@changePassword");
//Employees
Route::put('/leaves/approve',"Employee\EmployeeController@switchstatus");
Route::get('/leaves/unapprovedleaves',"Employee\EmployeeController@unapprovedleaves")->name('unapprovedleaves');

Route::post('/applyleave',"Employee\EmployeeController@applyleave");
Route::get('/leaves',"Employee\EmployeeController@leaves");
Route::get('/allleaves',"Employee\EmployeeController@allleaves");
Route::get('/showallleaves/{id}',"Employee\EmployeeController@showallleaves");

Route::put('/employees/updateBulk',"Employee\EmployeeController@updateBulk");
Route::post('/employees/storeRemarks',"Employee\EmployeeController@storeRemarks");
Route::get('/employees/cases/{id}',"Employee\EmployeeController@cases");

Route::get('/employees/fetchData/{id}',"Employee\EmployeeController@fetchData");
Route::resource('/employees',"Employee\EmployeeController");

//Payment
Route::get('/attendence',"Employee\EmployeeController@showattendence")->name('attendence');

Route::get('/payment/showpayslip/{id}',"Employee\PaymentController@showpayslip");
Route::get('/payment/paymenthistory/{id}',"Employee\PaymentController@paymenthistory");
Route::put('/payment/changestatus',"Employee\PaymentController@changestatus");
Route::resource('/payment',"Employee\PaymentController");


//Jobs
Route::get('/job/departments/',"Employee\JobController@fetchDepartments");
Route::resource('/job',"Employee\JobController");

Route::get('/chat/cases/{id}',"Employee\ChatController@cases");
Route::get('/chat/{id?}',"Employee\ChatController@index")->name('chatlist');
Route::resource('/chat',"Employee\ChatController");

Route::resource('/settings',"Employee\SettingsController");


//Inventory


Route::resource('/products',"Employee\InventoryController");
Route::resource('/supplier',"Employee\SupplierController");
Route::resource('/orders',"Employee\OrderController");