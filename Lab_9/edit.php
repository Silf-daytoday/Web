<?php
$mysqli = mysqli_connect('localhost', 'root', '12345', 'addressbook');
if (mysqli_connect_errno()) {
    echo '<p class="error">Ошибка подключения: ' . mysqli_connect_error() . '</p>';
    exit();
}
mysqli_set_charset($mysqli, 'utf8');

// Обработка отправки формы редактирования
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_submit'])) {
    $id = (int)$_POST['id'];
    $surname    = mysqli_real_escape_string($mysqli, $_POST['surname']);
    $name       = mysqli_real_escape_string($mysqli, $_POST['name']);
    $patronymic = mysqli_real_escape_string($mysqli, $_POST['patronymic']);
    $gender     = ($_POST['gender'] == 'М') ? 'М' : 'Ж';
    $birthdate  = date('Y-m-d', strtotime($_POST['birthdate']));
    $phone      = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $address    = mysqli_real_escape_string($mysqli, $_POST['address']);
    $email      = mysqli_real_escape_string($mysqli, $_POST['email']);
    $comment    = mysqli_real_escape_string($mysqli, $_POST['comment']);
    
    $sql = "UPDATE contacts SET 
            surname='$surname', name='$name', patronymic='$patronymic', gender='$gender',
            birthdate='$birthdate', phone='$phone', address='$address', email='$email', comment='$comment'
            WHERE id=$id";
    if (mysqli_query($mysqli, $sql)) {
        echo '<p class="success">Запись обновлена.</p>';
    } else {
        echo '<p class="error">Ошибка обновления: ' . mysqli_error($mysqli) . '</p>';
    }
}

// Определяем текущую запись
$current_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($current_id == 0) {
    $res = mysqli_query($mysqli, "SELECT * FROM contacts ORDER BY id LIMIT 1");
    $current = mysqli_fetch_assoc($res);
} else {
    $res = mysqli_query($mysqli, "SELECT * FROM contacts WHERE id=$current_id LIMIT 1");
    $current = mysqli_fetch_assoc($res);
}
if (!$current) {
    echo '<p>Нет записей для редактирования.</p>';
    mysqli_close($mysqli);
    exit();
}
$current_id = $current['id'];
?>
<h2>Редактирование записи</h2>

<!-- Список всех записей (фамилия + имя) с выделением текущей -->
<div class="edit-list">
<?php
$res_all = mysqli_query($mysqli, "SELECT id, surname, name FROM contacts ORDER BY surname, name");
while ($row = mysqli_fetch_assoc($res_all)) {
    if ($row['id'] == $current_id) {
        echo '<strong>' . htmlspecialchars($row['surname'] . ' ' . $row['name']) . '</strong><br>';
    } else {
        echo '<a href="?p=edit&id=' . $row['id'] . '">' . htmlspecialchars($row['surname'] . ' ' . $row['name']) . '</a><br>';
    }
}
?>
</div>

<!-- Форма редактирования -->
<form method="post" action="?p=edit&id=<?php echo $current_id; ?>">
    <input type="hidden" name="id" value="<?php echo $current_id; ?>">
    <div class="form-row"><label>Фамилия:</label><input type="text" name="surname" value="<?php echo htmlspecialchars($current['surname']); ?>" required></div>
    <div class="form-row"><label>Имя:</label><input type="text" name="name" value="<?php echo htmlspecialchars($current['name']); ?>" required></div>
    <div class="form-row"><label>Отчество:</label><input type="text" name="patronymic" value="<?php echo htmlspecialchars($current['patronymic']); ?>"></div>
    <div class="form-row"><label>Пол:</label>
        <select name="gender">
            <option value="М" <?php if ($current['gender'] == 'М') echo 'selected'; ?>>Мужской</option>
            <option value="Ж" <?php if ($current['gender'] == 'Ж') echo 'selected'; ?>>Женский</option>
        </select>
    </div>
    <div class="form-row"><label>Дата рождения:</label><input type="date" name="birthdate" value="<?php echo $current['birthdate']; ?>" required></div>
    <div class="form-row"><label>Телефон:</label><input type="text" name="phone" value="<?php echo htmlspecialchars($current['phone']); ?>" required></div>
    <div class="form-row"><label>Адрес:</label><input type="text" name="address" value="<?php echo htmlspecialchars($current['address']); ?>"></div>
    <div class="form-row"><label>E‑mail:</label><input type="email" name="email" value="<?php echo htmlspecialchars($current['email']); ?>"></div>
    <div class="form-row"><label>Комментарий:</label><textarea name="comment" rows="3"><?php echo htmlspecialchars($current['comment']); ?></textarea></div>
    <div class="form-row"><input type="submit" name="edit_submit" value="Изменить запись"></div>
</form>
<?php mysqli_close($mysqli); ?>