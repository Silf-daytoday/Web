<?php
date_default_timezone_set('Europe/Moscow');

function isNumber($str) {
    $str = trim($str);
    if ($str === '') return false;
    return is_numeric(str_replace(',', '.', $str));
}
function toNumber($str) {
    return (float) str_replace(',', '.', trim($str));
}

$iterationCounter = 0;
function dumpArray($arr, $iterationNumber = null) {
    global $iterationCounter;
    if ($iterationNumber === null) {
        $iterationCounter++;
        $num = $iterationCounter;
    } else {
        $num = $iterationNumber;
    }
    echo "<div class='iteration'>Итерация $num: [ " . implode(', ', $arr) . " ]</div>";
}

function selectionSort(&$arr) {
    global $iterationCounter;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $minIdx = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            if ($arr[$j] < $arr[$minIdx]) $minIdx = $j;
        }
        if ($minIdx != $i) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$minIdx];
            $arr[$minIdx] = $temp;
        }
        dumpArray($arr);
    }
    return $iterationCounter;
}

function bubbleSort(&$arr) {
    global $iterationCounter;
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($arr[$j] > $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
        }
        dumpArray($arr);
    }
    return $iterationCounter;
}

function shellSort(&$arr) {
    global $iterationCounter;
    $n = count($arr);
    $gap = floor($n / 2);
    while ($gap > 0) {
        for ($i = $gap; $i < $n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while ($j >= $gap && $arr[$j - $gap] > $temp) {
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
            }
            $arr[$j] = $temp;
        }
        dumpArray($arr);
        $gap = floor($gap / 2);
    }
    return $iterationCounter;
}

function gnomeSort(&$arr) {
    global $iterationCounter;
    $i = 1;
    $n = count($arr);
    while ($i < $n) {
        if ($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--;
        }
        dumpArray($arr);
    }
    return $iterationCounter;
}

function quickSort(&$arr, $left, $right) {
    global $iterationCounter;
    if ($left < $right) {
        $pivot = $arr[($left + $right) >> 1];
        $l = $left;
        $r = $right;
        do {
            while ($arr[$l] < $pivot) $l++;
            while ($arr[$r] > $pivot) $r--;
            if ($l <= $r) {
                $temp = $arr[$l];
                $arr[$l] = $arr[$r];
                $arr[$r] = $temp;
                $l++; $r--;
            }
        } while ($l <= $r);
        dumpArray($arr);
        quickSort($arr, $left, $r);
        quickSort($arr, $l, $right);
    }
}
function quickSortWrapper(&$arr) {
    global $iterationCounter;
    quickSort($arr, 0, count($arr) - 1);
    return $iterationCounter;
}

function builtinSort(&$arr) {
    sort($arr);
    return 0;
}

// ---------- Обработка входных данных ----------
if (!isset($_POST['element0']) || !isset($_POST['arrLength'])) {
    $errorMsg = "Массив не задан.";
    $valid = false;
} else {
    $length = (int)$_POST['arrLength'];
    $inputArray = [];
    $valid = true;
    $errorMsg = '';
    for ($i = 0; $i < $length; $i++) {
        $key = 'element' . $i;
        if (!isset($_POST[$key])) {
            $valid = false;
            $errorMsg = "Не передан элемент с индексом $i.";
            break;
        }
        $val = trim($_POST[$key]);
        if ($val === '') {
            $valid = false;
            $errorMsg = "Элемент $i пуст.";
            break;
        }
        if (!isNumber($val)) {
            $valid = false;
            $errorMsg = "Элемент '$val' не является числом.";
            break;
        }
        $inputArray[] = toNumber($val);
    }
    if ($valid && count($inputArray) == 0) {
        $valid = false;
        $errorMsg = "Массив пуст.";
    }
}

$algorithm = $_POST['algorithm'] ?? null;
$algoName = '';
$func = null;
switch ($algorithm) {
    case 'choice':   $algoName = 'Сортировка выбором';     $func = 'selectionSort'; break;
    case 'bubble':   $algoName = 'Пузырьковая сортировка'; $func = 'bubbleSort'; break;
    case 'shell':    $algoName = 'Сортировка Шелла';       $func = 'shellSort'; break;
    case 'gnome':    $algoName = 'Сортировка гнома';       $func = 'gnomeSort'; break;
    case 'quick':    $algoName = 'Быстрая сортировка';     $func = 'quickSortWrapper'; break;
    case 'builtin':  $algoName = 'Встроенная sort()';      $func = 'builtinSort'; break;
    default:         $algoName = 'Неизвестный алгоритм';   $func = null;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат сортировки</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Логотип университета">
            <span>Мой университет</span>
        </div>
        <div class="header-info">
            <h1>Щеблыкин Константин Евгеньевич</h1>
            <p>Группа 241-351</p>
            <p>Лабораторная работа №7: Сортировка массивов</p>
        </div>
    </header>

    <main>
        <?php if (!$valid): ?>
            <div class="error-box">
                <h2>Ошибка валидации</h2>
                <p><?php echo htmlspecialchars($errorMsg); ?></p>
                <p>Сортировка невозможна.</p>
                <a href="input.php" class="back-link">← Вернуться к вводу массива</a>
            </div>
        <?php elseif (!$func): ?>
            <div class="error-box">
                <h2>Ошибка</h2>
                <p>Выбран неизвестный алгоритм сортировки.</p>
                <a href="input.php" class="back-link">← Вернуться к вводу массива</a>
            </div>
        <?php else: ?>
            <h1><?php echo $algoName; ?></h1>
            <h3>Исходный массив:</h3>
            <div class="initial">[ <?php echo implode(', ', $inputArray); ?> ]</div>

            <h3>Процесс сортировки:</h3>
            <?php
            $arr = $inputArray;
            $iterationCounter = 0;
            $start = microtime(true);
            $iterations = $func($arr);
            $time = microtime(true) - $start;
            ?>
            <h3>Результат сортировки:</h3>
            <div class="result">[ <?php echo implode(', ', $arr); ?> ]</div>

            <p><strong>Сортировка завершена.</strong> Проведено итераций: <?php echo $iterations; ?>.</p>
            <p>Затрачено времени: <?php echo round($time, 6); ?> секунд.</p>
            <a href="input.php" class="back-link">← Вернуться к вводу массива</a>
        <?php endif; ?>
    </main>

    <footer>
        <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
    </footer>
</body>
</html>