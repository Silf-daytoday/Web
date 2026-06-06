<?php
date_default_timezone_set('Europe/Moscow');

// Инициализация хранилища
if (!isset($_GET['store'])) {
    $_GET['store'] = '';
}
if (!isset($_GET['count'])) {
    $_GET['count'] = 0;
}

// Обработка нажатия кнопки
if (isset($_GET['key'])) {
    $key = $_GET['key'];
    if ($key === 'reset') {
        $_GET['store'] = '';
    } else {
        $_GET['store'] .= $key;
    }
    $_GET['count']++;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа №А-3</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="Логотип университета" width="50" height="50">
            <span>Мой университет</span>
        </div>
        <div class="header-info">
            <h1>Щеблыкин Константин Евгеньевич</h1>
            <p>Группа 241-351</p>
            <p>Лабораторная работа №А-3: Использование GET‐параметров в ссылках. Виртуальная клавиатура</p>
        </div>
    </header>

    <main>
        <div class="keyboard">
            <!-- Окно просмотра результата -->
            <div class="display">
                <?php echo $_GET['store']; ?>
            </div>

            <!-- Первая строка цифр (1–5) -->
            <div class="row">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <a href="?key=<?php echo $i; ?>&store=<?php echo $_GET['store']; ?>&count=<?php echo $_GET['count']; ?>" class="key"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>

            <!-- Вторая строка цифр (6–9 и 0) -->
            <div class="row">
                <?php for ($i = 6; $i <= 9; $i++): ?>
                    <a href="?key=<?php echo $i; ?>&store=<?php echo $_GET['store']; ?>&count=<?php echo $_GET['count']; ?>" class="key"><?php echo $i; ?></a>
                <?php endfor; ?>
                <a href="?key=0&store=<?php echo $_GET['store']; ?>&count=<?php echo $_GET['count']; ?>" class="key">0</a>
            </div>

            <!-- Строка с кнопкой сброса (на всю ширину) -->
            <div class="row reset-row">
                <a href="?key=reset&store=<?php echo $_GET['store']; ?>&count=<?php echo $_GET['count']; ?>" class="key reset">СБРОС</a>
            </div>
        </div>
    </main>

    <footer>
        <p>Всего нажатий: <?php echo $_GET['count']; ?></p>
        <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
    </footer>
</body>
</html>