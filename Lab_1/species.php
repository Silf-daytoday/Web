<?php
// Устанавливаем московское время
date_default_timezone_set('Europe/Moscow');

// Заголовок страницы (ФИО, группа, номер и название работы)
$title = "Щеблыкин Константин Евгеньевич, группа 241-351 – Лабораторная работа № А-1: Простейшая программа на PHP. Конвертация статического контента в динамический.";

$menu1_link = "index.php";
$menu1_text = "Главная";
$menu1_active = false;

$menu2_link = "species.php";
$menu2_text = "Виды сов";
$menu2_active = true;

$menu3_link = "behavior.php";
$menu3_text = "Поведение";
$menu3_active = false;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <!-- Пункт меню 1: два включения PHP -->
            <a href="<?php echo $menu1_link; ?>"<?php if ($menu1_active) echo ' class="selected_menu"'; echo '>' . $menu1_text;?></a>
            <!-- Пункт меню 2 -->
            <a href="<?php echo $menu2_link; ?>"<?php if ($menu2_active) echo ' class="selected_menu"'; echo '>' . $menu2_text;?></a>
            <!-- Пункт меню 3 -->
            <a href="<?php echo $menu3_link; ?>"<?php if ($menu3_active) echo ' class="selected_menu"'; echo '>' . $menu3_text;?></a>
        </nav>
    </header>
    <main>

        <h1>Разнообразие видов сов</h1>
        <p>В мире насчитывается около 220 видов сов, объединённых в два семейства: настоящие совы (Strigidae) и сипуховые (Tytonidae). Рассмотрим некоторые из них.</p>
        <p>Совы населяют все континенты, кроме Антарктиды, и приспособлены к самым разным условиям обитания — от тундры до тропических лесов. Размеры варьируют от воробьиного сычика (длина около 15 см) до филина (длина до 75 см).</p>

        <h2>Филин (Bubo bubo)</h2>
        <p>Крупнейшая сова Европы, размах крыльев до 190 см. Обитает в лесах и горах, питается зайцами, грызунами, птицами. Имеет характерные "ушки" из перьев. Внесён в Красные книги многих стран.</p>

        <h2>Ушастая сова (Asio otus)</h2>
        <p>Среднего размера сова с длинными ушными пучками. Предпочитает открытые ландшафты, гнездится в старых гнёздах врановых. Питается мелкими грызунами. Обитает в Евразии и Северной Америке.</p>

        <h2>Сравнение видов</h2>
        <table border="1">
            <?php
            echo '<tr><th>Вид</th><th>Вес (г)</th><th>Особенности</th></tr>';
            ?>
            <tr>
                <td><?php echo "Филин"; ?></td>
                <td><?php echo "~2700"; ?></td>
                <td><?php echo "Мощные лапы, крупный, оранжевые или красные глаза, полет с характерным свистом"; ?></td>
            </tr>
            <tr>
                <td><?php echo "Ушастая сова"; ?></td>
                <td><?php echo "~250"; ?></td>
                <td><?php echo "Ярко-желтые или оранжево-желтые глаза, бесшумный полет"; ?></td>
            </tr>
        </table>

        <h2>Фотографии</h2>
        <img src="images/filin_static.jpg" alt="Филин" width="45%">
        <img src="images/owl_<?php echo (date('s') % 2 == 0) ? 'dynamic3' : 'dynamic4'; ?>.jpg" alt="Ушастая сова" width="45%">

    </main>
    <footer>
        <?php
        echo "Сформировано " . date('d.m.Y') . " в " . date('H‐i‐s');
        ?>
    </footer>
</body>
</html>