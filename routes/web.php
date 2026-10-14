<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::resource('students', StudentController::class);
Route::resource('instructors', InstructorController::class);
Route::resource('vehicles', VehicleController::class);
Route::resource('courses', CourseController::class);
Route::resource('lessons', LessonController::class);
