<?php
// Лабораторна робота №1. Ознайомлення із синтаксисом PHP.
// Файл виконує пункти 1-6 лабораторної роботи.
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна робота №1 — PHP</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.5; max-width: 900px; margin: 30px auto; padding: 0 15px; }
        section { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        h1, h2 { color: #1f2937; }
        pre { background: #f3f4f6; padding: 10px; border-radius: 6px; overflow-x: auto; }
        .ok { color: #047857; font-weight: bold; }
    </style>
</head>
<body>
<h1>Лабораторна робота №1: Ознайомлення із синтаксисом PHP</h1>

<section>
    <h2>1. Створення базового PHP-скрипта</h2>
    <?php
    // Команда echo виводить текст на веб-сторінку.
    echo "<p class='ok'>Hello, World!</p>";
    ?>
</section>

<section>
    <h2>2. Змінні та типи даних</h2>
    <?php
    // Оголошення змінних різних типів.
    $studentName = "Олександр";      // string — рядок
    $studentAge = 22;                 // integer — ціле число
    $averageScore = 89.5;             // float — число з плаваючою комою
    $isStudent = true;                // boolean — логічне значення

    // Виведення значень змінних за допомогою echo.
    echo "<p>Ім'я: $studentName</p>";
    echo "<p>Вік: $studentAge</p>";
    echo "<p>Середній бал: $averageScore</p>";
    echo "<p>Статус студента: " . ($isStudent ? "так" : "ні") . "</p>";

    // Виведення типів змінних за допомогою var_dump.
    echo "<pre>";
    var_dump($studentName);
    var_dump($studentAge);
    var_dump($averageScore);
    var_dump($isStudent);
    echo "</pre>";
    ?>
</section>

<section>
    <h2>3. Конкатенація рядків</h2>
    <?php
    // Створення двох рядкових змінних.
    $firstName = "Олександр";
    $lastName = "Матвєєв";

    // Об'єднання рядків виконується оператором крапки.
    $fullName = $firstName . " " . $lastName;

    echo "<p>Повне ім'я: $fullName</p>";
    ?>
</section>

<section>
    <h2>4. Умовні конструкції</h2>
    <?php
    // Числове значення для перевірки на парність.
    $number = 8;

    // Якщо залишок від ділення на 2 дорівнює 0, число парне.
    if ($number % 2 === 0) {
        echo "<p>Число $number є парним.</p>";
    } else {
        echo "<p>Число $number є непарним.</p>";
    }
    ?>
</section>

<section>
    <h2>5. Цикли</h2>
    <?php
    echo "<h3>Цикл for: числа від 1 до 10</h3>";
    echo "<p>";
    for ($i = 1; $i <= 10; $i++) {
        echo $i . " ";
    }
    echo "</p>";

    echo "<h3>Цикл while: числа від 10 до 1</h3>";
    echo "<p>";
    $j = 10;
    while ($j >= 1) {
        echo $j . " ";
        $j--;
    }
    echo "</p>";
    ?>
</section>

<section>
    <h2>6. Масиви</h2>
    <?php
    // Асоціативний масив з інформацією про студента.
    $student = [
        "ім'я" => "Олександр",
        "прізвище" => "Матвєєв",
        "вік" => 21,
        "спеціальність" => "Комп'ютерні науки"
    ];

    echo "<h3>Початковий масив</h3>";
    echo "<ul>";
    foreach ($student as $key => $value) {
        echo "<li>$key: $value</li>";
    }
    echo "</ul>";

    // Додавання нового елемента до масиву.
    $student["середній бал"] = 89.5;

    echo "<h3>Оновлений масив</h3>";
    echo "<pre>";
    print_r($student);
    echo "</pre>";
    ?>
</section>

<p><a href="index.html">Перейти до пункту 7: форма</a></p>
</body>
</html>
