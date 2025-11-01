<?php

namespace Trangiahuy\WeatherApp;

//use Dotenv\Dotenv;
use GuzzleHttp\Client;

class WeatherService
{
    private Client $client;
    private string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = $_ENV['WEATHER_API_KEY'];
        $this->apiUrl = 'https://api.openweathermap.org/data/2.5/weather';
        $this->client = new Client();
    }

    public function getWeather(string $city): array
    {
        $response = $this->client->get($this->apiUrl, [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
            ],
            'http_errors' => false,
            'timeout' => 10,
        ]);

        $weatherData = json_decode($response->getBody()->getContents(), true);
//        print_r($weatherData);
//        die();

        // openweatherapi: 200 thành công, 404 sai thành phố, 500 api sập/sập mạng
        if (($weatherData['cod'] ?? 0) == 200) {
            return [
                'city' => $weatherData['name'],
                'country' => $weatherData['sys']['country'],
                'weather' => $weatherData['weather'][0]['description'],
                'temperature' => $weatherData['main']['temp'],
                'feels_like' => $weatherData['main']['feels_like'],
                'humidity' => $weatherData['main']['humidity'],
                'wind' => $weatherData['wind']['speed'],
                'sunrise' => date('H:i:s', $weatherData['sys']['sunrise']),
                'sunset' => date('H:i:s', $weatherData['sys']['sunset']),
            ];
        }
        return [
            'cod' => (string)($weatherData['cod'] ?? '500'),
            'error' => $weatherData['message'] ?? 'unknown error',
            'raw' => $weatherData, // Trả về toàn bộ dữ liệu để debug
        ];
    }
}


