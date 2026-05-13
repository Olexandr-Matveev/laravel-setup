# Лабораторна робота №4

## Тема
Створення форми авторизації та реєстрації користувачів на PHP з використанням MySQL.

## Реалізовано

- база даних `users_db`;
- таблиця `users` з полями `id`, `username`, `email`, `password`, `created_at`;
- форма реєстрації методом `POST`;
- хешування пароля через `md5()` відповідно до формулювання лабораторної;
- prepared statements для захисту від SQL-інʼєкцій;
- форма авторизації;
- перевірка пароля;
- захищена сторінка `welcome.php`;
- збереження авторизованого користувача через `$_SESSION`;
- вихід із системи через `logout.php`;
- файл `dump.sql` для створення бази даних;
- дружнє повідомлення, якщо у PHP не увімкнено `mysqli`.

## Важлива примітка щодо паролів

У завданні одночасно вказано зберігати пароль через `md5()` і перевіряти його через `password_verify()`.
Технічно `password_verify()` не перевіряє MD5-хеші, тому в коді зроблено сумісну функцію `verifyPasswordForLab()`:

- якщо пароль збережений як `password_hash()`, використовується `password_verify()`;
- якщо пароль збережений як MD5, використовується `hash_equals(md5($password), $storedHash)`.

## Запуск

1. Запустіть MySQL Server.
2. Перевірте `config/config.php`:

```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'ВАШ_ROOT_ПАРОЛЬ');
define('DB_NAME', 'users_db');
define('DB_PORT', 3306);
```

3. Відкрийте термінал у папці проєкту.
4. Запустіть сервер:

```powershell
C:\php\php.exe -S localhost:8000
```

5. Відкрийте:

```text
http://localhost:8000/index.php
```

6. Спочатку відкрийте `init_db.php`, щоб створити базу даних і таблицю.
7. Потім перевірте `register.php`, `login.php`, `welcome.php`, `logout.php`.

## Якщо зʼявилась помилка `Call to undefined function mysqli_report()`

Це означає, що у PHP не увімкнене розширення `mysqli`.

1. Відкрийте `C:\php\php.ini`.
2. Перевірте рядок:

```ini
extension_dir = "C:\php\ext"
```

3. Увімкніть:

```ini
extension=mysqli
extension=pdo_mysql
```

4. Збережіть файл і перезапустіть PHP-сервер.

## Критерії успіху

- `init_db.php` створює базу `users_db` і таблицю `users`.
- `register.php` створює нового користувача.
- У таблиці `users` пароль збережено не відкритим текстом, а хешем.
- Повторний username або email не приймається.
- `login.php` авторизує користувача з правильним паролем.
- `welcome.php` доступний тільки після входу.
- `logout.php` очищає сесію.
- У коді використовуються prepared statements.
