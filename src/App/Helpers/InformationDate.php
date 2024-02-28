<?php
declare(strict_types=1);

namespace App\Helpers;

use DateTime;
use DateTimeZone;

class InformationDate
{
    public static function daysInMonth(int $month, int $year): int
    {
        return intval(date('t', mktime(0, 0, 0, $month, 1, $year)));
    }

    public static function monthNameByDate(\DateTime $dateTime): string
    {
        return  $dateTime->format('F');
    }

    public static function timezoneHasMinutesOffsetToUTC(string $date, string $timezone): int
    {
        $timezone = new DateTimeZone($timezone);
        $dateTimeZone = new DateTime("{$date} 12:00:00", $timezone);

        $offsetMinutes = $timezone->getOffset($dateTimeZone) / 60;

        $sign = ($offsetMinutes > 0) - ($offsetMinutes < 0);

        $intOffsetMinutes = intval(round($offsetMinutes));

        switch ($sign) {
            case 1:
                $value =  sprintf('+%s', $intOffsetMinutes);
                break;
            case -1:
                $value =  $intOffsetMinutes;
                break;
            case 0:
                $value =  $intOffsetMinutes;
                break;
        }

        return $value;
    }
}