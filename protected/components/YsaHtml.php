<?php
class YsaHtml extends CHtml
{
    public static function adminSaveSection($text = 'Save')
    {
        return '<section><div class="c"><button class="submit ysa big">' . $text . '</button></div></section>';
    }
}