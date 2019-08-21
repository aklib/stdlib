<?php

/**
 * DateTimeUtils.php
 * 
 * @since 27.09.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Util;

use DateTime;

class DateTimeUtils {
    const DATEFORMAT_PHP = 'd.m.Y H:i';
    const DATEFORMAT_JS = 'DD.MM.YYYY HH:mm';

    /** minute in seconds */
    const MINUTE = 60;

    /** hour in seconds */
    const HOUR = 3600;

    /** day in seconds */
    const DAY = 86400;

    /** week in seconds */
    const WEEK = 604800;

    /** average month in seconds */
    const MONTH = 2629800;

    /** average year in seconds */
    const YEAR = 31557600;
    
    /**
     * DateTime object factory.
     * @param  string|int|\DateTimeInterface
     * @return \DateTime
     */
    public static function from($time) {
        if ($time instanceof \DateTimeInterface) {
            return $time;
        } elseif (is_numeric($time)) {
            if ($time <= self::YEAR) {
                $time += time();
            }
            return (new DateTime('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));
        } else { // textual or null
            return new DateTime($time);
        }
    }

    /**
     * @return int|string
     */
    public static function getTimestamp($time = null, $micro = false) {
        $from = self::from($time);       
        $ts = $from->format('U') * ($micro ? 1000 : 1);        
        return is_float($tmp = $ts * 1) ? $ts : $tmp;
    }
    
    public static function getTimestampDefaultFrom($micro = false) {        
        return self::getTimestamp(self::from()->setTime(0,0,0), $micro);
    }
    
    /**
     * gets now timestamp
     * @param type $micro
     * @return int
     */
    public static function getTimestampDefaultTo($micro = false) {
        return self::getTimestamp(NULL, $micro);
    }
    
    /**
     * Gets formated yesterday 
     * @return string
     */
    public static function getDateDefaultFrom() {
        return self::from()->setTime(0,0,0)->format(self::DATEFORMAT_PHP);
    }
    
    /**
     * Gets formated now
     * @return string
     */
    public static function getDateDefaultTo() {
        return self::from()->format(self::DATEFORMAT_PHP);
    }
    
     public static function getFromRangeTo($range) {
         if(empty($range)){
             return self::getDateDefaultTo();
         }
         $explode = array_map('trim', explode('-', $range));
         if(!empty($explode[1])){
             return $explode[1];
         }
         return self::getDateDefaultTo();
     }
     
     public static function getFromRangeFrom($range) {
         if(empty($range)){
             return self::getDateDefaultFrom();
         }
         $explode = array_map('trim', explode('-', $range));
         if(!empty($explode[0])){
             return $explode[0];
         }
         return self::getDateDefaultFrom();
     }
        

}
