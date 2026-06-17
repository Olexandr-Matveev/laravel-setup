<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class GenerateTaskReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:report
                            {--project_id= : ID проєкту для фільтрації задач}';

    /**
     * The console command description.
     */
    protected $description = 'Вивести звіт про задачі';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $projectId = $this->option('project_id');
        $query = Task::query()->orderBy('id');

        if ($projectId !== null && $projectId !== '') {
            if (! ctype_digit((string) $projectId) || (int) $projectId < 1) {
                $this->warn('Опція --project_id повинна містити додатне ціле число.');

                return self::FAILURE;
            }

            $query->where('project_id', (int) $projectId);
            $this->info("Звіт по задачах проєкту ID: {$projectId}");
        } else {
            $this->info('Звіт по всіх задачах системи');
        }

        $tasks = $query->get(['id', 'title', 'status', 'due_date']);

        if ($tasks->isEmpty()) {
            $this->warn('Задачі відсутні.');

            return self::SUCCESS;
        }

        $rows = $tasks->map(static function (Task $task): array {
            return [
                $task->id,
                $task->title,
                $task->status,
                $task->due_date?->format('Y-m-d') ?? '-',
            ];
        })->all();

        $this->table(
            ['ID', 'Назва', 'Статус', 'Дедлайн'],
            $rows
        );

        return self::SUCCESS;
    }
}
