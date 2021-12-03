<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewItem extends JViewLegacy {
	function display($tmpl=null) {
		$model = $this->getModel();
		$this->app = JFactory::getApplication();

		switch ($this->getLayout()) {
			case 'tmpl' :
			$this->item = $model->loadFile();
			$this->user = JFactory::getUser();

			if (!$this->item->id) {
				echo JText::_('FWMG_FILE_NOT_FOUND');
				die();
			} elseif ($this->item->access and !in_array($this->item->access, $this->user->getAuthorisedViewLevels())) {
				echo JText::_('FWMG_FILE_ACCESS_NOT_ALLOWED');
				die();
			} elseif (!$this->item->published) {
				echo JText::_('FWMG_FILE_NOT_PUBLISHED');
				die();
			}

			$cid = $model->getCategoryId($this->item);
			$this->category = $model->loadCategory($cid);

			if ($this->category->access and !in_array($this->category->access, $this->user->getAuthorisedViewLevels())) {
				echo JText::_('FWMG_GALLERY_ACCESS_NOT_ALLOWED');
				die();
			} elseif ($this->category->id and !$this->category->published) {
				echo JText::_('FWMG_GALLERY_NOT_PUBLISHED');
				die();
			}

			if ($this->item->descr) {
				$this->item->descr = fwgHelper::fixDescriptionImagesLinks(JHtml::_('content.prepare', $this->item->descr));
			}

			if ($this->category->id) {
				$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			} else {
				$this->params = new fwgParams(new JRegistry);
			}
			$this->files = $model->loadFiles(0, $this->params);
			$this->is_html = false;
			$this->is_modal = $this->app->input->get('modal');

			$doc = JFactory::getDocument();
			$this->title = $doc->getTitle();
			if (mb_strtolower($this->title) != mb_strtolower($this->item->name)) {
				$this->title .= ' - '.$this->item->name;
			}

			echo fwgHelper::loadTemplate($this->app->input->getString('tmpl', 'item.item'), array('view'=>$this));
			break;
			case 'download' :
			if ($sys_filename = $model->getDownloadFilename()) {
				$filename  = '';
				if (is_array($sys_filename)) {
					$filename = $sys_filename['filename'];
					$sys_filename = $sys_filename['sys_filename'];
				}
				$send_data = true;
				if (!headers_sent()) {
					$headers = apache_request_headers();
					$time = filemtime($sys_filename);
					$mime = mime_content_type($sys_filename);
					header('Cache-Control: public');
					header("Pragma: cache");
					if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $time)) {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 304);
						$send_data = false;
					} else {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
					}
					header('Content-Length: '.filesize($sys_filename));
					header("Content-type: {$mime}");
					header('Content-Disposition: attachment; filename=' . rawurlencode(basename($filename?$filename:$sys_filename)));
				}
				if ($send_data) print file_get_contents($sys_filename);
			}
			break;
			case 'img' :
			if ($sys_filename = $model->getImage()) {
				$send_data = true;
				if (!headers_sent()) {
					$headers = apache_request_headers();
					$time = filemtime($sys_filename);
					$mime = mime_content_type($sys_filename);
					header('Cache-Control: public');
					header("Pragma: cache");
					if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $time)) {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 304);
						$send_data = false;
					} else {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
					}
					header('Content-Length: '.filesize($sys_filename));
					header("Content-type: {$mime}");
				}
				if ($send_data) print file_get_contents($sys_filename);
			} elseif ($errors = $model->getErrors()) {
				$wmtext = implode("\n\n", $errors);
	            $font_path = FWMG_ASSETS_PATH.'fonts/chesterfield.ttf';

				$box_width = $box_height = 0;
	            $bbox = imagettfbbox(36, 0, $font_path, $wmtext);
	            if ($bbox[0] < -1) $box_width = abs($bbox[2]) + abs($bbox[0]) - 1;
	            else $box_width = abs($bbox[2] - $bbox[0]);
	            if ($bbox[3] > 0) $box_height = abs($bbox[7] - $bbox[1]) - 1;
	            else $box_height = abs($bbox[7]) - abs($bbox[1]);

	            if ($wmfile = imagecreatetruecolor($box_width + 20, $box_height + 20)) {
	                $colorTransparent = imagecolortransparent($wmfile);
	                imagefill($wmfile, 0, 0, $colorTransparent);

	                $black = imagecolorallocate($wmfile, 0, 0, 0);
	                imagettftext($wmfile, 36, 0, 10, abs($bbox[7])+10, $black, $font_path, $wmtext);

					if (!headers_sent()) {
						header('Content-Type: image/png');
					}
					imagepng($wmfile);
					imagedestroy($wmfile);
	            }
			}
			break;
			default:
			$this->item = $model->loadFile();
			$this->user = JFactory::getUser();

			if (!$this->item->id) {
				echo JText::_('FWMG_FILE_NOT_FOUND');
				die();
			} elseif ($this->item->access and !in_array($this->item->access, $this->user->getAuthorisedViewLevels())) {
				echo JText::_('FWMG_FILE_ACCESS_NOT_ALLOWED');
				die();
			} elseif (!$this->item->published) {
				echo JText::_('FWMG_FILE_NOT_PUBLISHED');
				die();
			}

			$cid = $model->getCategoryId($this->item);
			$this->category = $model->loadCategory($cid);

			if ($this->category->access and !in_array($this->category->access, $this->user->getAuthorisedViewLevels())) {
				echo JText::_('FWMG_GALLERY_ACCESS_NOT_ALLOWED');
				die();
			} elseif ($this->category->id and !$this->category->published) {
				echo JText::_('FWMG_GALLERY_NOT_PUBLISHED');
				die();
			}

			if ($this->item->descr) {
				$this->item->descr = fwgHelper::fixDescriptionImagesLinks(JHtml::_('content.prepare', $this->item->descr));
			}

			if ($this->category->id) {
				$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			} else {
				$this->params = new fwgParams(new JRegistry);
			}

			$this->img_position = $model->getFilePosition($this->item->id, $this->category->id, $this->params);
			$this->img_limit = 30;
			$this->img_limitstart = min($this->img_position->total_qty - 30, max(0, $this->img_position->pos - ($this->img_limit/2)));

			$this->app->input->set('limit', $this->img_limit);
			$this->app->input->set('limitstart', $this->img_limitstart);

	        $this->files = $model->loadFiles($this->category->id, $this->params);

			$this->filesPagination = $model->getFilesPagination($this->category->id, $this->params);
			$this->files_order = $model->getFilesOrder($this->params);

			$this->is_modal = 1;
			$this->is_html = false;
			$this->open_as_popup = false;
			$this->active_item_id = $this->item->id;

			$doc = JFactory::getDocument();
			$this->title = $doc->getTitle();
			if (mb_strtolower($this->title) != mb_strtolower($this->item->name)) {
				$this->title .= ' - '.$this->item->name;
			}

			echo fwgHelper::escPluginsOutput($this->loadTemplate());
		}
		die();
	}
}
