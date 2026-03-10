<?php
require_once (__DIR__ . '/../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Обработка запроса на удаление отзыва
if (isset($_POST['deleteReview'])) {
    $reviewId = $_POST['reviewId'];
    $sql = "DELETE FROM Reviews WHERE Id_reviews = :reviewId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reviewId', $reviewId);
    $stmt->execute();
}

// Получение всех отзывов с информацией о пользователях
$sql = "SELECT Reviews.Id_reviews, Reviews.Text_reviews, Reviews.Grade, Users.username 
        FROM Reviews 
        JOIN Users ON Reviews.Id_user = Users.Id_user";
$stmt = $pdo->query($sql);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <?php foreach ($reviews as $review): ?>
        <div>
            <div class="cardText">Пользователь: <span class="cardData"><?= htmlspecialchars($review['username']); ?></span></div>
            <div class="cardText">Оценка: <span class="cardData"><?= htmlspecialchars($review['Grade']); ?></span></div>
            <div class="cardText">Отзыв: <span class="cardData cardDataText"><?= htmlspecialchars($review['Text_reviews']); ?></span></div>
            <form method="post" action="">
                <input type="hidden" name="reviewId" value="<?= $review['Id_reviews']; ?>">
                <input type="submit" name="deleteReview" value="Удалить" class="cardButton">
            </form>
            <hr>
        </div>
    <?php endforeach; ?>
</section>
