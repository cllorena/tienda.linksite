<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
/** @var Type $displayData */

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$params = $view->params;
$displayData['row'] = $row = $view->item;
$displayData['link'] = JURI::getInstance()->toString();

if ($params->get('show_back_link', 1)) {
    $id = $name = null;
    if ($view->category) {
        $id = $view->category->id;
        $name = $view->category->name;
    } else {
        $id = $row->category_id;
        $name = $row->_category_name;
    }
    if ($id) {
        $link = 'index.php?option=com_fwgallery&view=fwgallery&id='.$id.':'.JFilterOutput::stringURLSafe($name).'&Itemid='.$view->app->input->getInt('Itemid');
        $view->app->triggerEvent('onaddCategoryLinkParameters', array('com_fwgallery', &$link));
?>
	<div class="fwmg-file-gallery-name">
		<i class="fal fa-folder"></i>
		<a href="<?php echo fwgHelper::route($link); ?>">
			<?php echo esc_html($name); ?>
		</a>
	</div>
<?php
    }
}
if ($params->get('show_file_update', 1)) {
?>
            <div class="fwmg-file-date">
                <i class="fal fa-calendar-alt"></i> <?php echo fwgHelper::encodeDate($row->updated); ?>
            </div>
<?php
}
if ($params->get('show_file_owner', 1)) {
?>
            <div class="fwmg-file-owner">
                <i class="fal fa-user"></i> <?php echo esc_html($row->_user_name); ?>
            </div>
<?php
}
if ($params->get('show_file_description', 1)) {
?>
            <div class="fwmg-file-description"><?php echo fwgHelper::escPluginsOutput($row->descr); ?></div>
<?php
}

$view->app->triggerEvent('onshowFileExtraFields', array('com_fwgallery', $displayData));

$show_download = ($view->params->get('show_file_download', 1) and ($access = $view->params->get('download_access', 1) and in_array($access, $view->user->getAuthorisedViewLevels())) and fwgHelper::fileDownloadable($row));
if ($show_download) {
?>
            <div class="fwmg-file-download">
                <a class="btn btn-lg btn-primary" href="<?php echo fwgHelper::checkLink(fwgHelper::route('index.php?option=com_fwgallery&view=item&format=raw&layout=download&id='.$row->id)); ?>">
					<?php echo JText::_('FWMG_DOWNLOAD'); ?><i class="fal fa-angle-right"></i></a>
<?php
}
if ($params->get('show_file_info', 1)) {
    $file_info = '';
    $view->app->triggerEvent('ongetFileInfo', array('com_fwgallery', $row, $view, &$file_info));
?>
				<div class="fwmg-file-download-details">
<?php
    if ($file_info) {
        echo fwgHelper::escPluginsOutput($file_info);
    } else {
?>
					<i class="fal fa-expand-arrows mr-1"></i> <?php echo (int)$row->_width; ?> * <?php echo (int)$row->_height; ?>px
					<i class="fal fa-hdd ml-3 mr-1"></i> <?php echo fwgHelper::humanFileSize($row->_size); ?>
<?php
    }
?>
				</div>
<?php
}
//echo 'access: "'.$view->params->get('download_access').'"';
if ($show_download) {
?>
            </div>
<?php
}
if (JComponentHelper::getParams('com_fwgallery')->get('comments_type')) {
	echo fwgHelper::loadTemplate('item.comments', $displayData);
}
