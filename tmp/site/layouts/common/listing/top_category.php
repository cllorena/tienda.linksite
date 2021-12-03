<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

if ($view->params->get('show_gallery_name', 1)) {
?>
<div class="fwmg-page-header">
    <h2 itemprop="headline" data-title="<?php echo esc_attr($view->category->name); ?>"><?php echo esc_html($view->category->name); ?></h2>
</div>
<?php
}
?>

<div class="clearfix fwmg-gallery-info">
<?php
if ($view->params->get('show_gallery_update_date', 1) and $view->category->updated) {
?>
    <span class="fwmg-gallery-info-date">
        <i class="fal fa-calendar-alt"></i> <?php echo fwgHelper::encodeDate($view->category->updated); ?>
    </span>
<?php
}
if ($view->params->get('show_gallery_owner', 1) and $view->category->_user_name) {
?>
    <span class="fwmg-gallery-info-owner">
        <i class="fal fa-user"></i> <?php echo esc_html($view->category->_user_name); ?>
    </span>
<?php
}
if ($view->params->get('show_gallery_counter', 1)) {
?>
    <span class="fwmg-gallery-info-counter">
<?php
   if (!empty($view->category->_qty)) {
?>
        <span class="fwmg-gallery-info-counter-images">
            <i class="fal fa-folder"></i> <?php echo (int)$view->category->_qty; ?>
        </span>
<?php
	}
	if (!empty($view->category->_images_qty)) {
?>
        <span class="fwmg-gallery-info-counter-images">
            <i class="fal fa-image"></i> <?php echo (int)$view->category->_images_qty; ?>
        </span>
<?php
	}
	$buff = $view->app->triggerEvent('ongetCategoryExtraCounters', array('com_fwgallery', $view->category));
	foreach ($buff as $row) {
		if ($row) {
?>
        <span class="fwmg-gallery-info-counter-images">
            <?php echo fwgHelper::escPluginsOutput($row); ?>
        </span>
<?php
		}
	}
?>
    </span>
<?php
}
?>
</div>
<?php
if ($view->params->get('show_gallery_description', 1) and $view->category->descr and $view->params->get('gallery_descr_position', 'top') == 'top') {
?>
<div class="fwmg-gallery-description">
    <?php echo fwgHelper::escPluginsOutput($view->category->descr); ?>
</div>
<?php
}

$view->app->triggerEvent('onshowCategoryExtraFields', array('com_fwgallery', array_merge(array('row'=>$view->category), $displayData)));
