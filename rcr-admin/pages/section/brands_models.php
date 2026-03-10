<?php
require_once (__DIR__ . '/../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Обработка формы добавления модели
if (isset($_POST['addModel'])) {
    $modelName = $_POST['model_name'];
    $brandId = $_POST['brand_id'];
    $sql = "INSERT INTO Models (model_name, brand_id) VALUES (:model_name, :brand_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':model_name', $modelName);
    $stmt->bindParam(':brand_id', $brandId);
    $stmt->execute();
}

// Получение данных о брендах и моделях
$sql = "SELECT Brands.id_brand, Brands.brand_name, Models.model_name 
        FROM Brands 
        LEFT JOIN Models ON Brands.id_brand = Models.brand_id 
        ORDER BY Brands.brand_name, Models.model_name";
$stmt = $pdo->query($sql);
$brandsAndModels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>

    <div class="container">
        <ul class="responsive-table">
            <li class="table-header">
                <div class="col col-1">Бренд</div>
                <div class="col col-2">Модель</div>
            </li>

                    <?php
                    $currentBrand = null;
                    foreach ($brandsAndModels as $row) {
                        if ($row['brand_name'] !== $currentBrand) {
                            $currentBrand = $row['brand_name'];
                            echo "<li class='table-row'><div class='col col-1' >{$row['brand_name']}</div><div class='col col-2'>{$row['model_name']}</div></li>";
                        } else {
                            echo "<li class='table-row'><div class='col col-1' >{$row['brand_name']}</div><div class='col col-2' >{$row['model_name']}</div></li>";
                        }
                    }
                    ?>

        </ul>
    </div>

    <div class="contentMainTitle">Добавить новую модель</div>
    <form method="post" action="">
        <label for="brand_id" class="contentSubTitle">Бренд:</label>
        <select id="brand_id" name="brand_id" class="statusFilterSelect carFormInputWrapper" required>
            <?php
            $brands = $pdo->query("SELECT id_brand, brand_name FROM Brands ORDER BY brand_name")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($brands as $brand) {
                echo "<option value=\"{$brand['id_brand']}\">{$brand['brand_name']}</option>";
            }
            ?>
        </select>
        <label for="model_name" class="contentSubTitle" class="statusFilterSelect carFormInputWrapper">Название модели:</label>
        <input type="text" id="model_name" name="model_name"  required>
        <input type="submit" name="addModel" value="Добавить модель" class="cardButton">
    </form>
</section>
