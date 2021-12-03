<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::stylesheet('components/com_fwgallery/assets/css/lightgallery.min.css');
JHTML::_('jquery.framework');
JHTML::script('components/com_fwgallery/assets/js/lightgallery.min.js');
JHTML::script('components/com_fwgallery/assets/js/lg-fullscreen.min.js');
JHTML::script('components/com_fwgallery/assets/js/lg-zoom.min.js');

$view = $displayData['view'];

$params = $view->params;
$active_menu = JMenu::getInstance('site')->getActive();
$menu_params = $active_menu?$active_menu->getParams():new JRegistry();

if ($menu_params->get('show_page_heading', 1)) {
?>
<h1><?php echo $menu_params->get('page_heading', $menu_params->get('page_title')); ?></h1>
<?php
}
if ($view->files) {
    $buff = array();
    foreach ($view->files as $file) {
        if (!isset($buff[$file->category_id])) {
            $buff[$file->category_id] = array();
        }
        $buff[$file->category_id][] = $file;
    }
	$num = 0;
    foreach ($buff as $category_id=>$files) {
		$view->category = null;
		$descr_right = false;
        if ($view->subcategories) {
            foreach ($view->subcategories as $category) {
                if ($category->id == $category_id) {
					$view->category = $category;
					break;
                }
            }
        }
        $view->files = $files;
		if ($view->category) {
			$view->params = new fwgParams($view->category->params, fwgHelper::getParentGalleries($view->category->parent));
			$view->params->set('show_files_ordering', false);
			$view->params->set('show_files_limit', false);

			$view->open_as_popup = ($view->params->get('file_open_as') == 'popup');
			if (!$view->open_as_popup) {
				$view->app->triggerEvent('onCheckFileOpenAsPopup', array('com_fwgallery', &$view->open_as_popup, $view));
			}
?>
			<div id="fwgallery" class="fwmg-design-<?php echo esc_attr($view->params->get('template')); ?>">
<?php
			echo fwgHelper::loadTemplate('listing.top_category', $displayData);

			if (!$num) {
				if ($params->get('show_gallery_breadcrumbs', 1) and $view->path) {
?>
<div class="fwmg-gallery-path">
	<i class="fal fa-folder-open"></i>
<?php
if ($view->path) {
	foreach ($view->path as $i => $step) {
		if ($i) {
			?> &gt; <?php
		}
		?><a href="<?php echo esc_attr($step->link); ?>"><?php echo esc_html($step->name); ?></a><?php
	}
}
?>
</div>
<?php
				}
			}

			$descr_right = ($params->get('gallery_descr_position', 'top') != 'top' and !empty($view->category->descr) and $params->get('show_gallery_description', 1));

			if ($descr_right) {
?>
	<div class="row">
		<div class="col-md-9">
<?php
			}
		}
        echo fwgHelper::loadTemplate('listing.files', $displayData);
		if ($view->category) {
			if ($descr_right) {
?>
		</div>
		<div class="col-md-3">
			<?php echo fwgHelper::escPluginsOutput($view->category->descr); ?>
		</div>
	</div>
<?php
			}
?>
	</div>
<?php
			$view->app->triggerEvent('onshowListingGoogleData', array('com_fwgallery', $view));
		}
		$num++;
    }
}
if (!empty($view->open_as_popup)) {
?>
<div class="fwcss">
    <div class="modal fade" id="fwmg-one-file">
        <div class="modal-dialog modal-huge" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</div>
<?php
}
