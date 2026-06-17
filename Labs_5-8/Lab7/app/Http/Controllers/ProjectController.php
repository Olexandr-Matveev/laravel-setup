<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::query()
            ->with(['user', 'tasks.assignee', 'files'])
            ->latest()
            ->get();

        return response()->json($projects);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $project = Project::create($validated)
            ->load(['user', 'tasks', 'files']);

        return response()->json($project, 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json(
            $project->load(['user', 'tasks.author', 'tasks.assignee', 'files'])
        );
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        $project->update($validated);

        return response()->json(
            $project->fresh()->load(['user', 'tasks', 'files'])
        );
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}
