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

// Route::get('/', function () {
//     return view('welcome');
// });


// staticPage
Route::get('/', 'StaticPagesController@home')->name('index');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// User
// 资源路由
Route::resource('users', 'UserController');
	/*** 等价于
		Route::get('/users', 'UserController@index')->name('users.index');
		Route::get('/users/{user}', 'UserController@show')->name('users.show');
		Route::get('/users/create', 'UserController@create')->name('users.create');
		Route::post('/users', 'UserController@store')->name('users.store');
		Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
		Route::patch('/users/{user}', 'UserController@update')->name('users.update');
		Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
	***/
// 登录
Route::get('signup', 'UserController@signup')->name('signup');
Route::get('login', 'UserController@login')->name('login');
Route::post('login', 'UserController@logValidate')->name('login');
Route::delete('logout', 'UserController@logout')->name('logout');
// 注册验证
Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');
// 密码重置
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
// 微博
Route::resource('talks', 'TalksController', ['only' => ['store', 'destroy']]);
// 微博关注粉丝
Route::get('/users/{user}/followings', 'UserController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UserController@followers')->name('users.followers');
// 关注
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
// 取消关注
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');

// Auth::routes(); 同上 不再重复添加
	// Authentication Routes...
	// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	// Route::post('login', 'Auth\LoginController@login');
	// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

	// // Registration Routes...
	// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	// Route::post('register', 'Auth\RegisterController@register');

	// // Password Reset Routes...
	// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	// Route::post('password/reset', 'Auth\ResetPasswordController@reset');