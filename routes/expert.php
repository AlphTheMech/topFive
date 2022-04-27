<?php

use App\Http\Controllers\ExpertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ResultTestController;
use App\Http\Controllers\SubjectController;

Route::group(['prefix' => 'expert', 'middleware' => ['auth:sanctum', 'role:expert']], function () {
    Route::get('/get_all_expert', [ExpertController::class, 'getAllExpert']); //Получение всех экспертов
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
    //Route::get('/get_message', [UserController::class, 'getMessage']);// Получение сообщений
    //Route::get('/get_dialog', [UserController::class, 'getDialog']);//Получение диалогов
    Route::get('/get_result', [ResultTestController::class, 'getResults']); //Получение результата теста
    Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
    Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
    Route::get('/find_tests', [ExpertController::class, 'searchForAnExpert']); //Получение тестов с открытым доступом
    // Route::post('/accept_result', [UserController::class, 'postResultTest']); //Добавление результатов прохождения теста
    Route::post('/give_acces_expert', [TestController::class, 'addingAccessToTest']); //Открытие доступа к тесту
    Route::get('/get_all_subject', [SubjectController::class, 'getSubject']);
    Route::get('/find_for_admin', [UserController::class, 'findForAdmin']);
    Route::get('/all_subject', [SubjectController::class, 'allSubject']);
  });
  