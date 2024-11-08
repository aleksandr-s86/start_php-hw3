<?php

$address = '/code/birthdays.txt';

// Получаем текущую дату в формате ДД-ММ
$currentDate = date('d-m');

$name = readline("Введите имя: ");
$date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");

if(validate($date)){
    $data = $name . ", " . $date . "\r\n";

    $fileHandler = fopen($address, 'a');
    
    if(fwrite($fileHandler, $data)){
        echo "Запись $data добавлена в файл $address";
    }
    else {
        echo "Произошла ошибка записи. Данные не сохранены";
    }
    
    fclose($fileHandler);
}
else{
    echo "Введена некорректная информация";
}
// Функция для удаления строки
function deleteBirthday($filename, $searchTerm) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = false;

    // Открываем файл для записи
    $fileHandler = fopen($filename, 'w');
    if (!$fileHandler) {
        echo "Не удалось открыть файл для записи.\n";
        return;
    }

    foreach ($lines as $line) {
        list($name, $date) = explode(", ", $line);
        if (trim($name) === trim($searchTerm) || trim($date) === trim($searchTerm)) {
            $found = true; // Строка найдена
            echo "Запись '$line' была удалена.\n";
            continue; // Пропускаем эту строку
        }
        fwrite($fileHandler, $line . "\n"); // Записываем оставшиеся строки обратно
    }

    fclose($fileHandler);

    if (!$found) {
        echo "Запись с указанным именем или датой не найдена.\n";
    }
}

// Запросить у пользователя имя или дату для удаления
$searchTerm = readline("Введите имя или дату для удаления: ");
deleteBirthday($address, $searchTerm);

function findBirthdaysToday($filename, $currentDate) {
    //Функция findBirthdaysToday: Эта функция принимает имя файла и текущую дату. Она открывает файл для чтения и использует цикл while, чтобы построчно читать файл.
    // Открываем файл для чтения
    $fileHandler = fopen($filename, 'r');
    if (!$fileHandler) {
        echo "Не удалось открыть файл.";
        return;
    }

    $birthdays = [];

    // Читаем файл построчно
    while (($line = fgets($fileHandler)) !== false) {
        // Удаляем пробелы и разбиваем строку на части
        $line = trim($line); //Для каждой строки мы используем trim, чтобы удалить лишние пробелы.
        list($name, $date) = explode(", ", $line); //Затем используем explode, чтобы разделить строку на имя и дату.

        // Проверяем, совпадает ли дата с текущей
        if (date('d-m', strtotime($date)) === $currentDate) {
            $birthdays[] = $name; // Добавляем имя в массив
        }
    }

    fclose($fileHandler);

    // Выводим результаты
    if (count($birthdays) > 0) {
        echo "Сегодня день рождения у: " . implode(", ", $birthdays);
    } else {
        echo "Сегодня нет дней рождения.";
    }
}

// Вызов функции для поиска дней рождения
findBirthdaysToday($address, $currentDate);

function validate(string $date): bool {
    // Удаляем пробелы и проверяем формат
    $date = trim($date);
    if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
        return false;
    }

    $dateBlocks = explode("-", $date);
    $day = (int)$dateBlocks[0];
    $month = (int)$dateBlocks[1];
    $year = (int)$dateBlocks[2];

        // Проверка диапазонов
        if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 1900 || $year > date('Y')) {
            return false;
        }

        // Проверка на количество дней в месяце
        if (checkdate($month, $day, $year) === false) {
            return false;
        }

        return true;
}

//Регулярное выражение для формата: Используется preg_match для проверки, соответствует ли дата формату ДД-ММ-ГГГГ.

//Проверка диапазонов: Проверяются значения дня, месяца и года на корректность.

//Функция checkdate: Используется для проверки, существует ли дата (например, 30 февраля — это недопустимая дата).

//Теперь функция validate более надежна и защищена от некорректного ввода данных.