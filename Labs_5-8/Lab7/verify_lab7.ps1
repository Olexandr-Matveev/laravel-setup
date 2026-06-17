$ErrorActionPreference = "Stop"
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

function Assert-LastExitCode {
    param([string]$Step)

    if ($LASTEXITCODE -ne 0) {
        throw "$Step завершився з кодом $LASTEXITCODE."
    }
}

Write-Host "1. Підготовка чистої бази даних..." -ForegroundColor Cyan
docker compose exec -T php php artisan migrate:fresh --seed
Assert-LastExitCode "migrate:fresh --seed"

Write-Host "2. Перевірка попередження, коли задач немає..." -ForegroundColor Cyan
$emptyReport = docker compose exec -T php php artisan tasks:report 2>&1 | Out-String
Assert-LastExitCode "tasks:report без задач"
Write-Host $emptyReport

if ($emptyReport -notmatch "Задачі відсутні") {
    throw "Команда tasks:report не вивела потрібне попередження."
}

Write-Host "3. Інтерактивне створення задачі..." -ForegroundColor Cyan
$answers = @(
    "Lab 7 interactive task",
    "Created by tasks:create-interactive",
    "2026-12-31",
    "1",
    "2",
    "yes",
    ""
) -join "`n"

$interactiveOutput = $answers |
    docker compose exec -T php php artisan tasks:create-interactive 2>&1 |
    Out-String

Assert-LastExitCode "tasks:create-interactive"
Write-Host $interactiveOutput

if ($interactiveOutput -notmatch "створена з ID") {
    throw "Інтерактивна команда не підтвердила створення задачі."
}

Write-Host "4. Перевірка загального звіту..." -ForegroundColor Cyan
$allReport = docker compose exec -T php php artisan tasks:report 2>&1 | Out-String
Assert-LastExitCode "tasks:report"
Write-Host $allReport

if ($allReport -notmatch "Lab 7 interactive task" -or $allReport -notmatch "in_progress") {
    throw "Загальний звіт не містить створену задачу."
}

Write-Host "5. Перевірка фільтра --project_id..." -ForegroundColor Cyan
$projectReport = docker compose exec -T php php artisan tasks:report --project_id=1 2>&1 | Out-String
Assert-LastExitCode "tasks:report --project_id=1"
Write-Host $projectReport

if ($projectReport -notmatch "Lab 7 interactive task") {
    throw "Звіт за проєктом не містить створену задачу."
}

Write-Host "6. Перевірка Observer, Event і Listener..." -ForegroundColor Cyan
Start-Sleep -Milliseconds 500

$logPath = Join-Path $PSScriptRoot "storage\logs\laravel.log"

if (-not (Test-Path $logPath)) {
    throw "Файл storage/logs/laravel.log не знайдено."
}

$log = Get-Content $logPath -Tail 100 | Out-String
Write-Host $log

if ($log -notmatch "Task created" -or
    $log -notmatch "Notification sent to task assignee" -or
    $log -notmatch "Lab 7 interactive task") {
    throw "У логах немає підтвердження роботи Observer, Event або Listener."
}

Write-Host ""
Write-Host "Лабораторна №7 працює: обидві Artisan-команди, фільтр, таблиця, Observer, Event і Listener перевірені." -ForegroundColor Green
