<?php
$title = "Автопарк";
include('../../layout/header.php');
require_once('../../utils/connect/connectToDataBase.php');

// Подключаемся к базе данных
$pdo = connectToDataBase();

// Получаем максимальную цену для плейсхолдера
$priceQuery = "SELECT MAX(Price) as MaxPrice FROM Cars";
$priceStatement = $pdo->prepare($priceQuery);
$priceStatement->execute();
$maxPrice = $priceStatement->fetch(PDO::FETCH_ASSOC)['MaxPrice'];

// Базовый запрос
$query = "SELECT Cars.*, Models.model_name, Brands.brand_name
          FROM Cars
          JOIN Models ON Cars.id_models = Models.model_id
          JOIN Brands ON Models.brand_id = Brands.id_brand";

$conditions = [];
$params = [];

// Проверяем, есть ли параметр цены в GET-запросе
if (isset($_GET['price']) && is_numeric($_GET['price'])) {
    $price = floatval($_GET['price']);
    $conditions[] = "Cars.price <= :price";
    $params[':price'] = $price;
}

// Проверяем, есть ли параметр цвета в GET-запросе
if (isset($_GET['color']) && !empty($_GET['color'])) {
    $color = $_GET['color'];
    $conditions[] = "Cars.color = :color";
    $params[':color'] = $color;
}

// Проверяем, есть ли параметр бренда в GET-запросе
if (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $brand = $_GET['brand'];
    $conditions[] = "Brands.brand_name = :brand";
    $params[':brand'] = $brand;
}

// Проверяем, есть ли параметр модели в GET-запросе
if (isset($_GET['model']) && !empty($_GET['model'])) {
    $model = $_GET['model'];
    $conditions[] = "Models.model_name = :model";
    $params[':model'] = $model;
}

// Если есть условия, добавляем их в запрос
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Добавляем сортировку по цене
$query .= " ORDER BY Cars.price";

// Подготавливаем и выполняем запрос
$statement = $pdo->prepare($query);

// Привязываем параметры
foreach ($params as $key => $value) {
    $statement->bindValue($key, $value);
}

$statement->execute();
$cars = $statement->fetchAll(PDO::FETCH_ASSOC);

// Пагинация
$itemsPerPage = 6;
$totalCars = count($cars);
$totalPages = ceil($totalCars / $itemsPerPage);
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $itemsPerPage;
$cars = array_slice($cars, $offset, $itemsPerPage);

// Получаем уникальные цвета для фильтра
$colorsQuery = "SELECT DISTINCT color FROM Cars";
$colorsStatement = $pdo->prepare($colorsQuery);
$colorsStatement->execute();
$colors = $colorsStatement->fetchAll(PDO::FETCH_COLUMN);

// Получаем уникальные бренды для фильтра
$brandsQuery = "SELECT DISTINCT brand_name FROM Brands";
$brandsStatement = $pdo->prepare($brandsQuery);
$brandsStatement->execute();
$brands = $brandsStatement->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="section container">
    <div class="sectionCatalog">
        <form class="catalogFiltersContainer" id="searchForm" method="GET">
            <input type="number" id="priceInput" name="price" placeholder="до <?= $maxPrice ?> ₽" value="<?= isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '' ?>" class="formInput">
            <select id="colorSelect" name="color" class="formInput">
                <option value="">Выберите цвет</option>
                <?php foreach ($colors as $color): ?>
                    <option value="<?= htmlspecialchars($color) ?>" <?= isset($_GET['color']) && $_GET['color'] == $color ? 'selected' : '' ?>><?= htmlspecialchars($color) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="brandSelect" name="brand" class="formInput">
                <option value="">Выберите бренд</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand) ?>" <?= isset($_GET['brand']) && $_GET['brand'] == $brand ? 'selected' : '' ?>><?= htmlspecialchars($brand) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="modelSelect" name="model" class="formInput" style="display:none;">
                <option value="">Выберите модель</option>
            </select>
        </form>

        <div style="display: flex; flex-direction: column; align-items: end; gap: 10px; width: 100%;">

            <div id="sortPrice" class="sortPrice" style="cursor: pointer;">Сортировать по цене</div>

            <div id="noResultsMessage" style="display: <?= empty($cars) ? 'block' : 'none'; ?>;" class="warningMarker">
                Нет результатов по данному запросу
            </div>

            <div class="cardContainer" id="cardContainer">
                <?php foreach ($cars as $car): ?>
                    <a href="/rcr/pages/catalogItem/index.php?id_cars=<?php echo $car['id_cars']; ?>" class="cardLink" data-price="<?= $car['price'] ?>" data-color="<?= htmlspecialchars($car['color']) ?>" data-brand="<?= htmlspecialchars($car['brand_name']) ?>" data-model="<?= htmlspecialchars($car['model_name']) ?>">
                        <div class="card">
                            <img src="/rcr/public/cars/<?php echo $car['photo']; ?>" class="card-img-top" alt="Car Photo">
                            <div class="cardText">
                                <div class="cardTextBrand"><?php echo $car['brand_name']; ?></div>
                                <div class="cardTextModel"><?php echo $car['model_name']; ?></div>
                            </div>
                            <div class="cardPrice">
                                <div class="cardPriceText">
                                    <span class="priceText"><?php echo $car['price']; ?> ₽</span> / Сутки
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Пагинация -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>


        </div>
    </div>
