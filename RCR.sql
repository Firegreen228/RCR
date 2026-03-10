-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 10 2026 г., 20:56
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `RCR`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Additional_Services`
--

CREATE TABLE `Additional_Services` (
  `Id` int NOT NULL,
  `Id_contract` int NOT NULL,
  `Id_rates` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Brands`
--

CREATE TABLE `Brands` (
  `id_brand` int NOT NULL,
  `brand_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Brands`
--

INSERT INTO `Brands` (`id_brand`, `brand_name`) VALUES
(1, 'Audi'),
(2, 'BMW'),
(3, 'Mercedes-Benz'),
(4, 'Toyota'),
(5, 'Honda'),
(6, 'Ford');

-- --------------------------------------------------------

--
-- Структура таблицы `Canceled_Applications`
--

CREATE TABLE `Canceled_Applications` (
  `Id` int NOT NULL,
  `Id_contract` int NOT NULL,
  `Rejection_Reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Canceled_Applications`
--

INSERT INTO `Canceled_Applications` (`Id`, `Id_contract`, `Rejection_Reason`) VALUES
(1, 3, 'Ошибся при выборе даты'),
(2, 10, 'Нет водительских прав'),
(3, 6, 'Проверяю'),
(5, 2, 'Нет прав '),
(6, 3, 'Не понравилось'),
(7, 4, 'Проверяю'),
(8, 8, 'Проверяю');

-- --------------------------------------------------------

--
-- Структура таблицы `Cars`
--

CREATE TABLE `Cars` (
  `id_cars` int NOT NULL,
  `id_models` int NOT NULL,
  `year` int NOT NULL,
  `type` varchar(50) NOT NULL,
  `volume` int NOT NULL,
  `power` int NOT NULL,
  `mileage` int NOT NULL,
  `drive_unit` varchar(50) NOT NULL,
  `quantity_place` int NOT NULL,
  `color` text NOT NULL,
  `number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` int NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Cars`
--

INSERT INTO `Cars` (`id_cars`, `id_models`, `year`, `type`, `volume`, `power`, `mileage`, `drive_unit`, `quantity_place`, `color`, `number`, `price`, `photo`) VALUES
(1, 8, 2023, 'Автомат', 63, 317, 91344, 'Полный', 5, 'Белый', 'MMF4644', 6300, 'x1.png'),
(2, 2, 2021, 'Автомат', 94, 530, 133000, 'Полный', 5, 'Чёрный', 'X5M50D', 6600, 'x5.png'),
(3, 7, 2021, 'Автомат', 85, 530, 70000, 'Полный', 5, 'Чёрный', 'MXM6060', 6999, 'x6.png'),
(4, 1, 2022, 'Автомат', 70, 286, 133304, 'Полный', 5, 'Белый', 'JMJ190', 5500, 'a4.png'),
(5, 9, 2023, 'Автомат', 85, 507, 230334, 'Полный', 5, 'Чёрный', 'AEF435', 6669, 'sq8.png'),
(6, 10, 2022, 'Автомат', 64, 245, 90454, 'Передний', 5, 'Коричневый', 'OV6IDXD', 7845, 'q3.png'),
(7, 3, 2020, 'Автомат', 67, 333, 445000, 'Задний', 5, 'Серый', 'SCK7102', 6700, 'C.png'),
(8, 11, 2023, 'Автомат', 93, 612, 222346, 'Полный', 5, 'Серая', 'SEG6304', 7888, 'GLE.png'),
(9, 12, 2023, 'Автомат', 80, 802, 30000, 'Полный', 5, 'Жёлтый', '2ZPT72', 9999, 'amggt.png'),
(10, 4, 2023, 'Механика', 58, 304, 499786, 'Передний', 5, 'Белый', '13COR001', 3300, 'corola.png'),
(12, 14, 2022, 'Автомат', 130, 415, 666666, 'Полный', 5, 'Белый', '8NB059', 3000, 'Land.png'),
(13, 5, 2012, 'Автомат', 55, 240, 43333, 'Передний', 5, 'Синий', 'BH2844IX', 3452, 'civic.png'),
(14, 15, 2023, 'Автомат', 53, 261, 23233, 'Передний', 5, 'Белый', 'BRE029', 3400, 'urv.png'),
(15, 16, 2022, 'Вариатор', 53, 182, 233334, 'Полный', 5, 'Бардовый', 'BPB025', 4444, 'zrv.png'),
(16, 6, 2009, 'Автомат', 76, 760, 2000, 'Задний', 2, 'Красный', 'Т419ВВ', 11111, 'mustang.png'),
(17, 18, 2012, 'Механика', 45, 101, 678995, 'Передний', 5, 'Серебристый', 'KFU1320', 3333, 'fusin.png'),
(18, 17, 2020, 'Автомат', 55, 400, 200321, 'Полный', 3, 'Чёрный', '2452KV', 7780, 'F150.png');

-- --------------------------------------------------------

--
-- Структура таблицы `Drivers_license`
--

CREATE TABLE `Drivers_license` (
  `Id_drivers_license` int NOT NULL,
  `Id_user` int NOT NULL,
  `Series_drivers_license` int NOT NULL,
  `Number_drivers_license` int NOT NULL,
  `Date_of_issue` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Drivers_license`
--

INSERT INTO `Drivers_license` (`Id_drivers_license`, `Id_user`, `Series_drivers_license`, `Number_drivers_license`, `Date_of_issue`) VALUES
(1, 5, 7701, 397000, '2023-06-11');

-- --------------------------------------------------------

--
-- Структура таблицы `Lease_contract`
--

CREATE TABLE `Lease_contract` (
  `Id` int NOT NULL,
  `Id_user` int NOT NULL,
  `Id_car` int NOT NULL,
  `Start_of_rental` date NOT NULL,
  `End_of_ease` date NOT NULL,
  `Total_price` int NOT NULL,
  `Status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Lease_contract`
--

INSERT INTO `Lease_contract` (`Id`, `Id_user`, `Id_car`, `Start_of_rental`, `End_of_ease`, `Total_price`, `Status`) VALUES
(2, 35, 12, '2024-05-01', '2024-05-02', 14400, 'canceled'),
(3, 25, 10, '2024-06-01', '2024-07-01', 117000, 'canceled'),
(4, 25, 16, '2024-06-01', '2024-07-01', 345330, 'canceled'),
(10, 37, 14, '2026-01-29', '2026-02-01', 11700, 'Active');

-- --------------------------------------------------------

--
-- Структура таблицы `Models`
--

CREATE TABLE `Models` (
  `model_id` int NOT NULL,
  `model_name` varchar(50) NOT NULL,
  `brand_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Models`
--

INSERT INTO `Models` (`model_id`, `model_name`, `brand_id`) VALUES
(1, 'A4', 1),
(2, 'X5', 2),
(3, 'C-Class', 3),
(4, 'Corolla', 4),
(5, 'Civic', 5),
(6, 'Mustang', 6),
(7, 'X6 ', 2),
(8, 'X1', 2),
(9, 'SQ8', 1),
(10, 'Q3', 1),
(11, 'GLE', 3),
(12, 'AMG GT', 3),
(13, 'Camry', 4),
(14, 'Land Cruiser', 4),
(15, 'UR-V', 5),
(16, 'ZR-V', 5),
(17, 'F-150', 6),
(18, 'Fusion', 6),
(19, 'mustang', 6),
(20, 'sdfsdf', 6),
(21, 'sdfsdf', 6),
(22, 'sdfsdf', 6),
(23, 'sdfsdf', 6),
(24, 'sdfsdf', 6),
(25, 'sdfsdf', 6),
(26, 'sdfsdf', 6),
(27, 'sdfsdf', 6),
(28, 'sdfsdf', 6),
(29, 'sdfsdf', 6),
(30, 'sdfsdf', 6),
(31, 'sdfsdf', 6),
(32, 'sdfsdf', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `Rates`
--

CREATE TABLE `Rates` (
  `Id_rates` int NOT NULL,
  `Name_rates` varchar(100) NOT NULL,
  `Price_rates` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Rates`
--

INSERT INTO `Rates` (`Id_rates`, `Name_rates`, `Price_rates`) VALUES
(1, 'Видеорегистратор ', 100),
(2, 'Детское автокресло', 200),
(5, 'GPS', 300);

-- --------------------------------------------------------

--
-- Структура таблицы `Reviews`
--

CREATE TABLE `Reviews` (
  `Id_reviews` int NOT NULL,
  `Id_car` int NOT NULL,
  `Id_user` int NOT NULL,
  `Text_reviews` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `Grade` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Reviews`
--

INSERT INTO `Reviews` (`Id_reviews`, `Id_car`, `Id_user`, `Text_reviews`, `Grade`) VALUES
(11, 17, 35, 'Поездка на Ford Fusion стала для меня настоящим приключением! Этот мощный и стильный автомобиль не оставил равнодушным ни меня, ни окружающих. Вождение было динамичным и захватывающим благодаря мощному двигателю и отличной управляемости. Хотя автомобиль не новый, он все еще выглядит великолепно и обладает потрясающим звуком выхлопа. Спасибо RCR за предоставленную возможность насладиться этим легендарным автомобилем!    ', 5),
(14, 17, 25, 'Экономичный и надежный автомобиль для повседневных поездок. Прост в управлении, невысокий расход топлива. Отличный вариант для тех, кто ценит функциональность.', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `Id_user` int NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Surname` varchar(50) NOT NULL,
  `Patronymic` varchar(50) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Confirmation_code` varchar(100) NOT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`Id_user`, `Name`, `Surname`, `Patronymic`, `Email`, `Username`, `Password`, `Confirmation_code`, `isActive`) VALUES
(1, 'Алексей', 'Аникаев', 'Алексеевич', 'rcr@mai.com', 'admin', 'rcradmin', '', 0),
(5, 'Иван', 'Иванов', 'Иванович', 'vanechka28@mail.com', 'vaneck', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(6, 'Настя', 'Воронина', '', 'voronina18plus@ya.ru', 'qwe', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(7, 'Иван', 'Ешкеев', 'Иванович', 'ivanchikjjj@mail.ru', 'Ivanchuos', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(8, 'Азат', 'Вагизов', 'Нургалиевич', 'chic@gmail.ru', 'chicha', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(11, 'Степан', 'Пушков', 'Леонидович', 'stefannine@vc.com', 'nainestef', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(12, 'Ильнур', 'Файзулин', NULL, 'faizulik228@ya.ru', 'faizulik', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(15, 'Дмитрий', 'Пантюхин', NULL, 'gupibro@skuf.org', 'gupy', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(16, 'Дмитрий', 'Капустин', 'Алексеевич', 'fireshow@mail.ru', 'kadim', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(17, 'Григорий', 'Якимов', NULL, 'balvan123@gmail.ru', 'ballone', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(18, 'Евгений', 'Олин', NULL, 'Huflpaf@gmail.com', 'jojo', '$2y$10$QUjuxGRGkhb0GaKwWMzjEO56Dl/0gHGabn7gyrMORfwZLfddf2v..', '', 0),
(25, 'Алексей', 'Аникаев', 'Алексеевич', 'alekseyplotnikow228@mail.ru', 'younglil', '$2y$10$OKSgYgLMWg2rZ7sT24.mUOx.bOlAeU7NGauHbjg4RmzN3v5angdku', '523257', 1),
(35, 'Анастасия', 'Олина', 'Александровна', 'tapokprost61@gmail.com', 'chienne', '$2y$10$KjuUlwHCdyRzufIy4cUeOe2MM97da86Cjmc55JAFcl3LLGpEKiq7C', '523255', 1),
(37, 'настя', 'олина', '78', 'olinan106@gmail.com', 'chienneeee', '$2y$10$Un3oDXip5xp2nOILUsE5tO4FboQeBpFS8qfKQdJinSuQwH9QMjiFG', '341312', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Additional_Services`
--
ALTER TABLE `Additional_Services`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_contract` (`Id_contract`),
  ADD KEY `Id_rates` (`Id_rates`);

--
-- Индексы таблицы `Brands`
--
ALTER TABLE `Brands`
  ADD PRIMARY KEY (`id_brand`);

--
-- Индексы таблицы `Canceled_Applications`
--
ALTER TABLE `Canceled_Applications`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_contract` (`Id_contract`);

--
-- Индексы таблицы `Cars`
--
ALTER TABLE `Cars`
  ADD PRIMARY KEY (`id_cars`),
  ADD KEY `id_models` (`id_models`);

--
-- Индексы таблицы `Drivers_license`
--
ALTER TABLE `Drivers_license`
  ADD PRIMARY KEY (`Id_drivers_license`),
  ADD KEY `Id_user` (`Id_user`);

--
-- Индексы таблицы `Lease_contract`
--
ALTER TABLE `Lease_contract`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_user` (`Id_user`),
  ADD KEY `Id_car` (`Id_car`);

--
-- Индексы таблицы `Models`
--
ALTER TABLE `Models`
  ADD PRIMARY KEY (`model_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Индексы таблицы `Rates`
--
ALTER TABLE `Rates`
  ADD PRIMARY KEY (`Id_rates`);

--
-- Индексы таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`Id_reviews`),
  ADD KEY `Id_car` (`Id_car`),
  ADD KEY `Id_user` (`Id_user`) USING BTREE;

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Additional_Services`
--
ALTER TABLE `Additional_Services`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `Brands`
--
ALTER TABLE `Brands`
  MODIFY `id_brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `Canceled_Applications`
--
ALTER TABLE `Canceled_Applications`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `Cars`
--
ALTER TABLE `Cars`
  MODIFY `id_cars` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `Drivers_license`
--
ALTER TABLE `Drivers_license`
  MODIFY `Id_drivers_license` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `Lease_contract`
--
ALTER TABLE `Lease_contract`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `Models`
--
ALTER TABLE `Models`
  MODIFY `model_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `Rates`
--
ALTER TABLE `Rates`
  MODIFY `Id_rates` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `Id_reviews` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `Id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Additional_Services`
--
ALTER TABLE `Additional_Services`
  ADD CONSTRAINT `additional_services_ibfk_1` FOREIGN KEY (`Id_contract`) REFERENCES `Lease_contract` (`Id`),
  ADD CONSTRAINT `additional_services_ibfk_2` FOREIGN KEY (`Id_rates`) REFERENCES `Rates` (`Id_rates`);

--
-- Ограничения внешнего ключа таблицы `Brands`
--
ALTER TABLE `Brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`id_brand`) REFERENCES `Models` (`brand_id`);

--
-- Ограничения внешнего ключа таблицы `Cars`
--
ALTER TABLE `Cars`
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`id_models`) REFERENCES `Models` (`model_id`);

--
-- Ограничения внешнего ключа таблицы `Drivers_license`
--
ALTER TABLE `Drivers_license`
  ADD CONSTRAINT `drivers_license_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `Users` (`Id_user`);

--
-- Ограничения внешнего ключа таблицы `Lease_contract`
--
ALTER TABLE `Lease_contract`
  ADD CONSTRAINT `lease_contract_ibfk_5` FOREIGN KEY (`Id_user`) REFERENCES `Users` (`Id_user`),
  ADD CONSTRAINT `lease_contract_ibfk_6` FOREIGN KEY (`Id_car`) REFERENCES `Cars` (`id_cars`);

--
-- Ограничения внешнего ключа таблицы `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `Users` (`Id_user`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`Id_car`) REFERENCES `Cars` (`id_cars`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
