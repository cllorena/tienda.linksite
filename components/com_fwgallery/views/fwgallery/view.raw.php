<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryViewFwGallery extends JViewLegacy {
	function display($tmpl=null) {
		$model = $this->getModel();
		$this->user = JFactory::getUser();
		switch ($this->getLayout()) {
		case 'img' :
			if ($filename = $model->getImage()) {
				$send_data = true;
				if (!headers_sent()) {
					$headers = apache_request_headers();
					$time = filemtime($filename);
					$info = getimagesize($filename);
					header('Cache-Control: public');
					header("Pragma: cache");
					if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $time)) {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 304);
						$send_data = false;
					} else {
						header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
					}
					header('Content-Length: '.filesize($filename));
					header("Content-type: {$info['mime']}");
				}
				if ($send_data) print file_get_contents($filename);
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
		case 'load_files' :
			$this->app = JFactory::getApplication();
			$this->app->input->set('subcategorieslimit', 0);
			$this->app->input->set('subcategorieslimitstart', 0);
			$this->app->input->set('fileslimit', 0);
			$this->app->input->set('fileslimitstart', 0);
			$this->app->input->set('files_order', $this->app->input->get('order'));
			$this->category = $model->loadCategory();

			$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			if ($this->category->id) {
			    $this->params->set('gids', array($this->category->id));
			}

			$this->params->set('gallery_pagination_type', false);
			$this->params->set('show_gallery_ordering', false);
			$this->params->set('show_gallery_limit', false);

			$this->params->set('files_pagination_type', false);
			$this->params->set('show_files_ordering', false);
			$this->params->set('show_files_limit', false);

			$descr_right = ($this->params->get('gallery_descr_position', 'top') != 'top' and !empty($this->category->descr) and $this->params->get('show_gallery_description', 1));

			$this->subcategories = $model->loadSubcategories($this->params);
			if ($this->subcategories) {
				foreach ($this->subcategories as $i=>$file) {
					if ($file->descr) {
						$this->subcategories[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('description_length'));
					}
				}
			}

			$this->files = (array)$model->loadFiles($this->params);
			$this->files_order = $model->getFilesOrder($this->params);
			if ($this->files) {
				foreach ($this->files as $i=>$file) {
					if ($file->descr) {
						$this->files[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('image_description_length'));
					}
				}
			}
?>
<div id="fwgallery" class="fwcss fwmg-design-<?php echo esc_attr($this->params->get('template', 'common')); ?>">
<?php
			if ($this->category->name) {
				echo fwgHelper::loadTemplate('listing.top_category', array(
					'view'=>$this
				));
			}
			if ($this->subcategories) {
				echo fwgHelper::loadTemplate('listing.categories', array(
					'view'=>$this
				));
			}
			if ($descr_right) {
?>
<div class="row">
	<div class="col-md-9">
<?php
			}
			echo fwgHelper::loadTemplate('listing.files', array(
				'view'=>$this
			));
			if ($descr_right) {
?>
		</div>
		<div class="col-md-3">
			<?php echo fwgHelper::escPluginsOutput($this->category->descr); ?>
		</div>
	</div>
<?php
			}
?>
</div>
<?php
		break;
		}
		die();
	}
}
