<?php
/**
 * ------------------------------------------------------------------------
 * Asset - Parallax Pro
 * ------------------------------------------------------------------------
 * Copyright (C) 2014-2021 LogicHunt, All Rights Reserved.
 * license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: LogicHunt
 * Websites: http://logichunt.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die('Restricted access');

$app      = JFactory::getApplication();
$document = JFactory::getDocument();
$basepath = JUri::root(true).'/modules/' . $module->module . '/assets/';


// Path assignments
$jebase = JURI::base();


// Load OWL StyleSheet
$document->addStyleSheet($basepath.'libs/css/umbg.min.css');


// Load Main StyleSheet
$style_name = 'style.css';
$document->addStyleSheet($basepath.'css/'.$style_name);

//Load Override StyleSheet
$templatepath = 'templates/'.$app->getTemplate().'/css/'.$module->module.'.css';

if(file_exists(JPATH_SITE . '/' . $templatepath)) {
    $document->addStyleSheet(JURI::root(true).'/'.$templatepath);
}


// Script
// Add Dependency
JHtml::_('jquery.framework');


// jquery.bg.min.js
$document->addScript($basepath.'libs/jquery.umbg.min.js');





if( !empty($custom_style)) {

    $document->addStyleDeclaration($custom_style);

}

