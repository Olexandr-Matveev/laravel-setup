<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::query()
            ->with(['project', 'author', 'assignee', 'comments.user', 'files'])
            ->latest()
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['new', 'in_progress', 'done'])],
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['nullable', 'integer', 'exists:users,id'],
            'due_date' => ['nullable', 'date'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $validated['author_id'] ??= $project->user_id;

        $task = Task::create($validated)
            ->load(['project', 'author', 'assignee', 'comments', 'files']);

        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json(
            $task->load(['project', 'author', 'assignee', 'comments.user', 'files'])
        );
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['new', 'in_progress', 'done'])],
            'project_id' => ['sometimes', 'required', 'integer', 'exists:projects,id'],
            'assignee_id' => ['sometimes', 'nullable', 'integer', 'exists:users,id'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'author_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        $task->update($validated);

        return response()->json(
            $task->fresh()->load(['project', 'author', 'assignee', 'comments.user', 'files'])
        );
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
