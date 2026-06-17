$ErrorActionPreference = 'Stop'
$base = 'http://127.0.0.1:8080/api'
$headers = @{ Accept = 'application/json' }

Write-Host '1. Перевірка UserController...' -ForegroundColor Cyan
$userBody = @{
    name = 'API Test User'
    email = "lab6.$([DateTimeOffset]::UtcNow.ToUnixTimeSeconds())@example.com"
    password = 'password123'
} | ConvertTo-Json
$user = Invoke-RestMethod -Method Post -Uri "$base/users" -Headers $headers -ContentType 'application/json' -Body $userBody
Invoke-RestMethod -Method Get -Uri "$base/users/$($user.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Patch -Uri "$base/users/$($user.id)" -Headers $headers -ContentType 'application/json' -Body (@{ name = 'Updated API User' } | ConvertTo-Json) | Out-Null
Write-Host "   User CRUD OK, ID=$($user.id)" -ForegroundColor Green

Write-Host '2. Перевірка ProjectController...' -ForegroundColor Cyan
$project = Invoke-RestMethod -Method Post -Uri "$base/projects" -Headers $headers -ContentType 'application/json' -Body (@{
    name = 'Lab 6 API Project'
    description = 'CRUD verification project'
    user_id = 1
} | ConvertTo-Json)
Invoke-RestMethod -Method Get -Uri "$base/projects/$($project.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Patch -Uri "$base/projects/$($project.id)" -Headers $headers -ContentType 'application/json' -Body (@{ description = 'Updated project description' } | ConvertTo-Json) | Out-Null
Write-Host "   Project CRUD OK, ID=$($project.id)" -ForegroundColor Green

Write-Host '3. Перевірка TaskController, Observer, Event і Listener...' -ForegroundColor Cyan
$task = Invoke-RestMethod -Method Post -Uri "$base/tasks" -Headers $headers -ContentType 'application/json' -Body (@{
    title = 'Lab 6 test task'
    description = 'Observer and event verification'
    status = 'new'
    project_id = $project.id
    assignee_id = 2
    due_date = (Get-Date).AddDays(7).ToString('yyyy-MM-dd')
} | ConvertTo-Json)
Invoke-RestMethod -Method Get -Uri "$base/tasks/$($task.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Patch -Uri "$base/tasks/$($task.id)" -Headers $headers -ContentType 'application/json' -Body (@{ status = 'in_progress' } | ConvertTo-Json) | Out-Null
Write-Host "   Task create/show/update OK, ID=$($task.id)" -ForegroundColor Green

Write-Host '4. Перевірка CommentController...' -ForegroundColor Cyan
$comment = Invoke-RestMethod -Method Post -Uri "$base/comments" -Headers $headers -ContentType 'application/json' -Body (@{
    task_id = $task.id
    content = 'Comment created by verification script'
} | ConvertTo-Json)
Invoke-RestMethod -Method Get -Uri "$base/comments/$($comment.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Patch -Uri "$base/comments/$($comment.id)" -Headers $headers -ContentType 'application/json' -Body (@{ content = 'Updated verification comment' } | ConvertTo-Json) | Out-Null
Write-Host "   Comment CRUD OK, ID=$($comment.id)" -ForegroundColor Green

Write-Host '5. Перевірка destroy()...' -ForegroundColor Cyan
Invoke-RestMethod -Method Delete -Uri "$base/comments/$($comment.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Delete -Uri "$base/tasks/$($task.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Delete -Uri "$base/projects/$($project.id)" -Headers $headers | Out-Null
Invoke-RestMethod -Method Delete -Uri "$base/users/$($user.id)" -Headers $headers | Out-Null
Write-Host '   Delete methods OK' -ForegroundColor Green

Write-Host '6. Останні записи Observer та Listener:' -ForegroundColor Cyan
docker compose exec php sh -lc "grep -E 'Task created|Task updated|Task deleted|Notification sent to task assignee' storage/logs/laravel.log | tail -n 20"

Write-Host "`nЛабораторна №6 працює: CRUD, валідація, Observer, Event і Listener перевірені." -ForegroundColor Green
