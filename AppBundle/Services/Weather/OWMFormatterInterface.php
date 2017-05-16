<?php

namespace AppBundle\Services\Weather;
use Cmfcmf\OpenWeatherMap\CurrentWeather;

/**
 * Interface OWMFormatterInterface
 * @package AppBundle\Services
 */
interface OWMFormatterInterface
{
    /**
     * @param CurrentWeather $weather
     * @return mixed
     */
    public function format(CurrentWeather $weather);
}