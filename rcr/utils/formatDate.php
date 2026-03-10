<?php
function formatDate($date) {
    // Разбиваем дату на части
    $parts = explode('-', $date);

    // Форматируем дату в нужный вид (дд.мм.гггг)
    return $parts[2] . '.' . $parts[1] . '.' . $parts[0];
}
?>
