<?php

use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

require __DIR__ . '/auth.php';
// Route::post('/signin',[UserController::class, 'login']);
Route::get('/dowload_file',[ImageController::class , 'donwload']);
Route::middleware(['auth:sanctum'])->get('/user/info', [UserController::class, 'getInfoUser']);

// Route::get('/info', [UserController::class, 'getInfoUser']);//Информация об авторизированного пользователе
Route::get('/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
Route::post('/create_subject', [UserController::class, 'createSubject']); //Добавление предмета
Route::post('/create_test', [UserController::class, 'postTests']); //Добавление тестов 
Route::post('create_new_expert', [UserController::class, 'createExpert']); //Выдача прав доступа "Эксперт"
Route::post('create_new_teacher', [UserController::class, 'createTeacher']); //Выдача прав доступа "Учитель"
Route::get('/all_tests', [UserController::class, 'getAllTests']); //Получение всех тестов
Route::post('/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
Route::middleware(['auth:sanctum'])->get('/find_tests', [UserController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
Route::middleware(['auth:sanctum'])->post('/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
Route::middleware(['auth:sanctum'])->post('/image', [ImageController::class, 'create']); //Загрузка фото
Route::post('/login', [UserController::class, 'login']);

// Route::post('/signin', [UserController::class, 'login']);
// Route::group(['middleware' => 'role:user'], function( ){
//     Route::post('/signin', [UserController::class, 'login']);

// });
// Route::group(['middleware' => 'role:expert'], function( ){
//     Route::post('/signin', [UserController::class, 'login']);

// });
// Route::group(['middleware' => 'role:admin'], function( ){
//     Route::post('/signin', [UserController::class, 'login']);

// });
// Route::group(['middleware' => 'role:teacher'], function( ){
//     Route::post('/signin', [UserController::class, 'login']);

// });