# WEB-balance

**Установка проекта**
Для того чтобы проект заработал, изначально нужно создать базу данных и поменять conf файл.

**Рассмотрим базу данных:**
Для работы нам понадобится 2 таблицы.

  1. Создаём базу данных в XAMPP.
  2. Называем базу данных, как угодно.
  3. Первая таблица "agapov"
     Структура таблицы:
     ![image](https://github.com/user-attachments/assets/34294519-ca1e-480d-be7b-0f81667f1783)

````
    CREATE TABLE `your_database_name`.`agapov` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `balance` DOUBLE DEFAULT 0,
  PRIMARY KEY (`id`)
);
````

  4. Вторая таблица
     Таблица "purchases"
     Структура таблицы:
     ![image](https://github.com/user-attachments/assets/a50408d9-9d2c-4dd7-9275-9ae822f525a4)

  5. Третья таблица
    Таблица "transactions"
    Структура таблицы:
    ![image](https://github.com/user-attachments/assets/c8971f68-ba06-4ee5-8629-c46d3146469c)
  6. Четвёртая таблица 
    Таблица "cards" 
    Структура таблицы: 
    ![image](https://github.com/user-attachments/assets/4c34a2f0-e192-463d-a9c4-e2bed6f9a7aa)

````
  CREATE TABLE cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    artist VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

INSERT INTO cards (title, description, artist, price, image_url) VALUES
('Стена и прочие друзья', 'Убей меня, Эйс', 'Убей меня, Эйс', 25, 'images/card1.jpg'),
('Глаза. Рты.', 'Убей меня, Эйс', 'Убей меня, Эйс', 30, 'images/card2.jpg'),
('Хуже, чем вчера...', 'Убей меня, Эйс', 'Убей меня, Эйс', 120, 'images/card3.jpg'),
('Adrenaline', 'Deftones', 'Deftones', 50, 'images/Deftones-Adrenaline.jpg'),
('Around the fur', 'Deftones', 'Deftones', 120, 'images/Deftones-Around-the-fur.jpg'),
('Covers', 'Deftones', 'Deftones', 70, 'images/Deftones-Covers.jpg');
````
