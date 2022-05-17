<?php

use App\Http\Controllers\ExpertController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoggingController;
use App\Http\Controllers\ResultTestController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WhiteListIpController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::group(['middleware' => 'ip', 'prefix' => 'ip'], function () {
        Route::get('/logging', [LoggingController::class, 'getLogging']);
        Route::post('/post', [WhiteListIpController::class, 'postIp']);
        Route::get('/get', [WhiteListIpController::class, 'getIp']);
        Route::patch('/update/{id}', [WhiteListIpController::class, 'updateIp']);
        Route::delete('/delete/{id}', [WhiteListIpController::class, 'deleteIp']);
        Route::delete('/logging/delete', [LoggingController::class, 'deleteAllLog']);
    });
    // Route::get('/logging', [LoggingController::class, 'getLogging']);
    Route::get('/get', [WhiteListIpController::class, 'getIp']);
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
    Route::get('/test_statistics', [StatisticController::class, 'gettingTestStatistics']); //Статистика по тесту
    Route::get('/all_test_statistics', [StatisticController::class, 'gettingTestStatisticsAll']); //Общая статистика
    Route::post('/give_acces_expert', [TestController::class, 'addingAccessToTest']); //Открытие доступа к тесту
    Route::get('/get_result', [ResultTestController::class, 'getResults']); //Получение результата теста
    Route::get('/get_all_expert', [ExpertController::class, 'getAllExpert']); //Получение всех экспертов
    Route::get('/find_for_admin', [UserController::class, 'findForAdmin']); //Поиск по почте
    Route::post('/create_subject', [SubjectController::class, 'createSubject']); //Добавление предмета
    Route::get('/info', [UserController::class, 'getInfoUser']); //Получение информации о пользователе
    Route::post('/image', [ImageController::class, 'create']); //Загрузка фото
    Route::post('/create_new_teacher', [UserController::class, 'createTeacher']); //Выдача прав доступа "Учитель"
    Route::get('/all_tests', [TestController::class, 'getAllTests']); //Получение всех тестов
    Route::post('/give_acces_expert', [TestController::class, 'addingAccessToTest']); //Открытие доступа к тесту
    Route::post('/create_test', [TestController::class, 'postTests']); //Добавление тестов 
    Route::get('/get_all_subject', [SubjectController::class, 'getSubject']);
    Route::get('/all_subject', [SubjectController::class, 'allSubject']);
    Route::get('teacher_statistics', [StatisticController::class, 'teacherExperts']);
    Route::patch('/update_test/{test}', [TestController::class, 'updateTests']); //Обновление теста
    Route::delete('/remove_access_to_test', [TestController::class, 'removeAccessToTest']); //Удаление доступа к тесту 
    Route::patch('/update_subject/{subject}', [SubjectController::class, 'updateSubject']); //Обновление предмета
    Route::delete('/delete_subject/{subject}', [SubjectController::class, 'deleteSubject']); //Удаление предмета
    Route::delete('/delete_test', [TestController::class, 'deleteTest']); //Удаление теста
    Route::patch('/update_attempt/{result}', [ResultTestController::class, 'updateAttemptToTest']); //Обновление попыток теста 
    Route::patch('/update_user_info', [UserController::class, 'updateInfoUser']); //Обновление персональных данных
});
