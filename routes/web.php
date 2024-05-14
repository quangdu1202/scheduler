<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\ModuleClass\ModuleClassController;
use App\Http\Controllers\PracticeClass\PracticeClassController;
use App\Http\Controllers\PracticeRoom\PracticeRoomController;
use App\Http\Controllers\Schedule\ScheduleController;
use App\Http\Controllers\StudentMark\StudentMarkController;
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

Route::get('/', [HomeController::class, 'index'])->name('calendar');
Route::get('/filter', [HomeController::class, 'filter'])->name('calendar.filter');
Route::get('rooms', [HomeController::class, 'rooms'])->name('rooms');
Route::get('weekly', [HomeController::class, 'weeklyCalendar'])->name('weekly');
Route::get('getCellData', [HomeController::class, 'getCellData']);
Route::post('registerSchedule', [HomeController::class, 'registerSchedule']);

Route::get('mark-by-module', [MarkController::class, 'markByModule'])->name('mark-by-module');
Route::get('mark-by-practice', [MarkController::class, 'markByPractice'])->name('mark-by-practice');

// New

Route::get('/test', [HomeController::class, 'test'])->name('test');

Route::resource('practice-classes', PracticeClassController::class);

Route::get('modules/{id}/practice-classes', [ModuleController::class, 'showPracticeClasses'])->name('modules.show-practice-classes');
Route::get('/getModulesJsonData', [ModuleController::class, 'getJsonData'])->name('modules.get-json-data');
Route::resource('modules', ModuleController::class);

Route::get('/getSinglePracticeRoomJsonData', [PracticeRoomController::class, 'getSinglePracticeRoomJsonData'])->name('practice-rooms.get-single-room-json-data');
Route::get('/getPracticeRoomsJsonData', [PracticeRoomController::class, 'getJsonData'])->name('practice-rooms.get-json-data');
Route::resource('practice-rooms', PracticeRoomController::class);

Route::get('/getPracticeClassesJsonData', [PracticeClassController::class, 'getJsonData'])->name('practice-classes.get-json-data');
Route::get('/getJsonDataForSchedule/{practice_class_id}', [PracticeClassController::class, 'getJsonDataForSchedule'])->name('practice-classes.get-json-data-for-schedule');
Route::get('/getJsonDataForStudentsOfPracticeClass', [PracticeClassController::class, 'getJsonDataForStudentsOfPracticeClass'])->name('practice-classes.get-student-data-for-schedule');
Route::post('/updatePracticeClassStatus', [PracticeClassController::class, 'updatePracticeClassStatus'])->name('practice-classes.update-practice-class-status');
Route::resource('practice-classes', PracticeClassController::class);

Route::get('/getJsonDataForStudentsOfModuleClass', [ModuleClassController::class, 'getJsonDataForStudentsOfModuleClass'])->name('module-classes.get-student-data-for-mclass');
Route::get('/getModuleClassJsonData', [ModuleClassController::class, 'getJsonData'])->name('module-classes.get-json-data');
Route::post('/updateModuleClassStatus', [ModuleClassController::class, 'updateModuleClassStatus'])->name('module-classes.update-mclass-status');
Route::resource('module-classes', ModuleClassController::class);

Route::get('/getMarkJsonDataByPracticeClass/{practice_class_id}', [StudentMarkController::class, 'getMarkJsonDataByPracticeClass'])->name('student-marks.get-json-data-by-pclass');
Route::resource('student-marks', StudentMarkController::class);


Route::get('getAvailableRooms', [ScheduleController::class, 'getAvailableRooms'])->name('schedules.get-available-rooms');
Route::put('schedules', [ScheduleController::class, 'updateSingleSchedule'])->name('schedules.update-single-schedule');
Route::delete('schedules', [ScheduleController::class, 'deleteSingleSchedule'])->name('schedules.delete-single-schedule');
Route::resource('schedules', ScheduleController::class);

Route::get('/teacher/register-classes', [TeacherController::class, 'index'])->name('teacher.register-classes');
Route::post('/teacher/register-classes', [TeacherController::class, 'registerClass'])->name('teacher.register');
Route::get('/teacher/get-available-classes', [TeacherController::class, 'getJsonData'])->name('teacher.get-available-classes');
Route::get('/teacher/get-class-schedules/{practice_class_id}', [TeacherController::class, 'getJsonDataForSchedule'])->name('teacher.get-class-schedules');
Route::get('/teacher/get-registered-class', [TeacherController::class, 'getRegisteredClasses'])->name('teacher.get-registered-class');
Route::get('/teacher/get-schedule-table', [TeacherController::class, 'getJsonDataForScheduleTable'])->name('teacher.get-schedule-table');
Route::post('/teacher/cancel-registered-class', [TeacherController::class, 'cancelRegisteredClass'])->name('teacher.cancel-registered-class');

Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');