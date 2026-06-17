$ErrorActionPreference = "Stop"
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

function Assert-True {
    param(
        [bool]$Condition,
        [string]$Message
    )

    if (-not $Condition) {
        throw $Message
    }
}

function Get-HttpStatus {
    param(
        [string]$Method,
        [string]$Url,
        [string]$Body = ""
    )

    $outputFile = [System.IO.Path]::GetTempFileName()

    try {
        $arguments = @(
            "-s",
            "-o", $outputFile,
            "-w", "%{http_code}",
            "-X", $Method,
            "-H", "Accept: application/json"
        )

        if ($Body -ne "") {
            $arguments += @(
                "-H", "Content-Type: application/json",
                "-d", $Body
            )
        }

        $arguments += $Url

        $status = & curl.exe @arguments
        $content = Get-Content $outputFile -Raw

        return @{
            Status = [int]$status
            Content = $content
        }
    }
    finally {
        Remove-Item $outputFile -Force -ErrorAction SilentlyContinue
    }
}

Write-Host "1. Перевірка маршрутів зовнішнього API..." -ForegroundColor Cyan
$routes = docker compose exec -T php php artisan route:list --path=external 2>&1 | Out-String

Assert-True ($LASTEXITCODE -eq 0) "Не вдалося отримати список маршрутів."
Assert-True ($routes -match "api/external/posts") "Маршрути зовнішнього API не зареєстровані."
Write-Host $routes

Write-Host "2. GET списку записів..." -ForegroundColor Cyan
$posts = Invoke-RestMethod `
    -Method Get `
    -Uri "http://127.0.0.1:8080/api/external/posts" `
    -Headers @{"Accept"="application/json"}

Assert-True ($posts.Count -ge 1) "GET /external/posts не повернув список."
Assert-True ($posts[0].PSObject.Properties.Name -contains "title") "У записах немає поля title."
Write-Host "Отримано записів:" $posts.Count -ForegroundColor Green

Write-Host "3. GET одного запису..." -ForegroundColor Cyan
$post = Invoke-RestMethod `
    -Method Get `
    -Uri "http://127.0.0.1:8080/api/external/posts/1" `
    -Headers @{"Accept"="application/json"}

Assert-True ($post.id -eq 1) "GET /external/posts/1 повернув неправильний ID."
Assert-True (-not [string]::IsNullOrWhiteSpace([string]$post.title)) "Запис не містить title."
Write-Host "GET одного запису OK, ID=1" -ForegroundColor Green

Write-Host "4. POST створення запису..." -ForegroundColor Cyan
$body = @{
    title = "Lab 8 test post"
    body = "Created through Laravel HTTP Client"
    userId = 1
} | ConvertTo-Json -Compress

$created = Invoke-RestMethod `
    -Method Post `
    -Uri "http://127.0.0.1:8080/api/external/posts" `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"} `
    -Body $body

Assert-True ($created.title -eq "Lab 8 test post") "POST не повернув переданий title."
Assert-True ($created.body -eq "Created through Laravel HTTP Client") "POST не повернув переданий body."
Assert-True ($created.userId -eq 1) "POST не повернув переданий userId."
Assert-True ($created.id -ne $null) "POST не повернув ID."
Write-Host "POST OK, отримано ID:" $created.id -ForegroundColor Green

Write-Host "5. Перевірка валідації..." -ForegroundColor Cyan
$invalid = Get-HttpStatus `
    -Method "POST" `
    -Url "http://127.0.0.1:8080/api/external/posts" `
    -Body "{}"

Assert-True ($invalid.Status -eq 422) "Некоректний POST повинен повернути 422, отримано $($invalid.Status)."
Write-Host "Validation OK: HTTP 422" -ForegroundColor Green

Write-Host "6. Перевірка обробки помилки зовнішнього API..." -ForegroundColor Cyan
$missing = Get-HttpStatus `
    -Method "GET" `
    -Url "http://127.0.0.1:8080/api/external/posts/999999"

Assert-True ($missing.Status -eq 404) "Неіснуючий зовнішній запис повинен повернути 404, отримано $($missing.Status)."
Write-Host "External error handling OK: HTTP 404" -ForegroundColor Green

Write-Host "7. Перевірка логування..." -ForegroundColor Cyan
Start-Sleep -Milliseconds 500

$logPath = Join-Path $PSScriptRoot "storage\logs\laravel.log"
Assert-True (Test-Path $logPath) "Файл storage/logs/laravel.log не знайдено."

$log = Get-Content $logPath -Tail 150 | Out-String
Write-Host $log

Assert-True ($log -match "External API request successful") "У логах немає успішного HTTP-запиту."
Assert-True ($log -match "External API request failed") "У логах немає помилки HTTP-запиту."
Assert-True ($log -match "duration_ms") "У логах немає часу виконання запиту."

Write-Host ""
Write-Host "Лабораторна №8 працює: GET, POST, JSON, статуси, помилки та логування перевірені." -ForegroundColor Green
