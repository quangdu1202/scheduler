<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PracticeClass\PracticeClassController;
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
//Route::get('practice-class', [PracticeClassController::class, 'index'])->name('practice-class.index');

Route::resource('practice-classes', PracticeClassController::class, [
    'names' => [
        'index' => 'practice-classes.index',
        'show' => 'practice-classes.show',
        'create' => 'practice-classes.create',
        'edit' => 'practice-classes.edit',
        'update' => 'practice-classes.update',
        'store' => 'practice-classes.save',
        'destroy' => 'practice-classes.delete',
    ]
]);
//Auth::routes();
//
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
