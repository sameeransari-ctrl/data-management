<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as facadesRequest;


/**
 * Method changeDateToFormat
 *
 * @param \ $date   $date — [explicite description]
 * @param $format $format [explicite description]
 *
 * @return void
 */
function changeDateToFormat($date, $format = '')
{
    $format = !empty($format) ? $format : 'Y-m-d';
    return date($format, strtotime($date));
}

/**
 * Method currentDateByFormat
 *
 * @param \ $format $format — [explicite description]
 *
 * @return void
 */
function currentDateByFormat($format)
{
    return date($format);
}

/**
 * Method convertMinutesToHours
 *
 * @param int $minutes [explicite description]
 *
 * @return void
 */
function convertMinutesToHours(int $minutes)
{
    $hours = floor($minutes / 60);
    $min = $minutes - ($hours * 60);

    if (!$min) {
        return $hours;
    }
    return $hours . ":" . $min;
}

/**
 * Method getDuration
 *
 * @param \ $minutes $minutes — [explicite description]
 *
 * @return void
 */
function getDuration($minutes)
{
    $text = '';
    if ($minutes >= 60) {
        $hours = (int)($minutes / 60);
        $minutes = $minutes % 60;
        if ($hours) {
            $text .= $hours . ' ' . trans('labels.h');
        }

        if ($minutes) {
            $text .= ' ' . $minutes . ' ' . trans('labels.min');
        }
    } else {
        $text = $minutes . ' ' . trans('labels.min');
    }
    return $text;
}

/**
 * Method convertDateToTz
 *
 * @param string $date
 * @param string $format
 * @param string $fromTz 
 * @param string $toTz 
 * @param bool   $withoutTranslate 
 *
 * @return void
 */
function convertDateToTz(
    $date,
    $format = 'Y-m-d H:i:s',
    $fromTz = '',
    $toTz = '',
    $withoutTranslate = true
) {
    if ($toTz == '' && (Session::get('time_zone'))) {
        $toTz = Session::get('time_zone');
    } elseif ($toTz == '') {
        $toTz = config('app.timezone');
    }

    if (!$fromTz) {
        $fromTz = config('app.timezone');
    }
    $date = new \DateTime($date, new \DateTimeZone($fromTz));
    $date->setTimezone(new \DateTimeZone($toTz));
    $date = $date->format($format);
    if ($withoutTranslate) {
        return $date;
    }
    return Carbon::parse($date)->translatedFormat($format);
}

/**
 * Method nowDate
 *
 * @param $format   $format [explicite description]
 * @param $timezone $timezone [explicite description]
 *
 * @return void
 */
function nowDate($format = 'Y-m-d H:i:s', $timezone = null)
{
    $timezone = $timezone ? $timezone : Session::get('timezone');
    return Carbon::now()->setTimezone($timezone)->format($format);
}

/**
 * Method expiryDays
 *
 * @param $date $date [explicite description]
 *
 * @return void
 */
function expiryDays($date)
{
    $date = Carbon::parse($date);
    $now = Carbon::now();
    $day = $date->diffInDays($now);
    $return = '';
    if ($day == 0) {
        $return = trans('labels.today_expiry');
    } elseif ($day == 1) {
        $return = $day . ' ' . trans('labels.day');
    } else {
        $return = $day . ' ' . trans('labels.days');
    }
    return $return;
}

/**
 * Method getConvertedDate
 *
 * @param $date   string
 * @param $format string
 *
 * @return string
 */
function getConvertedDate($date, $format = '')
{
    if ($format == '') {
        $format = config('constants.date_format.admin_display');
    }

    return Carbon::parse($date)->format($format);
}
