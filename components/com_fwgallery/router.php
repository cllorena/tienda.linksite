<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

if (!defined('FWMG_COMPONENT_SITE')) {
	define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
}

function fwGalleryBuildRoute(&$query) {
	$segments = array();
	$app = JFactory::getApplication();
    if (empty($query['Itemid'])) {
		$app->triggerEvent('ongetRouteItemId', array('com_fwgallery', &$query, &$segments));
	}

    if (!empty($query['Itemid'])) {
        $menu = JMenu::getInstance('site');
        $item = $menu->getItems('id', $query['Itemid'], true);
        if ($item) {
			$orig_query = $query;
		 	if (!empty($query['view'])) {
				if ($query['view'] != $item->query['view']) {
					$segments[] = $query['view'];
				}
				unset($query['view']);
			}
		 	if (!empty($query['layout'])) {
				if (!(isset($item->query['layout']) and $query['layout'] == $item->query['layout'])) {
					$segments[] = $query['layout'];
				}
				unset($query['layout']);
			}
			$app->triggerEvent('onbuildRoute', array('com_fwgallery', $item, &$query, &$segments, $orig_query));
		}
	}

	return $segments;
}
function fwGalleryParseRoute(&$segments) {
    $vars = array();

    if (!empty($segments)) {
		require_once(FWMG_COMPONENT_SITE.'/helpers/helper.php');
		$menu = JMenu::getInstance('site');
		$item = $menu->getActive();
		JFactory::getApplication()->triggerEvent('onparseRoute', array('com_fwgallery', $item, &$segments, &$vars));
    	switch (count($segments)) {
    		case 2 :
	    		$vars['view'] = $segments[0];
	    		$vars['layout'] = $segments[1];
    		break;
    		case 1 :
    			if (in_array($segments[0], array('item', 'fwgallery', 'usersection'))) {
		    		$vars['view'] = $segments[0];
    			} elseif (in_array($segments[0], array('category', 'category_edit', 'image', 'image_edit', 'video', 'video_edit', 'batch', 'import'))) {
		    		$vars['view'] = 'usersection';
		    		$vars['layout'] = $segments[0];
    			} elseif ($segments[0] == 'default' and empty($item->query['view'])) {
					$vars['view'] = 'fwgallery';
    			} else {
    				if ($item) {
			    		$vars['view'] = $item->query['view'];
			    		$vars['layout'] = $segments[0];
    				}
    			}
    		break;
    	}
		$segments = array();
    }
//	echo '<pre>'; /*print_r($_SERVER);*/ print_r($segments); print_r($vars); echo '</pre>'; die();
	return $vars;
}
