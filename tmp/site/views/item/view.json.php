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
		$this->app = JFactory::getApplication();
		$model = $this->getModel();
		$data = new stdclass;
		switch ($this->getLayout()) {
			case 'get_item_link' :
			$data->result = $model->getItemLink();
			break;
			case 'tmpl' :
			$this->item = $model->loadFile();
			$this->user = JFactory::getUser();

			if (!$this->item->id) {
				$data->msg = JText::_('FWMG_FILE_NOT_FOUND');
			} elseif ($this->item->access and !in_array($this->item->access, $this->user->getAuthorisedViewLevels())) {
				$data->msgJText::_('FWMG_FILE_ACCESS_NOT_ALLOWED');
			} elseif (!$this->item->published) {
				$data->msg = JText::_('FWMG_FILE_NOT_PUBLISHED');
			} else {
				$cid = $model->getCategoryId($this->item);
				$this->category = $model->loadCategory($cid);

				if ($this->category->access and !in_array($this->category->access, $this->user->getAuthorisedViewLevels())) {
					$data->msg = JText::_('FWMG_GALLERY_ACCESS_NOT_ALLOWED');
				} elseif ($this->category->id and !$this->category->published) {
					$data->msg = JText::_('FWMG_GALLERY_NOT_PUBLISHED');
				}

				if ($this->item->descr) {
					$this->item->descr = fwgHelper::fixDescriptionImagesLinks(JHtml::_('content.prepare', $this->item->descr));
				}

				$this->params = new fwgParams($this->category->params, fwgHelper::getParentGalleries($this->category->parent));

				$this->img_position = $model->getFilePosition($this->item->id, $this->category->id, $this->params);
				$this->img_limit = 30;
				$this->img_limitstart = min($this->img_position->total_qty - 30, max(0, $this->img_position->pos - ($this->img_limit/2)));

				$this->app->input->set('limit', $this->img_limit);
				$this->app->input->set('limitstart', $this->img_limitstart);

				$this->files = $model->loadFiles($this->category->id, $this->params);
				$this->is_html = false;
				$this->open_as_popup = false;
				$this->active_item_id = $this->item->id;

				$data->title = $this->item->name;
				$data->link = fwgHelper::route('index.php?option=com_fwgallery&view=item&w=&h=&layout=&tmpl=&format=html&id='.$this->item->id.':'.JFilterOutput::stringURLSafe($this->item->name), false);
				$data->html = fwgHelper::loadTemplate($this->app->input->getString('tmpl', 'item.item'), array('view'=>$this));
			}
			break;
		}
		$data->msg = $model->getError();
		die(json_encode($data));
	}
}
