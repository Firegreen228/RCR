<?php
require_once (__DIR__ . '/../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Запрос на получение списка брендов
$sqlBrands = "SELECT id_brand, brand_name FROM Brands";
$stmtBrands = $pdo->query($sqlBrands);
$brands = $stmtBrands->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT Cars.id_cars, Models.model_name, Brands.brand_name, Cars.number
            FROM Cars 
            INNER JOIN Models ON Cars.id_models = Models.model_id 
            INNER JOIN Brands ON Models.brand_id = Brands.id_brand";
$stmt = $pdo->query($sql);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="cardButtonWrapper">
    <button class="cardButton addCarButton" onclick="addCar()">Добавить автомобиль</button>
</div>
<div class="container">
    <ul class="responsive-table">
        <li class="table-header">
            <div class="col col-1">Код</div>
            <div class="col col-2">Бренд</div>
            <div class="col col-3">Модель</div>
            <div class="col col-4">Номер</div>
            <div class="col col-4"></div>
        </li>
        <?php foreach ($cars as $car): ?>
            <li class="table-row">
                <div class="col col-1" ><?php echo $car['id_cars']; ?></div>
                <div class="col col-2" ><?php echo $car['brand_name']; ?></div>
                <div class="col col-3" ><?php echo $car['model_name']; ?></div>
                <div class="col col-4" ><?php echo $car['number']; ?></div>
                <div class="col col-4"  onclick="deleteCar(<?php echo $car['id_cars']; ?>)">
                    <span class="deleteButton"><img src="/assets/media/icons8-close.svg" alt="icon" width="10">Удалить</span>
                </div>
            </li>
        <?php endforeach; ?>

    </ul>
</div>

<section class="addCarSection" style="display: none;">
    <div class="cardButtonWrapper">
        <button type="button" onclick="backToTable()" class="cancellationButton backButton">Назад</button>
    </div>
    <form action="/pages/section/function/add_car.php" method="POST" class="addCarForm" enctype="multipart/form-data">
        <div class="addCarWrapper">
            <select name="brand" id="brand" onchange="loadModels(this.value)" required class="statusFilterSelect carFormInputWrapper">
                <option value="">Выберите бренд</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo $brand['id_brand']; ?>" class="statusFilterItem"><?php echo $brand['brand_name']; ?></option>
                    <?php endforeach; ?>
                </select>

            <label for="model" id="modelLabel" style="display: none;"></label>
                <select name="model" id="model" style="display: none;" required class="statusFilterSelect carFormInputWrapper">
            </select>

            <label for="number" class="carFormLabel">Год выпуска:</label>
            <input type="text" name="year" id="year" required class="statusFilterSelect carFormInputWrapper" placeholder="_._._._г">

            <label for="type" class="carFormLabel">Коробка передач:</label>
            <select name="type" id="type" required class="statusFilterSelect carFormInputWrapper">
                <option value="Автомат" class="statusFilterItem">Автомат</option>
                <option value="Механика" class="statusFilterItem">Механика</option>
            </select>

            <label for="volume" class="carFormLabel">Объем двигателя:</label>
            <input type="text" name="volume" id="volume" required class="statusFilterSelect carFormInputWrapper" placeholder="_._л">

            <label for="power" class="carFormLabel">Мощность:</label>
            <input type="text" name="power" id="power" required class="statusFilterSelect carFormInputWrapper">

            <label for="mileage" class="carFormLabel">Пробег:</label>
            <input type="text" name="mileage" id="mileage" required class="statusFilterSelect carFormInputWrapper">

            <label for="drive_unit" class="carFormLabel">Привод:</label>
            <select name="drive_unit" id="drive_unit" required class="statusFilterSelect carFormInputWrapper">
                <option value="Полный" class="statusFilterItem">Полный</option>
                <option value="Задний" class="statusFilterItem">Задний</option>
                <option value="Передний" class="statusFilterItem">Передний</option>
            </select>

            <label for="quantity_place" class="carFormLabel">Количесвто мест:</label>
            <input type="text" name="quantity_place" id="quantity_place" required class="statusFilterSelect carFormInputWrapper">

            <label for="color" class="carFormLabel">Цвет:</label>
            <input type="text" name="color" id="color" required class="statusFilterSelect carFormInputWrapper">

            <label for="number" class="carFormLabel">Номер:</label>
            <input type="text" name="number" id="number" required class="statusFilterSelect carFormInputWrapper" placeholder="#-###-##">

            <label for="price" class="carFormLabel">Цена:</label>
            <input type="text" name="price" id="price" required class="statusFilterSelect carFormInputWrapper">

            <input type="file" name="photo" id="photo" accept="image/*" required class="addCarButtonPhoto">

        </div>
        <input type="submit" value="Добавить автомобиль" class="cancellationButton buttonCarForm">
    </form>
</section>

<script>
    function loadModels(brandId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/utils/get_models.php?brandId=' + brandId, true);
        xhr.send();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var models = JSON.parse(xhr.responseText);
                    var modelSelect = document.getElementById('model');
                    var modelLabel = document.getElementById('modelLabel');

                    // Показываем поле выбора модели
                    modelSelect.style.display = 'block';
                    modelLabel.style.display = 'block';

                    modelSelect.innerHTML = '<option value="">Модель</option>';
                    models.forEach(function(model) {
                        var option = document.createElement('option');
                        option.value = model.model_id;
                        option.textContent = model.model_name;
                        modelSelect.appendChild(option);
                    });
                } else {
                    console.error('Ошибка загрузки моделей');
                }
            }
        };
    }
</script>


<script>
    function deleteCar(carId) {
        if (confirm('Вы уверены, что хотите удалить этот автомобиль?')) {
            // Создаем объект XMLHttpRequest
            var xhr = new XMLHttpRequest();
            // Настраиваем запрос
            xhr.open('POST', '/pages/section/function/delete_car.php', true);
            // Устанавливаем заголовок Content-Type
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            // Отправляем запрос
            xhr.send('carId=' + carId);
            // Обрабатываем ответ
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Успешно удалено
                        // Удаляем соответствующую строку из таблицы без перезагрузки страницы
                        var rowToDelete = document.querySelector('.col-1[data-car-id="' + carId + '"]').parentNode;
                        rowToDelete.parentNode.removeChild(rowToDelete);
                    } else {
                        // Ошибка при удалении
                        alert('Ошибка при удалении автомобиля.');
                    }
                }
            };
        }
    }

    function addCar() {
        // Показываем секцию редактирования и скрываем другие элементы
        document.querySelector('.container').style.display = 'none';
        document.querySelector('.addCarSection').style.display = 'flex';
    }
    function backToTable() {
        // Скрываем секцию редактирования и показываем карточки автомобилей
        document.querySelector('.addCarSection').style.display = 'none';
        document.querySelector('.container').style.display = 'block';
    }
</script>
