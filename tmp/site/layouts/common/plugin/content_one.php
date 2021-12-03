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

$styles = array();
if ($width = $displayData['width'] and is_numeric($width) and $width <= 100) {
	$styles[] = 'width:'.$width.'%';
}
if (in_array($displayData['float'], array('left', 'right'))) {
	$styles[] = 'float:'.$displayData['float'];
}
?>
<div class="fwmg-plg-content-one-file"<?php if ($styles) { ?> style="<?php echo implode(';', $styles); ?>"<?php } ?>>
	<div id="fwgallery" class="fwcss fwmg-design-<?php echo esc_attr($params->get('template', 'common')); ?>">
		<?php echo fwgHelper::loadTemplate('listing.file', $displayData); ?>
	</div>
</div>
