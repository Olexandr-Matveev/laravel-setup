<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->notImplemented();
    }

    public function store(Request $request): JsonResponse
    {
        return $this->notImplemented();
    }

    public function show(Comment $comment): JsonResponse
    {
        return $this->notImplemented();
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        return $this->notImplemented();
    }

    public function destroy(Comment $comment): JsonResponse
    {
        return $this->notImplemented();
    }

    private function notImplemented(): JsonResponse
    {
        return response()->json([
            'message' => 'Comment controller methods will be implemented in laboratory work 6.',
        ], 501);
    }
}
