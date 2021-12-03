<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class JHTMLfwView {
	static function orderingListLinks($params) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		ob_start();
?>
<div class="input-group">
<?php
		if ($app->isClient('site') or ($app->isClient('administrator') and $user->authorise('core.edit', 'com_fwgallery'))) {
			if (!empty($params['display_up'])) {
?>
	<a href="javascript:" onclick="return listItemTask('cb<?php echo esc_attr($params['num']); ?>','orderup')" class="input-group-prepend form-control-sm btn" id="btnGroupAddon"><i class="fa fa-angle-up"></i></a>
<?php
			}
?>
	<input class="form-control form-control-sm" placeholder="0" aria-describedby="btnGroupAddon" type="text" name="order[]" value="<?php echo esc_attr($params['value']); ?>">
<?php
		if (!empty($params['display_down'])) {
?>
	<a href="javascript:" onclick="return listItemTask('cb<?php echo esc_attr($params['num']); ?>','orderdown')" class="input-group-append form-control-sm btn" id="btnGroupAddon"><i class="fa fa-angle-down"></i></a>
<?php
			}
		}
?>
</div>
<?php
		return ob_get_clean();
	}
	static function radioListLinks($params) {
		ob_start();
?>
<div class="btn-group<?php if (!empty($params['wrapper_class'])) { ?> <?php echo esc_attr($params['wrapper_class']); } ?>" role="group">
<?php
		$row = $params['row'];
		foreach ($params['buttons'] as $button) {
			$key = $button['name'];
			$is_checked = $row->$key;
			$classes = array('btn btn-sm');
			if ($is_checked) {
				$classes[] = 'active';
				if (!empty($button['active_class'])) $classes[] = $button['active_class'];
			}
?>
	<a href="javascript:" onclick="return listItemTask('cb<?php echo esc_js($params['num']); ?>','<?php echo esc_attr($is_checked?$button['task_off']:$button['task_on']); ?>')" class="<?php echo esc_attr(implode(' ', $classes)); ?>" title="<?php echo esc_attr(JText::_($is_checked?$button['title_off']:$button['title_on'])); ?>"><i class="<?php echo esc_attr($button['icon_class']); ?>"></i></a>
<?php
		}
?>
</div>
<?php
		return ob_get_clean();
	}
	static function booleanListLink($params) {
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		ob_start();
		if ($app->isClient('site') or ($app->isClient('administrator') and $user->authorise('core.edit', 'com_fwgallery'))) {
?>
<button type="button" class="btn btn-sm <?php echo esc_attr($params['value']?$params['class_on']:$params['class_off']); ?>" onclick="return listItemTask('cb<?php echo esc_js($params['num']); ?>','<?php echo esc_js($params['value']?$params['task_off']:$params['task_on']); ?>')" title="<?php echo esc_attr(JText::_($params['value']?$params['title_off']:$params['title_on'])); ?>"><i class="far fa-<?php echo esc_attr($params['value']?'check':'times'); ?>"></i></button>
<?php
		} else {
?>
<i class="fa fa-<?php echo esc_attr($params['value']?'check':'times'); ?>"></i>
<?php
		}
		return ob_get_clean();
	}
	static function menuItem($params) {
		ob_start();
?>
<li class="fwa-menu-item<?php if (!empty($params['active'])) { ?> active<?php } ?>">
	<a href="<?php echo esc_attr($params['link']); ?>">
		<i class="<?php echo esc_attr($params['icon_class']); ?>"></i> <?php echo esc_html($params['name']); ?>
	</a>
</li>
<?php
		return ob_get_clean();
	}
	static function radioGroup($name, $value, $params) {
		ob_start();
		$classes = array('btn-group');
		if (!empty($params['wrapper_class'])) {
			$classes[] = $params['wrapper_class'];
		}
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" role="group">
<?php
		foreach ($params['buttons'] as $button) {
			if (!isset($button['active_class'])) $button['active_class'] = '';
			$classes = array('btn');
			if (!empty($button['class'])) {
				$classes[] = $button['class'];
			}
			if ($value == $button['value']) {
				$classes[] = 'active';
				if ($button['active_class']) {
					$classes[] = $button['active_class'];
				}
			}
?>
	<button type="button" class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-value="<?php echo esc_attr($button['value']); ?>" data-active-class="<?php echo esc_attr($button['active_class']); ?>"><?php if (!empty($button['icon_class'])) { ?><i class="<?php echo esc_attr($button['icon_class']); ?>"></i><?php } if (!empty($button['title'])) { echo esc_html($button['title']); } ?></button>
<?php
		}
?>
</div>
<input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" />
<?php
		if (!defined('FW_RADIO_HANDLER_LOADED')) {
			define('FW_RADIO_HANDLER_LOADED', 1);
			JHTML::_('jquery.framework');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
		$('.btn-group button').click(function() {
			var $button = $(this);
			var $wrapper = $button.closest('.btn-group');
			var $input = $wrapper.next();
			if ($input.length && $input.val() != $button.data('value')) {
				$wrapper.find('button').each(function() {
					var $btn = $(this);
					var active_class = $btn.data('active-class');
					if ($button[0] == $btn[0]) {
						$btn.addClass('active');
						if (active_class) {
							$btn.addClass(active_class);
						}
					} else {
						if ($btn.hasClass('active')) {
							$btn.removeClass('active');
						}
						if (active_class && $btn.hasClass(active_class)) {
							$btn.removeClass(active_class);
						}
					}
				});
				$input.val($button.data('value')).change();
			}
		});
    })(jQuery);
});
</script>
<?php
		}
		return ob_get_clean();
	}
	static function handleUploadButton($params) {
		ob_start();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
<?php
		if (!defined('FWVIEW_UPLOAD_HANDLER_LOADED')) {
			define('FWVIEW_UPLOAD_HANDLER_LOADED', true);
?>
	$(document).on('change', '.fwcss input[type="file"]', function() {
		var $inp = $(this);
		var files = $inp.prop('files');
		if (files && files.length) {
			var buff = [];
			for (var i = 0; i < files.length; i++) {
				var file = files[i];
				if ('name' in file) {
					buff.push(file.name);
				} else {
					buff.push(file.fileName);
				}
			}
			$inp.next().html(buff.join(','));
		} else {
			$inp.next().html('<?php echo JText::_('FWMG_CHOOSE_FILE', true); ?>');
		}
	});
	$('.fwcss input[type="file"]').change();
<?php
		}
?>
	$(document).on('click', '<?php echo esc_js($params['button']); ?>', function() {
		var form = this.form;
		var $button = $(this);
		var $input = $('<?php echo esc_js($params['input']); ?>');
		var filename = $input.val();
		if (filename != '') {
			var exts = [<?php if (!empty($params['exts'])) { foreach ($params['exts'] as $i=>$ext) { if ($i) { ?>,<?php } ?>'<?php echo esc_js($ext); ?>'<?php } } ?>];
			if (exts) {
				var ext = filename.split('.').pop().toLowerCase();
				var ext_found = false;
				for (var i = 0; i < exts.length; i++) {
					if (exts[i] == ext) {
						ext_found = true;
						break;
					}
				}
				if (!ext_found) {
					fwmg_alert('<?php echo esc_js(JText::sprintf('FWMG_EXTENSIONS_ALLOWED', implode(", ", $params['exts']))); ?>');
					return;
				}
			}
<?php
		if (!empty($params['submit_code'])) echo fwgHelper::escPluginsOutput($params['submit_code']);
?>
			var formdata = new FormData(form);
			<?php if (!empty($params['view'])) { ?>formdata.append('view', '<?php echo esc_js($params['view']); ?>');<?php } ?>
			formdata.append('layout', '<?php echo esc_js($params['layout']); ?>');
			formdata.append('format', 'json');
			formdata.append('task', '');

<?php
		if (!empty($params['submit_code'])) echo fwgHelper::escPluginsOutput($params['submit_code']);
?>

			var $progress_bar = $('<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>');
			$button.attr('disabled', true).after($progress_bar);

			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();

					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							$progress_bar.find('.progress-bar').css('width', percentComplete+'%');
						}
					}, false);

					return xhr;
				},
				url: <?php if (empty($params['url'])) { ?>form.action<?php } else { ?>'<?php echo esc_js($params['url']); ?>'<?php } ?>,
				type: "POST",
				data: formdata,
				dataType: 'json',
				contentType: false,
				processData: false,
				success: <?php if (empty($params['callback'])) { ?>function(result) {
					$progress_bar.remove();
					$button.attr('disabled', false);

					var $parent = $input.parent();
					$input.next().html('<?php echo JText::_('FWMG_CHOOSE_FILE', true); ?>');

					var input_html = $parent.html();
					$parent.html(input_html);

					var data;
					if (typeof result == 'object') {
						data = result;
					} else {
						data = $.parseJSON(result);
					}

					if (data && data.data && data.data[0]) {
						data = data.data[0];
					}
					if (data) {
						if (data.id) {
							$('input[name="id"]').val(data.id);
						}
						if (data.image) {
							$('<?php echo esc_js($params['image']); ?>').attr('src', data.image);
						}
<?php
		if (!empty($params['response_code'])) echo fwgHelper::escPluginsOutput($params['response_code']);
?>
						if (data.msg) {
							fwmg_alert(data.msg);
						}
					}
				}
<?php
				} else echo fwgHelper::escPluginsOutput($params['callback']);
