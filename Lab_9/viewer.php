<?php
// Функция возвращает HTML таблицы и пагинации
function getFriendsList($sort, $page) {
    $host = 'localhost';
    $user = 'root';
    $pass = '12345';          
    $db   = 'addressbook';
    
    $mysqli = mysqli_connect($host, $user, $pass, $db);
    if (mysqli_connect_errno()) {
        return '<p class="error">Ошибка подключения к БД: ' . mysqli_connect_error() . '</p>';
    }
    mysqli_set_charset($mysqli, 'utf8');
    
    // 1. Общее количество записей
    $res = mysqli_query($mysqli, "SELECT COUNT(*) AS cnt FROM contacts");
    $row = mysqli_fetch_assoc($res);
    $total = $row['cnt'];
    if ($total == 0) {
        mysqli_close($mysqli);
        return '<p>В таблице нет данных.</p>';
    }
    
    // 2. Пагинация (10 записей на страницу)
    $per_page = 5;
    $pages = ceil($total / $per_page);
    if ($page < 0) $page = 0;
    if ($page >= $pages) $page = $pages - 1;
    $offset = $page * $per_page;
    
    // 3. Сортировка
    switch ($sort) {
        case 'surname':
            $order = "ORDER BY surname, name";
            break;
        case 'birthdate':
            $order = "ORDER BY birthdate";
            break;
        default:
            $order = "ORDER BY created_at";
    }
    
    // 4. Запрос данных
    $sql = "SELECT id, surname, name, patronymic, gender, birthdate, phone, address, email, comment 
            FROM contacts $order LIMIT $offset, $per_page";
    $res_data = mysqli_query($mysqli, $sql);
    if (!$res_data) {
        mysqli_close($mysqli);
        return '<p class="error">Ошибка запроса: ' . mysqli_error($mysqli) . '</p>';
    }
    
    // 5. Формирование HTML-таблицы
    $html = '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<tr><th>ID</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Пол</th><th>Дата рождения</th><th>Телефон</th><th>Адрес</th><th>E‑mail</th><th>Комментарий</th></tr>';
    while ($row = mysqli_fetch_assoc($res_data)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['patronymic']) . '</td>';
        $html .= '<td>' . ($row['gender'] == 'М' ? 'Мужской' : 'Женский') . '</td>';
        $html .= '<td>' . date('d.m.Y', strtotime($row['birthdate'])) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    
    // 6. Ссылки пагинации
    if ($pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i == $page) {
                $html .= '<span>' . ($i + 1) . '</span>';
            } else {
                $link = '?p=viewer&sort=' . $sort . '&pg=' . $i;
                $html .= '<a href="' . $link . '">' . ($i + 1) . '</a>';
            }
        }
        $html .= '</div>';
    }
    
    mysqli_free_result($res_data);
    mysqli_close($mysqli);
    return $html;
}

// Вызов функции с параметрами из URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created';
$page = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
echo getFriendsList($sort, $page);
?>