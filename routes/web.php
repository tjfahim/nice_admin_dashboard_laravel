<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        $user = Auth::user();
        $role = $user->role; // Get the user's role

        // Check the user's role and redirect accordingly
        if ($role === 'admin') {
            return view('admin.dashboard'); // Render the admin dashboard view
        } elseif ($role === 'user') {
            return view('user.dashboard'); // Render the user dashboard view
        }
    }

    // Redirect to the login page if the user is not authenticated or has no role
    return redirect('/login');
});


Route::get('/login', 'App\Http\Controllers\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\LoginController@login');
Route::post('/logout', 'App\Http\Controllers\LoginController@logout')->name('logout');
Route::get('/register', 'App\Http\Controllers\LoginController@showLoginForm')->name('register');
Route::post('/register', 'App\Http\Controllers\LoginController@register');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {
        Route::get('/dashboard', 'App\Http\Controllers\AdminController@dashboard')->name('admin.dashboard');
    });

    Route::group(['prefix' => 'user', 'middleware' => 'role:user'], function () {
        Route::get('/dashboard', 'App\Http\Controllers\UserController@dashboard')->name('user.dashboard');
    });
});