?>
			});
		}
	});
    })(jQuery);
});
</script>
<?php
		return ob_get_clean();
	}
	static function handleRemoveButton($params) {
		ob_start();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$(document).on('click', '<?php echo esc_js($params['button']); ?>', function() {
		var form = this.form;
		var $button = $(this);

		$img = $('<img/>', {
			src: '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
			style: 'margin: 0 0 0 6px;'
		});
		$button.attr('disabled', true).after($img);
		$.ajax({
			url: <?php if (empty($params['url'])) { ?>form.action<?php } else { ?>'<?php echo esc_js($params['url']); ?>'<?php } ?>,
			type: 'post',
			dataType: 'json',
			data: {
				format: 'json',
				id: form.id.value,
				button_id: $button.data('id'),
				<?php if (!empty($params['view'])) { ?>view: '<?php echo esc_js($params['view']); ?>',<?php } ?>
				layout: '<?php echo esc_js($params['layout']); ?>'
			},
			success: <?php if (empty($params['callback'])) { ?>function(result) {
				$img.remove();
				$button.attr('disabled', false);
				var data;
				if (typeof result == 'object') {
					data = result;
				} else {
					data = $.parseJSON(result);
				}
				if (data && data.data && data.data[0]) {
					data = data.data[0];
				}
				if (data) {
					if (data.image) {
						$('<?php echo esc_js($params['image']); ?>').attr('src', data.image);
					}
					if (data.msg) {
						var $msg = $('<div class="alert alert-warning alert-dismissible fade show" role="alert">\
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
    <span aria-hidden="true">&times;</span>\
  </button>\
  '+data.msg+'</div>');
						$button.before($msg);
						$('button', $msg).click(function() {
							$msg.remove();
						});
						setTimeout(function() {
							$msg.remove();
						}, 3000);
					}
				}
			}
<?php
			} else echo fwgHelper::escPluginsOutput($params['callback']);
?>
		});
	});
	})(jQuery);
});
</script>
<?php
		return ob_get_clean();
	}
}
