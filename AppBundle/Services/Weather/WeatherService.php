<?php
namespace AppBundle\Services\Weather;

use Cmfcmf\OpenWeatherMap;

/**
 * Class WeatherService
 * @package AppBundle\Services\Weather
 */
class WeatherService implements AppWeatherInterface
{
    const LANGUAGE = 'en';
    const UNITS = 'metric';
    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var OWMWeatherToHTML
     */
    protected $weatherToHTML;

    /**
     * WeatherService constructor.
     * @param $apiKey
     * @param OWMWeatherToHTML $weatherFormatter
     */
    public function __construct($apiKey, OWMWeatherToHTML $weatherFormatter)
    {
        $this->apiKey = $apiKey;
        $this->weatherToHTML = $weatherFormatter;
    }

    /**
     * @param $location
     * @return null|string
     */
    public function getWeather($location)
    {
        $owm = new OpenWeatherMap($this->apiKey);
        try {
            $weather = $owm->getWeather($location, self::UNITS, self::LANGUAGE);
        } catch(\Exception $e) {
            $weather = false;
        }
        return !$weather instanceof OpenWeatherMap\CurrentWeather ? null : $this->weatherToHTML->format($weather);
    }
}