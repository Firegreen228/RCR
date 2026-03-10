<?php
function connectToDataBase()
{
    $host = 'localhost';
    $dbname = 'RCR';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // Установка PDO для выброса исключений при ошибке
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Обработка ошибок соединения
        echo "Ошибка соединения: " . $e->getMessage();
        exit();
    }
}
?>
