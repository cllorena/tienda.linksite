<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JHtml::_('formbehavior.chosen', 'select.select-choices');

$menus = array(
    'fwgallery',
    'addon',
    'category',
    'file',
    'tag',
	'data',
	'css',
    'translation',
    'configuration'
);
$icons = array(
    'fwgallery' => 'fal fa-fw fa-tachometer-alt',
    'category' => 'fal fa-fw fa-folders',
    'file' => 'fal fa-fw fa-photo-video',
    'tag' => 'fal fa-fw fa-tags',
    'addon' => 'fal fa-fw fa-puzzle-piece',
	'data' => 'fal fa-fw fa-database',
    'translation' => 'fal fa-fw fa-globe',
	'css' => 'fal fa-fw fa-file-code',
    'configuration' => 'fal fa-fw fa-sliders-h'
);
$titles = array(
    'fwgallery' => 'DASHBOARD',
    'category' => 'CATEGORIES',
    'file' => 'FILES',
    'tag' => 'TAGS',
    'addon' => 'ADDONS',
	'data' => 'DATA_MANAGEMENT',
    'translation' => 'TRANSLATION',
    'css' => 'STYLES',
);

$params = JComponentHelper::getParams('com_fwgallery');
$update_code = $params->get('update_code');
$verified_code = $params->get('verified_code');
$user_name = $params->get('user_name');
$user_avatar = $params->get('user_avatar');
$user_email = $params->get('user_email');

$account_verified = ($update_code and $update_code == $verified_code);
$view = $displayData['view'];

$model = JModelLegacy::getInstance('addon', 'fwGalleryModel');
$buy_qty = 0;
if ($addons = $model->getPluginsData()) {
	foreach ($addons as $addon) {
		if (!empty($addon->type) and empty($addon->installed) and empty($addon->installable)) {
			$buy_qty++;
		}
	}
}
$deals_qty = 0;
if ($deals = $model->getDeals()) {
	$deals_qty = count($deals);
}
?>
<!-- Temp common menu layout start -->
<div id="fw-admin" class="container-fluid fwcss fw-admin">
    <div class="d-flex">
        <div id="fwaSidebarWrapper" class="fwa-sidebar-wrapper<?php if ($view->app->input->cookie->get('fwmg_hide_left_sidebar')) { ?> collapsed<?php } ?>">
            <div class="fwa-sidebar">
                <a id="toggleSidebarMenu" class="fwa-sidebar-toggle">
                    <i id="toggleSidebarIcon" class="far <?php if ($view->app->input->cookie->get('fwmg_hide_left_sidebar')) { ?>fa-bars<?php } else { ?>fa-arrow-left<?php } ?> px-2 mr-2"></i>
                    <?php echo JText::_('COM_FWGALLERY'); ?>
                    <img src="<?php echo FWMG_ADMIN_ASSETS_URI; ?>images/icon_fw_gallery_logo_200.png" />
                </a>
                <div class="fwa-account">
                    <button class="btn fwa-user-no-login" data-toggle="modal" data-bs-toggle="modal" data-target="#fwmg-login-register" data-bs-toggle="modal" data-bs-target="#fwmg-login-register"<?php if ($account_verified) { ?> style="display:none" <?php } ?>>
                        <i class="fal fa-user-lock"></i>
                        <span><?php echo JText::_('FWMG_VERIFY_ACCOUNT'); ?></span>
                        <div><?php echo JText::_('FWMG_VERIFY_ACCOUNT_HINT'); ?></div>
                    </button>
                    <div class="d-flex fwa-account-user fwa-user-logged-in"<?php if (!$account_verified) { ?> style="display:none!important;"<?php } ?>>
                        <div class="fwa-account-photo">
                            <a href="#fwmg-logout" data-toggle="modal" data-bs-toggle="modal" title="<?php echo esc_attr($user_name); ?>">
                                <img src="<?php echo esc_attr($user_avatar); ?>"> 
                            </a>
                        </div>
                        <div class="fwa-account-user-info">
                            <div class="fwa-account-user-info-name">
                                <a href="#fwmg-logout" data-toggle="modal" data-bs-toggle="modal" title="<?php echo esc_attr($user_name); ?>">
                                    <?php echo JFilterOutput::cleanText($user_name); ?>
                                </a>
                            </div>
                            <div class="fwa-account-user-info-verified"><?php echo JText::_('FWMG_VERIFIED_ACCOUNT_HINT'); ?></div>
                        </div>
                    </div>
                </div>
                <div id="fwaSidebarRightBtn" class="fwa-sidebar-right-btn">
                    <i class="fal fa-magic">
                        <span class="badge">2</span>
                    </i>
                    <?php echo JText::_('FWMG_PRODUCT_WIZARD'); ?>
                </div>

                <ul class="fwa-sidebar-menu">
<?php
foreach ($menus as $menu) {
	$name = JText::_('FWMG_'.(empty($titles[$menu])?$menu:$titles[$menu]));
    echo JHTML::_('fwView.menuItem', array(
        'active' => ($menu == $view->menu),
        'link' => 'index.php?option=com_fwgallery&view='.$menu,
        'icon_class' => $icons[$menu],
        'name' => $name,
        'menu' => $menu
    ));
}
?>
                </ul>
            </div>
        </div>
        <div class="fwa-main">
            <div class="d-flex fwa-main-header">
                <div class="fwa-main-header-text">
                    <h2><i class="<?php echo esc_attr($icons[$view->menu]); ?> mr-2"></i> <?php echo esc_html($displayData['title']); ?></h2>
                </div>
<?php
$buttons = fwgButtons::getInstance()->getButtons();
if ($buttons) {
?>
                <div class="fwa-toolbar-wrapper">
                    <div class="fwa-toolbar">
<?php
	foreach ($buttons as $button) {
		$onclick = '';
		if ($button->listSelect) {
			if (is_string($button->listSelect)) {
				$onclick = "if (document.adminForm.boxchecked.value == 0) { fwmg_alert('".JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST', true)."'); } else { if (confirm('".JText::_($button->listSelect, true)."')) { Joomla.submitbutton('".$button->task."'); } }";
			} else {
				$onclick = "if (document.adminForm.boxchecked.value == 0) { fwmg_alert('".JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST', true)."'); } else { Joomla.submitbutton('".$button->task."'); }";
			}
		} else {
			$onclick = "Joomla.submitbutton('".esc_js($button->task)."');";
		}
?>
						<button type="button"<?php if ($button->task == 'add') { ?> id="fwmg-add-button"<?php } ?> class="btn<?php if (!empty($button->color)) { ?> btn-<?php echo esc_attr($button->color); } ?>" onclick="<?php echo esc_attr($onclick); ?>" role="button">
							<i class="<?php echo esc_attr($button->icon); ?>"></i>
							<span><?php echo esc_html($button->alt); ?></span>
						</button>
<?php
	}
?>
                    </div>
                </div>
<?php
}
?>
            </div>
            <div class="fwa-main-body">
