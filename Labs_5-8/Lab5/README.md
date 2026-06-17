# Лабораторна робота №5

## Laravel. Вступ

Навчальний Laravel-проєкт таск-трекера, створений у межах лабораторної роботи №5 з дисципліни «Стек технологій проєктування WEB-застосувань».

## Реалізовано

У проєкті створено основні сутності таск-трекера:

* `User` — користувач системи;
* `Project` — проєкт користувача;
* `Task` — завдання, яке належить проєкту;
* `Comment` — коментар до завдання;
* `File` — файл, прикріплений до користувача, проєкту або завдання.

Реалізовано:

* міграції таблиць `projects`, `tasks`, `comments`, `files`;
* моделі Laravel Eloquent;
* зв’язки між моделями;
* API-маршрути для проєктів, завдань і коментарів;
* метод `index()` для отримання списку проєктів;
* метод `store()` для створення нового проєкту;
* валідацію даних під час створення проєкту.

## Використані технології

* PHP 8.3
* Laravel 12
* PostgreSQL
* Redis
* Nginx
* Docker Compose
* Adminer

## Запуск проєкту

### 1. Перейти до папки лабораторної

```powershell
cd Labs_5-8\Lab5
```

### 2. Створити файл конфігурації

У Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

У Linux або macOS:

```bash
cp .env.example .env
```

### 3. Запустити Docker-контейнери

```powershell
docker compose up -d postgres redis php nginx adminer
```

### 4. Встановити PHP-залежності

```powershell
docker compose exec php composer install
```

### 5. Створити ключ Laravel

```powershell
docker compose exec php php artisan key:generate
```

### 6. Створити таблиці та тестові дані

```powershell
docker compose exec php php artisan migrate:fresh --seed
```

### 7. Очистити кеш Laravel

```powershell
docker compose exec php php artisan optimize:clear
```

## Перевірка API-маршрутів

```powershell
docker compose exec php php artisan route:list --path=api
```

Основні маршрути лабораторної:

```text
GET  /api/projects
POST /api/projects
```

Також створені ресурсні маршрути для:

```text
/api/tasks
/api/comments
```

## Тестовий користувач

Після виконання команди `migrate:fresh --seed` створюється користувач:

```text
ID: 1
Name: Test User
Email: test@example.com
Password: password
```

## Перевірка створення проєкту

У Windows PowerShell:

```powershell
$body = @{
    name = "Laboratory Project"
    description = "Task tracker for laboratory work 5"
    user_id = 1
} | ConvertTo-Json

Invoke-RestMethod `
    -Method Post `
    -Uri "http://127.0.0.1:8080/api/projects" `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"} `
    -Body $body
```

У відповідь має повернутися створений проєкт з його `id`, назвою, описом і користувачем.

## Перевірка списку проєктів

```powershell
Invoke-RestMethod `
    -Method Get `
    -Uri "http://127.0.0.1:8080/api/projects" `
    -Headers @{"Accept"="application/json"}
```

Також список проєктів можна відкрити у браузері:

```text
http://127.0.0.1:8080/api/projects
```

## Доступні адреси

Laravel API:

```text
http://127.0.0.1:8080
```

Adminer:

```text
http://127.0.0.1:8084
```

## Зупинення проєкту

```powershell
docker compose down
```

## Примітка

Файли `.env`, `vendor` і `node_modules` не завантажуються до GitHub. Після клонування репозиторію потрібно виконати наведені вище команди встановлення та запуску.
