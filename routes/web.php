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


        //////////////////////////////////////////////////////////////////////////////
        // Auth routes
        //////////////////////////////////////////////////////////////////////////////
Auth::routes();
Route::get('/login/prof', [App\Http\Controllers\Auth\LoginController::class, 'showProfLoginForm'])->name('loginProf');
Route::post('/login/prof', [App\Http\Controllers\Auth\LoginController::class, 'loginProf']);
Route::get('/register/prof', [App\Http\Controllers\Auth\RegisterController::class, 'showProfRegisterForm']);
Route::post('/register/prof', [App\Http\Controllers\Auth\RegisterController::class, 'registerProf'])->name('registerProf');
Route::get('/login/private/admin', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('loginAdmin');
Route::post('/login/admin', [App\Http\Controllers\Auth\LoginController::class, 'loginAdmin']);

        //////////////////////////////////////////////////////////////////////////////
        // Views routes
        //////////////////////////////////////////////////////////////////////////////
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', 'App\Http\Controllers\Parent\DashboardController@index')->name('dashboard');
Route::get('/reports', 'App\Http\Controllers\Parent\DashboardController@index')->name('reports');
Route::get('/enfants/{eleveId?}/{classeId?}', 'App\Http\Controllers\Parent\DashboardController@enfants')->name('enfants');
Route::get('/compte', 'App\Http\Controllers\Parent\DashboardController@account')->name('compte');
Route::put('/compte/info', 'App\Http\Controllers\Parent\AccountController@updateInfos')->name('compte.updateInfo');
Route::put('/compte/password', 'App\Http\Controllers\Parent\AccountController@updatePassword')->name('compte.updatePassword');

Route::prefix('/prof')->name('prof.')->group(function(){
	
    Route::get('/dashboard', 'App\Http\Controllers\Prof\DashboardController@index')->name('dashboard');
    Route::get('/enseignement/{classeId?}', 'App\Http\Controllers\Prof\DashboardController@teaching')->name('enseignement');
    Route::get('/correspondance/{eleveId}', 'App\Http\Controllers\Prof\DashboardController@showAddObservationView')->name('correspondance');
    Route::get('/compte', 'App\Http\Controllers\Prof\DashboardController@account')->name('compte');
    Route::put('/compte/info', 'App\Http\Controllers\Prof\AccountController@updateInfos')->name('compte.updateInfo');
    Route::put('/compte/password', 'App\Http\Controllers\Prof\AccountController@updatePassword')->name('compte.updatePassword');
});

Route::prefix('/admin')->name('admin.')->group(function(){
    
    Route::get('/dashboard', 'App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    Route::get('/classes', 'App\Http\Controllers\Admin\DashboardController@classes')->name('classes');
    Route::get('/enseignants', 'App\Http\Controllers\Admin\DashboardController@enseignants')->name('enseignants');
    Route::get('/parents', 'App\Http\Controllers\Admin\DashboardController@parents')->name('parents');
    Route::get('/eleves', 'App\Http\Controllers\Admin\DashboardController@eleves')->name('eleves');
    Route::get('/settings', 'App\Http\Controllers\Admin\DashboardController@settings')->name('settings');
    Route::get('/config', 'App\Http\Controllers\Admin\DashboardController@sysConfig')->name('sysConfig');
    Route::post('/config', 'App\Http\Controllers\Admin\DashboardController@sysConfigAuth')->name('sysConfigAuth');
    Route::put('/compte/password', 'App\Http\Controllers\Admin\AccountController@updatePassword')->name('updatePassword');
   
  
});

        //////////////////////////////////////////////////////////////////////////////
        // API routes
        //////////////////////////////////////////////////////////////////////////////

Route::put('/confirmations/prof/{profId}', 'App\Http\Controllers\API\AccountsController@updateProfStatus');
Route::put('/confirmations/parent/{parentId}', 'App\Http\Controllers\API\AccountsController@updateParentStatus');


Route::post('/affectations/prof/classe', 'App\Http\Controllers\API\DBInitController@affectProfClasse')->name('affectProfClasse');
Route::post('/affectations/prof/formation', 'App\Http\Controllers\API\DBInitController@affectProfFormation')->name('affectProfFormation');

Route::post('/affectations/parent/children', 'App\Http\Controllers\API\DBInitController@affectParentChildren')->name('affectParentChildren');
Route::post('/affectations/prof/formation', 'App\Http\Controllers\API\DBInitController@affectProfFormation')->name('affectProfFormation');

Route::post('/affectations/eleve/formation', 'App\Http\Controllers\API\DBInitController@affectEleveFormation')->name('affectEleveFormation');
Route::post('/affectations/eleve/classe', 'App\Http\Controllers\API\DBInitController@affectEleveClasse')->name('affectEleveClasse');

Route::post('/affectations/classe/formation', 'App\Http\Controllers\API\DBInitController@affectClasseFormation')->name('affectClasseFormation');
Route::post('/affectations/matiere/formation', 'App\Http\Controllers\API\DBInitController@affectMatiereFormation')->name('affectMatiereFormation');

Route::post('/classes', 'App\Http\Controllers\API\ClassFormatController@addClasse')->name('registerClasse');
Route::post('/formations', 'App\Http\Controllers\API\ClassFormatController@addFormation')->name('registerFormation');

Route::get('/eleves/view/{eleveId}', 'App\Http\Controllers\API\ElevesController@getPublicInfos')->name('viewEleve');
Route::post('/eleves', 'App\Http\Controllers\API\ElevesController@add')->name('registerEleve');
Route::delete('/eleves/{eleveId}', 'App\Http\Controllers\API\ElevesController@delete')->name('deleteEleve');
Route::get('/matieres', 'App\Http\Controllers\API\MatieresController@getAll');
Route::post('/matieres', 'App\Http\Controllers\API\MatieresController@create')->name('registerMatiere');

Route::get('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@getObservation');
Route::post('/observations/add', 'App\Http\Controllers\API\ObservationsController@add');
Route::put('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@update');
Route::put('/observations/read/{obsId}', 'App\Http\Controllers\API\ObservationsController@markAsRead');

Route::put('/profnotifications/read/{notifId}', 'App\Http\Controllers\API\ProfRelatedController@markAsRead');

Route::get('/evaluations/planning/prof/{profId}', 'App\Http\Controllers\API\EvaluationsController@getPlanOfProf');
Route::get('/evaluations/planning/classe/{classeId}/{profId?}', 'App\Http\Controllers\API\EvaluationsController@getPlanOfClass');
Route::post('/evaluations/planning/add', 'App\Http\Controllers\API\EvaluationsController@add');
Route::put('/evaluations/planning/{id}', 'App\Http\Controllers\API\EvaluationsController@update');

Route::post('/events/planning/add', 'App\Http\Controllers\API\EvaluationsController@addEvent');

Route::put('/settings/smsinfos', 'App\Http\Controllers\API\SettingsController@updateSMSInfos')->name('settings.updateSMSInfos');
Route::put('/settings/notifprefs', 'App\Http\Controllers\API\SettingsController@updateNotifPreferences')->name('settings.updateNotifPref');


Route::post('telegram/class/{classId}/prof/{profId}/add-channel', 'TelegramController@addChannel');
Route::delete('telegram/class/{classId}/prof/{profId}/delete-channel', 'TelegramController@deleteChannel');

Route::prefix('/api')->name('api.')->group(function(){
    // Route::get('/observations/{id}', 'App\Http\Controllers\API\ObservationsController@getObservation');

});
