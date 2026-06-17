<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExternalApiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('comments', CommentController::class);

Route::get('/external/posts', [ExternalApiController::class, 'posts']);
Route::get('/external/posts/{id}', [ExternalApiController::class, 'show'])
    ->whereNumber('id');
Route::post('/external/posts', [ExternalApiController::class, 'store']);
