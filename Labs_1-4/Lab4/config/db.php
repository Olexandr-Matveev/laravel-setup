<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

function getDbConnection()
{
    if (!isMysqliAvailable()) {
        renderEnvironmentError('Модуль mysqli не увімкнений', 'PHP не бачить розширення mysqli, тому функції mysqli_report(), mysqli_connect() та prepared statements недоступні.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        $connection->set_charset('utf8mb4');
        return $connection;
    } catch (mysqli_sql_exception $exception) {
        renderEnvironmentError('Помилка підключення до бази даних', $exception->getMessage());
    }
}

function getServerConnection()
{
    if (!isMysqliAvailable()) {
        renderEnvironmentError('Модуль mysqli не увімкнений', 'PHP не бачить розширення mysqli. Увімкніть extension=mysqli у php.ini і перезапустіть сервер.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, '', DB_PORT);
        $connection->set_charset('utf8mb4');
        return $connection;
    } catch (mysqli_sql_exception $exception) {
        renderEnvironmentError('Помилка підключення до MySQL Server', $exception->getMessage());
    }
}
