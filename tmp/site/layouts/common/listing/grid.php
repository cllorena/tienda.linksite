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
$active_menu = JMenu::getInstance('site')->getActive();
$menu_params = $active_menu?$active_menu->getParams():new JRegistry();

if ($menu_params->get('show_page_heading', 1)) {
?>
<h1><?php echo $menu_params->get('page_heading', $menu_params->get('page_title')); ?></h1>
<?php
}
$all_cats = array();
?>
<div id="fwgallery" class="fwcss">
    <div class="fwmg-top-categories">
<?php
if ($view->subcategories) {
	foreach ($view->subcategories as $cat) {
?>
        <button type="button" class="btn btn-secondary" data-filter="<?php echo esc_attr($cat->id); ?>"><?php echo esc_html($cat->name); ?></button>
<?php
/** @todo show subcategories buttons as well */
        $all_cats[] = $cat;
	}
	$view->subcategories = null;
}
?>
    </div>
    <div class="fwmg-cascading-grid-files">
    </div>
</div>
<script>
var cats = [];
<?php
foreach ($all_cats as $cat) {
	?>cats.push({id:<?php echo (int)$cat->id; ?>,name:'<?php echo esc_js(preg_replace('#[^a-z0-9]#', '-', strtolower($cat->name))); ?>'});<?php
}
?>

document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$('.fwmg-top-categories button').click(function(ev) {
		ev.preventDefault();
		$('.fwmg-top-categories button').removeClass('active');
		var $btn = $(this).addClass('active');
		var filter = $btn.data('filter');

		var $list = $('.fwmg-cascading-grid-files');

		if (filter == 'all') {
			window.location.hash = '';
		} else if (isNaN(filter)) {
			window.location.hash = '#'+filter;
		} else {
			for (var i = 0; i < cats.length; i++) {
				if (cats[i].id == filter) {
					window.location.hash = '#'+cats[i].name;
					break;
				}
			}
		}
		$list.html('<div class="fa-3x"><i class="fas fa-sync fa-spin"></i></div>').load('<?php echo fwgHelper::route('index.php?option=com_fwgallery&view=fwgallery&format=raw&Itemid=0', false); ?>', {
			id: filter,
			layout: 'load_files'
		});
	});

	var menu_clicked = false;
	var hash = window.location.hash.toString().replace('#', '');
	if (hash != '') {
		var $btn = $('.fwmg-top-categories button[data-filter="'+hash+'"]');
		if ($btn.length) {
			menu_clicked = true;
			$btn.click();
		} else {
			for (var i = 0; i < cats.length; i++) {
				if (cats[i].name == hash) {
					menu_clicked = true;
					$('.fwmg-top-categories button[data-filter="'+cats[i].id+'"]').click();
					break;
				}
			}
		}
	}
	if (!menu_clicked) {
		$('.fwmg-top-categories button:first').click();
	}
	})(jQuery);
});
</script>
