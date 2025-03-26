<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get current weather data for Bihać
     *
     * @return array
     */
    public function getBihacWeather(): array
    {
        // Cache for 1 hour to avoid too many API calls
        return Cache::remember('weather_bihac', now()->addHour(), function() {
            $client = new Client();
            $apiKey = config('services.weatherapi.key');
            
            try {
                // Make API request to WeatherAPI
                $response = $client->get('http://api.weatherapi.com/v1/current.json', [
                    'query' => [
                        'key' => $apiKey,
                        'q' => 'Bihac,Bosnia', // More specific location
                        'aqi' => 'no' // Don't include air quality data
                    ],
                    'timeout' => 5 // Timeout after 5 seconds
                ]);
                
                // Decode the JSON response
                $data = json_decode($response->getBody(), true);
                
                // Return formatted weather data
                return [
                    'temp' => $data['current']['temp_c'],
                    'condition' => $data['current']['condition']['text'],
                    'icon' => basename($data['current']['condition']['icon'], '.png'),
                    'humidity' => $data['current']['humidity'],
                    'wind_kph' => $data['current']['wind_kph'],
                    'feels_like' => $data['current']['feelslike_c'],
                    'last_updated' => $data['current']['last_updated']
                ];
                
            } catch (\Exception $e) {
                // Log the error
                Log::error('Weather API Error: ' . $e->getMessage());
                
                // Return fallback data if API fails
                return $this->getFallbackWeather();
            }
        });
    }

    /**
     * Fallback weather data when API fails
     *
     * @return array
     */
    protected function getFallbackWeather(): array
    {
        return [
            'temp' => 20,
            'condition' => 'Partly cloudy',
            'icon' => '116', // Default weather icon code
            'humidity' => 65,
            'wind_kph' => 10,
            'feels_like' => 19,
            'last_updated' => now()->toDateTimeString()
        ];
    }
}