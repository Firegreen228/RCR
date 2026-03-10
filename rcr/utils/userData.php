<?php

// Функция для получения данных пользователя из базы данных
function getUserData($user_id, $pdo) {
    $query = "SELECT * FROM Users WHERE Id_user = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$user_id]);
    $userData = $statement->fetch(PDO::FETCH_ASSOC);
    return $userData;
}
?>
