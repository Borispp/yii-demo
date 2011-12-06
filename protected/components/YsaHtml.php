<?php
class YsaHtml extends CHtml
{
    public static function adminSaveSection($text = 'Save')
    {
        return '<section><div class="c"><button class="submit ysa big">' . $text . '</button></div></section>';
    }
    
    public static function optionImage($name, $value, $htmlOptions = array(), $imageOptions = array())
    {
        $value = (int) $value;
        if ($value) {
            $image = OptionImage::model()->findByPk($value);
        }
        if ($image) {
            $string = '<div class="option-image">
                <span><img src="' . $image->url() . '" alt="" /></span><a href="#" class="btn red small remove" rel="' . $imageOptions['id'] . '">delete</a>
            </div>';
            return $string;
        } else {
            return CHtml::fileField($name, $value, $htmlOptions);
        }
    }
}