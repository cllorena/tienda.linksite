<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$management = array();
$view->app->triggerEvent('ongetUsersectionMenu', array('com_fwgallery', $view, &$management));

$current_layout = $view->getLayout();

if ($management) {
?><div class="fwmg-management-panel-section">
	<div class="fwmg-management-panel-header"><?php echo JText::_('FWMG_USER_MENU'); ?></div>
	<ul class="nav"><?php
	foreach ($management as $layout=>$item) {
		$active = ($current_layout == $layout);

		$buff = explode('_', $current_layout);
		if (count($buff) == 2 and $buff[1] == 'edit' and $buff[0] == $layout) {
			$active = true;
		}
		?><li class="nav-item"><?php
		?><a class="nav-link<?php if ($active) { ?> active<?php } ?>" href="<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=usersection&layout='.$layout.'&Itemid='.$view->app->input->getInt('Itemid')); ?>"><i class="far fa-<?php echo esc_attr($item['icon']); ?> fa-fw"></i> <?php echo JText::_($item['title']); ?></a><?php
		?></li><?php
	}
?></ul>
	</div>
<?php
}
