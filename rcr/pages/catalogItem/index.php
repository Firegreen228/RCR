<?php
require_once('../../utils/connect/connectToDataBase.php');
require_once('../../utils/userData.php');

// Подключаемся к базе данных
$pdo = connectToDataBase();

// Проверяем, передан ли параметр id_cars
if (!isset($_GET['id_cars']) || !is_numeric($_GET['id_cars'])) {
    // Если параметр id_cars не передан или не является числом, перенаправляем на страницу каталога
    header('Location: /catalog.php');
    exit();
}

session_start();
if (isset($_SESSION['user_id'])) {
    // Получаем данные пользователя из базы данных
    $userData = getUserData($_SESSION['user_id'], $pdo);
    $auth = true;
} else {
    $auth = false;
}

// Получаем id автомобиля из параметра запроса
$id_cars = $_GET['id_cars'];

// Запрос для получения данных об автомобиле с выбранным id и средней оценки из отзывов
$query = "SELECT Cars.*, Models.model_name, Brands.brand_name,
          (SELECT AVG(Grade) FROM Reviews WHERE Id_car = Cars.id_cars) AS average_rating
          FROM Cars
          JOIN Models ON Cars.id_models = Models.model_id
          JOIN Brands ON Models.brand_id = Brands.id_brand
          WHERE Cars.id_cars = ?";
$statement = $pdo->prepare($query);
$statement->execute([$id_cars]);
$car = $statement->fetch(PDO::FETCH_ASSOC);

// Проверяем, найден ли автомобиль с заданным id
if (!$car) {
    // Если автомобиль не найден, перенаправляем на страницу каталога
    header('Location: /rcr/pages/catalog/index.php');
    exit();
}

// Запрос для получения отзывов для текущего автомобиля
$reviewsQuery = "SELECT Reviews.*, Users.username FROM Reviews
                 JOIN Users ON Reviews.Id_user = Users.Id_user
                 WHERE Reviews.Id_car = ?";
$reviewsStatement = $pdo->prepare($reviewsQuery);
$reviewsStatement->execute([$id_cars]);
$reviews = $reviewsStatement->fetchAll(PDO::FETCH_ASSOC);

// Получаем текущую дату
$currentDate = date('Y-m-d');

// Подключаем шапку страницы
$title = $car['brand_name'] . ' ' . $car['model_name'];
include('../../layout/header.php');
?>

