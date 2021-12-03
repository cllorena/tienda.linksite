<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class JHTMLfwSgImage {
    static function get($path, $filename='') {
        $fullname = $path.$filename;
        if (file_exists($fullname) and is_file($fullname)) {
            jimport('joomla.filesystem.file');
            $ext = strtolower(JFile::getExt($filename));
            if (file_exists($fullname)) {

                switch ($ext) {
                    case 'gif' :
                        $image = @imagecreatefromgif($fullname);
                        break;
                    case 'png' :
                        $buff = @imagecreatefrompng($fullname);
                        list($width, $height) = getimagesize($fullname);
                        $image = imagecreatetruecolor($width, $height);
                        $white = imagecolorallocate($image,  255, 255, 255);
                        imagefilledrectangle($image, 0, 0, $width, $height, $white);
                        imagecopy($image, $buff, 0, 0, 0, 0, $width, $height);
                        break;
                    default :
                        $image = @imagecreatefromjpeg($fullname);
                }

                if ($image and in_array($ext, array('gif', 'png', 'jpg', 'jpeg')) and function_exists('exif_read_data')) {
                    $rotate = 0;
                    $mirror = 0;
                    if ($exif = @exif_read_data($fullname)) {
                        switch (JArrayHelper::getValue($exif, 'Orientation')) {
                            case 3 :
                                $rotate = 180;
                            break;
                            case 6 :
                                $rotate = 270;
                            break;
                            case 8 :
                                $rotate = 90;
                            break;
        /* part with a mirror effect */
                            case 2 :
                                $mirror = 1;
                            break;
                            case 4 :
                                $mirror = 1;
                                $rotate = 180;
                            break;
                            case 5 :
                                $mirror = 1;
                                $rotate = 270;
                            break;
                            case 7 :
                                $mirror = 1;
                                $rotate = 90;
                            break;
                        }
                    }

                    if ($rotate) {
                        $image = imagerotate($image, $rotate, 0);
                    }
                    if ($mirror) {
                        $width = imagesx($image);
                        $height = imagesy($image);

                        $buff_image = imagecreatetruecolor($width, $height);
                        $y = 1;

                        while ( $y < $height ) {
                            for ( $i = 1; $i <= $width; $i++ )
                                imagesetpixel($buff_image, $i, $y, imagecolorat($image, ( $i ), ($height - $y)));
                            $y = $y + 1;
                        }
                        imagecopy($image, $buff_image, 0, 0, 0, 0, $width, $height);
                        imagedestroy($buff_image);
                    }
                }

                return $image;
            }
        }
    }
    static function put($image, $path, $filename='') {
        jimport('joomla.filesystem.file');
        $fullname = $path.$filename;
        if (file_exists($fullname) and is_file($fullname) and is_writable($fullname)) {
            JFile::delete($fullname);
        }
        ob_start();
        $ext = strtolower(JFile::getExt($fullname));
        switch ($ext) {
            case 'gif' :
                @imagegif($image);
                break;
            case 'png' :
                @imagepng($image);
                break;
            default :
                @imagejpeg($image);
        }
        if ($buff = ob_get_clean()) {
            JFile::write($fullname, $buff);
        }
    }
    static function upload($src_filename, $trg_filename) {
        jimport('joomla.filesystem.file');
        if (JFile::copy($src_filename, $trg_filename)) {
            return true;
        }
    }
    static function fit($image, $path, $filename, $width, $height, $wmfile, $wmtext, $wmpos, $is_just_shrink) {
        jimport('joomla.filesystem.file');

        $org_width = @imagesx($image);
        $org_height = @imagesy($image);
        if (!$org_width or !$org_height) return;

        $org_ratio = $org_width/$org_height;

        if (!$width and !$height) {
            $width = $org_width;
            $height = $org_height;
        } elseif (!$width) {
            $width = $height * $org_width / $org_height;
        } elseif (!$height) {
            $height = $width * $org_height / $org_width;
        }

        if ($is_just_shrink) {
            $x_offset = 0;
            $y_offset = 0;
            $s_width = $org_width;
            $s_height = $org_height;

            if ($org_ratio > 1) { /* width larger, so srink by width */
                if ($org_width <= $width) {
                    $width = $org_width;
                    $height = $org_height;
                } else $height = round($width / $org_ratio);
            } else { /* height larger or eq */
                if ($org_height <= $height) {
                    $width = $org_width;
                    $height = $org_height;
                } else $width = round($height * $org_ratio);
            }
        } else {
            $trg_ratio = $width/$height;

            if ($org_ratio < $trg_ratio) { /*cut vertical top & shrink */
                $s_width = $org_width;
                $s_height = (int)($org_width / $trg_ratio);

                $x_offset = 0;
                $y_offset = (int)(($org_height-$s_height)/5);
            } elseif ($org_ratio > $trg_ratio) { /* cut horisontal middle & shrink */
                $s_width = (int)($org_height * $trg_ratio);
                $s_height = $org_height;

                $x_offset = (int)(($org_width-$s_width)/2);
                $y_offset = 0;
            } else { /* images fully proportional - just shrink */
                $s_width = $org_width;
                $s_height = $org_height;

                $x_offset = 0;
                $y_offset = 0;
            }
        }

        $thumb = imagecreatetruecolor($width, $height);

        $ct = imagecolortransparent($image);
        if ($ct >= 0) {
            $color_tran = @imagecolorsforindex($image, $ct);
            $ct2 = imagecolorexact($thumb, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
            imagefill($thumb, 0, 0, $ct2);
        }

        imagecopyresampled(
            $thumb,
            $image,
            0,
            0,
            $x_offset,
            $y_offset,
            $width,
            $height,
            $s_width,
            $s_height
        );
        imagedestroy($image);
        unset($image);

        if ($wmfile and file_exists(JPATH_SITE.'/'.$wmfile)) {
            $wmfile = imagecreatefrompng(JPATH_SITE.'/'.$wmfile);
        } elseif ($wmtext) {
            /* calculate text size */
            $font_path = FWMG_ASSETS_PATH.'fonts/chesterfield.ttf';

            $bbox = imagettfbbox(36, 0, $font_path, $wmtext);
            if ($bbox[0] < -1) $box_width = abs($bbox[2]) + abs($bbox[0]) - 1;
            else $box_width = abs($bbox[2] - $bbox[0]);
            if ($bbox[3] > 0) $box_height = abs($bbox[7] - $bbox[1]) - 1;
            else $box_height = abs($bbox[7]) - abs($bbox[1]);

            if ($wmfile = imagecreatetruecolor($box_width, $box_height + 2)) {
                $colorTransparent = imagecolortransparent($wmfile);
                imagefill($wmfile, 0, 0, $colorTransparent);

                $black = imagecolorallocate($wmfile, 0, 0, 0);
                imagettftext($wmfile, 36, 0, 0, abs($bbox[7]), $black, $font_path, $wmtext);
            }
        } else $wmfile = false;

        if ($wmfile) {
            $wm_width = imagesx($wmfile);
            $wm_height = imagesy($wmfile);
            $shr_coeff = min($width/1024, $height/768);
            if ($shr_coeff < 1) {
                $new_wm_width = (int)($wm_width*$shr_coeff);
                $new_wm_height = (int)($wm_height*$shr_coeff);
                $buff = imagecreatetruecolor($new_wm_width, $new_wm_height);
                $colorTransparent = imagecolortransparent($buff);
                imagefill($buff, 0, 0, $colorTransparent);

                ImageCopyResampled($buff, $wmfile, 0, 0, 0, 0, $new_wm_width, $new_wm_height, $wm_width, $wm_height);

                $wmfile = $buff;
                $wm_width = $new_wm_width;
                $wm_height = $new_wm_height;
            }

            $indent = 50 * $shr_coeff;
            switch ($wmpos) {
                case  'center' :
                    $dest_x = round(($width - $wm_width)/2);
                    $dest_y = round(($height - $wm_height)/2);
                break;
                case  'left top' :
                    $dest_x = $indent;
                    $dest_y = $indent;
                break;
                case  'right top' :
                    $dest_x = $width - $wm_width - $indent;
                    $dest_y = $indent;
                break;
                case  'left bottom' :
                    $dest_x = $indent;
                    $dest_y = $height - $wm_height - $indent;
                break;
                default :
                    $dest_x = $width - $wm_width - $indent;
                    $dest_y = $height - $wm_height - $indent;
            }
            imagecopy($thumb, $wmfile, $dest_x, $dest_y, 0, 0, $wm_width, $wm_height);
        }
        JHTML::_('fwsgImage.put', $thumb, $path, $filename);
        imagedestroy($thumb);
        unset($thumb);
    }
	static function delete(&$file) {
		if ($file->_sys_filename) {
			jimport('joomla.filesystem.file');
			$path = fwgHelper::getImagePath($file->_sys_filename);
			if (is_file($path.$file->_sys_filename)) {
				JFile::delete($path.$file->_sys_filename);
			}

			$db = JFactory::getDBO();
			$db->setQuery('DELETE FROM #__fwsg_file_image WHERE file_id = '.$file->id);
			$db->execute();

			$file->_image_exists = false;
			$file->_sys_filename = '';

			fwgHelper::clearImageCache('f'.$file->id);
			if ($file->category_id) {
				fwgHelper::clearImageCache('с'.$file->category_id);
			}

			return true;
		}
	}
	static function store(&$file, $image) {
		jimport('joomla.filesystem.file');
		$filename = '';
		$ext = strtolower(JFile::getExt($image['name']));
		if (in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {
			$path = '';
			do {
				$filename = md5($image['name'].rand()).'.'.$ext;
				$path = fwgHelper::getImagePath($filename);
			} while(file_exists($path.$filename));
			if (!file_exists($path)) {
				JFile::write($path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
			}
			if (JHTMLfwSgImage::upload($image['tmp_name'], $path.$filename)) {
				if ($file->_sys_filename) {
					$prev_path = fwgHelper::getImagePath($file->_sys_filename);
					if (file_exists($prev_path.$file->_sys_filename)) {
						JFile::delete($prev_path.$file->_sys_filename);
					}
				}

				$info = (array)getimagesize($path.$filename);
				$width = (int)JArrayHelper::getValue($info, 0);
				$height = (int)JArrayHelper::getValue($info, 1);
				$size = (int)filesize($path.$filename);
				$alt = JFile::stripExt($image['name']);

				$db = JFactory::getDBO();
				$query = '`width` = '.$width.', `height` = '.$height.', `size` = '.(int)$size.', sys_filename = '.$db->quote($filename).', `filename` = '.$db->quote($image['name']).', `alt` = '.$db->quote($alt);

				if ($file->_image_exists) {
					$db->setQuery('UPDATE #__fwsg_file_image SET '.$query.' WHERE file_id = '.$file->id);
				} else {
					$db->setQuery('INSERT INTO #__fwsg_file_image SET '.$query.', file_id = '.$file->id);
				}
				$db->execute();

				$file->_image_exists = true;
				$file->_sys_filename = $filename;

				fwgHelper::clearImageCache('f'.$file->id);
				if ($file->category_id) {
					fwgHelper::clearImageCache('с'.$file->category_id);
				}

				return true;
			}
		}
	}
	static function rotate($path, $filename, $rotate) {
		if ($image = JHTMLfwSgImage::get($path, $filename)) {
			$image = imagerotate($image, $rotate, 0);
			JHTMLfwSgImage::put($image, $path, $filename);
		}
	}
}
