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

$displayData['row'] = $row = $view->item;
$displayData['link'] = JURI::getInstance()->toString();

?>
<div id="fwgallery" class="fwcss fwmg-file fwmg-file-<?php echo esc_attr($row->type); ?> fwmg-design-<?php echo esc_attr($params->get('template', 'common')); ?>">
<?php
$tmpl = '';
$view->app->triggerEvent('onGetOneFileLayout', array('com_fwgallery', &$tmpl, $view));
if ($tmpl) {
	echo fwgHelper::escPluginsOutput($tmpl);
} else {
	if ($params->get('show_file_name', 1)) {
?>
    <div class="fwmg-page-header">
        <h2 itemprop="headline" data-title="<?php echo esc_attr($row->name); ?>"><?php echo esc_html($row->name); ?></h2>
    </div>
<?php
	}
?>
    <div class="row">
        <div class="col-md-7">
			<div class="fwmg-file-thumb">
				<?php echo fwgHelper::loadTemplate('item.left', $displayData); ?>
			</div>
			<div>
<?php
	if ($view->params->get('show_file_slideshow', 1)) {
		echo fwgHelper::loadTemplate('item.gallery', $displayData);
	}
?>
			</div>
			<div class="fwmg-file-bottom">
				<?php echo fwgHelper::loadTemplate('item.bottom', $displayData); ?>
			</div>
        </div>
        <div class="col-md-5 fwmg-file-text">
			<?php echo fwgHelper::loadTemplate('item.right', $displayData); ?>
        </div>
    </div>
</div>
<script>
var fwmg_alert_default_title = '<?php echo esc_js(JText::_('FWMG_NOTICE')); ?>';
<?php
	if (empty($view->is_html)) {
?>
setTimeout(function() {
<?php
	} else {
?>
document.addEventListener('DOMContentLoaded', function() {
<?php
	}
?>
	(function($) {
	function fwmg_calc_gallery_btns($frame) {
        var $film = $frame.find('.fwmg-file-gallery-thumbs');
		var $left_btn = $frame.find('.fwmg-file-gallery-btn-left').css('opacity', 0);
		var $right_btn = $frame.find('.fwmg-file-gallery-btn-right').css('opacity', 0);

		var qty = $film.find('li').length;
		var $active = $film.find('li.active');
		var pos = $active.length?$film.find('li').index($active[0]):0;

		if (pos > 0) {
			$left_btn.animate({'opacity': 1}, 300);
		} else {
			$left_btn.animate({'opacity': 0}, 300);
		}

		if (pos + 1 < qty) {
			$right_btn.animate({'opacity': 1}, 300);
		} else {
			$right_btn.animate({'opacity': 0}, 300);
		}
	}

	$('.fwmg-file-gallery-thumbs-wrapper').each(function() {
		fwmg_calc_gallery_btns($(this));
	});

    $('.fwmg-file-gallery-thumbs li.active').click();

    $('.fwmg-file').on('click', '.fwmg-file-gallery-thumbs li', function() {
        var $el = $(this);
		var el_width = $el.width();
        var $film = $el.closest('.fwmg-file-gallery-thumbs');
        var $frame = $el.closest('.fwmg-file-gallery-thumbs-wrapper');
		var $file_wrp = $frame.closest('.fwmg-file');

		var pos = $film.find('li').index(this);

		var left_eadge = pos * (el_width + 8);
		var right_eadge = left_eadge + (el_width + 8);

        var current_offset = parseInt($film.css('margin-left')) * -1;

		if (left_eadge < current_offset) {
			$film.animate({'margin-left': (left_eadge * -1) + 'px'}, 300);
		}

		if (right_eadge > current_offset + $frame.width()) {
			$film.animate({'margin-left': ((right_eadge - $frame.width()) * -1) + 'px'}, 300);
		}

        if (!$el.hasClass('active')) {
            $film.find('.active').removeClass('active');
            $el.addClass('active');
			$('.fwmg-file-gallery button').attr('disabled', true);

			fwmg_calc_gallery_btns($frame);

			var id = $el.data('id');
			var height = $('.fwmg-file-thumb').height();
			$file_wrp.find('.fwmg-file-thumb').css('min-height', height+'px').html('<i class="fad fa-spinner-third fa-spin"></i>').load('', {
				option: 'com_fwgallery',
				format: 'raw',
				layout: 'tmpl',
				tmpl: 'item.left',
				view: 'item',
				id: id,
				modal: $film.closest('.modal').length
			}, function() {
				$('.fwmg-file-gallery button').attr('disabled', false);
				var $wrp = $(this);
				var $img = $wrp.find('img');
				if ($img.length) {
					$img.on('load', function() {
						$wrp.css('min-height', '');
					});
				} else {
					setTimeout(function() {
						$wrp.css('min-height', '');
					}, 1000);
				}
			});
			$file_wrp.find('.fwmg-file-text').addClass('fwmg-loading').load('', {
				option: 'com_fwgallery',
				format: 'raw',
				layout: 'tmpl',
				tmpl: 'item.right',
				view: 'item',
				id: id,
				modal: $film.closest('.modal').length
			}, function() {
				$(this).removeClass('fwmg-loading');
			});
			$file_wrp.find('.fwmg-file-bottom').addClass('fwmg-loading').load('', {
				option: 'com_fwgallery',
				format: 'raw',
				layout: 'tmpl',
				tmpl: 'item.bottom',
				view: 'item',
				id: id,
				modal: $film.closest('.modal').length
			}, function() {
				$(this).removeClass('fwmg-loading');
			});
        }
    }).on('click', '.fwmg-file-gallery-btn-left', function(ev) {
		ev.stopImmediatePropagation();
		var $btn = $(this);
        var $film = $btn.parent().find('.fwmg-file-gallery-thumbs');

		var $active = $film.find('li.active');
		var pos = $active.length?$film.find('li').index($active[0]):0;
		if (pos > 0) {
            var $frame = $btn.closest('.fwmg-file-gallery-thumbs-wrapper');
            var img_start = $frame.data('start');
            var img_limit = $frame.data('limit');

			$film.find('li').eq(pos - 1).click();
            if (pos < 5 && img_start > 0) {
                var img_limitstart = Math.max(0, img_start - img_limit);
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data : {
        				option: 'com_fwgallery',
        				format: 'json',
        				layout: 'tmpl',
        				tmpl: 'item.gallery_items',
        				view: 'item',
                        limitstart: img_limitstart,
                        limit: Math.min(img_limit, img_start),
              			is_site: 1
                    }
                }).done(function(data) {
                    if (data.html) {
                        var $elems = $(data.html);
                        $elems.find('img').on('load', function() {
                            var $el = $(this).closest('li');
                    		var el_width = $el.width();
                            var current_offset = parseInt($film.css('margin-left'));
                            $film.css('margin-left', (current_offset - el_width)+'px');
                        });
                        $film.prepend($elems);
                        $frame.data('start', img_start - $elems.find('img').length);
                    }
                    if (data.msg) {
                        alert(data.msg);
                    }
                });
            }
		}
    }).on('click', '.fwmg-file-gallery-btn-right', function(ev) {
		ev.stopImmediatePropagation();
		var $btn = $(this);
        var $film = $btn.parent().find('.fwmg-file-gallery-thumbs');
		var qty = $film.find('li').length;
		var $active = $film.find('li.active');
		var pos = $active.length?$film.find('li').index($active[0]):0;
		if (pos < qty) {
			$film.find('li').eq(pos + 1).click();

            var $frame = $btn.closest('.fwmg-file-gallery-thumbs-wrapper');
            var img_end = $frame.data('end');
            var img_qty = $frame.data('qty');
            var img_limit = $frame.data('limit');
/* if left less then 5 images and not all images loaded - load more */
            if (qty - pos < 5 && img_end < img_qty) {
                var img_limitstart = img_end;
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data : {
        				option: 'com_fwgallery',
        				format: 'json',
        				layout: 'tmpl',
        				tmpl: 'item.gallery_items',
        				view: 'item',
                        limitstart: img_limitstart,
                        limit: Math.min(img_limit, img_qty - img_end),
              			is_site: 1
                    }
                }).done(function(data) {
                    if (data.html) {
                        var $elems = $(data.html);
                        $film.append($elems);
                        $frame.data('end', img_end + $elems.find('img').length);
                    }
                    if (data.msg) {
                        alert(data.msg);
                    }
                });
            }
		}
    });
	$("body").keydown(function(e) {
		/* if lightbox is on, skip keys pressing */
		if ($(this).hasClass('lg-on')) return;
		/* otherwise imitate clicking of gallery buttons */
	    if ((e.keyCode || e.which) == 37) {
	        $('.fwmg-file-gallery-btn-left').click();
	    } else if ((e.keyCode || e.which) == 39) {
	        $('.fwmg-file-gallery-btn-right').click();
	    }
	}).on('click', 'button[data-dismiss="modal"]', function() {
		$(this).closest('.modal').modal('hide');
	});
	})(jQuery);
<?php
	if (empty($view->is_html)) {
?>
}, 300);
<?php
	} else {
?>
});
<?php
	}
?>
</script>
<?php
}
$view->app->triggerEvent('onshowItemGoogleData', array('com_fwgallery', $view));
