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

		$this->app = JFactory::getApplication();
		$this->app->triggerEvent('onbeforeCategoryLoad', array('com_fwgallery', $this));

        $this->category = $model->loadCategory();
		$this->user = JFactory::getUser();

		if ($this->category->access and !in_array($this->category->access, $this->user->getAuthorisedViewLevels())) {
			echo JText::_('FWMG_GALLERY_ACCESS_NOT_ALLOWED');
			return;
		} elseif ($this->category->id and !$this->category->published) {
			echo JText::_('FWMG_GALLERY_NOT_PUBLISHED');
			return;
		}
		if ($this->category->descr) {
			$this->category->descr = JHtml::_('content.prepare', $this->category->descr);
		}
		$this->subcategory_id = 0;
		if ($this->category->id) {
			$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
			if (!in_array($this->category->id, $this->params->get('gids', array()))) {
				$this->subcategory_id = $this->category->id;
			}
			$this->params->set('gids', array($this->category->id));
			$this->path = fwgHelper::loadCategoriesPath($this->category->id, $this->app->input->getInt('Itemid'));
			if ($this->path) {
				$menu = JMenu::getInstance('site');
				if ($active = $menu->getActive()) {
					array_unshift($this->path, (object)array(
						'id' => 0,
						'alias' => $active->alias,
						'name' => $active->title,
						'link' => 'index.php?Itemid='.$active->id
					));
				}
			}

			$this->app->getParams()->set('gallery_columns', $this->params->get('gallery_columns'));
			$this->app->getParams()->set('gallery_rows', $this->params->get('gallery_rows'));
			$this->app->getParams()->set('files_columns', $this->params->get('files_columns'));
			$this->app->getParams()->set('files_rows', $this->params->get('files_rows'));

			$doc = JFactory::getDocument();
			$title = $doc->getTitle();
			if (mb_strtolower($title) != mb_strtolower($this->category->name)) {
				$doc->setTitle($title.' - '.$this->category->name);
			}
		} else {
			$this->params = new fwgParams(new JRegistry);
			$this->path = array();
		}

		$this->layout = $this->params->get('layout', 'list');
		if (!in_array($this->layout, array('list', 'flat', 'grid'))) {
			$this->layout = 'list';
		}

		$this->app->triggerEvent('onbeforeFilesLoad', array('com_fwgallery', $this->category, $this));

		$this->files_order = $model->getFilesOrder($this->params);
		$this->subcategories_order = $model->getSubcategoriesOrder($this->params);

		if ($this->layout == 'flat') {
			/* flat layout */
			$this->params->set('show_files_ordering', false);
			$this->params->set('show_files_limit', false);

			$qty = $model->loadFlatFilesQty($this->params);
			$this->app->input->set('fileslimitstart', 0);
			$this->app->input->set('fileslimit', $qty);

			$this->files = $model->loadFlatFiles($this->params);
	        $this->filesPagination = $model->getFlatFilesPagination($this->params);
			if ($this->files_order) {
				$this->filesPagination->setAdditionalUrlParam('files_order', $this->files_order);
			}
			$this->subcategories = $model->loadFlatSubcategories($this->files);
			$this->app->triggerEvent('onbeforeCategoryDisplay', array('com_fwgallery', $this->category, $this));
		} elseif ($this->layout == 'list') {
			$this->subcategories = $model->loadSubcategories($this->params);
			if ($this->subcategories) {
				foreach ($this->subcategories as $i=>$file) {
					if ($file->descr) {
						$this->subcategories[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('description_length'));
					}
				}
			}
			$this->files = $model->loadFiles($this->params);
			if ($this->files) {
				foreach ($this->files as $i=>$file) {
					if ($file->descr) {
						$this->files[$i]->descr = fwgHelper::stripTags($file->descr, $this->params->get('image_description_length'));
					}
				}
			}
			$this->filesPagination = $model->getFilesPagination($this->params);
			$this->subcategoriesPagination = $model->getSubcategoriesPagination($this->params);
			if ($this->subcategories_order) {
				$this->subcategoriesPagination->setAdditionalUrlParam('subcategories_order', $this->subcategories_order);
				$this->filesPagination->setAdditionalUrlParam('subcategories_order', $this->subcategories_order);
			}

			if ($this->files_order) {
				$this->subcategoriesPagination->setAdditionalUrlParam('files_order', $this->files_order);
				$this->filesPagination->setAdditionalUrlParam('files_order', $this->files_order);
			}
			$this->app->triggerEvent('onbeforeCategoryDisplay', array('com_fwgallery', $this->category, $this));

			$this->search = fwgHelper::getSearchData();
			if ($this->search) {
				foreach ($this->search as $i=>$row) {
					if (empty($row)) continue;
					if (is_array($row)) {
						foreach ($row as $j=>$subrow) {
							if (empty($subrow)) continue;
							$this->filesPagination->setAdditionalUrlParam('search['.$i.']['.$j.']', $subrow);
						}
					} else {
						$this->filesPagination->setAdditionalUrlParam('search['.$i.']', $row);
					}
				}
			}
		} elseif ($this->layout == 'grid') {
			/* cascaging grid */
			$this->subcategories = $model->loadAllCategories($this->params);
			$designs = array();
			if ($buff = $this->params->get('template')) {
				$designs[] = $buff;
			}
			if ($this->subcategories) {
				$path = JPATH_SITE.'/plugins/fwgallerytmpl/';
				JHTML::stylesheet('components/com_fwgallery/assets/css/fwmg-design-styles.css', array('version'=>'v=100'));
				foreach ($this->subcategories as $cat) {
					$params = new fwgParams($cat->params, fwgHelper::getParentGalleries($cat->parent));
					if ($buff = $params->get('template') and !in_array($buff, $designs) and file_exists($path.$buff.'/assets/css/fwmg-design-styles.css')) {
						JHTML::stylesheet('plugins/fwgallerytmpl/'.$buff.'/assets/css/fwmg-design-styles.css');
						$designs[] = $buff;
					}
				}
			}
			$this->app->triggerEvent('onbeforeCategoryDisplay', array('com_fwgallery', $this->category, $this));
		}
		$this->open_as_popup = ($this->params->get('file_open_as') == 'popup');
		if (!$this->open_as_popup) {
			$this->app->triggerEvent('onCheckFileOpenAsPopup', array('com_fwgallery', &$this->open_as_popup, $this));
		}
		$this->is_html = $this->app->input->getCmd('format', 'html') == 'html';
		parent::display($tmpl);
	}
	function getPaginationLinks($pagination, $options = array()) {
		$list = array(
			'prefix'       => $pagination->prefix,
			'limit'        => $pagination->limit,
			'limitstart'   => $pagination->limitstart,
			'total'        => $pagination->total,
			'limitfield'   => $pagination->getLimitBox(),
			'pagescounter' => $pagination->getPagesCounter(),
			'pages'        => $pagination->getPaginationPages(),
			'pagesTotal'   => $pagination->pagesTotal,
		);
		return fwgHelper::loadTemplate('pagination.links', array('view' => $this, 'list' => $list, 'options' => $options));
	}
}
