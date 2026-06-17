# Лабораторна робота №7

## Laravel. Команди

Проєкт є продовженням лабораторної роботи №6. У ньому реалізовано дві власні Artisan-команди для перегляду задач і інтерактивного створення нової задачі.

## Реалізовані вимоги

- команда `tasks:report`;
- опціональний параметр `--project_id`;
- виведення всіх задач або задач конкретного проєкту;
- поля звіту: ID, назва, статус, дедлайн;
- повідомлення-попередження, якщо задач немає;
- використання `$this->info()`, `$this->warn()` та `$this->table()`;
- інтерактивна команда `tasks:create-interactive`;
- запити назви, опису, дедлайну, статусу та ID виконавця;
- підтвердження створення задачі;
- створення задачі через модель `Task`;
- автоматичне спрацювання `TaskObserver`, `TaskCreated` і `NotifyAssignee`.

Інтерактивна команда використовує перший доступний проєкт у базі даних, а автором задачі призначає власника цього проєкту. Після `migrate:fresh --seed` доступний тестовий проєкт `Demo Project`.

## Запуск

З кореня репозиторію:

```powershell
cd Labs_5-8\Lab7
Copy-Item .env.example .env

docker compose up -d postgres redis php nginx adminer
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate:fresh --seed
docker compose exec php php artisan optimize:clear
```

Якщо `.env` уже існує, команду `Copy-Item` виконувати не потрібно.

## Перевірка наявності команд

```powershell
docker compose exec php php artisan list tasks
```

У списку мають бути:

```text
tasks:report
tasks:create-interactive
```

## Команда tasks:report

Вивести всі задачі:

```powershell
docker compose exec php php artisan tasks:report
```

Вивести задачі конкретного проєкту:

```powershell
docker compose exec php php artisan tasks:report --project_id=1
```

Якщо задач немає, команда виведе попередження `Задачі відсутні.`

## Команда tasks:create-interactive

```powershell
docker compose exec php php artisan tasks:create-interactive
```

Команда послідовно запитує:

1. назву задачі;
2. короткий опис;
3. дату дедлайну;
4. статус;
5. ID виконавця;
6. підтвердження створення.

Після підтвердження задача зберігається в базі даних.

## Тестові дані після migrate:fresh --seed

- автор: `test@example.com`, пароль `password`;
- виконавець: `assignee@example.com`, пароль `password`;
- тестовий проєкт: `Demo Project`.

## Автоматична перевірка

```powershell
powershell -ExecutionPolicy Bypass -File .\verify_lab7.ps1
```

Скрипт:

- перевіряє попередження за відсутності задач;
- запускає інтерактивне створення задачі;
- перевіряє загальний звіт;
- перевіряє фільтр `--project_id`;
- перевіряє спрацювання `TaskObserver`, `TaskCreated` і `NotifyAssignee`.

## Перевірка логів

```powershell
Get-Content .\storage\logs\laravel.log -Tail 50
```

У логах повинні бути повідомлення:

```text
Task created
Notification sent to task assignee
```

## Зупинення

```powershell
docker compose down
```

Файли `.env`, `vendor`, `node_modules` і журнали Laravel не додаються до GitHub.
