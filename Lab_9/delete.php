<?php
$mysqli = mysqli_connect('localhost', 'root', '12345', 'addressbook');
if (mysqli_connect_errno()) {
    echo '<p class="error">Ошибка подключения: ' . mysqli_connect_error() . '</p>';
    exit();
}
mysqli_set_charset($mysqli, 'utf8');

// Удаление записи, если передан параметр del_id
if (isset($_GET['del_id'])) {
    $del_id = (int)$_GET['del_id'];
    $res = mysqli_query($mysqli, "SELECT surname FROM contacts WHERE id=$del_id");
    if ($row = mysqli_fetch_assoc($res)) {
        $surname = $row['surname'];
        $sql_del = "DELETE FROM contacts WHERE id=$del_id";
        if (mysqli_query($mysqli, $sql_del)) {
            echo '<p class="success">Запись с фамилией ' . htmlspecialchars($surname) . ' удалена.</p>';
        } else {
            echo '<p class="error">Ошибка удаления: ' . mysqli_error($mysqli) . '</p>';
        }
    } else {
        echo '<p class="error">Запись не найдена.</p>';
    }
}

// Список всех записей (фамилия + инициалы) для выбора
$res_all = mysqli_query($mysqli, "SELECT id, surname, name, patronymic FROM contacts ORDER BY surname, name");
if (mysqli_num_rows($res_all) == 0) {
    echo '<p>Нет записей для удаления.</p>';
} else {
    echo '<h2>Выберите запись для удаления:</h2>';
    echo '<div class="delete-list">';
    while ($row = mysqli_fetch_assoc($res_all)) {
        // Получаем первый символ имени
        $initials = '';
        if (preg_match('/^./u', $row['name'], $matches)) {
            $initials = $matches[0] . '.';
        }
        // Если есть отчество – добавляем его первый символ
        if (!empty($row['patronymic']) && preg_match('/^./u', $row['patronymic'], $matches)) {
            $initials .= $matches[0] . '.';
        }
        $full = $row['surname'] . ' ' . $initials;
        echo '<a href="?p=delete&del_id=' . $row['id'] . '">' . htmlspecialchars($full) . '</a><br>';
    }
    echo '</div>';
}
mysqli_close($mysqli);
?>