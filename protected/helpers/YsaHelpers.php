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
		return trim(preg_replace('~' . $replacement . '{2,}~', $replacement, preg_replace('~[^a-zA-Z0-9]~si', $replacement, $value)), ' ' . $replacement);
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

	public static function formatDate($date, $format = 'Y-m-d H:i:s')
	{
		return date($format, strtotime($date));
	}

	/**
	 * @static
	 * @param $category
	 * @param $message
	 * @call Yii::t
	 * @return void
	 */
	public static function t($category,$message)
	{
		echo Yii::t($category, $message);
	}

	public static function truncate($string, $length = 80, $etc = '&#133;', $break_words = false, $middle = false)
	{
		if ($length == 0)
			return '';

		if (strlen($string) > $length) {
			$length -= min($length, strlen($etc));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
			}
			if(!$middle) {
				return substr($string, 0, $length) . $etc;
			} else {
				return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
			}
		} else {
			return $string;
		}
	}

	public static function encode($string)
	{
		return base64_encode(sha1($string, true));
	}

	public static function short($number, $frombase = 20, $tobase = 36)
	{
		return base_convert($number, $frombase, $tobase);
	}

	public static function readableFilesize($size)
	{
		$mod = 1024;

		$units = explode(' ','B KB MB GB TB PB');
		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}

		return round($size, 2) . ' ' . $units[$i];
	}
	
	/**
	* xml2array() will convert the given XML text to an array in the XML structure.
	* Link: http://www.bin-co.com/php/scripts/xml2array/
	* Arguments : $contents - The XML text
	*                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
	*                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
	* Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
	* Examples: $array =  xml2array(file_get_contents('feed.xml'));
	*              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
	*/
	public static function xml2array($contents, $get_attributes=1, $priority = 'tag') {
		if(!$contents) return array();

		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array; //Refference

		//Go through the tags.
		$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.

			$result = array();
			$attributes_data = array();

			if(isset($value)) {
				if($priority == 'tag') $result = $value;
				else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
			}

			//Set the attributes too.
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag') $attributes_data[$attr] = $val;
					else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
					$repeated_tag_index[$tag.'_'.$level] = 1;

					$current = &$current[$tag];

				} else { //There was another element with the same tag name

					if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
						$repeated_tag_index[$tag.'_'.$level]++;
					} else {//This section will make the value an array if multiple tags with the same name appear together
						$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
						$repeated_tag_index[$tag.'_'.$level] = 2;

						if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

					}
					$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
					$current = &$current[$tag][$last_item_index];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

				} else { //If taken, put all things inside a list(array)
					if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

						// ...push the new element into that array.
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag.'_'.$level]++;

					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}

							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}

		return($xml_array);
	}

	/**
	 * Recursive import files from path.
	 * @static
	 * @param $path
	 */
	public static function importRecursive($path)
	{
		$path = rtrim($path, '/').'/*';
		foreach(glob($path) as $filename)
		{
			if (is_file($filename))
			{
				require_once($filename);
				continue;
			}
			YsaHelpers::importRecursive($filename);
		}
	}
}