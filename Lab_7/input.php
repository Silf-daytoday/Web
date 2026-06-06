<?php
date_default_timezone_set('Europe/Moscow');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ввод массива – Лабораторная работа №7</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function addElement(tableId) {
            var table = document.getElementById(tableId);
            var rowCount = table.rows.length;
            var newRow = table.insertRow(rowCount);
            var cell = newRow.insertCell(0);
            cell.className = 'element_row';
            var inputName = 'element' + rowCount;
            cell.innerHTML = '<input type="text" name="' + inputName + '" value="">';
            document.getElementById('arrLength').value = rowCount + 1;
        }
        window.onload = function() {
            var t = document.getElementById('elements');
            document.getElementById('arrLength').value = t.rows.length;
        }
    </script>
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
        <h2>Ввод элементов массива</h2>
        <form method="post" action="sort_process.php">
            <table id="elements">
                <tr><td class="element_row"><input type="text" name="element0" value=""></td></tr>
            </table>
            <input type="hidden" name="arrLength" id="arrLength" value="1">
            <div class="button-row">
                <input type="button" value="➕ Добавить ещё один элемент" onclick="addElement('elements')">
            </div>
            <div class="button-row">
                <label for="algorithm">Выберите алгоритм сортировки:</label>
                <select name="algorithm" id="algorithm">
                    <option value="choice">Сортировка выбором</option>
                    <option value="bubble">Пузырьковый алгоритм</option>
                    <option value="shell">Алгоритм Шелла</option>
                    <option value="gnome">Алгоритм садового гнома</option>
                    <option value="quick">Быстрая сортировка</option>
                    <option value="builtin">Встроенная функция sort()</option>
                </select>
            </div>
            <div class="button-row">
                <input type="submit" value="🚀 Сортировать массив">
            </div>
        </form>
    </main>

    <footer>
        <p>Сформировано <?php echo date('d.m.Y H:i:s'); ?> (МСК)</p>
    </footer>
</body>
</html>