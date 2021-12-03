<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$effect = $view->params->get('file_hover', 'none');
$layout = $view->params->get('file_layout', 'bottom');
$row = $displayData['row'];

$classes = array(
	'fwmg-grid-item',
	'fwmg-effect-'.$effect,
	'fwmg-info-layout-'.$layout
);
if (!$view->params->get('show_file_icon', 1)) {
	$classes[] = 'fwmg-hide-file-type';
}

$link = fwgHelper::route('index.php?option=com_fwgallery&view=item&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name).'&Itemid='.$view->app->input->getInt('Itemid'));
?>
<div class="<?php echo implode(' ', $classes); ?>">
<?php
if ($row->type == 'image') {
?>
	<img src="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&layout=img&format=raw&w='.$view->params->get('image_width_listing', 300).'&h='.$view->params->get('image_height_listing', 300).'&js=1&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name)); ?>" />
<?php
} else {
	$view->app->triggerEvent('onshowFileListTypeOutput', array('com_fwgallery.'.$row->type, $displayData));
}
?>
	<a href="<?php echo esc_attr($link); ?>" class="fwmg-grid-item-custom-link<?php if (!empty($view->open_as_popup)) { ?> fwmg-modal<?php } ?>"></a>
<?php
if ($layout != 'hide') {
?>
	<div class="fwmg-grid-item-info">
<?php
	echo fwgHelper::loadTemplate('listing.file_text', array_merge($displayData, array(
		'link' => $link
	)));
?>
    </div>
<?php
}
?>
</div>
