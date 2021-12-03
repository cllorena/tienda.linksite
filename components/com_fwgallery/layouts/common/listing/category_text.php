<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$row = $displayData['row'];
$link = $displayData['link'];

if ($view->params->get('show_gallery_item_name', 1)) {
?>
<div class="fwmg-grid-item-name">
    <a href="<?php echo esc_attr($link); ?>" title="<?php echo esc_attr($row->name); ?>">
        <?php echo esc_html($row->name); ?></a>
</div>
<?php
}
if ($view->params->get('show_gallery_item_counter', 1)) {
?>
<div class="fwmg-grid-item-stats">
<?php if ((int)$row->_qty) : ?>
    <span class="fwmg-grid-item-stats-galleries">
        <i class="fal fa-folder"></i> <?php echo (int)$row->_qty; ?>
    </span>
<?php endif; ?>
<?php if ((int)$row->_images_qty) : ?>
    <span class="fwmg-grid-item-stats-images">
        <i class="fal fa-image"></i> <?php echo (int)$row->_images_qty; ?>
    </span>
<?php endif; ?>
    <?php echo fwgHelper::escPluginsOutput(implode('', $view->app->triggerEvent('ongetCategoryListExtraCounters', array('com_fwgallery', $row)))); ?>
</div>
<?php
}

if ($view->params->get('show_gallery_item_update_date', 1)) {
?>
<div class="fwmg-grid-item-update"><i class="fal fa-calendar-alt"></i> <?php echo fwgHelper::encodeDate($row->updated); ?></div>
<?php
}
?>
<?php
if ($view->params->get('show_gallery_item_owner', 1)) {
?>
<div class="fwmg-grid-item-owner"><i class="fal fa-user"></i> <?php echo esc_html($row->_user_name); ?></div>
<?php
}

if ($view->params->get('show_gallery_item_description', 1)) {
?>
<div class="fwmg-grid-item-description"><?php echo fwgHelper::stripTags($row->descr, $view->params->get('description_length', 20)); ?></div>
<?php
}

$view->app->triggerEvent('onshowCategoriesListingExtraFields', array('com_fwgallery', $displayData));
