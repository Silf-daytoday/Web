<?php
function getMenu() {
    $current = isset($_GET['p']) ? $_GET['p'] : 'viewer';
    // Базовые ссылки с сохранением параметров сортировки и страницы (для просмотра)
    $viewer_link = '?p=viewer';
    if (isset($_GET['sort'])) $viewer_link .= '&sort=' . $_GET['sort'];
    if (isset($_GET['pg']))   $viewer_link .= '&pg='   . $_GET['pg'];
    
    $add_link    = '?p=add';
    $edit_link   = '?p=edit';
    $delete_link = '?p=delete';
    
    $html = '<div id="main_menu">';
    $html .= '<a href="' . $viewer_link . '"' . ($current == 'viewer' ? ' class="active"' : '') . '>Просмотр</a>';
    $html .= '<a href="' . $add_link    . '"' . ($current == 'add'    ? ' class="active"' : '') . '>Добавление записи</a>';
    $html .= '<a href="' . $edit_link   . '"' . ($current == 'edit'   ? ' class="active"' : '') . '>Редактирование записи</a>';
    $html .= '<a href="' . $delete_link . '"' . ($current == 'delete' ? ' class="active"' : '') . '>Удаление записи</a>';
    $html .= '</div>';
    
    // Подменю сортировки – только для пункта "Просмотр"
    if ($current == 'viewer') {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created';
        $pg   = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
        $html .= '<div id="submenu">';
        $html .= '<a href="?p=viewer&sort=created&pg=' . $pg . '"' . ($sort == 'created' ? ' class="active"' : '') . '>По умолчанию</a>';
        $html .= '<a href="?p=viewer&sort=surname&pg=' . $pg . '"' . ($sort == 'surname' ? ' class="active"' : '') . '>По фамилии</a>';
        $html .= '<a href="?p=viewer&sort=birthdate&pg=' . $pg . '"' . ($sort == 'birthdate' ? ' class="active"' : '') . '>По дате рождения</a>';
        $html .= '</div>';
    }
    return $html;
}
?>