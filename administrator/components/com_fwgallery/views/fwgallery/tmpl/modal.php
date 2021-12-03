<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div class="container-popup">
	<ul class="nav nav-tabs">
<?php
if ($this->sublayout == 'galleries') {
?>
	<li class="active"><a href="javascript:"><?php echo JText::_('FWMG_SELECT_GALLERY_TO_INSERT'); ?></a></li>
<?php
} else {
?>
	<li><a href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=galleries&tmpl=component')); ?>"><?php echo JText::_('FWMG_SELECT_GALLERY_TO_INSERT'); ?></a></li>
<?php
}
if ($this->sublayout == 'images') {
?>
	<li class="active"><a href="javascript:"><?php echo JText::_('FWMG_SELECT_IMAGE_TO_INSERT'); ?></a></li>
<?php
} else {
?>
	<li><a href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=images&tmpl=component')); ?>"><?php echo JText::_('FWMG_SELECT_IMAGE_TO_INSERT'); ?></a></li>
<?php
}
?>
	</ul>
	<div class="tab-content">
<?php
include('modal_'.$this->sublayout.'.php');
?>
	</div>
</div>
