<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Support\Facades\Log;

class NotifyAssignee
{
    public function handle(TaskCreated $event): void
    {
        $task = $event->task->loadMissing('assignee');

        if ($task->assignee === null) {
            Log::warning('Task created without assignee', [
                'task_id' => $task->id,
                'title' => $task->title,
            ]);

            return;
        }

        Log::info('Notification sent to task assignee', [
            'task_id' => $task->id,
            'task_title' => $task->title,
            'assignee_id' => $task->assignee->id,
            'assignee_email' => $task->assignee->email,
        ]);
    }
}
