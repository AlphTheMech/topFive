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
Route::get('/dowload_file', [ImageController::class, 'donwload']); //Скачивание файла
// Route::get('/info', [UserController::class, 'getInfoUser']);//Информация об авторизированного пользователе
// Route::post('/signin', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'login']); //Авторизация
Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::get('/get_dialog', [UserController::class, 'getDialog']);
    Route::get('/user/info', [UserController::class, 'getInfoUser']);//Получение информации о пользователе
    Route::post('/user/image', [ImageController::class, 'create']); //Загрузка фото
    Route::get('/user/find_tests', [UserController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
    Route::post('/user/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
});
Route::group(['middleware' => ['auth:sanctum', 'role:expert']], function () {
    Route::get('/expert/info', [UserController::class, 'getInfoUser']);//Получение информации о пользователе
    Route::post('/expert/image', [ImageController::class, 'create']); //Загрузка фото
    Route::get('/expert/find_tests', [UserController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
    // Route::post('/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
});

Route::group(['middleware' => ['auth:sanctum', 'role:teacher']], function () {
    Route::get('/teacher/info', [UserController::class, 'getInfoUser']);//Получение информации о пользователе
    Route::post('/teacher/image', [ImageController::class, 'create']); //Загрузка фото
    Route::post('/teacher/create_new_expert', [UserController::class, 'createExpert']); //Выдача прав доступа "Эксперт"
    Route::post('/teacher/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
    Route::get('/teacher/get_all_expert', [UserController::class, 'getAllExpert']); //Получение всех экспертов
    Route::get('/teacher/all_tests', [UserController::class, 'getAllTests']); //Получение всех тестов
    Route::post('/teacher/create_test', [UserController::class, 'postTests']); //Добавление тестов 
    Route::post('/teacher/create_subject', [UserController::class, 'createSubject']); //Добавление предмета
    Route::get('/teacher/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
});
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('/admin/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
    Route::post('/admin/create_subject', [UserController::class, 'createSubject']); //Добавление предмета
    Route::get('/admin/info', [UserController::class, 'getInfoUser']);//Получение информации о пользователе
    Route::post('/admin/image', [ImageController::class, 'create']); //Загрузка фото
    Route::post('/admin/create_new_teacher', [UserController::class, 'createTeacher']); //Выдача прав доступа "Учитель"
    Route::get('/admin/all_tests', [UserController::class, 'getAllTests']); //Получение всех тестов
    Route::post('/admin/create_new_expert', [UserController::class, 'createExpert']); //Выдача прав доступа "Эксперт"
    Route::post('/admin/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
    Route::post('/admin/create_test', [UserController::class, 'postTests']); //Добавление тестов 
    
});
