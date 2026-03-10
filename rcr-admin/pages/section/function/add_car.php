<?php
// Подключение к базе данных
require_once (__DIR__ . '/../../../utils/connectToDataBase.php');
$pdo = connectToDataBase();

// Получение данных из формы
$brand = $_POST['brand'];
$model = $_POST['model'];
$year = $_POST['year'];
$type = $_POST['type'];
$volume = $_POST['volume'];
$power = $_POST['power'];
$mileage = $_POST['mileage'];
$drive_unit = $_POST['drive_unit'];
$quantity_place = $_POST['quantity_place'];
$color = $_POST['color'];
$number = $_POST['number'];
$price = $_POST['price'];

// Обработка загруженного файла
$targetDir = "C:/openserver/ospanel/domains/rcr-admin/public/cars/";
$fileName = basename($_FILES["photo"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

// Проверка, является ли файл изображением
$allowTypes = array('jpg','png','jpeg','gif');
if(in_array($fileType, $allowTypes)){
    // Переместите файл в указанную папку
    if(move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)){
        // Здесь вы можете выполнить дополнительные действия, если загрузка прошла успешно
        // Например, сохранить только название файла с его разрешением
        $photoFileName = $fileName;

        // Копирование файла в другую папку
        $additionalDir = "C:/openserver/ospanel/domains/rcr/public/cars/";
        if(!copy($targetFilePath, $additionalDir . $fileName)){
            echo "Ошибка при копировании файла в дополнительную папку.";
        }

        // Вставка данных в базу данных
        $sql = "INSERT INTO Cars (id_models, year, type, volume, power, mileage, drive_unit, quantity_place, color, number, price, photo) VALUES (:model, :year, :type, :volume, :power, :mileage, :drive_unit, :quantity_place, :color, :number, :price, :photo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':volume', $volume);
        $stmt->bindParam(':power', $power);
        $stmt->bindParam(':mileage', $mileage);
        $stmt->bindParam(':drive_unit', $drive_unit);
        $stmt->bindParam(':quantity_place', $quantity_place);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':number', $number);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photo', $photoFileName);
        $stmt->execute();

        // Перенаправление обратно на страницу с автомобилями
        header("Location: /pages/index.php");
        exit();
    }else{
        echo "Произошла ошибка при загрузке файла.";
    }
}else{
    echo 'Извините, разрешены только JPG, JPEG, PNG и GIF файлы.';
}
?>
