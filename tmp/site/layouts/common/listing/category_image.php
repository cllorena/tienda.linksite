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
$link = fwgHelper::route('index.php?option=com_fwgallery&view=fwgallery&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name).'&Itemid='.$view->app->input->getInt('Itemid'));
$img_link = fwgHelper::route('index.php?option=com_fwgallery&view=fwgallery&layout=img&format=raw&w='.$view->params->get('image_width_listing', 300).'&h='.$view->params->get('image_height_listing', 300).'&js=1&id='.$row->id.':'.JFilterOutput::stringURLSafe($row->name));

$file_type = 'image';
if ($row->media_code and in_array($row->media, array('youtube', 'vimeo', 'mp4'))) $file_type = 'video';
?>
<a href="<?php echo esc_attr($link); ?>" class="fwmg-grid-item-<?php echo esc_attr($file_type); ?>-wrapper">
<?php
$media_displayed = false;
if (!$view->params->get('dont_gallery_item_video_autoplay')) {
	if ($row->media_code and in_array($row->media, array('youtube', 'vimeo', 'mp4'))) {
		switch ($row->media) {
			case 'youtube' :
?>
	<iframe src="https://www.youtube.com/embed/<?php echo esc_attr($row->media_code); ?>?rel=0&amp;fs=1" frameborder="0" allow="autoplay; fullscreen" allowfullscreen="allowfullscreen" allowautoplay></iframe>
<?php
			$media_displayed = true;
			break;
			case 'vimeo' :
?>
	<iframe src="https://player.vimeo.com/video/<?php echo esc_attr($row->media_code); ?>" frameborder="0" allow="autoplay" webkitallowfullscreen mozallowfullscreen allowfullscreen="allowfullscreen"></iframe>
<?php
			$media_displayed = true;
			break;
			case 'mp4' :
			$path = fwgHelper::getImagePath($row->media_code);
			if (file_exists($path.$row->media_code)) {
?>
	<video class="video-js vjs-default-skin vjs-mental-skin" width="100%" height="100%" controls preload="none" poster="<?php echo esc_attr($img_link); ?>" data-setup="{}">
	   <source src="<?php echo esc_attr(fwgHelper::getImageLink($row->media_code).$row->media_code); ?>" type="video/mp4" />
	</video>
<?php
				$media_displayed = true;
			}
			break;
		}
	}
}
if (!$media_displayed) {
    $img_attrs = array();
    $view->app->triggerEvent('oncollectFWGCategoryImageAltAttributes', array('com_fwgallery', $row, $view, &$img_attrs));
	if (!$img_attrs) $img_attrs[] = $row->name;
?>
	<div class="fwmg-grid-item-image">
		<img alt="<?php echo esc_attr(implode(' ', $img_attrs)); ?>" src="<?php echo esc_attr($img_link); ?>" />
	</div>
<?php
}
?>
</a>
