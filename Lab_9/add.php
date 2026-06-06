<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_submit'])) {
    $mysqli = mysqli_connect('localhost', 'root', '12345', 'addressbook');
    if (mysqli_connect_errno()) {
        $message = '<p class="error">Ошибка подключения: ' . mysqli_connect_error() . '</p>';
    } else {
        mysqli_set_charset($mysqli, 'utf8');
        $surname    = mysqli_real_escape_string($mysqli, $_POST['surname']);
        $name       = mysqli_real_escape_string($mysqli, $_POST['name']);
        $patronymic = mysqli_real_escape_string($mysqli, $_POST['patronymic']);
        $gender     = ($_POST['gender'] == 'М') ? 'М' : 'Ж';
        $birthdate  = date('Y-m-d', strtotime($_POST['birthdate']));
        $phone      = mysqli_real_escape_string($mysqli, $_POST['phone']);
        $address    = mysqli_real_escape_string($mysqli, $_POST['address']);
        $email      = mysqli_real_escape_string($mysqli, $_POST['email']);
        $comment    = mysqli_real_escape_string($mysqli, $_POST['comment']);
        
        $sql = "INSERT INTO contacts (surname, name, patronymic, gender, birthdate, phone, address, email, comment)
                VALUES ('$surname', '$name', '$patronymic', '$gender', '$birthdate', '$phone', '$address', '$email', '$comment')";
        if (mysqli_query($mysqli, $sql)) {
            $message = '<p class="success">✅ Запись добавлена</p>';
        } else {
            $message = '<p class="error">❌ Ошибка: запись не добавлена. ' . mysqli_error($mysqli) . '</p>';
        }
        mysqli_close($mysqli);
    }
}
?>
<h2>Добавление новой записи</h2>
<?php echo $message; ?>
<form method="post" action="">
    <div class="form-row"><label>Фамилия:</label><input type="text" name="surname" required></div>
    <div class="form-row"><label>Имя:</label><input type="text" name="name" required></div>
    <div class="form-row"><label>Отчество:</label><input type="text" name="patronymic"></div>
    <div class="form-row"><label>Пол:</label>
        <select name="gender">
            <option value="М">Мужской</option>
            <option value="Ж">Женский</option>
        </select>
    </div>
    <div class="form-row"><label>Дата рождения:</label><input type="date" name="birthdate" required></div>
    <div class="form-row"><label>Телефон:</label><input type="text" name="phone" required></div>
    <div class="form-row"><label>Адрес:</label><input type="text" name="address"></div>
    <div class="form-row"><label>E‑mail:</label><input type="email" name="email"></div>
    <div class="form-row"><label>Комментарий:</label><textarea name="comment" rows="3"></textarea></div>
    <div class="form-row"><input type="submit" name="add_submit" value="Добавить запись"></div>
</form>