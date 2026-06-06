<?php
date_default_timezone_set('Europe/Moscow');
header('Content-Type: text/html; charset=utf-8');

// ---------- Вспомогательные функции для анализа (работают в CP1251) ----------
function isCyrillicLetter($ch) {
    $code = ord($ch);
    // диапазоны кириллицы в CP1251: А-Я (192-223), а-я (224-255), Ё (168), ё (184)
    return ($code >= 192 && $code <= 255) || $code == 168 || $code == 184;
}

function isLatinLetter($ch) {
    $code = ord($ch);
    return ($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 122);
}

function isLetter($ch) {
    return isCyrillicLetter($ch) || isLatinLetter($ch);
}

function isPunctuation($ch) {
    $punct = ['.', ',', '!', '?', ';', ':', '-', '(', ')', '"', "'", '…', '«', '»', '—'];
    return in_array($ch, $punct);
}

function isDigit($ch) {
    return ctype_digit($ch);
}

// ---------- Функция анализа текста (текст уже в CP1251) ----------
function analyzeText($text) {
    $result = [];
    $result['char_count'] = strlen($text);
    $total_letters = $lower_letters = $upper_letters = 0;
    $punct_count = $digit_count = 0;
    $char_counts = [];
    $word_counts = [];
    $current_word = '';

    for ($i = 0; $i < strlen($text); $i++) {
        $ch = $text[$i];
        $lower_ch = strtolower($ch);
        $char_counts[$lower_ch] = isset($char_counts[$lower_ch]) ? $char_counts[$lower_ch] + 1 : 1;

        if (isLetter($ch)) {
            $total_letters++;
            $code = ord($ch);
            if (isCyrillicLetter($ch)) {
                // заглавные кириллические: А-Я (192-223) и Ё (168)
                if (($code >= 192 && $code <= 223) || $code == 168) {
                    $upper_letters++;
                } else {
                    $lower_letters++;
                }
            } else { // латиница
                if (ctype_upper($ch)) {
                    $upper_letters++;
                } else {
                    $lower_letters++;
                }
            }
            $current_word .= $ch;
        } else {
            if ($current_word !== '') {
                $word_lower = strtolower($current_word);
                $word_counts[$word_lower] = isset($word_counts[$word_lower]) ? $word_counts[$word_lower] + 1 : 1;
                $current_word = '';
            }
            if (isPunctuation($ch)) $punct_count++;
            if (isDigit($ch)) $digit_count++;
        }
    }
    if ($current_word !== '') {
        $word_lower = strtolower($current_word);
        $word_counts[$word_lower] = isset($word_counts[$word_lower]) ? $word_counts[$word_lower] + 1 : 1;
    }

    $result['total_letters'] = $total_letters;
    $result['lower_letters'] = $lower_letters;
    $result['upper_letters'] = $upper_letters;
    $result['punct_count']   = $punct_count;
    $result['digit_count']   = $digit_count;
    $result['word_count']    = count($word_counts);
    $result['word_counts']   = $word_counts;
    $result['char_counts']   = $char_counts;
    return $result;
}

// ---------- Получение данных из формы ----------
$raw_text = isset($_POST['data']) ? $_POST['data'] : '';
$has_text = (trim($raw_text) !== '');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат анализа текста</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo"><img src="logo.png" alt="Логотип"><span>Мой университет</span></div>
        <div class="header-info">
            <h1>Щеблыкин Константин Евгеньевич</h1>
            <p>Группа 241-351</p>
            <p>Лабораторная работа №8: Анализ текста</p>
        </div>
    </header>

    <main>
        <?php if (!$has_text): ?>
            <div class="error-box">
                <p><strong>Нет текста для анализа</strong></p>
                <a href="index.html" class="back-link">← Другой анализ</a>
            </div>
        <?php else:
            $text_cp = iconv("UTF-8", "windows-1251//IGNORE", $raw_text);
            $stats = analyzeText($text_cp);
            $src_text = $raw_text; // оригинал в UTF-8
        ?>
            <div class="source-text">
                <h3>Исходный текст</h3>
                <div class="src_text"><?php echo nl2br(htmlspecialchars($src_text)); ?></div>
            </div>

            <h3>Общая статистика</h3>
            <table class="analysis-table">
                <tr><th>Параметр</th><th>Значение</th></tr>
                <tr><td>Количество символов (включая пробелы)</td><td><?php echo $stats['char_count']; ?></td></tr>
                <tr><td>Количество букв</td><td><?php echo $stats['total_letters']; ?></td></tr>
                <tr><td>Из них строчных</td><td><?php echo $stats['lower_letters']; ?></td></tr>
                <tr><td>Из них заглавных</td><td><?php echo $stats['upper_letters']; ?></td></tr>
                <tr><td>Количество знаков препинания</td><td><?php echo $stats['punct_count']; ?></td></tr>
                <tr><td>Количество цифр</td><td><?php echo $stats['digit_count']; ?></td></tr>
                <tr><td>Количество слов</td><td><?php echo $stats['word_count']; ?></td></tr>
            </table>

            <h3>Количество вхождений каждого символа (без учёта регистра)</h3>
            <table class="analysis-table">
                <tr><th>Символ</th><th>Количество</th></tr>
                <?php
                $char_counts = $stats['char_counts'];
                ksort($char_counts);
                foreach ($char_counts as $ch_cp => $count):
                    $ch_utf8 = iconv("windows-1251", "UTF-8//IGNORE", $ch_cp);
                    if ($ch_utf8 === false) continue;
                    if ($ch_cp === ' ')       $display = '[пробел]';
                    elseif ($ch_cp === "\n")  $display = '[перенос строки]';
                    elseif ($ch_cp === "\r")  $display = '[возврат каретки]';
                    elseif ($ch_cp === "\t")  $display = '[табуляция]';
                    else                      $display = $ch_utf8;
                ?>
                    <tr><td><?php echo htmlspecialchars($display); ?></td><td><?php echo $count; ?></td></tr>
                <?php endforeach; ?>
            </table>

            <h3>Список слов и количество их вхождений (по алфавиту)</h3>
            <table class="analysis-table">
                <tr><th>Слово</th><th>Количество</th></tr>
                <?php
                $word_counts = $stats['word_counts'];
                ksort($word_counts);
                foreach ($word_counts as $word_cp => $count):
                    $word_utf8 = iconv("windows-1251", "UTF-8//IGNORE", $word_cp);
                    if ($word_utf8 === false) continue;
                ?>
                    <tr><td><?php echo htmlspecialchars($word_utf8); ?></td><td><?php echo $count; ?></td></tr>
                <?php endforeach; ?>
            </table>

            <div class="button-row">
                <a href="index.html" class="back-link">← Другой анализ</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
    </footer>
</body>
</html>