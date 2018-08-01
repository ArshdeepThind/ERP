<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
   'uses' => 'ProductController@getIndex',
   'as' => 'product.index'
]);
Route::get('/add-to-cart/{id}', [
   'uses' => 'ProductController@getAddToCart',
   'as' => 'product.addToCart'
]);

Route::get('/reduce/{id}', [
    'uses' => 'ProductController@getReduceByOne',
    'as' => 'product.reduceByOne'
]);
Route::get('/remove/{id}', [
    'uses' => 'ProductController@getRemoveItem',
    'as' => 'product.remove'
]);

Route::get('/shopping-cart', [
   'uses' => 'ProductController@getCart',
   'as' => 'product.shoppingCart'
]);
Route::get('/checkout', [
   'uses' => 'ProductController@getCheckout',
   'as' => 'checkout',
   'middleware' => 'auth'
]);
Route::post('/checkout', [
   'uses' => 'ProductController@postCheckout',
   'as' => 'checkout',
   'middleware' => 'auth'
]);

Route::group(['prefix' => 'user'], function () {
   Route::group(['middleware' => 'guest'], function () {
       Route::get('/signup', [
           'uses' => 'UserController@getSignup',
           'as' => 'user.signup'
       ]);
       Route::post('/signup', [
           'uses' => 'UserController@postSignup',
           'as' => 'user.signup'
       ]);
       Route::get('/signin', [
           'uses' => 'UserController@getSignin',
           'as' => 'user.signin'
       ]);
       Route::post('/signin', [
           'uses' => 'UserController@postSignin',
           'as' => 'user.signin'
       ]);
   });
   Route::group(['middleware' => 'auth'], function () {
       Route::get('/profile', [
           'uses' => 'UserController@getProfile',
           'as' => 'user.profile'
       ]);
       Route::get('/logout', [
           'uses' => 'UserController@getLogout',
           'as' => 'user.logout'
       ]);
   });
});


Route::group(['prefix' => 'admin'], function () {
  Route::get('/', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'employee'], function () {
  Route::get('/', 'EmployeeAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'EmployeeAuth\LoginController@login');
  Route::post('/logout', 'EmployeeAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'EmployeeAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'EmployeeAuth\RegisterController@register');

  Route::post('/password/email', 'EmployeeAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'EmployeeAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'EmployeeAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'EmployeeAuth\ResetPasswordController@showResetForm');
});
