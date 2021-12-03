<?php
/**
* @title		joombig responsive fullscreen slider module
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2014 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
    // no direct access
    defined('_JEXEC') or die('Restricted access');
	$mosConfig_absolute_path = JPATH_SITE;
	$mosConfig_live_site = JURI :: base();
	if(substr($mosConfig_live_site, -1)=="/") { $mosConfig_live_site = substr($mosConfig_live_site, 0, -1); }

    $module_name             = basename(dirname(__FILE__));
    $module_dir              = dirname(__FILE__);
    $module_id               = $module->id;
    $document                = JFactory::getDocument();
    $style                   = $params->get('sp_style');

    if( empty($style) )
    {
        JFactory::getApplication()->enqueueMessage( 'Slider style no declared. Check joombig responsive fullscreen slider configuration and save again from admin panel' , 'error');
        return;
    }

    $layoutoverwritepath     = JURI::base(true) . '/templates/'.$document->template.'/html/'. $module_name. '/tmpl/'.$style;
    $document                = JFactory::getDocument();
    require_once $module_dir.'/helper.php';
    $helper = new mod_Responsivefullscreenslider($params, $module_id);
    $data = (array) $helper->display();
	$enable_jQuery				= $params->get('enable_jQuery', 1);
	$width_module				= $params->get('width_module', "100%");
	$height_module 				= $params->get('height_module', "500px");
	$left_module 				= $params->get('left_module', "0%");
	$auto_play 					= $params->get('auto_play', 1);
	$time_transitions 			= $params->get('time_transitions', 4000);
	$display_title 				= $params->get('display_title', 1);
	$display_des 				= $params->get('display_des', 1);
	$display_ball 				= $params->get('display_ball', 1);
	$imgscroll 					= $params->get('imgscroll', $mosConfig_live_site."/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/images/titleist2.png");
    //$option = (array) $params->get('animation')->$style;
    if(  is_array( $helper->error() )  )
    {
        JFactory::getApplication()->enqueueMessage( implode('<br /><br />', $helper->error()) , 'error');
    } else {
        if( file_exists($layoutoverwritepath.'/view.php') )
        {
            require(JModuleHelper::getLayoutPath($module_name, $layoutoverwritepath.'/view.php') );   
        } else {
            require(JModuleHelper::getLayoutPath($module_name, $style.'/view') );   
        }

        $helper->setAssets($document, $style);
}