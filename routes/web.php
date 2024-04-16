<?php

use App\Http\Controllers\Acess\AcessController;
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

Route::get('/cadastra-usuario/{code}', [AcessController::class, 'registerUserExternal'])->name('cadastra-usuario')->middleware(\App\Http\Middleware\NoCacheMiddleware::class);
Route::post('/createUserExternal', [UserController::class, 'createUserExternal'])->name('createUserExternal');

Route::post('/logon', [AcessController::class, 'logon'])->name('logon');

Route::middleware(['auth', 'check.type:3'])->group(function () {

    Route::get('/app', [DashboardController::class, 'app'])->name('app');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profileUpdate', [UserController::class, 'profileUpdate'])->name('profileUpdate');
    
    Route::get('/listUser/{tipo?}', [UserController::class, 'listUser'])->name('listUser');
    Route::get('/filterUser', [UserController::class, 'filterUser'])->name('filterUser');
    Route::get('/viewUser/{id?}', [UserController::class, 'viewUser'])->name('viewUser');
    Route::get('/view/{id}', [UserController::class, 'view'])->name('view');
    Route::post('/createUser', [UserController::class, 'createUser'])->name('createUser');
    Route::post('/updateUser', [UserController::class, 'updateUser'])->name('updateUser');
    Route::post('/deleteUser', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/importUser', [UserController::class, 'importUser'])->name('importUser');

    Route::get('/listReport', [UserController::class, 'listReport'])->name('listReport');

    Route::get('/listGrupo', [UserController::class, 'listGrupo'])->name('listGrupo');
    Route::post('/createGrupo', [UserController::class, 'createGrupo'])->name('createGrupo');
    Route::post('/deleteGrupo', [UserController::class, 'deleteGrupo'])->name('deleteGrupo');

    Route::get('/list-whatsapp', [WhatsappController::class, 'listWhatsapp'])->name('list-whatsapp');
    Route::post('registrer-whatsapp', [WhatsappController::class, 'registrerWhatsapp'])->name('registrer-whatsapp');
    Route::post('delete-whatsapp', [WhatsappController::class, 'deleteWhatsapp'])->name('delete-whatsapp');

    Route::get('/log', [WhatsappController::class, 'log'])->name('log');

    Route::get('/list-happy', [WhatsappController::class, 'listHappy'])->name('list-happy');
    Route::get('/send-happy/{number?}', [WhatsappController::class, 'sendHappy'])->name('send-happy');

    Route::get('/logout', [AcessController::class, 'logout'])->name('logout');
})->middleware(\App\Http\Middleware\NoCacheMiddleware::class);