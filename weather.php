<?php
require_once __DIR__ . '/vendor/autoload.php';

use Trangiahuy\WeatherApp\WeatherService;
use Dotenv\Dotenv;

// For _ENV

// Load dotenv
$dotenv = Dotenv::createImmutable(__DIR__); // đọc file .env ở __DIR__
$dotenv->load(); // Load .env vào $_ENV


if ($argc < 2) {
    echo "Bạn chưa nhập tên thành phố.\nCú pháp: php weather.php <city>\n";
    exit(1);
}
$weatherService = new WeatherService();
$city = $argv[1];
echo "------------------------------\n";

// If error exists
$weather = $weatherService->getWeather($city);
if (isset($weather['error'])) {
    echo "Lỗi: {$weather['error']}\n";
    exit(1);
}

echo "Thành phố : " . $weather['city'] . " ({$weather['country']})\n";
echo "Nhiệt độ  : " . $weather['temperature'] . "°C (cảm giác như {$weather['feels_like']}°C)\n";
echo "Độ ẩm     : " . $weather['humidity'] . "%\n";
echo "Thời tiết : " . ucfirst($weather['weather']) . "\n";
echo "Gió       : " . $weather['wind'] . " m/s\n";
echo "------------------------------\n";
