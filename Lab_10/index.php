<?php
session_start();

// Инициализация сессии
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
    $_SESSION['iteration'] = 0;
}
$_SESSION['iteration']++;

/**
 * Проверяет, является ли строка числом (целым или вещественным, положительным или отрицательным)
 */
function isnum($s) {
    $s = trim($s);
    if ($s === '') return false;
    // Убираем ведущий знак
    if ($s[0] === '-' || $s[0] === '+') $s = substr($s, 1);
    if ($s === '') return false;
    $dot = false;
    for ($i = 0; $i < strlen($s); $i++) {
        $ch = $s[$i];
        if ($ch === '.') {
            if ($dot) return false;
            $dot = true;
        } elseif ($ch < '0' || $ch > '9') return false;
    }
    return true;
}

/**
 * Применяет операцию к двум числам
 */
function applyOp($a, $op, $b) {
    switch ($op) {
        case '+': return $a + $b;
        case '-': return $a - $b;
        case '*': return $a * $b;
        case '/': case ':':
            if ($b == 0) return 'Деление на ноль!';
            return $a / $b;
        default: return 'Неизвестная операция';
    }
}

/**
 * Вычисляет выражение, содержащее только умножение и деление
 */
function computeMulDiv($expr) {
    $expr = trim($expr);
    if (isnum($expr)) return (float)$expr;

    // Разбиваем по операторам * / :, сохраняя их
    $parts = preg_split('#([*/:])#', $expr, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($parts === false) return 'Ошибка разбора';

    $result = (float)computeMulDiv($parts[0]);
    for ($i = 1; $i < count($parts); $i += 2) {
        $op = $parts[$i];
        $next = (float)computeMulDiv($parts[$i+1]);
        $res = applyOp($result, $op, $next);
        if (!isnum($res)) return $res;
        $result = (float)$res;
    }
    return $result;
}

/**
 * Вычисляет арифметическое выражение без скобок (с учётом приоритетов)
 */
function calculate($expr) {
    $expr = trim($expr);
    if ($expr === '') return 'Выражение не задано!';

    // Если выражение начинается с + или -, подставляем 0 в начало
    if (preg_match('/^[+-]/', $expr)) {
        $expr = '0' . $expr;
    }

    if (isnum($expr)) return (float)$expr;

    // Разбиваем по знакам + и — (бинарные операторы)
    $len = strlen($expr);
    $terms = [];
    $last = 0;
    $sign = '+';
    for ($i = 0; $i < $len; $i++) {
        $ch = $expr[$i];
        if ($ch === '+' || $ch === '-') {
            $term = substr($expr, $last, $i - $last);
            if ($term !== '') {
                $terms[] = [$sign, $term];
            }
            $sign = $ch;
            $last = $i + 1;
        }
    }
    $term = substr($expr, $last);
    if ($term !== '') $terms[] = [$sign, $term];

    // Если нет ни одного бинарного + или -, то выражение — это произведение/деление
    if (count($terms) == 0) return computeMulDiv($expr);
    if (count($terms) == 1) return computeMulDiv($terms[0][1]);

    // Вычисляем сумму/разность слагаемых, каждое из которых может содержать * и /
    $result = 0;
    foreach ($terms as $idx => $t) {
        $val = computeMulDiv($t[1]);
        if (!isnum($val)) return $val;
        $val = (float)$val;
        if ($idx == 0) {
            $result = ($t[0] === '+' ? $val : -$val);
        } else {
            $result += ($t[0] === '+' ? $val : -$val);
        }
    }
    return $result;
}

/**
 * Проверяет правильность расстановки скобок
 */
function SqValidator($val) {
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] === '(') $open++;
        elseif ($val[$i] === ')') {
            $open--;
            if ($open < 0) return false;
        }
    }
    return $open === 0;
}

/**
 * Вычисляет выражение с возможными скобками (рекурсивно)
 */
function calculateSq($val) {
    if (!SqValidator($val)) return 'Неправильная расстановка скобок';
    $start = strpos($val, '(');
    if ($start === false) {
        return calculate($val);
    }
    // Находим парную закрывающую скобку
    $end = $start + 1;
    $open = 1;
    while ($open > 0 && $end < strlen($val)) {
        if ($val[$end] === '(') $open++;
        elseif ($val[$end] === ')') $open--;
        $end++;
    }
    $left = substr($val, 0, $start);
    $middle = substr($val, $start + 1, $end - $start - 2);
    $right = substr($val, $end);
    $mid_res = calculateSq($middle);
    if (!isnum($mid_res)) return $mid_res;
    $new_expr = $left . $mid_res . $right;
    return calculateSq($new_expr);
}

// ---------- Обработка данных формы ----------
$expr = isset($_POST['expr']) ? trim($_POST['expr']) : '';
$post_iter = isset($_POST['iteration']) ? (int)$_POST['iteration'] : 0;
$output = '';
$error = false;

if ($expr !== '' && ($post_iter + 1) == $_SESSION['iteration']) {
    $res = calculateSq($expr);
    if (isnum($res)) {
        $output = (float)$res;
        $error = false;
    } else {
        $output = $res;
        $error = true;
    }
    $_SESSION['history'][] = htmlspecialchars($expr) . ' = ' . htmlspecialchars($output);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Калькулятор – Щеблыкин К.Е., группа 241-351</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="logo"><img src="logo.png" alt="Логотип"><span>Мой университет</span></div>
            <div class="header-info">
                <h1>Щеблыкин Константин Евгеньевич</h1>
                <p>Группа 241-351</p>
                <p>Лабораторная работа № В-2: Калькулятор</p>
            </div>
        </header>

        <main>
            <?php if ($expr !== '' && ($post_iter + 1) == $_SESSION['iteration']): ?>
                <div class="result-box">
                    <?php if ($error): ?>
                        <p class="error">Ошибка: <?php echo $output; ?></p>
                    <?php else: ?>
                        <p class="success">Результат: <?php echo $output; ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <label for="expr">Введите арифметическое выражение:</label><br>
                <input type="text" name="expr" id="expr" size="50" value="<?php echo htmlspecialchars($expr); ?>" required>
                <input type="hidden" name="iteration" value="<?php echo $_SESSION['iteration']; ?>">
                <button type="submit">Вычислить</button>
            </form>
        </main>

        <footer>
            <h3>История вычислений</h3>
            <?php if (empty($_SESSION['history'])): ?>
                <p>История пуста</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($_SESSION['history'] as $entry): ?>
                        <li><?php echo $entry; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
        </footer>
    </div>
</body>
</html>