</section>

<?php
include('../../layout/footer.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('priceInput');
        const colorSelect = document.getElementById('colorSelect');
        const brandSelect = document.getElementById('brandSelect');
        const modelSelect = document.getElementById('modelSelect');
        const cardContainer = document.getElementById('cardContainer');
        const catalogCards = Array.from(cardContainer.getElementsByClassName('cardLink'));
        const noResultsMessage = document.getElementById('noResultsMessage');
        const sortPrice = document.getElementById('sortPrice');

        let sortDirection = null;

        brandSelect.addEventListener('change', function() {
            const brand = brandSelect.value;
            if (brand) {
                fetch(`/utils/getModels.php?brand=${brand}`)
                    .then(response => response.json())
                    .then(models => {
                        modelSelect.innerHTML = '<option value="">Выберите модель</option>';
                        models.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model.model_name;
                            option.textContent = model.model_name;
                            modelSelect.appendChild(option);
                        });
                        modelSelect.style.display = 'block';
                    });
            } else {
                modelSelect.style.display = 'none';
            }
            filterCards();
        });

        function filterCards() {
            const maxPrice = parseFloat(priceInput.value) || <?= $maxPrice ?>;
            const selectedColor = colorSelect.value;
            const selectedBrand = brandSelect.value;
            const selectedModel = modelSelect.value;

            let matchesFound = false;

            for (let card of catalogCards) {
                const cardPrice = parseFloat(card.getAttribute('data-price'));
                const cardColor = card.getAttribute('data-color');
                const cardBrand = card.getAttribute('data-brand');
                const cardModel = card.getAttribute('data-model');

                const priceMatches = cardPrice <= maxPrice;
                const colorMatches = selectedColor === '' || cardColor === selectedColor;
                const brandMatches = selectedBrand === '' || cardBrand === selectedBrand;
                const modelMatches = selectedModel === '' || cardModel === selectedModel;

                if (priceMatches && colorMatches && brandMatches && modelMatches) {
                    card.style.display = '';
                    matchesFound = true;
                } else {
                    card.style.display = 'none';
                }
            }

            noResultsMessage.style.display = matchesFound ? 'none' : 'block';

            if (sortDirection) {
                sortCards(sortDirection);
            }
        }

        function sortCards(direction) {
            const sortedCards = catalogCards.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));

                if (direction === 'asc') {
                    return priceA - priceB;
                } else {
                    return priceB - priceA;
                }
            });

            cardContainer.innerHTML = '';
            sortedCards.forEach(card => cardContainer.appendChild(card));
        }

        priceInput.addEventListener('input', filterCards);
        colorSelect.addEventListener('change', filterCards);
        modelSelect.addEventListener('change', filterCards);

        sortPrice.addEventListener('click', function() {
            if (sortDirection === null) {
                sortDirection = 'desc';
                sortPrice.innerText = 'По убыванию цены';
            } else if (sortDirection === 'desc') {
                sortDirection = 'asc';
                sortPrice.innerText = 'По возрастанию цены';
            } else {
                sortDirection = null;
                sortPrice.innerText = 'Сортировать по цене';
            }
            filterCards();
        });

        filterCards();
    });
</script>
