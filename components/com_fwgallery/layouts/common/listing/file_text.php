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

if ($view->params->get('show_files_name', 1)) {
?>
<div class="fwmg-grid-item-name">
	<a href="<?php echo esc_attr($link); ?>"<?php if (!empty($view->open_as_popup)) { ?> class="fwmg-modal"<?php } ?> title="<?php echo esc_attr($row->name); ?>">
		<?php echo esc_html($row->name); ?></a></div>
<?php
}
if ($view->params->get('show_files_update', 1)) {
?>
<div class="fwmg-grid-item-upload">
	<i class="fal fa-calendar-alt fa-fw"></i> <?php echo fwgHelper::encodeDate($row->updated); ?>
</div>
<?php
}
if ($view->params->get('show_files_owner', 1)) {
?>
<div class="fwmg-grid-item-owner">
	<i class="fal fa-user fa-fw"></i> <?php echo esc_html($row->_user_name); ?>
</div>
<?php
}
if ($view->params->get('show_files_description', 1)) {
?>
<div class="fwmg-grid-item-description"><?php echo fwgHelper::stripTags($row->descr, $view->params->get('image_description_length', 100)); ?></div>
<?php
}

$view->app->triggerEvent('onshowFilesListingExtraFields', array('com_fwgallery', $displayData));

$show_download = ($view->params->get('show_files_download', 1) and ($access = $view->params->get('download_access') and in_array($access, $view->user->getAuthorisedViewLevels())) and fwgHelper::fileDownloadable($row));
$show_files_info = $view->params->get('show_files_info', 1);
if ($show_download or $show_files_info) {
?>
<div class="fwmg-file-download">
<?php
	if ($show_download) {
?>
	<a class="btn btn-primary" href="<?php echo fwgHelper::checkLink(fwgHelper::route('index.php?option=com_fwgallery&view=item&format=raw&layout=download&id='.$row->id)); ?>">
		<?php echo JText::_('FWMG_DOWNLOAD'); ?>
	</a>
<?php
	}
	if ($show_files_info) {
        $file_info = '';
        $view->app->triggerEvent('ongetFileInfo', array('com_fwgallery', $row, $view, &$file_info));
?>
	<div class="fwmg-file-download-details">
<?php
        if ($file_info) {
            echo fwgHelper::escPluginsOutput($file_info);
        } else {
?>
		<i class="fal fa-expand-arrows fa-fw"></i> <?php echo (int)$row->_width; ?>*<?php echo (int)$row->_height; ?>px
		<i class="fal fa-hdd fa-fw ml-3"></i> <?php echo fwgHelper::humanFileSize($row->_size); ?>
<?php
        }
?>
	</div>
<?php
	}
?>
</div>
<?php
}
/** @todo move the code below into addons */
switch($row->type) {
	case 'video':
		$icon_type = 'fal fa-video';
		break;
	case 'audio':
		$icon_type = 'fal fa-music';
		break;
	default: $icon_type = 'fal fa-image';
}
?>
<div class="fwmg-grid-item-type">
	<i class="<?php echo esc_attr($icon_type); ?>"></i>
</div>
