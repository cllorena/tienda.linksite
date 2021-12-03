<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$effect = $view->params->get('gallery_hover', 'none');
$layout = $view->params->get('gallery_layout', 'bottom');
$row = $displayData['row'];

$link = fwgHelper::route('index.php?option=com_fwgallery&view=fwgallery&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name).'&Itemid='.$view->app->input->getInt('Itemid'));
?>
<div class="fwmg-grid-item fwmg-effect-<?php echo esc_attr($effect); ?> fwmg-info-layout-<?php echo esc_attr($layout); if (!$view->params->get('dont_gallery_item_video_autoplay')) { ?> fwmg-grid-item-autoplay<?php } ?>">
<?php
echo fwgHelper::loadTemplate('listing.category_image', array_merge($displayData, array(
	'link' => $link
)));
?>
    <div class="fwmg-grid-item-info">
<?php
echo fwgHelper::loadTemplate('listing.category_text', array_merge($displayData, array(
	'link' => $link
)));
?>
    </div>
</div>
