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
Route::get('/dowload_file', [ImageController::class, 'donwload']);
Route::post('/login', [UserController::class, 'login']); //Авторизация
Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum', 'role:user']], function () {
  Route::get('/get_all_subject', [UserController::class, 'getSubject']);
  Route::post('getFriends', [UserController::class, 'getFriends']); //Получение чатов
  Route::prefix('session')->group(function () {
    Route::post('/create', [UserController::class, 'createSession']); //Создание чата
    Route::post('/chats/{session}', [UserController::class, 'chats']); //Вывод сообщений
    Route::post('/read/{session}', [UserController::class, 'readMessage']); //Сообщение прочитано
    Route::post('/clear/{session}', [UserController::class, 'clearMessages']); //Удалить сообщения
    Route::post('/block/{session}', [UserController::class, 'blockUser']); //Заблокировать пользователя 
    Route::post('/unblock/{session}', [UserController::class, 'unblockUser']); //Разблокировать пользователя 
  });
  Route::post('/send/{session}', [UserController::class, 'send']); //Добавление сообщения
  Route::get('/get_result', [UserController::class, 'getResults']); //Получение результата теста
  Route::get('/get_message', [UserController::class, 'getMessage']); // Получение сообщений
  Route::get('/get_dialog', [UserController::class, 'getDialog']); //Получение диалогов
  Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
  Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
  Route::get('/find_tests', [UserController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
  Route::post('/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
  Route::get('/get_all_expert', [UserController::class, 'getAllExpert']); //Получение всех экспертов
  Route::get('/all_subject', [UserController::class, 'allSubject']);
});
Route::group(['prefix' => 'expert', 'middleware' => ['auth:sanctum', 'role:expert']], function () {
  Route::get('/get_all_expert', [UserController::class, 'getAllExpert']); //Получение всех экспертов
  Route::post('getFriends', [UserController::class, 'getFriends']); //Получение чатов
  Route::prefix('session')->group(function () {
    Route::post('/create', [UserController::class, 'createSession']); //Создание чата
    Route::post('/chats/{session}', [UserController::class, 'chats']); //Вывод сообщений
    Route::post('/read/{session}', [UserController::class, 'readMessage']); //Сообщение прочитано
    Route::post('/clear/{session}', [UserController::class, 'clearMessages']); //Удалить сообщения
    Route::post('/block/{session}', [UserController::class, 'blockUser']); //Заблокировать пользователя 
    Route::post('/unblock/{session}', [UserController::class, 'unblockUser']); //Разблокировать пользователя 
  });
  Route::post('/send/{session}', [UserController::class, 'send']); //Добавление сообщения
  //Route::get('/get_message', [UserController::class, 'getMessage']);// Получение сообщений
  //Route::get('/get_dialog', [UserController::class, 'getDialog']);//Получение диалогов
  Route::get('/get_result', [UserController::class, 'getResults']); //Получение результата теста
  Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
  Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
  Route::get('/find_tests', [UserController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
  // Route::post('/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
  Route::post('/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
  Route::get('/get_all_subject', [UserController::class, 'getSubject']);
  Route::get('/find_for_admin', [UserController::class, 'findForAdmin']);
  Route::get('/all_subject', [UserController::class, 'allSubject']);
});

Route::group(['prefix' => 'teacher', 'middleware' => ['auth:sanctum', 'role:teacher']], function () {
  Route::post('getFriends', [UserController::class, 'getFriends']); //Получение чатов
  Route::prefix('session')->group(function () {
    Route::post('/create', [UserController::class, 'createSession']); //Создание чата
    Route::post('/chats/{session}', [UserController::class, 'chats']); //Вывод сообщений
    Route::post('/read/{session}', [UserController::class, 'readMessage']); //Сообщение прочитано
    Route::post('/clear/{session}', [UserController::class, 'clearMessages']); //Удалить сообщения
    Route::post('/block/{session}', [UserController::class, 'blockUser']); //Заблокировать пользователя 
    Route::post('/unblock/{session}', [UserController::class, 'unblockUser']); //Разблокировать пользователя 
  });
  Route::post('/send/{session}', [UserController::class, 'send']); //Добавление сообщения
  Route::get('/get_dialog', [UserController::class, 'getDialog']); //Получение диалогов
  Route::get('/get_result', [UserController::class, 'getResults']); //Получение результата теста
  Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
  Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
  Route::post('/create_new_teacher', [UserController::class, 'createTeacher']); //Выдача прав доступа "Учитель"
  Route::post('/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
  Route::get('/get_all_expert', [UserController::class, 'getAllExpert']); //Получение всех экспертов
  Route::get('/all_tests', [UserController::class, 'getAllTests']); //Получение всех тестов
  Route::post('/create_test', [UserController::class, 'postTests']); //Добавление тестов 
  Route::post('/create_subject', [UserController::class, 'createSubject']); //Добавление предмета
  Route::get('/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
  Route::get('/test_statistics', [UserController::class, 'gettingTestStatistics']); //Статистика по тесту
  Route::get('/all_test_statistics', [UserController::class, 'gettingTestStatisticsAll']); //Общая статистика
  Route::get('/get_all_subject', [UserController::class, 'getSubject']);
  Route::get('/all_subject', [UserController::class, 'allSubject']);
  Route::get('teacher_statistics', [UserController::class, 'teacherExperts']);
});
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin']], function () {
  Route::group(['namespace' => 'Api', 'middleware' => 'ip'], function () {
  });
  Route::post('getFriends', [UserController::class, 'getFriends']); //Получение чатов
  Route::prefix('session')->group(function () {
    Route::post('/create', [UserController::class, 'createSession']); //Создание чата
    Route::post('/chats/{session}', [UserController::class, 'chats']); //Вывод сообщений
    Route::post('/read/{session}', [UserController::class, 'readMessage']); //Сообщение прочитано
    Route::post('/clear/{session}', [UserController::class, 'clearMessages']); //Удалить сообщения
    Route::post('/block/{session}', [UserController::class, 'blockUser']); //Заблокировать пользователя 
    Route::post('/unblock/{session}', [UserController::class, 'unblockUser']); //Разблокировать пользователя 
  });
  Route::post('/send/{session}', [UserController::class, 'send']); //Добавление сообщения
  Route::get('/test_statistics', [UserController::class, 'gettingTestStatistics']); //Статистика по тесту
  Route::get('/all_test_statistics', [UserController::class, 'gettingTestStatisticsAll']); //Общая статистика
  Route::post('/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
  Route::get('/get_dialog', [UserController::class, 'getDialog']); //Получение диалогов
  Route::get('/get_result', [UserController::class, 'getResults']); //Получение результата теста
  Route::get('/get_all_expert', [UserController::class, 'getAllExpert']); //Получение всех экспертов
  Route::get('/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
  Route::post('/create_subject', [UserController::class, 'createSubject']); //Добавление предмета
  Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
  Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
  Route::post('/create_new_teacher', [UserController::class, 'createTeacher']); //Выдача прав доступа "Учитель"
  Route::get('/all_tests', [UserController::class, 'getAllTests']); //Получение всех тестов
  // Route::post('/admin/create_new_expert', [UserController::class, 'createExpert']); //Выдача прав доступа "Эксперт"
  Route::post('/give_acces_expert', [UserController::class, 'addingAccessToTest']); //Открытие доступа к тесту
  Route::post('/create_test', [UserController::class, 'postTests']); //Добавление тестов 
  Route::get('/get_all_subject', [UserController::class, 'getSubject']);
  Route::get('/all_subject', [UserController::class, 'allSubject']);
});