<section class="section container" style="display: flex; justify-content: center">
    <div class="sectionCar">
        <div class="sectionSubTitle"><a href="/rcr/pages/catalog/index.php" class="catalogLink">Вернуться в каталог</a></div>
        <div class="sectionTitle">
            <?php echo $car['brand_name']; ?> <span class="color-gold"><?php echo $car['model_name']; ?></span>
        </div>
        <div class="sectionCarContainer">
            <div class="carContainerImage">
                <img src="/rcr/public/cars/<?php echo $car['photo']; ?>" class="carImg" alt="Photo">
            </div>
            <div class="carContainerDescription">
                <div class="descriptionHeader">
                    <div class="containerDescriptionTitle">
                        <?php echo $car['brand_name']; ?> <?php echo $car['model_name']; ?>
                    </div>
                    <div class="headerRating">
                        <?php
                        if ($car['average_rating'] !== null) {
                            echo '<img src="../../assets/media/icon/icons8-star-96.png" alt="icon" width="30">';
                            echo '<span class="containerDescriptionTitle color-gold">' . round($car['average_rating'], 1) . '</span>';
                        } else {
                            echo "<div class='descriptionDataText'>нет отзывов</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="descriptionBody">
                    <div class="descriptionBodyData">
                        <div class="descriptionData">
                            <span class="descriptionDataText">Год выпуска: </span> <?php echo $car['year']; ?>
                        </div>
                        <div class="descriptionData">
                            <span class="descriptionDataText">Коробка передач: </span> <?php echo $car['type']; ?>
                        </div>
                        <div class="descriptionData">
                            <span class="descriptionDataText">Объем бака: </span> <?php echo $car['volume']; ?> л
                        </div>
                        <div class="descriptionData">
                            <span class="descriptionDataText">Мощность двигателя: </span> <?php echo $car['power']; ?> л.с.
                        </div>
                    </div>
                    <div class="descriptionBodyData">
                        <div class="descriptionData">
                            <span class="descriptionDataText">Цвет: </span> <?php echo $car['color']; ?>
                        </div>
                        <div class="descriptionData">
                            <span class="descriptionDataText">Трансмиссия: </span> <?php echo $car['drive_unit']; ?> привод
                        </div>
                        <div class="descriptionDataPrice color-gold">
                            <?php echo $car['price']; ?> ₽
                            <span class="descriptionDataText">/ Сутки</span>
                        </div>
                    </div>
                </div>
                <div class="calculator">
                    <div class="containerDescriptionTitle">Рассчитать стоимость</div>
                    <div class="calculatorWrapper">
                        <div class="calculatorInputWrapper">
                            <label for="start_date" class="calculatorLabel">Начало:</label>
                            <input type="date" id="start_date" name="start_date" class="calculatorInput" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        <div style="color: #ffffffbf;">&minus;</div>
                        <div class="calculatorInputWrapper">
                            <label for="end_date" class="calculatorLabel">Окончание:</label>
                            <input type="date" id="end_date" name="end_date" class="calculatorInput" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                    </div>
                    <div class="descriptionDataText">Итоговая стоимость аренды: <span id="total_cost" class="totalPrice color-gold">0 ₽</span></div>
                    <div class="calculatorButton">
                        <?php if (!$auth): ?>
                            <a href="#" class="mainButton cButton" data-bs-toggle="modal" data-bs-target="#exampleModal">Забронировать</a>
                        <?php else: ?>
                            <a href="/rcr/pages/application/index.php?id_cars=<?php echo $id_cars; ?>" class="mainButton cButton">Забронировать</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="sectionCarReviews">
            <div class="reviewsButton">
                <button class="mainButton cButton" onclick="showReviewForm()">Добавить отзыв</button>
            </div>
            <?php if ($auth): ?>
                <div id="reviewForm" style="display: none;">
                    <form id="reviewFormElement" class="reviewFormElement">
                        <input type="hidden" name="id_cars" value="<?php echo $id_cars; ?>">
                        <div class="formGroup">
                            <label for="reviewText" class="reviewsText">Ваш отзыв:</label>
                            <textarea id="reviewText" name="reviewText" class="reviewFormInput" rows="4">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="reviewGrade" class="reviewsText">Оценка:</label>
                            <select id="reviewGrade" name="reviewGrade" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <button type="submit" class="mainButton cButton">Сохранить</button>
                    </form>
                </div>
            <?php endif; ?>
            <?php if (!empty($reviews)): ?>
                <div class="sectionCarReviewsTitle">Отзывы:</div>
                <div id="reviewsContainer" class="reviewsContainer">
                    <?php foreach ($reviews as $review): ?>
                        <div class="reviewsCard">
                            <div class="reviewsUsername">
                                <img src="../../assets/media/icon/icons8-user-64.png" alt="icon" width="40">
                                <?php echo $review['username']; ?>
                            </div>
                            <div class="reviewsText">
                                <?php echo $review['Text_reviews']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    // Функция для вычисления стоимости аренды
    function calculateRent() {
        var startDateValue = document.getElementById('start_date').value;
        var endDateValue = document.getElementById('end_date').value;

        if (!startDateValue || !endDateValue) {
            document.getElementById('total_cost').innerText = "0 ₽";
            return;
        }

        var startDate = new Date(startDateValue);
        var endDate = new Date(endDateValue);

        if (startDate >= endDate) {
            document.getElementById('total_cost').innerText = "0 ₽";
            return;
        }

        var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var rentPricePerDay = <?php echo $car['price']; ?>;
        var totalCost = rentPricePerDay * diffDays;

        document.getElementById('total_cost').innerText = totalCost + " ₽";
    }

    document.getElementById('start_date').addEventListener('change', calculateRent);
    document.getElementById('end_date').addEventListener('change', calculateRent);

    window.onload = function() {
        calculateRent();
    };

    // Функция для показа формы добавления отзыва
    function showReviewForm() {
        document.getElementById('reviewForm').style.display = 'block';
    }

    // Обработка отправки формы добавления отзыва
    document.getElementById('reviewFormElement').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        fetch('add_review.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var newReview = document.createElement('div');
                    newReview.classList.add('reviewsCard');
                    newReview.innerHTML = `
                    <div class="reviewsUsername">
                        <img src="../../assets/media/icon/icons8-user-64.png" alt="icon" width="40">
                        ${data.username}
                    </div>
                    <div class="reviewsText">
                        ${data.reviewText}
                    </div>
                `;
                    document.getElementById('reviewsContainer').prepend(newReview);
                    document.getElementById('reviewForm').style.display = 'none';
                    document.getElementById('reviewText').value = '';
                    document.getElementById('reviewGrade').value = '1';
                } else {
                    alert('Ошибка при добавлении отзыва. Попробуйте еще раз.');
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<?php
include('../../layout/footer.php');
?>
