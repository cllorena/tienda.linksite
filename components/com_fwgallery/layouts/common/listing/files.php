<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$grid = $view->params->get('files_grid', 'standard');
?>
<div class="fwmg-files">
	<div id="fwmg-grid" class="fwmg-grid-wrapper fwmg-grid-<?php echo esc_attr($grid); ?>">
		<?php echo fwgHelper::loadTemplate('listing.files_list', $displayData); ?>
    </div>
	<?php echo fwgHelper::loadTemplate('listing.files_footer', $displayData); ?>
</div>
