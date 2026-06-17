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
            ->with(['user', 'tasks', 'files'])
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
        return response()->json([
            'message' => 'This method will be implemented in laboratory work 6.',
        ], 501);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        return response()->json([
            'message' => 'This method will be implemented in laboratory work 6.',
        ], 501);
    }

    public function destroy(Project $project): JsonResponse
    {
        return response()->json([
            'message' => 'This method will be implemented in laboratory work 6.',
        ], 501);
    }
}
