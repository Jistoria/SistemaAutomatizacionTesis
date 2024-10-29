<?php

namespace Modules\ImportDataFile\Utils;

class DateUtils
{
    public const MONTHS_ES_TO_EN = [
        'enero' => 'January', 'febrero' => 'February', 'marzo' => 'March', 'abril' => 'April',
        'mayo' => 'May', 'junio' => 'June', 'julio' => 'July', 'agosto' => 'August',
        'septiembre' => 'September', 'octubre' => 'October', 'noviembre' => 'November', 'diciembre' => 'December'
    ];

    public static function convertMonthToEnglish($dateString)
    {
        return str_ireplace(array_keys(self::MONTHS_ES_TO_EN), array_values(self::MONTHS_ES_TO_EN), $dateString);
    }
}
