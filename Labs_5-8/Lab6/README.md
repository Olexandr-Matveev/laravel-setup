# Лабораторна робота №6

## Laravel. Події

Проєкт є продовженням лабораторної роботи №5. Реалізовано повний CRUD API для користувачів, проєктів, задач і коментарів, валідацію, `TaskObserver`, подію `TaskCreated` та слухача `NotifyAssignee`.

## Реалізовані вимоги

- `UserController`, `ProjectController`, `TaskController`, `CommentController`;
- методи `index`, `store`, `show`, `update`, `destroy` у кожному контролері;
- валідація даних для проєктів, задач і коментарів;
- `TaskObserver`, який логує створення, оновлення та видалення задачі;
- `TaskCreated`, яка викликається після створення задачі;
- `NotifyAssignee`, який записує повідомлення виконавцю у лог;
- API-маршрути для всіх чотирьох сутностей.

## Запуск

З кореня репозиторію:

```powershell
cd Labs_5-8\Lab6
Copy-Item .env.example .env

docker compose up -d postgres redis php nginx adminer
docker compose exec php composer install
docker compose exec php php artisan key:generate
docker compose exec php php artisan migrate:fresh --seed
docker compose exec php php artisan optimize:clear
```

Якщо `.env` уже існує, команду `Copy-Item` виконувати не потрібно.

## Перевірка маршрутів та подій

```powershell
docker compose exec php php artisan route:list --path=api
docker compose exec php php artisan event:list
```

У маршрутах мають бути `users`, `projects`, `tasks`, `comments`. У списку подій має бути зв’язок `TaskCreated` → `NotifyAssignee`.

## Тестові дані після `migrate:fresh --seed`

- автор: `test@example.com`, пароль `password`;
- виконавець: `assignee@example.com`, пароль `password`;
- тестовий проєкт: `Demo Project`.

## Автоматична перевірка CRUD, Observer та Event

```powershell
powershell -ExecutionPolicy Bypass -File .\verify_lab6.ps1
```

Скрипт створює та перевіряє користувача, проєкт, задачу й коментар, оновлює їх, видаляє та показує записи Observer і Listener з `storage/logs/laravel.log`.

## Ручна перевірка логів

```powershell
docker compose exec php sh -lc "tail -n 50 storage/logs/laravel.log"
```

У логах повинні бути повідомлення:

```text
Task created
Notification sent to task assignee
Task updated
Task deleted
```

## Адреси

- API: `http://127.0.0.1:8080/api`
- Adminer: `http://127.0.0.1:8084`

## Зупинення

```powershell
docker compose down
```

Файли `.env`, `vendor` та `node_modules` не додаються до GitHub.
