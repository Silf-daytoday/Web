<?php
require 'menu.php';
date_default_timezone_set('Europe/Moscow');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записная книжка – Щеблыкин К.Е., группа 241-351</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="logo">
                <img src="logo.png" alt="Логотип университета">
                <span>Мой университет</span>
            </div>
            <div class="header-info">
                <h1>Щеблыкин Константин Евгеньевич</h1>
                <p>Группа 241-351</p>
                <p>Лабораторная работа № В-1: Записная книжка</p>
            </div>
        </header>
        <?php
        // Вывод основного меню
        echo getMenu();
        ?>
        <div id="content">
            <?php
            $page = isset($_GET['p']) ? $_GET['p'] : 'viewer';
            $allowed = ['viewer', 'add', 'edit', 'delete'];
            if (!in_array($page, $allowed)) $page = 'viewer';
            include $page . '.php';
            ?>
        </div>
        <footer>
            <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?></p>
        </footer>
    </div>
</body>
</html>