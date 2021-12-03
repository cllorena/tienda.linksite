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
?>
<a href="<?php echo esc_attr($link); ?>" class="fwmg-grid-item-<?php echo esc_attr($row->type); ?>-wrapper<?php if (!empty($view->open_as_popup)) { ?> fwmg-modal<?php } ?>">
    <div class="fwmg-grid-item-<?php echo esc_attr($row->type); ?>">
<?php
if ($row->type == 'image') {
    $img_attrs = array();
    $view->app->triggerEvent('oncollectFWGFileImageAltAttributes', array('com_fwgallery', $row, $view, &$img_attrs));
    if (!$img_attrs) $img_attrs[] = $row->name;
?>
		<img alt="<?php echo esc_attr(implode(' ', $img_attrs)); ?>" src="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&layout=img&format=raw&w='.$view->params->get('image_width_listing', 300).'&h='.$view->params->get('image_height_listing', 300).'&js=1&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name)); ?>" />
<?php
} else {
	$view->app->triggerEvent('onshowFileListTypeOutput', array('com_fwgallery.'.$row->type, $displayData));
}
?>
    </div>
</a>
