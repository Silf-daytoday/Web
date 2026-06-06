<?php
date_default_timezone_set('Europe/Moscow');

// Функция: число в ссылку (для однозначных чисел 2-9)
function outNumAsLink($x) {
    if ($x >= 2 && $x <= 9) {
        return '<a href="?content=' . $x . '">' . $x . '</a>';
    } else {
        return $x;
    }
}

// Функция: вывод строки (столбца)
function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        echo outNumAsLink($n) . ' x ' . outNumAsLink($i) . ' = ' . outNumAsLink($i * $n) . '<br>';
    }
}

// Табличная верстка
function outTableForm() {
    if (!isset($_GET['content'])) {
        echo '<table>';
        echo '<tr><th>×</th>';
        for ($j = 2; $j <= 9; $j++) echo '<th>' . outNumAsLink($j) . '</th>';
        echo '</tr>';
        for ($i = 2; $i <= 9; $i++) {
            echo '<tr><th>' . outNumAsLink($i) . '</th>';
            for ($j = 2; $j <= 9; $j++) echo '<td>' . outNumAsLink($i * $j) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        $n = (int)$_GET['content'];
        echo '<table>';
        echo '<tr><th>Таблица умножения на ' . $n . '</th></tr>';
        for ($i = 2; $i <= 9; $i++) {
            echo '<tr><td>' . outNumAsLink($n) . ' x ' . outNumAsLink($i) . ' = ' . outNumAsLink($i * $n) . '</td></tr>';
        }
        echo '</table>';
    }
}

// Блочная верстка
function outDivForm() {
    if (!isset($_GET['content'])) {
        for ($i = 2; $i <= 9; $i++) {
            echo '<div class="ttRow">';
            echo '<div class="ttColHeader">' . outNumAsLink($i) . '</div>';
            outRow($i);
            echo '</div>';
        }
    } else {
        $n = (int)$_GET['content'];
        echo '<div class="ttSingleRow">';
        echo '<div class="ttColHeader">Таблица умножения на ' . $n . '</div>';
        outRow($n);
        echo '</div>';
    }
}

// Параметры (без значения по умолчанию для выделения)
if (isset($_GET['html_type'])) {
    $html_type = $_GET['html_type'];
} else {
    $html_type = null;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа №5</title>
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
            <p>Лабораторная работа №5: Таблица умножения</p>
        </div>
    </header>

    <!-- Главное меню (горизонтальное) -->
    <div id="main_menu">
        <a href="?html_type=TABLE<?php if(isset($_GET['content'])) echo '&content='.$_GET['content']; ?>" <?php if(isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE') echo 'class="selected"'; ?>>Табличная верстка</a>
        <a href="?html_type=DIV<?php if(isset($_GET['content'])) echo '&content='.$_GET['content']; ?>" <?php if(isset($_GET['html_type']) && $_GET['html_type'] == 'DIV') echo 'class="selected"'; ?>>Блочная верстка</a>
    </div>

    <div class="container">
        <!-- Основное меню (боковое) -->
        <div id="product_menu">
            <?php
            $base = '?';
            if(isset($_GET['html_type'])) $base .= 'html_type='.$_GET['html_type'].'&';
            echo '<a href="' . $base . '"';
            if(!isset($_GET['content'])) echo ' class="selected"';
            echo '>Вся таблица умножения</a>';

            for($i=2; $i<=9; $i++) {
                $link = '?';
                if(isset($_GET['html_type'])) $link .= 'html_type='.$_GET['html_type'].'&';
                $link .= 'content='.$i;
                echo '<a href="' . $link . '"';
                if(isset($_GET['content']) && $_GET['content']==$i) echo ' class="selected"';
                echo '>Таблица умножения на ' . $i . '</a>';
            }
            ?>
        </div>

        <!-- Основное содержимое -->
        <main>
            <?php
            if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
                outTableForm();
            } else {
                outDivForm();
            }
            ?>
        </main>
    </div>

    <footer>
        <?php
        $info = '';
        if(!isset($_GET['html_type']) || $_GET['html_type']=='TABLE') $info .= 'Табличная верстка. ';
        else $info .= 'Блочная верстка. ';
        if(!isset($_GET['content'])) $info .= 'Таблица умножения полностью. ';
        else $info .= 'Столбец таблицы умножения на '.(int)$_GET['content'].'. ';
        $info .= date('d.m.Y H:i:s');
        echo '<p>' . $info . '</p>';
        ?>
    </footer>
</body>
</html>