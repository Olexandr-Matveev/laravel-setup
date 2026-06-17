<?php

namespace App\Http\Controllers;

use App\Services\ExternalTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExternalApiController extends Controller
{
    public function __construct(
        private readonly ExternalTaskService $externalTaskService
    ) {
    }

    /**
     * Отримати список записів із зовнішнього API.
     */
    public function posts(): JsonResponse
    {
        return $this->respond(
            $this->externalTaskService->getPosts()
        );
    }

    /**
     * Отримати один запис за ID.
     */
    public function show(int $id): JsonResponse
    {
        return $this->respond(
            $this->externalTaskService->getPostById($id)
        );
    }

    /**
     * Створити новий запис у зовнішньому API.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'userId' => ['required', 'integer', 'min:1'],
        ]);

        return $this->respond(
            $this->externalTaskService->createPost($validated)
        );
    }

    /**
     * Перетворити результат сервісу на JSON-відповідь API.
     *
     * @param array{
     *     success: bool,
     *     status: int,
     *     data: mixed,
     *     duration_ms: float,
     *     error?: string
     * } $result
     */
    private function respond(array $result): JsonResponse
    {
        if ($result['success']) {
            return response()->json(
                $result['data'],
                $result['status']
            );
        }

        return response()->json([
            'message' => $result['error'] ?? 'Помилка зовнішнього API.',
            'external_status' => $result['status'],
            'details' => $result['data'],
        ], $this->clientStatus($result['status']));
    }

    private function clientStatus(int $externalStatus): int
    {
        if ($externalStatus >= 400 && $externalStatus < 500) {
            return $externalStatus;
        }

        return 502;
    }
}
