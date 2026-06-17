<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Comment::query()
                ->with(['task', 'user'])
                ->latest()
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_id' => ['required', 'integer', 'exists:tasks,id'],
            'content' => ['required', 'string', 'max:1000'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $task = Task::findOrFail($validated['task_id']);
        $validated['user_id'] ??= $task->author_id;

        $comment = Comment::create($validated)
            ->load(['task', 'user']);

        return response()->json($comment, 201);
    }

    public function show(Comment $comment): JsonResponse
    {
        return response()->json($comment->load(['task', 'user']));
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $validated = $request->validate([
            'task_id' => ['sometimes', 'required', 'integer', 'exists:tasks,id'],
            'content' => ['sometimes', 'required', 'string', 'max:1000'],
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        $comment->update($validated);

        return response()->json(
            $comment->fresh()->load(['task', 'user'])
        );
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
