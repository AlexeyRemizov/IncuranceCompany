<?php
namespace AppBundle\Services\Weather;
/**
 * Interface AppWeatherInterface
 * @package AppBundle\Services\Weather
 */
interface AppWeatherInterface
{
    /**
     * @param $location
     * @return mixed
     */
    public function getWeather($location);
}