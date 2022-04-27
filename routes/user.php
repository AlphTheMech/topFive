<?php

use App\Http\Controllers\ExpertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ResultTestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestController;

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::get('/get_all_subject', [SubjectController::class, 'getSubject']);
    Route::post('getFriends', [MessengerController::class, 'getFriends']); //Получение чатов
    Route::prefix('session')->group(function () {
        Route::post('/create', [MessengerController::class, 'createSession']); //Создание чата
        Route::post('/chats/{session}', [MessengerController::class, 'chats']); //Вывод сообщений
        Route::post('/read/{session}', [MessengerController::class, 'readMessage']); //Сообщение прочитано
        Route::post('/clear/{session}', [MessengerController::class, 'clearMessages']); //Удалить сообщения
        Route::post('/block/{session}', [MessengerController::class, 'blockUser']); //Заблокировать пользователя 
        Route::post('/unblock/{session}', [MessengerController::class, 'unblockUser']); //Разблокировать пользователя 
    });
    Route::post('/send/{session}', [MessengerController::class, 'send']); //Добавление сообщения
    Route::get('/get_result', [ResultTestController::class, 'getResults']); //Получение результата теста
    Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
    Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
    Route::get('/find_tests', [ExpertController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
    Route::post('/accept_result', [TestController::class, 'postResultTest']); //Добавление результатов прохождения теста
    Route::get('/get_all_expert', [ExpertController::class, 'getAllExpert']); //Получение всех экспертов
    Route::get('/all_subject', [SubjectController::class, 'allSubject']);
});