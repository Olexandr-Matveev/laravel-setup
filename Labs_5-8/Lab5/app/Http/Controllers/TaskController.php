<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->notImplemented();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->notImplemented();
    }

    public function show(Task $task): JsonResponse
    {
        return $this->notImplemented();
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        return $this->notImplemented();
    }

    public function destroy(Task $task): JsonResponse
    {
        return $this->notImplemented();
    }

    private function notImplemented(): JsonResponse
    {
        return response()->json([
            'message' => 'Task controller methods will be implemented in laboratory work 6.',
        ], 501);
    }
}
