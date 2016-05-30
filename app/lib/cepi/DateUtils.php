<?php
namespace Cepi;

class DateUtils
{
   public static function now($format = '')
   {
       if($format === '') $format = 'Y-m-d';
       return (new \DateTime())->format($format);
   }

    public static function Ymd($date = 'now')
    {
        return (new \DateTime($date))->format('Y-m-d');
    }

    public static function datePicker($date, $format = '')
    {
        if($format === '') $format = 'Y-m-j';
        return (new \DateTime($date))->format($format);
    }

    public static function mysqlDatetime($date = 'now')
    {
        return (new \DateTime($date))->format('Y-m-d H:i:s');
    }
}
