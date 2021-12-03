
jQuery(function($) {
    $('body').on('click', 'a.fwmg-modal', function(ev) {
        ev.preventDefault();
        var $link = $(this);
        var $popup = $('#fwmg-one-file');
        if (!$popup.length) {
            $popup = $('<div class="modal fade" id="fwmg-one-file">\
    <div class="modal-dialog modal-huge" role="document">\
        <div class="modal-content">\
            <div class="modal-header">\
                <h5 class="modal-title"></h5>\
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">\
                    <span aria-hidden="true">&times;</span>\
                </button>\
            </div>\
            <div class="modal-body"></div>\
        </div>\
    </div>\
</div>');
            var $wrp = $('<div/>', {
                'class': 'fwcss'
            });
            $('body').append($wrp);
            $wrp.append($popup);
        }
        $popup.find('.modal-body').html('').load($link.attr('href'), {
            'format': 'raw'
        });
        $popup.on('show show.bs.modal', function() {
            $(this).addClass('show').data('title', $('title').html());
        }).on('hide hide.bs.modal', function() {
            $(this).removeClass('show');
			$('title').html($(this).data('title'));
        }).modal('show');
    });
   // Load more
   $('.fwmg-more-block a').each(function() {
	  var $link = $(this);
      var $wrp = $link.closest('.fwmg-galleries .fwmg-files');
	  var limit = $link.data('items-per-page');
	  var total = parseInt($link.data('total'));
	  var selector = $link.data('selector');
	  var loaded = $wrp.find(selector).length;
	  var qty = Math.min(limit, total - loaded);
	  $('span', $link).html(qty);
    });
	$('.fwmg-galleries').on('mouseenter', '.fwmg-grid-item-autoplay:has("iframe")', function() {
		var $ifr = $(this).find('iframe');
		var src = $ifr.attr('src');
		if (src.indexOf('youtube') == -1) {
			$ifr.attr('src', src+'?autoplay=1');
		} else {
			$ifr.attr('src', src+'&amp;autoplay=1&amp;mute=1');
		}
	}).on('mouseleave', '.fwmg-grid-item-autoplay:has("iframe")', function() {
		var $ifr = $(this).find('iframe');
		var src = $ifr.attr('src');
		if (src.indexOf('youtube') == -1) {
			$ifr.attr('src', src.replace('?autoplay=1', ''));
		} else {
			$ifr.attr('src', src.replace('&amp;autoplay=1&amp;mute=1', ''));
		}
	}).on('mouseenter', '.fwmg-grid-item-autoplay:has("video")', function() {
		$(this).find('video').get(0).play().catch(function() {});
	}).on('mouseleave', '.fwmg-grid-item-autoplay:has("video")', function() {
		$(this).find('video').get(0).pause();
	});

	var buff = location.toString().split('#');
	if (buff.length == 2 && $('.fwmg-files a.fwmg-modal').length) {
		$.ajax({
		  url: '',
		  type: 'post',
		  dataType: 'json',
		  data: {
			  format: 'json',
			  view: 'item',
			  layout: 'get_item_link',
			  link: buff[1]
		  }
		}).done(function(data) {
			if (data.result) {
				var $link = $('<a />', {
					'href': data.result,
					'class': 'fwmg-modal',
					'style': 'display:none'
				});
				$('#fwgallery').append($link);
				$link.click();
				setTimeout(function() {
					$link.remove();
				}, 300);
			}
		});
	}
});
function fwmg_alert(msg, title = '', status = 'info') {
	if (title == '' && window.fwmg_alert_default_title) {
		title = fwmg_alert_default_title;
	}
	var $el = jQuery('<div role="alert" aria-live="assertive" aria-atomic="true" class="toast toast-' + status + '" data-autohide="false">\
  <div class="toast-header">\
    <i class="far fa-bell"></i>\
    <strong>'+ title + '</strong>\
  </div>\
  <div class="toast-body">'+ msg + '</div>\
</div>');
	var $wrp = jQuery('#fwmg-toast-stack');
	if (!$wrp.length) {
		$wrp = jQuery('<div />', {
			id: 'fwmg-toast-stack'
		});
		jQuery('body').append($wrp);
		$wrp.wrap('<div class="fwcss"></div>');
	}
	$wrp.append($el);
	$el.animate({ 'opacity': 1 }, 500);
	setTimeout(function () {
		$el.animate({ 'opacity': 0 }, 500, function () {
			$el.remove();
		});
	}, 5000);
}
