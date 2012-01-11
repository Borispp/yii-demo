<?php
class VarDumper extends CVarDumper 
{
    public static function dump($var,$depth=10,$highlight=true,$die = true)
    {
        echo self::dumpAsString($var,$depth,$highlight);
        if ($die) {
            die;
        }
    }
	
    public static function dumpAjax($var,$depth=10,$highlight=false,$die = true)
    {
        echo self::dumpAsString($var,$depth,$highlight);
        if ($die) {
            die;
        }
    }
}