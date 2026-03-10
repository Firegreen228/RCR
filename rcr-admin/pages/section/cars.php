<?php
require_once (__DIR__ . '/../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

$sql = "SELECT Cars.id_cars, Models.model_name, Brands.brand_name, Cars.year, Cars.type, Cars.volume, Cars.power, Cars.mileage, Cars.color, Cars.number, Cars.price, Cars.photo, Cars.drive_unit 
            FROM Cars 
            INNER JOIN Models ON Cars.id_models = Models.model_id 
            INNER JOIN Brands ON Models.brand_id = Brands.id_brand";
$stmt = $pdo->query($sql);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="filterWrapper">
    <div class="searchWrapper">
        <input type="text" id="carSearchInput" placeholder="Поиск" class="searchInput">
    </div>
</div>

<div class="contentCarsCards">
    <?php foreach ($cars as $car): ?>
        <div class="carCard" id="car-<?php echo $car['id_cars']; ?>">
            <div class="carCarsPhoto">
                <img class="carCarsPhoto" src="/public/cars/<?php echo $car['photo']; ?>" alt="<?php echo $car['brand_name'] . ' ' . $car['model_name']; ?>" width="300" height="225">
            </div>
            <div class="carCardDescription">
                <div class="carCardTitle">
                    <?php echo $car['brand_name'] . ' ' . $car['model_name']; ?>
                    <span>(<?php echo $car['id_cars']; ?>)</span>
                </div>
                <div class="carCardDate">
                    <div class="carCardDateMenu">
                        <div class="cardText">Год выпуска:
                            <span class="cardData"><?php echo $car['year']; ?></span>
                        </div>
                        <div class="cardText">Тип трансмиссии:
                            <span class="cardData"><?php echo $car['type']; ?></span>
                        </div>
                        <div class="cardText">Объем двигателя:
                            <span class="cardData"><?php echo $car['volume']; ?> л</span>
                        </div>
                        <div class="cardText">Мощность:
                            <span class="cardData"><?php echo $car['power']; ?> л.с.</span>
                        </div>
                    </div>
                    <div class="carCardDateMenu">
                        <div class="cardText">Пробег:
                            <span class="cardData"><?php echo $car['mileage']; ?> км</span>
                        </div>
                        <div class="cardText">Цвет:
                            <span class="cardData"><?php echo $car['color']; ?></span>
                        </div>
                        <div class="cardText">Номер:
                            <span class="cardData сarNumber"><?php echo $car['number']; ?></span>
                        </div>
                        <div class="cardText">Цена:
                            <span class="cardData"><?php echo $car['price']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="cardButtonWrapper">
                    <button class="cardButton" onclick="editCar(<?php echo $car['id_cars']; ?>)">Редактировать</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<section class="editCarSection" style="display: none;">
    <div class="carCardTitle" id="editCarTitle"></div>
    <div id="updateCarForm">
        <form action="/pages/section/function/update_cars.php" method="POST" id="editCarForm">
            <!-- Скрытое поле для хранения ID автомобиля -->
            <input type="hidden" name="carId" id="editCarId" value="">
            <!-- Поля для редактирования -->
<div class="carForm">
            <div class="carFormInput">
             <label for="year" class="cardText">Год выпуска:</label>
             <input type="text" name="year" id="editYear" value="" class="formInput">
            </div>

            <div class="carFormInput">
            <label for="mileage" class="cardText">Пробег (км):</label>
            <input type="text" name="mileage" id="editMileage" value="" class="formInput">
            </div>

            <div class="carFormInput">
            <label for="color" class="cardText">Цвет:</label>
            <input type="text" name="color" id="editColor" value="" class="formInput">
            </div>

            <div class="carFormInput">
            <label for="number" class="cardText">Номер:</label>
            <input type="text" name="number" id="editNumber" value="" class="formInput">
            </div>

            <div class="carFormInput">
            <label for="price" class="cardText">Цена:</label>
            <input type="text" name="price" id="editPrice" value="" class="formInput">
            </div>
</div>
            <!-- Добавьте другие поля, которые нужно отредактировать -->
            <input type="submit" value="Сохранить изменения" class="cardButton">
            <button type="button" onclick="backToCars()" class="cancellationButton backButton">Назад</button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carSearchInput = document.getElementById('carSearchInput');
        var carCards = document.querySelectorAll('.carCard');

        carSearchInput.addEventListener('input', searchCars);

        function searchCars() {
            var searchTerm = carSearchInput.value.trim().toLowerCase();
            carCards.forEach(function (carCard) {
                var carId = carCard.id.split('-')[1]; // Получаем идентификатор автомобиля из ID карточки
                var brandName = carCard.querySelector('.carCardTitle').textContent.toLowerCase();
                var modelName = carCard.querySelector('.carCardTitle').textContent.toLowerCase();
                var carNumber = carCard.querySelector('.сarNumber').textContent.toLowerCase();

                if (carId.includes(searchTerm) || brandName.includes(searchTerm) || modelName.includes(searchTerm) || carNumber.includes(searchTerm)) {
                    carCard.style.display = 'flex';
                } else {
                    carCard.style.display = 'none';
                }
            });
        }
    });

    function editCar(carId) {
        // Находим данные автомобиля по его ID
        var car = <?php echo json_encode($cars); ?>;
        var editCar = car.find(function(item) {
            return item.id_cars === carId;
        });

        // Заполняем форму данными выбранного автомобиля
        document.getElementById('editCarId').value = editCar.id_cars;
        document.getElementById('editYear').value = editCar.year;
        document.getElementById('editMileage').value = editCar.mileage;
        document.getElementById('editColor').value = editCar.color;
        document.getElementById('editNumber').value = editCar.number;
        document.getElementById('editPrice').value = editCar.price;
        // Заполняем заголовок редактируемого автомобиля
        document.getElementById('editCarTitle').innerText = editCar.brand_name + ' ' + editCar.model_name + ' (' + editCar.id_cars + ')';

        // Показываем секцию редактирования и скрываем другие элементы
        document.querySelector('.contentCarsCards').style.display = 'none';
        document.querySelector('.editCarSection').style.display = 'flex';
    }
    function backToCars() {
        // Скрываем секцию редактирования и показываем карточки автомобилей
        document.querySelector('.editCarSection').style.display = 'none';
        document.querySelector('.contentCarsCards').style.display = 'flex';
    }
</script>
