<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$tabs = array(
	'plugin'=>'PLUGINS',
	'module'=>'MODULES',
	'theme'=>'THEMES'
);
?>
<div>
	<ul class="nav nav-tabs" role="tablist">
<?php
foreach ($tabs as $tab=>$title) {
?>
		<li role="presentation" class="nav-item">
			<a class="nav-link<?php if ($tab == $displayData['tab']) { ?> active<?php } ?>" href="index.php?option=com_fwgallery&view=addon&tab=<?php echo esc_attr($tab); ?>"><?php echo JText::_('FWMG_'.$title); ?></a>
		</li>
<?php
}
?>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active">
