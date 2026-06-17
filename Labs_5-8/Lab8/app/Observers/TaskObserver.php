<?php

namespace App\Observers;

use App\Events\TaskCreated;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    public function created(Task $task): void
    {
        Log::info('Task created', [
            'task_id' => $task->id,
            'title' => $task->title,
            'project_id' => $task->project_id,
            'assignee_id' => $task->assignee_id,
        ]);

        TaskCreated::dispatch($task);
    }

    public function updated(Task $task): void
    {
        Log::info('Task updated', [
            'task_id' => $task->id,
            'changes' => $task->getChanges(),
        ]);
    }

    public function deleted(Task $task): void
    {
        Log::warning('Task deleted', [
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
    }
}
