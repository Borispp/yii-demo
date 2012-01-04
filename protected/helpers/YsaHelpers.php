<?php
class YsaHelpers
{
	public static function encrypt($string)
	{
		return md5($string . Yii::app()->params['salt']);
	}

	public static function date($format = null, $timestamp = null)
	{
		return date(null === $format ? Yii::app()->params['date_format'] : $format, null === $timestamp ? time() : $timestamp);
	}

	/**
	 * genRandomString - Generates random string
	 * @author Macinville <http://macinville.blogspot.com>
	 * @license MIT License <http://www.opensource.org/licenses/mit-license.php>
	 * @param int $length Length of the return string.
	 * @param string $chars User-defined set of characters to be used in randoming. If this is set, $type will be ignored.
	 * @param array $type Type of the string to be randomed.Can be set by boolean values.
	 * <ul>
	 * <li><b>alphaSmall</b> - small letters, true by default</li>
	 * <li><b>alphaBig</b> - big letters, true by default</li>
	 * <li><b>num</b> - numbers, true by default</li>
	 * <li><b>othr</b> - non-alphanumeric characters found on regular keyboard, false by default</li>
	 * <li><b>duplicate</b> - allow duplicate use of characters, true by default</li>
	 * </ul>
	 * @return string The generated random string
	 */
	public static function genRandomString($length=10, $chars='', $type=array())
	{
		//initialize the characters
		$alphaSmall = 'abcdefghijklmnopqrstuvwxyz';
		$alphaBig = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '0123456789';
		$othr = '`~!@#$%^&*()/*-+_=[{}]|;:",<>.\/?' . "'";

		$characters = "";
		$string = '';
		//defaults the array values if not set
		isset($type['alphaSmall'])  ? $type['alphaSmall']: $type['alphaSmall'] = true;  //alphaSmall - default true
		isset($type['alphaBig'])    ? $type['alphaBig']: $type['alphaBig'] = true;      //alphaBig - default true
		isset($type['num'])         ? $type['num']: $type['num'] = true;                //num - default true
		isset($type['othr'])        ? $type['othr']: $type['othr'] = false;             //othr - default false
		isset($type['duplicate'])   ? $type['duplicate']: $type['duplicate'] = true;    //duplicate - default true

		if (strlen(trim($chars)) == 0) {
			$type['alphaSmall'] ? $characters .= $alphaSmall : $characters = $characters;
			$type['alphaBig'] ? $characters .= $alphaBig : $characters = $characters;
			$type['num'] ? $characters .= $num : $characters = $characters;
			$type['othr'] ? $characters .= $othr : $characters = $characters;
		}
		else
			$characters = str_replace(' ', '', $chars);

		if($type['duplicate'])
			for (; $length > 0 && strlen($characters) > 0; $length--) {
				$ctr = mt_rand(0, (strlen($characters)) - 1);
				$string .= $characters[$ctr];
			}
		else
			$string = substr (str_shuffle($characters), 0, $length);

		return $string;
	}


	public static function filterSystemName($value, $replacement = '-')
	{
		$value = strtolower($value);
		return preg_replace('~' . $replacement . '{2,}~', $replacement, preg_replace('~[^a-zA-Z0-9]~si', $replacement, $value));
	}

	public static function isSerialized($string) {
		return (@unserialize($string) !== false);
	}

	public static function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
	{
		$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($passKey !== null) {
			// Although this function's purpose is to just make the
			// ID short - and not so much secure,
			// with this patch by Simon Franz (http://blog.snaky.org/)
			// you can optionally supply a password to make it harder
			// to calculate the corresponding numeric ID

			for ($n = 0; $n<strlen($index); $n++) {
				$i[] = substr( $index,$n ,1);
			}

			$passhash = hash('sha256',$passKey);
			$passhash = (strlen($passhash) < strlen($index))
					? hash('sha512',$passKey)
					: $passhash;

			for ($n=0; $n < strlen($index); $n++) {
				$p[] =  substr($passhash, $n ,1);
			}

			array_multisort($p,  SORT_DESC, $i);
			$index = implode($i);
		}

		$base  = strlen($index);

		if ($to_num) {
			// Digital number  <<--  alphabet letter code
			$in  = strrev($in);
			$out = 0;
			$len = strlen($in) - 1;
			for ($t = 0; $t <= $len; $t++) {
				$bcpow = bcpow($base, $len - $t);
				$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
			}

			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$out -= pow($base, $pad_up);
				}
			}
			$out = sprintf('%F', $out);
			$out = substr($out, 0, strpos($out, '.'));
		} else {
			// Digital number  -->>  alphabet letter code
			if (is_numeric($pad_up)) {
				$pad_up--;
				if ($pad_up > 0) {
					$in += pow($base, $pad_up);
				}
			}

			$out = "";
			for ($t = floor(log($in, $base)); $t >= 0; $t--) {
				$bcp = bcpow($base, $t);
				$a   = floor($in / $bcp) % $base;
				$out = $out . substr($index, $a, 1);
				$in  = $in - ($a * $bcp);
			}
			$out = strrev($out); // reverse
		}

		return $out;
	}


	public static function mimeToExtention($imagetype)
	{
		switch($imagetype)
		{
			case 'image/bmp': return 'bmp';
			case 'image/cis-cod': return 'cod';
			case 'image/gif': return 'gif';
			case 'image/ief': return 'ief';
			case 'image/jpeg': return 'jpg';
			case 'image/pipeg': return 'jfif';
			case 'image/tiff': return 'tif';
			case 'image/x-cmu-raster': return 'ras';
			case 'image/x-cmx': return 'cmx';
			case 'image/x-icon': return 'ico';
			case 'image/x-portable-anymap': return 'pnm';
			case 'image/x-portable-bitmap': return 'pbm';
			case 'image/x-portable-graymap': return 'pgm';
			case 'image/x-portable-pixmap': return 'ppm';
			case 'image/x-rgb': return 'rgb';
			case 'image/x-xbitmap': return 'xbm';
			case 'image/x-xpixmap': return 'xpm';
			case 'image/x-xwindowdump': return 'xwd';
			case 'image/png': return 'png';
			case 'image/x-jps': return 'jps';
			case 'image/x-freehand': return 'fh';
			case 'application/pdf' : return 'pdf';
			default: return false;
		}
	}

	public static function html2rgb($color)
	{
		if ($color[0] == '#')
			$color = substr($color, 1);
		if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1],
				$color[2].$color[3],
				$color[4].$color[5]);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;
		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
		return array($r, $g, $b);
	}

	public static function path2Url($path)
	{
		return str_ireplace(rtrim(Yii::getPathOfAlias('webroot'), '/'), '', $path);
	}

}