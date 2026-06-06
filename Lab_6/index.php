<?php
date_default_timezone_set('Europe/Moscow');

// Преобразование числа (запятая -> точка)
function fixNumber($num) {
    $num = trim(str_replace(',', '.', $num));
    return is_numeric($num) ? (float)$num : null;
}

// Случайное число от 0 до 100 с одним знаком после запятой
function randNum() {
    return round(mt_rand(0, 10000) / 100, 1);
}

// Название задачи по ключу
function taskName($key) {
    $names = [
        'square'     => 'Площадь треугольника (по трём сторонам)',
        'perimeter'  => 'Периметр треугольника',
        'volume'     => 'Объём прямоугольного параллелепипеда',
        'mean'       => 'Среднее арифметическое трёх чисел',
        'hypotenuse' => 'Гипотенуза прямоугольного треугольника (катеты A, B)',
        'quadratic'  => 'Дискриминант квадратного уравнения (A·x² + B·x + C = 0)'
    ];
    return $names[$key] ?? 'Неизвестная задача';
}

// Обработка отправленной формы
if (isset($_POST['A'])) {
    $fio    = trim($_POST['FIO']);
    $group  = trim($_POST['GROUP']);
    $about  = trim($_POST['ABOUT']);
    $task   = $_POST['TASK'];
    $a      = fixNumber($_POST['A']);
    $b      = fixNumber($_POST['B']);
    $c      = fixNumber($_POST['C']);
    $userAnswer = isset($_POST['user_result']) ? trim($_POST['user_result']) : '';
    $email  = trim($_POST['MAIL']);
    $sendMail = isset($_POST['send_mail']);
    $view   = $_POST['VIEW'];

    $correct = null;
    $errorMsg = null;

    switch ($task) {
        case 'square':
            if ($a !== null && $b !== null && $c !== null && $a > 0 && $b > 0 && $c > 0) {
                if ($a + $b > $c && $a + $c > $b && $b + $c > $a) {
                    $p = ($a + $b + $c) / 2;
                    $correct = round(sqrt($p * ($p - $a) * ($p - $b) * ($p - $c)), 2);
                } else {
                    $errorMsg = "Треугольник с такими сторонами не существует.";
                }
            } else {
                $errorMsg = "Все стороны должны быть положительными числами.";
            }
            break;
        case 'perimeter':
            if ($a !== null && $b !== null && $c !== null && $a > 0 && $b > 0 && $c > 0) {
                $correct = round($a + $b + $c, 2);
            } else {
                $errorMsg = "Все стороны должны быть положительными числами.";
            }
            break;
        case 'volume':
            if ($a !== null && $b !== null && $c !== null && $a > 0 && $b > 0 && $c > 0) {
                $correct = round($a * $b * $c, 2);
            } else {
                $errorMsg = "Все измерения должны быть положительными числами.";
            }
            break;
        case 'mean':
            if ($a !== null && $b !== null && $c !== null) {
                $correct = round(($a + $b + $c) / 3, 2);
            } else {
                $errorMsg = "Все три числа должны быть заданы.";
            }
            break;
        case 'hypotenuse':
            if ($a !== null && $b !== null && $a > 0 && $b > 0) {
                $correct = round(sqrt($a * $a + $b * $b), 2);
            } else {
                $errorMsg = "Катеты должны быть положительными числами.";
            }
            break;
        case 'quadratic':
            if ($a !== null && $b !== null && $c !== null) {
                $correct = round($b * $b - 4 * $a * $c, 2);
            } else {
                $errorMsg = "Все коэффициенты должны быть числами.";
            }
            break;
        default:
            $errorMsg = "Неизвестная задача.";
    }

    // Формирование отчёта (для email и для экрана)
    $out_text = "ФИО: $fio\n";
    $out_text .= "Группа: $group\n";
    if ($about) $out_text .= "О себе: $about\n";
    $out_text .= "Тип задачи: " . taskName($task) . "\n";
    $out_text .= "Входные данные: A = " . ($a ?? 'не задано') . ", B = " . ($b ?? 'не задано') . ", C = " . ($c ?? 'не задано') . "\n";
    $out_text .= "Ваш ответ: " . ($userAnswer === '' ? 'не введён' : $userAnswer) . "\n";
    if ($errorMsg) {
        $out_text .= "Ошибка вычисления программой: $errorMsg\n";
        $out_text .= "Результат: невозможно проверить.\n";
    } else {
        $out_text .= "Правильный ответ: $correct\n";
        if ($userAnswer !== '') {
            $userNum = fixNumber($userAnswer);
            if ($userNum !== null && $userNum == $correct) {
                $out_text .= "Результат: Тест пройден\n";
            } else {
                $out_text .= "Результат: Ошибка – тест не пройден\n";
            }
        } else {
            $out_text .= "Результат: Задача самостоятельно решена не была\n";
        }
    }

    // HTML-версия отчёта
    $html_out = "<div class='report'>";
    $html_out .= "<p><strong>ФИО:</strong> " . htmlspecialchars($fio) . "</p>";
    $html_out .= "<p><strong>Группа:</strong> " . htmlspecialchars($group) . "</p>";
    if ($about) $html_out .= "<p><strong>О себе:</strong> " . nl2br(htmlspecialchars($about)) . "</p>";
    $html_out .= "<p><strong>Тип задачи:</strong> " . taskName($task) . "</p>";
    $html_out .= "<p><strong>Входные данные:</strong> A = " . ($a ?? 'не задано') . ", B = " . ($b ?? 'не задано') . ", C = " . ($c ?? 'не задано') . "</p>";
    $html_out .= "<p><strong>Ваш ответ:</strong> " . ($userAnswer === '' ? 'не введён' : htmlspecialchars($userAnswer)) . "</p>";
    if ($errorMsg) {
        $html_out .= "<p class='error'>❌ Ошибка вычисления: $errorMsg</p>";
        $html_out .= "<p>Проверка невозможна.</p>";
    } else {
        $html_out .= "<p><strong>Правильный ответ:</strong> $correct</p>";
        if ($userAnswer !== '') {
            $userNum = fixNumber($userAnswer);
            if ($userNum !== null && $userNum == $correct) {
                $html_out .= "<p class='success'>✅ Тест пройден</p>";
            } else {
                $html_out .= "<p class='error'>❌ Ошибка: тест не пройден</p>";
            }
        } else {
            $html_out .= "<p class='warning'>⚠ Задача самостоятельно решена не была</p>";
        }
    }
    $html_out .= "</div>";

    // Имитация отправки email (без реальной функции mail)
    if ($sendMail && !empty($email)) {
        //<mail( $_POST['MAIL'], 'Результат тестирования',
            //str_replace('<br>', "\r\n", $out_text),
            //"From: auto@mami.ru\n"."Content-Type: text/plain; charset=utf-8\n" );
        $html_out .= "<p class='info'>📧 Результаты теста были автоматически отправлены на e‑mail " . $email . "</p>";
    }

    // Ссылка "Повторить тест" (только для версии браузера)
    if ($view == 'browser') {
        $link = "?F=" . urlencode($fio) . "&G=" . urlencode($group);
        $html_out .= '<div class="repeat-link"><a href="' . $link . '" class="repeat-btn">Повторить тест</a></div>';
    }

    $display_mode = ($view == 'browser') ? 'browser-mode' : 'print-mode';
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа №А-6</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body class="<?php echo $display_mode; ?>">
        <header>
            <div class="logo"><img src="logo.png" alt="Логотип"><span>Мой университет</span></div>
            <div class="header-info">
                <h1>Щеблыкин Константин Евгеньевич</h1>
                <p>Группа 241-351</p>
                <p>Лабораторная работа №6: Использование форм для передачи данных в программу РНР. Тест математических знаний</p>
            </div>
        </header>
        <main><?php echo $html_out; ?></main>
        <footer><p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p></footer>
    </body>
    </html>
    <?php
    exit;
}

