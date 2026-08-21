<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\PublicController;

use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AttendanceController;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

// Public Routes
Route::get('/settings', [PublicController::class, 'settings']);
Route::get('/pages/{slug}', [PublicController::class, 'page']);
Route::get('/articles', [PublicController::class, 'articles']);
Route::get('/articles/{slug}', [PublicController::class, 'article']);
Route::get('/galleries', [PublicController::class, 'galleries']);
Route::get('/teachers', [PublicController::class, 'teachers']);
Route::post('/contact', [PublicController::class, 'submitContact']);

// PPDB Routes
Route::get('/ppdb/info', [PublicController::class, 'ppdbInfo']);
Route::post('/ppdb/register', [PpdbController::class, 'register']);
Route::get('/ppdb/status/{registration_number}', [PpdbController::class, 'status']);

// Attendance Route
Route::post('/attendance/checkin', [AttendanceController::class, 'checkin']);
Route::post('/attendance/scan', [AttendanceController::class, 'scan']);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PpdbAdminController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardStats']);

    // Admin Settings
    Route::post('/admin/settings', [AdminController::class, 'updateSettings']);
    Route::post('/admin/upload-image', [AdminController::class, 'uploadImage']);
    
    // Admin Pages
    Route::put('/admin/pages/{slug}', [AdminController::class, 'updatePage']);

    // Admin Articles
    Route::post('/admin/articles', [AdminController::class, 'storeArticle']);
    Route::put('/admin/articles/{id}', [AdminController::class, 'updateArticle']);
    Route::delete('/admin/articles/{id}', [AdminController::class, 'destroyArticle']);

    // Admin Teachers
    Route::post('/admin/teachers', [AdminController::class, 'storeTeacher']);
    Route::put('/admin/teachers/{id}', [AdminController::class, 'updateTeacher']);
    Route::delete('/admin/teachers/{id}', [AdminController::class, 'destroyTeacher']);

    // Admin Galleries
    Route::post('/admin/galleries', [AdminController::class, 'storeGallery']);
    Route::delete('/admin/galleries/{id}', [AdminController::class, 'destroyGallery']);

    // Admin Students & Attendance
    Route::get('/admin/students', [StudentController::class, 'index']);
    Route::post('/admin/students', [StudentController::class, 'store']);
    Route::post('/admin/students/generate-pins', [StudentController::class, 'generatePins']);
    Route::put('/admin/students/{id}/reset-pin', [StudentController::class, 'resetPin']);
    Route::get('/admin/attendances', [StudentController::class, 'attendances']);
    Route::get('/admin/attendance/export', [StudentController::class, 'exportAttendances']);

    // Admin PPDB
    Route::get('/admin/ppdb', [PpdbAdminController::class, 'index']);
    Route::get('/admin/ppdb/{id}', [PpdbAdminController::class, 'show']);
    Route::put('/admin/ppdb/{id}/status', [PpdbAdminController::class, 'updateStatus']);
});
