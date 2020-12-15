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
    // Auth routes
Auth::routes();
Route::get('/login/prof', [App\Http\Controllers\Auth\LoginController::class, 'showProfLoginForm'])->name('loginProf');
Route::post('/login/prof', [App\Http\Controllers\Auth\LoginController::class, 'loginProf']);
    // View routes
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', 'App\Http\Controllers\Parent\DashboardController@index')->name('dashboard');
Route::get('/reports', 'App\Http\Controllers\Parent\DashboardController@index')->name('reports');
Route::get('/enfants/{eleveId?}/{classeId?}', 'App\Http\Controllers\Parent\DashboardController@enfants')->name('enfants');
Route::get('/compte', 'App\Http\Controllers\Parent\AccountController@showEditProfileForms')->name('compte');
Route::put('/compte/info', 'App\Http\Controllers\Parent\AccountController@updateInfos')->name('compte.updateInfo');
Route::put('/compte/password', 'App\Http\Controllers\Parent\AccountController@updatePassword')->name('compte.updatePassword');

Route::prefix('/prof')->name('prof.')->group(function(){
	
    Route::get('/dashboard', 'App\Http\Controllers\Prof\DashboardController@index')->name('dashboard');
    Route::get('/enseignement/{classeId?}', 'App\Http\Controllers\Prof\DashboardController@teaching')->name('enseignement');
    Route::get('/correspondance/{eleveId}', 'App\Http\Controllers\Prof\DashboardController@showAddObservationView')->name('correspondance');
    // Route::get('/reports', 'App\Http\Controllers\Prof\DashboardController@index')->name('reports');
    Route::get('/compte', 'App\Http\Controllers\Prof\AccountController@showEditProfileForms')->name('compte');
    Route::put('/compte/info', 'App\Http\Controllers\Prof\AccountController@updateInfos')->name('compte.updateInfo');
Route::put('/compte/password', 'App\Http\Controllers\Prof\AccountController@updatePassword')->name('compte.updatePassword');
});
// API routes

Route::get('/eleves/view/{eleveId}', 'App\Http\Controllers\API\ElevesController@getPublicInfos')->name('viewEleve');
Route::get('/matieres', 'App\Http\Controllers\API\MatieresController@getAll');

Route::get('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@getObservation');
Route::post('/observations/add', 'App\Http\Controllers\API\ObservationsController@add');
Route::put('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@update');
Route::put('/observations/read/{obsId}', 'App\Http\Controllers\API\ObservationsController@markAsRead');

Route::get('/evaluations/planning/prof/{profId}', 'App\Http\Controllers\API\EvaluationsController@getPlanOfProf');
Route::get('/evaluations/planning/classe/{classeId}/{profId?}', 'App\Http\Controllers\API\EvaluationsController@getPlanOfClass');
Route::post('/evaluations/planning/add', 'App\Http\Controllers\API\EvaluationsController@add');
Route::put('/evaluations/planning/{id}', 'App\Http\Controllers\API\EvaluationsController@update');
Route::prefix('/api')->name('api.')->group(function(){
    // Route::get('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@getObservation');

});
