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
		$data = new stdclass;
		$this->app = JFactory::getApplication();
		switch ($this->getLayout()) {
		case 'load_categories_list' :
		    $this->app->input->set('subcategories_order', $this->app->input->get('order'));
		    $this->app->input->set('subcategorieslimit', $this->app->input->get('fileslimit'));
			$this->app->input->set('subcategorieslimitstart', $this->app->input->get('fileslimitstart'));
			$this->category = $model->loadCategory();
			$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			if ($this->category->id) {
			    $this->params->set('gids', array($this->category->id));
			}
			$this->subcategories = $model->loadSubcategories($this->params);
			$this->subcategories_order = $model->getSubcategoriesOrder($this->params);
			if ($this->subcategories) {
				foreach ($this->subcategories as $i=>$file) {
					if ($file->descr) {
						$this->subcategories[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('description_length'));
					}
				}
			}
			$this->no_grid_wrap = true;
			$data->html = fwgHelper::loadTemplate('listing.categories_list', array(
				'view' => $this
			));
		break;
		case 'load_files_list' :
			$this->app->input->set('files_order', $this->app->input->get('order'));
			$this->category = $model->loadCategory();
			$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			if ($this->category->id) {
			    $this->params->set('gids', array($this->category->id));
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
			$this->no_grid_wrap = true;
			$this->open_as_popup = ($this->params->get('file_open_as') == 'popup');
			if (!$this->open_as_popup) {
				$this->app->triggerEvent('onCheckFileOpenAsPopup', array('com_fwgallery', &$this->open_as_popup, $this));
			}
			$data->html = fwgHelper::loadTemplate('listing.files_list', array(
				'view'=>$this
			));
		break;
		}
		$data->msg = implode('\\n', $model->getErrors());
		die(json_encode($data));
	}
}
