<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\Module\ModuleController;
use App\Http\Controllers\PracticeClass\PracticeClassController;
use App\Http\Controllers\PracticeRoom\PracticeRoomController;
use App\Http\Controllers\RoomController;
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
Route::post('getRoomData', [RoomController::class, 'getRoomData']);
Route::post('registerSchedule', [HomeController::class, 'registerSchedule']);

Route::get('mark-by-module', [MarkController::class, 'markByModule'])->name('mark-by-module');
Route::get('mark-by-practice', [MarkController::class, 'markByPractice'])->name('mark-by-practice');

Route::resource('practice-classes', PracticeClassController::class);

Route::resource('modules', ModuleController::class);
Route::get('modules/{id}/practice-classes', [ModuleController::class, 'showPracticeClasses'])->name('modules.show-practice-classes');
Route::get('/getModulesJsonData', [ModuleController::class, 'getJsonData'])->name('modules.get-json-data');

Route::resource('practice-rooms', PracticeRoomController::class);
Route::get('/getPracticeRoomsJsonData', [PracticeRoomController::class, 'getJsonData'])->name('practice-rooms.get-json-data');

//Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
