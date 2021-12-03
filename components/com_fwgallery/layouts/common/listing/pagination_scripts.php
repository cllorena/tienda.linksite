<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

if (defined('FWMG_AJAX_PAGINATION_SCRIPTS_LOADED')) return;
define('FWMG_AJAX_PAGINATION_SCRIPTS_LOADED', true);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
    $('.fwmg-more-block a').on( 'click', function(e) {
	  e.preventDefault();
	  var $link = $(this).hide();
	  var $spinner = $(this).siblings('.loading-spinner').show();
      var buff = $link.data('target').split(' ', 2);
	  var $target = $link.closest(buff[0]).find(buff[1]);
	  var selector = $link.data('selector');
	  var limit = $link.data('items-per-page');
	  $.ajax({
		  url: '',
		  type: 'post',
		  dataType: 'json',
		  data: {
			  format: 'json',
              option: 'com_fwgallery',
              view: 'fwgallery',
			  layout: 'load_'+$link.data('layout')+'_list',
			  t: $link.data('tmpl'),
              gids: $link.data('gids'),
			  fileslimit: limit,
			  fileslimitstart: $target.find(selector).length,
			  order: $link.data('order'),
              is_site: 1
		  }
	  }).done(function(data) {
		  $spinner.hide();
		  $link.show();

		  var $cols = $target.find('.fwmg-grid-column');
		  if ($cols.length) {
			var $elems = $('<div>'+data.html+'</div>');
			$cols.each(function(index) {
				var $col = $(this);
				var $subcol = $elems.find('.fwmg-grid-column:eq('+index+') > div');
				if ($subcol.length) {
					$col.append($subcol);
				}
			});
		  } else {
			var $elems = $(data.html);
			$target.append($elems);
		  }

		  var total = parseInt($link.data('total'));
		  var loaded = $target.find(selector).length;
		  var qty = Math.min(limit, total - loaded);

		  $link.find('span').html(qty);

		  $link.closest('.fwmg-more-block').find('.fwmg-gf-counter span').html(loaded);

		  if (total == loaded) {
			  $link.hide();
		  }
		  if (data.msg) alert(data.msg);
	  });
   });
    })(jQuery);
});
</script>
