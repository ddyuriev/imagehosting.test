<?php

namespace App\Helpers;


use Illuminate\Support\Str;

class StringHelper
{
    /**
     * @param $string
     * @return array|string|string[]|null
     */
    public static function transliterateAndLowerCase($string)
    {
        $string = mb_strtolower($string, 'UTF-8');
        $string = \AnyAscii::transliterate($string);
        return preg_replace('/[^a-z0-9_.]/', '', $string);
    }

    /**
     * @param $file
     * @return array|string|string[]|null
     */
    public static function newFileName($file)
    {
        return self::transliterateAndLowerCase(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension());
    }

}
