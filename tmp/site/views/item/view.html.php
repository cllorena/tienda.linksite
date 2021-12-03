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
			$this->item->descr = JHtml::_('content.prepare', $this->item->descr);
		}

		$doc = JFactory::getDocument();
		$title = $doc->getTitle();
		if (mb_strtolower($title) != mb_strtolower($this->item->name)) {
			$doc->setTitle($title.' - '.$this->item->name);
		}

		$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));
		$this->app->triggerEvent('onbeforeFileDisplay', array('com_fwgallery', $this->item, &$this->params));
//		$this->params->set('gids', array($this->category->id));

		$this->img_position = $model->getFilePosition($this->item->id, $this->category->id, $this->params);
		$this->img_limit = 30;
		$this->img_limitstart = min(max(0, $this->img_position->total_qty - 30), max(0, $this->img_position->pos - ($this->img_limit / 2)));

		$this->app->input->set('limit', $this->img_limit);
		$this->app->input->set('limitstart', $this->img_limitstart);

        $this->files = $model->loadFiles($this->category->id, $this->params);

		$this->filesPagination = $model->getFilesPagination($this->category->id, $this->params);
		$this->files_order = $model->getFilesOrder($this->params);
		$this->open_as_popup = false;
		$this->active_item_id = $this->item->id;

		$this->is_html = $this->app->input->getCmd('format', 'html') == 'html';
		parent::display($tmpl);
	}
}
