<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('projects', ProjectController::class);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('comments', CommentController::class);
