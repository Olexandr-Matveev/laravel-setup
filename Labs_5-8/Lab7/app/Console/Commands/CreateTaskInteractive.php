<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Console\Command;

class CreateTaskInteractive extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:create-interactive';

    /**
     * The console command description.
     */
    protected $description = 'Інтерактивно створити нову задачу';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $project = Project::query()->orderBy('id')->first();

        if ($project === null) {
            $this->warn('Спочатку створіть хоча б один проєкт.');

            return self::FAILURE;
        }

        do {
            $title = trim((string) $this->ask('Введіть назву задачі'));

            if ($title === '') {
                $this->warn('Назва задачі є обов’язковою.');
            }
        } while ($title === '');

        $description = trim((string) $this->ask('Короткий опис (необов’язково)'));
        $description = $description === '' ? null : $description;

        do {
            $dueDate = trim((string) $this->ask('Дата дедлайну (у форматі YYYY-MM-DD)'));

            if ($dueDate === '') {
                $dueDate = null;
                break;
            }

            $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $dueDate);
            $dateErrors = DateTimeImmutable::getLastErrors();
            $dateIsValid = $parsedDate !== false
                && ($dateErrors === false
                    || ($dateErrors['warning_count'] === 0 && $dateErrors['error_count'] === 0));

            if (! $dateIsValid || $parsedDate->format('Y-m-d') !== $dueDate) {
                $this->warn('Введіть коректну дату у форматі YYYY-MM-DD.');
                $dueDate = '';
            }
        } while ($dueDate === '');

        $status = $this->choice(
            'Оберіть статус',
            ['new', 'in_progress', 'done'],
            0
        );

        do {
            $assigneeInput = trim((string) $this->ask('ID виконавця (або залиште порожнім)'));

            if ($assigneeInput === '') {
                $assigneeId = null;
                break;
            }

            if (! ctype_digit($assigneeInput) || ! User::query()->whereKey((int) $assigneeInput)->exists()) {
                $this->warn('Користувача з таким ID не знайдено.');
                $assigneeInput = '__invalid__';
                continue;
            }

            $assigneeId = (int) $assigneeInput;
        } while ($assigneeInput === '__invalid__');

        if (! $this->confirm('Створити цю задачу?', true)) {
            $this->warn('Створення задачі скасовано.');

            return self::SUCCESS;
        }

        $task = Task::query()->create([
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'project_id' => $project->id,
            'author_id' => $project->user_id,
            'assignee_id' => $assigneeId,
            'due_date' => $dueDate,
        ]);

        $this->info("Задача '{$task->title}' створена з ID: {$task->id}");

        return self::SUCCESS;
    }
}