// ---------- Показ формы (если форма не отправлена) ----------
$preset_fio   = isset($_GET['F']) ? $_GET['F'] : '';
$preset_group = isset($_GET['G']) ? $_GET['G'] : '';

$defaultA = randNum();
$defaultB = randNum();
$defaultC = randNum();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа №А-6</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleEmailField() {
            var emailDiv = document.getElementById('emailField');
            emailDiv.style.display = document.getElementById('sendMailCheck').checked ? 'block' : 'none';
        }
        window.onload = toggleEmailField;
    </script>
</head>
<body>
    <header>
        <div class="logo"><img src="logo.png" alt="Логотип"><span>Мой университет</span></div>
        <div class="header-info">
            <h1>Щеблыкин Константин Евгеньевич</h1>
            <p>Группа 241-351</p>
            <p>Лабораторная работа №6: Использование форм для передачи данных в программу РНР. Тест математических знаний</p>
        </div>
    </header>

    <main>
        <form method="post" action="" class="test-form">
            <div class="form-row"><label>ФИО:</label><input type="text" name="FIO" value="<?php echo htmlspecialchars($preset_fio); ?>" required></div>
            <div class="form-row"><label>Номер группы:</label><input type="text" name="GROUP" value="<?php echo htmlspecialchars($preset_group); ?>" required></div>
            <div class="form-row"><label>Немного о себе:</label><textarea name="ABOUT" rows="3"></textarea></div>
            <div class="form-row"><label>Значение A:</label><input type="text" name="A" value="<?php echo $defaultA; ?>" required></div>
            <div class="form-row"><label>Значение B:</label><input type="text" name="B" value="<?php echo $defaultB; ?>" required></div>
            <div class="form-row"><label>Значение C:</label><input type="text" name="C" value="<?php echo $defaultC; ?>" required></div>
            <div class="form-row"><label>Ваш ответ:</label><input type="text" name="user_result"></div>

            <div class="form-row"><label>Задача:</label>
                <select name="TASK">
                    <option value="square">Площадь треугольника</option>
                    <option value="perimeter">Периметр треугольника</option>
                    <option value="volume">Объём параллелепипеда</option>
                    <option value="mean">Среднее арифметическое</option>
                    <option value="hypotenuse">Гипотенуза (по катетам A, B)</option>
                    <option value="quadratic">Дискриминант квадратного уравнения</option>
                </select>
            </div>

            <div class="form-row checkbox-row">
                <input type="checkbox" name="send_mail" id="sendMailCheck" onclick="toggleEmailField()">
                <label for="sendMailCheck">Отправить результат теста на e‑mail</label>
            </div>
            <div id="emailField" style="display:none;" class="form-row">
                <label>Ваш e‑mail:</label><input type="email" name="MAIL">
            </div>

            <div class="form-row"><label>Версия:</label>
                <select name="VIEW">
                    <option value="browser">Версия для просмотра в браузере</option>
                    <option value="print">Версия для печати</option>
                </select>
            </div>

            <div class="form-row button-row"><button type="submit" class="submit-btn">Проверить</button></div>
        </form>
    </main>

    <footer><p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p></footer>
</body>
</html>