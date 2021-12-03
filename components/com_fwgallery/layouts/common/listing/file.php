<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
/** @var Type $displayData */

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$effect = $view->params->get('file_hover', 'none');
$layout = $view->params->get('file_layout', 'bottom');
$row = $displayData['row'];

$classes = array(
	'fwmg-grid-item',
	'fwmg-grid-block-'.$row->type,
	'fwmg-effect-'.$effect,
	'fwmg-info-layout-'.$layout
);
if (!$view->params->get('show_file_icon', 1)) {
	$classes[] = 'fwmg-hide-file-type';
}
$view->app->triggerEvent('onaddFileExtraClasses', array('com_fwgallery', $row, &$classes, $view));

$link = 'index.php?option=com_fwgallery&view=item&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name).'&files_order='.$view->files_order.((!empty($view->subcategory_id) and $view->subcategory_id != $row->category_id)?('&cid='.$view->subcategory_id):'').'&Itemid='.$view->app->input->getInt('Itemid');
$view->app->triggerEvent('onaddFileLinkParameters', array('com_fwgallery', &$link));
$link = fwgHelper::route($link);
?>
<div class="<?php echo implode(' ', $classes); ?>">
<?php
echo fwgHelper::loadTemplate('listing.file_image', array_merge($displayData, array(
	'link' => $link
)));
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
