<?php
class YsaImage extends Image
{
	public static $allowed_types = array
	(
		IMAGETYPE_GIF => 'gif',
		IMAGETYPE_JPEG => 'jpg',
		IMAGETYPE_PNG => 'png',
	);
	
    function auto_crop($w, $h) {
        $x_scale = $w / $this->width;
        $y_scale = $h / $this->height;
        $relative_width = (int)round($y_scale * $this->width);
        $relative_height = (int)round($x_scale * $this->height);

        if($relative_height > $h) {
            $this->resize($w, null);
        } else {
            $this->resize(null, $h);
        }

        return $this->crop($w, $h);
    }
}