<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExternalTaskService
{
    private const BASE_URL = 'https://jsonplaceholder.typicode.com';

    /**
     * Отримати список записів.
     *
     * @return array{
     *     success: bool,
     *     status: int,
     *     data: mixed,
     *     duration_ms: float,
     *     error?: string
     * }
     */
    public function getPosts(): array
    {
        return $this->request('GET', '/posts');
    }

    /**
     * Отримати один запис за ID.
     *
     * @return array{
     *     success: bool,
     *     status: int,
     *     data: mixed,
     *     duration_ms: float,
     *     error?: string
     * }
     */
    public function getPostById(int $id): array
    {
        return $this->request('GET', "/posts/{$id}");
    }

    /**
     * Створити новий запис.
     *
     * @param array{title: string, body: string, userId: int} $data
     *
     * @return array{
     *     success: bool,
     *     status: int,
     *     data: mixed,
     *     duration_ms: float,
     *     error?: string
     * }
     */
    public function createPost(array $data): array
    {
        return $this->request('POST', '/posts', $data);
    }

    /**
     * Виконати HTTP-запит, перевірити статус і записати результат у лог.
     *
     * @return array{
     *     success: bool,
     *     status: int,
     *     data: mixed,
     *     duration_ms: float,
     *     error?: string
     * }
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        $startedAt = microtime(true);
        $url = self::BASE_URL.$endpoint;

        try {
            $client = Http::baseUrl(self::BASE_URL)
                ->acceptJson()
                ->timeout(15);

            /** @var Response $response */
            $response = match ($method) {
                'GET' => $client->get($endpoint),
                'POST' => $client->post($endpoint, $data),
                default => throw new \InvalidArgumentException("Непідтримуваний HTTP-метод: {$method}"),
            };

            $durationMs = $this->durationInMilliseconds($startedAt);

            if ($response->successful()) {
                Log::info('External API request successful', [
                    'method' => $method,
                    'url' => $url,
                    'status' => $response->status(),
                    'duration_ms' => $durationMs,
                ]);

                return [
                    'success' => true,
                    'status' => $response->status(),
                    'data' => $response->json(),
                    'duration_ms' => $durationMs,
                ];
            }

            if ($response->failed()) {
                Log::error('External API request failed', [
                    'method' => $method,
                    'url' => $url,
                    'status' => $response->status(),
                    'duration_ms' => $durationMs,
                    'response' => $response->json(),
                ]);

                return [
                    'success' => false,
                    'status' => $response->status(),
                    'data' => $response->json(),
                    'duration_ms' => $durationMs,
                    'error' => 'Зовнішній API повернув помилку.',
                ];
            }

            Log::error('External API returned an unexpected status', [
                'method' => $method,
                'url' => $url,
                'status' => $response->status(),
                'duration_ms' => $durationMs,
            ]);

            return [
                'success' => false,
                'status' => $response->status(),
                'data' => $response->json(),
                'duration_ms' => $durationMs,
                'error' => 'Зовнішній API повернув неочікуваний HTTP-статус.',
            ];
        } catch (Throwable $exception) {
            $durationMs = $this->durationInMilliseconds($startedAt);

            Log::error('External API connection error', [
                'method' => $method,
                'url' => $url,
                'duration_ms' => $durationMs,
                'exception' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 503,
                'data' => null,
                'duration_ms' => $durationMs,
                'error' => 'Не вдалося з’єднатися із зовнішнім API.',
            ];
        }
    }

    private function durationInMilliseconds(float $startedAt): float
    {
        return round((microtime(true) - $startedAt) * 1000, 2);
    }
}
