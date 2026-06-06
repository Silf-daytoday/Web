<?php
date_default_timezone_set('Europe/Moscow');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Анализ текста – Лабораторная работа №8</title>
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
        <h2>Введите текст для анализа</h2>
        <form method="post" action="result.php">
            <textarea name="data" rows="10" cols="70" placeholder="Введите или вставьте сюда любой текст..."></textarea>
            <div><input type="submit" value="🔍 Анализировать"></div>
        </form>
    </main>
    <footer><p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p></footer>
</body>
</html>