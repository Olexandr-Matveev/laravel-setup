# Лабораторна робота №8

## Laravel. HTTP-запити та зовнішні API

Проєкт є продовженням лабораторної роботи №7. Реалізовано інтеграцію Laravel із зовнішнім REST API JSONPlaceholder через вбудований HTTP Client.

## Реалізовано

- сервіс `ExternalTaskService`;
- метод `getPosts()`;
- метод `getPostById($id)`;
- метод `createPost(array $data)`;
- GET-запит списку записів;
- GET-запит одного запису за ID;
- POST-запит із полями `title`, `body`, `userId`;
- перевірку `$response->successful()`;
- перевірку `$response->failed()`;
- обробку помилок з’єднання;
- контролер `ExternalApiController`;
- методи `posts()`, `show($id)`, `store(Request $request)`;
- API-маршрути `/api/external/posts`;
- логування успішних запитів;
- логування помилок;
- логування часу виконання в `duration_ms`.

Зовнішній API:

```text
https://jsonplaceholder.typicode.com
```

## Запуск

З кореня репозиторію:

```powershell
cd Labs_5-8\Lab8
Copy-Item .env.example .env

docker compose up -d postgres redis php nginx adminer
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate:fresh --seed
docker compose exec php php artisan optimize:clear
```

Якщо `.env` уже існує, команду `Copy-Item` виконувати не потрібно.

## Перевірка маршрутів

```powershell
docker compose exec php php artisan route:list --path=external
```

Мають бути маршрути:

```text
GET  api/external/posts
GET  api/external/posts/{id}
POST api/external/posts
```

## GET — список записів

```powershell
Invoke-RestMethod `
    -Method Get `
    -Uri "http://127.0.0.1:8080/api/external/posts" `
    -Headers @{"Accept"="application/json"}
```

## GET — один запис

```powershell
Invoke-RestMethod `
    -Method Get `
    -Uri "http://127.0.0.1:8080/api/external/posts/1" `
    -Headers @{"Accept"="application/json"}
```

## POST — створення запису

```powershell
$body = @{
    title = "Lab 8 test post"
    body = "Created through Laravel HTTP Client"
    userId = 1
} | ConvertTo-Json

Invoke-RestMethod `
    -Method Post `
    -Uri "http://127.0.0.1:8080/api/external/posts" `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"} `
    -Body $body
```

JSONPlaceholder повертає створений запис з HTTP-статусом `201` та тестовим ID `101`. Сервіс не зберігає його назавжди, оскільки JSONPlaceholder є демонстраційним API.

## Перевірка валідації

POST без обов’язкових полів повинен повернути HTTP `422`.

## Перевірка помилки зовнішнього API

```powershell
try {
    Invoke-RestMethod `
        -Method Get `
        -Uri "http://127.0.0.1:8080/api/external/posts/999999" `
        -Headers @{"Accept"="application/json"}
} catch {
    Write-Host "HTTP status:" ([int]$_.Exception.Response.StatusCode)
}
```

Очікується HTTP `404`.

## Автоматична перевірка

```powershell
powershell -ExecutionPolicy Bypass -File .\verify_lab8.ps1
```

Скрипт перевіряє:

- три API-маршрути;
- GET списку записів;
- GET одного запису;
- POST створення запису;
- валідацію з HTTP `422`;
- обробку помилки зовнішнього API;
- логи успішних запитів, помилок і часу виконання.

Для автоматичної перевірки потрібен доступ до Інтернету.

## Логи

```powershell
Get-Content .\storage\logs\laravel.log -Tail 100
```

Шукайте:

```text
External API request successful
External API request failed
duration_ms
```

## Зупинення

```powershell
docker compose down
```

Файли `.env`, `vendor`, `node_modules` і журнали Laravel не додаються до GitHub.
