<?php

namespace AppBundle\Services\Weather;

use AppBundle\Services\Weather\OWMFormatterInterface;
use Cmfcmf\OpenWeatherMap\CurrentWeather;

/**
 * Class OWMWeatherToHTML
 * @package AppBundle\Services\Weather
 */
class OWMWeatherToHTML implements OWMFormatterInterface
{
    /**
     * @param CurrentWeather $weather
     * @return string
     */
    public function format(CurrentWeather $weather)
    {
        return
            '<b>Temperature: </b>'.$weather->temperature->now->getValue()."°C <br>".
            '<b>Humidity: </b>'.$weather->humidity->getValue()."% ". $weather->humidity->getDescription()."<br>".
            '<b>Pressure: </b>'.$weather->pressure->getValue()." hPa <br>".
            '<b>Wind: </b>' .$weather->wind->speed->getFormatted().'('.$weather->wind->direction->getDescription().")<br>".
            '<b>Clouds: </b>'.$weather->clouds->getValue().' - '.$weather->clouds->getDescription()."<br>".
            '<b>City: </b>'. $weather->city->country.', '.$weather->city->name;
    }
}