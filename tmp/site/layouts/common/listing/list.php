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

$params = $view->params;
$descr_right = ($params->get('gallery_descr_position', 'top') != 'top' and !empty($view->category->descr) and $params->get('show_gallery_description', 1));
?>
<?php
if ($params->get('show_page_heading', 0)) {
?>
	<h1 data-title="<?php echo esc_attr($params->get('page_heading', $params->get('page_title'))); ?>"><?php echo $params->get('page_heading', $params->get('page_title')); ?></h1>
<?php
}
?>
<div id="fwgallery" class="fwcss fwmg-design-<?php echo esc_attr($params->get('template', 'common')); ?>">
<?php
if ($view->category->name and $params->get('show_category_info', 1)) {
    echo fwgHelper::loadTemplate('listing.top_category', $displayData);
}
if ($params->get('show_gallery_breadcrumbs', 1) and $view->path) {
?>
<div class="fwmg-gallery-path">
	<i class="fal fa-folder-open"></i>
<?php
if ($view->path) {
	foreach ($view->path as $i => $step) {
		if ($i) {
			?> &gt; <?php
		}
		?><a href="<?php echo esc_attr(fwgHelper::route($step->link)); ?>"><?php echo esc_html($step->name); ?></a><?php
	}
}
?>
</div>
<?php
}
if ($descr_right) {
?>
	<div class="row">
		<div class="col-md-9">
<?php
}
$view->app->triggerEvent('onshowListingTopInfo', array('com_fwgallery', $view));
if ($view->subcategories) {
    echo fwgHelper::loadTemplate('listing.categories', $displayData);
}
if ($view->files) {
    echo fwgHelper::loadTemplate('listing.files', $displayData);
}
if ($descr_right) {
?>
		</div>
		<div class="col-md-3">
			<?php echo fwgHelper::escPluginsOutput($view->category->descr); ?>
		</div>
	</div>
<?php
}
if (!empty($view->open_as_popup)) {
?>
    <div class="modal fade" id="fwmg-one-file">
        <div class="modal-dialog modal-huge" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
<?php
}
?>
</div>
<script>
var fwmg_alert_default_title = '<?php echo esc_js(JText::_('FWMG_NOTICE')); ?>';
</script>
<?php
$view->app->triggerEvent('onshowListingGoogleData', array('com_fwgallery', $view));
