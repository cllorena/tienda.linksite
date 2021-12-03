<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$params = $view->params;
$displayData['row'] = $row = $view->item;
$displayData['link'] = JURI::getInstance()->toString();
$name = JFilterOutput::stringURLSafe($row->name);
?>
            <div class="fwmg-file-thumb-img">
<?php
if ($row->type == 'image') {
    $img_attrs = array();
    $view->app->triggerEvent('oncollectFWGFileImageAltAttributes', array('com_fwgallery', $row, $view, &$img_attrs));
    if (!$img_attrs) $img_attrs[] = $row->name;
?>
                <img alt="<?php echo esc_attr(implode(' ', $img_attrs)); ?>" data-id="<?php echo (int)$row->id; ?>" src="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&layout=img&format=raw&js=1&w='.$params->get('image_width_lightbox', 2048).'&h='.$params->get('image_height_lightbox', 2048).'&id='.$row->id.':'.$name); ?>" />
<?php
	if ($params->get('show_file_fullscreen', 1)) {
?>
                <a href="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&layout=img&format=raw&js=1&w='.$params->get('image_width_lightbox', 2048).'&h='.$params->get('image_height_lightbox', 2048).'&id='.$row->id.':'.$name); ?>" class="btn btn-secondary fwmg-item-full"><i class="fal fa-expand"></i></a>
<?php
	}
} else {
	$view->app->triggerEvent('onshowFileTypeOutput', array('com_fwgallery.'.$row->type, $displayData));
}

?>

            </div>
<script>
<?php
if (empty($view->is_html)) {
?>
setTimeout(function() {
	(function($) {
<?php
} else {
?>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
<?php
}
if ($row->type == 'image' and $params->get('show_file_fullscreen', 1)) {
?>
	$('.fwmg-file-thumb-img').lightGallery({
		selector:'.fwmg-item-full',
		download: false,
		hideBarsDelay: 10000
	});
<?php
}
if (empty($view->is_html)) {
?>
	$('.fwmg-file .fwmg-page-header h2').html('<?php echo esc_js($row->name); ?>');
	//location.
<?php
	if (!empty($view->title)) {
?>
	$('title').html('<?php echo esc_js($view->title); ?>');
<?php
	}
	if (empty($view->is_modal)) {
		if (!empty($view->title)) {
?>
	window.history.pushState({'html':null,"pageTitle":'<?php echo esc_js($view->title); ?>'}, '', '<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=item&id='.$row->id.':'.$name, false); ?>');
<?php
		}
	} else {
?>
	location.hash = '<?php echo (int)$row->id; ?>:<?php echo esc_js($name); ?>';
<?php
	}
}
if (empty($view->is_html)) {
?>
	})(jQuery);
}, 500);
<?php
} else {
?>
	})(jQuery);
});
<?php
}
?>
</script>