<?php
// Устанавливаем временную зону (для подвала)
date_default_timezone_set('Europe/Moscow');

// ========== Инициализация переменных ==========
$x = -10;          // начальное значение аргумента
$steps = 100;             // количество вычисляемых значений
$step = 2;             // шаг изменения аргумента
$min_limit = -100;       // минимальное значение функции для остановки
$max_limit = 1000;        // максимальное значение функции для остановки
$type = 'E';             // ТИП ВЕРСТКИ: A, B, C, D или E (измените для выбора)

// Переменные для статистики
$sum = 0;
$count = 0;
$min = null;
$max = null;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа №А-2, Вариант 6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Логотип университета" width="50" height="50">
            <span>Мой университет</span>
        </div>
        <div class="header-info">
            <h1>Щеблыкин Константин Евгеньевич</h1>
            <p>Группа 241-351, вариант - 6</p>
            <p>Лабораторная работа №А-2: Циклические алгоритмы. Табулирование функций</p>
        </div>
    </header>

    <main>
        <?php
        // ========== Открывающие теги в зависимости от типа верстки ==========
        switch ($type) {
            case 'B':
                echo "<ul class='result-list'>\n";
                break;
            case 'C':
                echo "<ol class='result-list'>\n";
                break;
            case 'D':
                echo "<table class='result-table'>\n";
                echo "<tr><th>№</th><th>x</th><th>f(x)</th></tr>\n";
                break;
            case 'E':
                echo "<div class='result-blocks'>\n";
                break;
            // для типа A просто начнём вывод, можно обернуть в div для стилизации
            default:
                echo "<div class='result-text'>\n";
        }

        // ========== Цикл вычислений ==========
        for ($i = 0; $i < $steps; $i++, $x += $step) {
            // Вычисление функции с проверкой деления на ноль
            if ($x <= 10) {
                $f = $x * $x * 0.33 + 4;
            } 
            elseif ($x < 20) {
                $f = 18 * $x - 3;
            } 
            else { // x >= 20
                $denom = $x * 0.1 - 2;
                if ($denom == 0) {
                    $f = 'error';
                } 
                else {
                    $f = (1 / $denom) + 3;
                }
            }

            // Округление, если не ошибка
            if (is_numeric($f)) {
                $f_rounded = round($f, 3);
                // Накопление статистики
                $sum += $f_rounded;
                $count++;
                if ($min === null || $f_rounded < $min) $min = $f_rounded;
                if ($max === null || $f_rounded > $max) $max = $f_rounded;
            } 
            else {
                $f_rounded = $f; // 'error'
            }

            // Формирование вывода в зависимости от типа верстки
            switch ($type) {
                case 'A': // строчный вывод
                    if ($i > 0) echo "<br>\n";
                    echo "f(" . round($x, 3) . ") = " . $f_rounded;
                    break;
                case 'B': // маркированный список
                    echo "<li>f(" . round($x, 3) . ") = " . $f_rounded . "</li>\n";
                    break;
                case 'C': // нумерованный список
                    echo "<li>f(" . round($x, 3) . ") = " . $f_rounded . "</li>\n";
                    break;
                case 'D': // таблица
                    echo "<tr>";
                    echo "<td>" . ($i + 1) . "</td>";
                    echo "<td>" . round($x, 3) . "</td>";
                    echo "<td>" . $f_rounded . "</td>";
                    echo "</tr>\n";
                    break;
                case 'E': // блоки
                    echo "<div class='block'>f(" . round($x, 3) . ") = " . $f_rounded . "</div>\n";
                    break;
            }

            // Проверка на выход за пределы min/max (остановка цикла)
            if (is_numeric($f) && ($f >= $max_limit || $f < $min_limit)) {
                break;
            }
        }

        // ========== Закрывающие теги ==========
        switch ($type) {
            case 'B':
                echo "</ul>\n";
                break;
            case 'C':
                echo "</ol>\n";
                break;
            case 'D':
                echo "</table>\n";
                break;
            case 'E':
                echo "</div>\n";
                break;
            default:
                echo "</div>\n";
        }

        // ========== Вывод статистики ==========
        if ($count > 0) {
            $average = round($sum / $count, 3);
            echo "<div class='statistics'>";
            echo "<p><strong>Статистика (по числовым значениям):</strong></p>";
            echo "<ul>";
            echo "<li>Сумма: $sum</li>";
            echo "<li>Минимум: $min</li>";
            echo "<li>Максимум: $max</li>";
            echo "<li>Среднее арифметическое: $average</li>";
            echo "<li>Количество вычисленных значений: $count</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<p>Нет числовых результатов для статистики.</p>";
        }
        ?>
    </main>

    <footer>
        <?php
        // Вывод типа верстки в подвале
        $type_names = [
            'A' => 'Простая верстка (строки)',
            'B' => 'Маркированный список',
            'C' => 'Нумерованный список',
            'D' => 'Табличная верстка',
            'E' => 'Блочная верстка'
        ];
        echo "<p>Тип верстки: " . ($type_names[$type]) . "</p>";
        echo "<p>Сформировано " . date('d.m.Y') . " в " . date('H:i:s') . " (МСК)</p>";
        ?>
    </footer>
</body>
</html>