<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');//->middleware('auth');


// Auth::routes();
// Route::get('/login/prof', 'App\Http\Controllers\Auth\LoginController@showProfLoginForm');
// Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showParentLoginForm');
// Route::get('/register/prof', 'App\Http\Controllers\Auth\RegisterController@showProfRegisterForm');
// Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showParentRegisterForm');

// Route::post('/login/prof', 'App\Http\Controllers\Auth\LoginController@profLogin');
// Route::post('/login', 'App\Http\Controllers\Auth\LoginController@parentLogin');
// Route::post('/register/prof', 'App\Http\Controllers\Auth\RegisterController@createProf');
// Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@createParent');

// Route::prefix('/prof')->name('prof.')->namespace('Prof')->group(function(){
// 	Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');
	
// });
// Route::prefix('/parent')->name('parent.')->namespace('Parent')->group(function(){
// 	Route::namespace('Auth')->group(function(){
// 		Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');
// 	});
	
//   });


// Route::group(['middleware' => 'auth:parent'], function () {
// 	Route::get('table-list', function () {
// 		return view('pages.table_list');
// 	})->name('table');

// 	Route::get('typography', function () {
// 		return view('pages.typography');
// 	})->name('typography');

// 	Route::get('reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');

// 	Route::get('map', function () {
// 		return view('pages.map');
// 	})->name('map');

// 	Route::get('notifications', function () {
// 		return view('pages.notifications');
// 	})->name('notifications');

// 	Route::get('rtl-support', function () {
    // 		return view('pages.language');
    // 	})->name('language');
    
	
    // });

Auth::routes();
    // View routes
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', 'App\Http\Controllers\Parent\DashboardController@index')->name('dashboard');
Route::get('/reports', 'App\Http\Controllers\Parent\DashboardController@index')->name('reports');
Route::get('/enfants/{eleveId?}/{classeId?}', 'App\Http\Controllers\Parent\DashboardController@enfants')->name('enfants');

Route::prefix('/prof')->name('prof.')->group(function(){
	
    Route::get('/dashboard', 'App\Http\Controllers\Prof\DashboardController@index')->name('dashboard');
    Route::get('/enseignement/{classeId?}', 'App\Http\Controllers\Prof\DashboardController@teaching')->name('enseignement');
    Route::get('/correspondance/{eleveId}', 'App\Http\Controllers\Prof\DashboardController@showAddObservationView')->name('correspondance');
    Route::get('/reports', 'App\Http\Controllers\Prof\DashboardController@index')->name('reports');
});
// API routes

Route::post('/observations/add', 'App\Http\Controllers\API\ObservationsController@add');
Route::put('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@update');
Route::prefix('/apio')->name('api.')->group(function(){
    // Route::get('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@getObservation');

});
