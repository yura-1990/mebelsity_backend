<?php

namespace App\Traits;

trait LanguageTrait
{
    public static function convert($lang){
        return $lang . '_' . app()->getLocale();
    }
}
