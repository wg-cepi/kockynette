<?php
namespace Cepi;

class StringUtils
{
    /**
     * Trim multiple white spaces
     * @param $string
     * @return mixed
     */
    public static function tmws($string)
    {
        $string = trim($string);
        return preg_replace('/\s+/', ' ', $string);
    }

    /**
     * Clear all white spaces
     * @param $string
     * @return mixed
     */
    public static function caws($string)
    {
        return preg_replace('/\s+/', '', $string);
    }
}
