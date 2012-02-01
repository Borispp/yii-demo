<?php
class YsaHtml extends CHtml
{
	const ID_PREFIX = 'ysa';
	
    public static function adminSaveSection($text = 'Save')
    {
        return '<section><div class="c"><button class="submit ysa big">' . $text . '</button></div></section>';
    }
    
    public static function optionImage($name, $value, $htmlOptions = array(), $imageOptions = array())
    {
        $image = OptionImage::model()->findByPk((int) $value);

        if ($image) {
            $string = '<div class="option-image">
                <span><img src="' . $image->url() . '" alt="" /></span><a href="#" class="btn red small remove" rel="' . $imageOptions['id'] . '">delete</a>
            </div>';
            return $string;
        } else {
            return CHtml::fileField($name, $value, $htmlOptions);
        }
    }
    
    public static function pageHeaderTitle($name)
    {
        return '<div class="page-header-wrapper"><section class="page-header"><h2>' . $name . '</h2></section></div>';
    }
}