<?php

use App\Http\Controllers\Acess\AcessController;
use App\Http\Controllers\Agenda\AgendaController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Whatsapp\WhatsappController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckUserType;

Route::get('/', function () {
    return view('index');
})->name('login');

Route::get('/forgout/{code?}', [AcessController::class, 'forgout'])->name('forgout');
Route::post('/forgout-password', [AcessController::class, 'forgoutPassword'])->name('forgout-password');
Route::post('/recoverPassword', [AcessController::class, 'recoverPassword'])->name('recoverPassword');

Route::get('/cadastra-usuario/{code}/{grupo?}', [AcessController::class, 'registerUserExternal'])->name('cadastra-usuario');
Route::post('/createUserExternal', [UserController::class, 'createUserExternal'])->name('createUserExternal');

Route::post('/logon', [AcessController::class, 'logon'])->name('logon');

Route::middleware(['auth', 'check.type:3'])->group(function () {

    Route::get('/app', [DashboardController::class, 'app'])->name('app');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profileUpdate', [UserController::class, 'profileUpdate'])->name('profileUpdate');
    
    Route::get('/listUser/{tipo?}', [UserController::class, 'listUser'])->name('listUser');
    Route::get('/filterUser', [UserController::class, 'filterUser'])->name('filterUser');
    Route::get('/registrerUser/{tipo?}', [UserController::class, 'registrerUser'])->name('registrerUser');
    Route::get('/viewUser/{id?}', [UserController::class, 'viewUser'])->name('viewUser');
    Route::post('/createUser', [UserController::class, 'createUser'])->name('createUser');
    Route::post('/updateUser', [UserController::class, 'updateUser'])->name('updateUser');
    Route::post('/deleteUser', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/importUser', [UserController::class, 'importUser'])->name('importUser');

    Route::get('/listGrupo', [UserController::class, 'listGrupo'])->name('listGrupo');
    Route::post('/createGrupo', [UserController::class, 'createGrupo'])->name('createGrupo');
    Route::post('/deleteGrupo', [UserController::class, 'deleteGrupo'])->name('deleteGrupo');

    Route::get('/listEvent', [AgendaController::class, 'listEvent'])->name('listEvent');
    Route::get('/viewEvent/{id}', [AgendaController::class, 'viewEvent'])->name('viewEvent');
    Route::get('/filterEvent', [AgendaController::class, 'filterEvent'])->name('filterEvent');
    Route::post('registrerEvent', [AgendaController::class, 'registrerEvent'])->name('registrerEvent');
    Route::post('updateEvent', [AgendaController::class, 'updateEvent'])->name('updateEvent');
    Route::post('deleteEvent', [AgendaController::class, 'deleteEvent'])->name('deleteEvent');

    Route::get('/listWhatsapp', [WhatsappController::class, 'listWhatsapp'])->name('listWhatsapp');
    Route::post('registrerWhatsapp', [WhatsappController::class, 'registrerWhatsapp'])->name('registrerWhatsapp');
    Route::post('deleteWhatsapp', [WhatsappController::class, 'deleteWhatsapp'])->name('deleteWhatsapp');

    Route::get('/listMessage', [WhatsappController::class, 'listMessage'])->name('listMessage');
    Route::post('registrerMessage', [WhatsappController::class, 'registrerMessage'])->name('registrerMessage');

    Route::get('/logout', [AcessController::class, 'logout'])->name('logout');
});