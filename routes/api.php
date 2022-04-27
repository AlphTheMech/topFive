<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MessengerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require __DIR__ . '/user.php';
require __DIR__ . '/expert.php';
require __DIR__ . '/teacher.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
Route::get('/dowload_file', [ImageController::class, 'donwload']);
Route::post('/login', [UserController::class, 'login']); //Авторизация



