<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\ModuleClass\ModuleClassController;
use App\Http\Controllers\PracticeClass\PracticeClassController;
use App\Http\Controllers\PracticeRoom\PracticeRoomController;
use App\Http\Controllers\Schedule\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('root');

Route::get('/test', [HomeController::class, 'test'])->name('test');

Route::prefix('modules')->controller(ModuleController::class)->group(function () {
    Route::get('/getModulesJsonData', 'getJsonData')->name('modules.get-json-data');
});
Route::resource('modules', ModuleController::class);

Route::prefix('practice-rooms')->controller(PracticeRoomController::class)->group(function () {
    Route::get('/getSinglePracticeRoomJsonData', 'getSinglePracticeRoomJsonData')->name('practice-rooms.get-single-room-json-data');
    Route::get('/getPracticeRoomsJsonData', 'getJsonData')->name('practice-rooms.get-json-data');
});
Route::resource('practice-rooms', PracticeRoomController::class);

Route::prefix('practice-classes')->controller(PracticeClassController::class)->group(function () {
    Route::get('/getSignatureClassInfo', 'getSignatureClassInfo')->name('practice-classes.get-signature-info');
    Route::get('/getPracticeClassesJsonData', 'getJsonData')->name('practice-classes.get-json-data');
    Route::get('/getJsonDataForSchedule/{practice_class_id}', 'getJsonDataForSchedule')->name('practice-classes.get-json-data-for-schedule');
    Route::get('/getStudentsList', 'getStudentsOfPracticeClass')->name('practice-classes.get-students-of-pclass');
    Route::post('/updatePracticeClassStatus', 'updatePracticeClassStatus')->name('practice-classes.update-practice-class-status');
});
Route::resource('practice-classes', PracticeClassController::class);

Route::prefix('module-classes')->controller(ModuleClassController::class)->group(function () {
    Route::get('/getStudentsOfModuleClass', 'getJsonDataForStudentsOfModuleClass')->name('module-classes.get-student-data-for-mclass');
    Route::get('/getModuleClassJsonData', 'getJsonData')->name('module-classes.get-json-data');
    Route::post('/updateModuleClassStatus', 'updateModuleClassStatus')->name('module-classes.update-mclass-status');
});
Route::resource('module-classes', ModuleClassController::class);

Route::prefix('schedules')->controller(ScheduleController::class)->group(function () {
    Route::get('/getAvailableRooms', 'getAvailableRooms')->name('schedules.get-available-rooms');
    Route::put('/', 'updateSingleSchedule')->name('schedules.update-single-schedule');
    Route::put('/signature', 'updateSignatureSchedule')->name('schedules.update-signature-schedule');
    Route::delete('/', 'deleteSingleSchedule')->name('schedules.delete-single-schedule');
});
Route::resource('schedules', ScheduleController::class);

Route::prefix('teacher')->controller(TeacherController::class)->group(function () {
    Route::get('/register-classes', 'registerClasses')->name('teacher.register-classes');
    Route::get('/manage-classes', 'manageClasses')->name('teacher.manage-classes');
    Route::post('/register-class', 'registerClass')->name('teacher.register-class');
    Route::get('/get-available-classes', 'getJsonData')->name('teacher.get-available-classes');
    Route::get('/get-class-schedules/{practice_class_id}', 'getJsonDataForSchedule')->name('teacher.get-class-schedules');
    Route::get('/get-registered-classes', 'getRegisteredClasses')->name('teacher.get-registered-classes');
    Route::get('/get-schedule-table', 'getJsonDataForScheduleTable')->name('teacher.get-schedule-table');
    Route::get('/get-registered-schedule-table', 'getJsonDataForScheduleTable')->name('teacher.get-registered-schedule-table');
    Route::get('/get-classes-ondate', 'getClassOndate')->name('teacher.get-classes-ondate');
    Route::post('/cancel-registered-class', 'cancelRegisteredClass')->name('teacher.cancel-registered-class');
});

Route::prefix('student')->controller(StudentController::class)->group(function () {
    Route::get('/register-classes', 'index')->name('student.register-classes');
    Route::get('/manage-classes', 'manageClasses')->name('student.manage-classes');
    Route::get('/get-schedule-table', 'getJsonDataForScheduleTable')->name('student.get-schedule-table');
    Route::get('/get-registered-schedule-table', 'getJsonDataForScheduleTable')->name('student.get-registered-schedule-table');
    Route::get('/get-available-classes', 'getAvailableClasses')->name('student.get-available-classes');
    Route::get('/get-class-schedules/{practice_class_id}', 'getJsonDataForSchedule')->name('student.get-class-schedules');
    Route::post('/register-class', 'registerClass')->name('student.register-class');
    Route::post('/cancel-registered-class', 'cancelRegisteredClass')->name('student.cancel-registered-class');
    Route::get('/get-registered-class', 'getRegisteredClasses')->name('student.get-registered-class');
    Route::get('/get-registered-class-schedules/{practice_class_id}/{shift}', 'getRegisteredClassSchedules')->name('student.get-registered-class-schedules');
    Route::get('/get-classes-ondate', 'getClassOndate')->name('student.get-classes-ondate');
});

Auth::routes();