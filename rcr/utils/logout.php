<?php
// Завершаем сессию
session_start();
session_destroy();

// Перенаправляем на главную страницу
header('Location: /rcr/index.php');
exit();
?>
