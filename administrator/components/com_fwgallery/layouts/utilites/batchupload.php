<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$allusers = $displayData['allusers'];
$current_user = $displayData['current_user'];
$category = $displayData['category'];
$reload = $displayData['reload'];

$max_size = fwgHelper::getIniSize('upload_max_filesize');
$post_size = fwgHelper::getIniSize('post_max_size');

$exts = array('jpg', 'jpeg', 'png', 'gif');
JFactory::getApplication()->triggerEvent('ongetAffordableExts', array('com_fwgallery', &$exts));
?>
<div class="modal fade" id="fwmg-batch-upload">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo JText::_('FWMG_BATCH_UPLOAD'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="index.php?option=com_fwgallery&amp;view=file" method="post" name="uploadForm" id="uploadForm" enctype="multipart/form-data">
					<div class="row mx-0">
						<div class="col-md">
							<div id="fwmg-batch-uploader"></div>
						</div>
						<div class="col-md">
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_GALLERY'); ?>
								</label>
								<div class="col-sm-7">
									<?php echo JHTML :: _('fwsgCategory.getCategories', 'category_id', $category, 'class="required select-choices"', $multiple=false, $first_option=JText::_('FWMG_SELECT_GALLERY')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_PUBLISHED'); ?>
								</label>
								<div class="col-sm-7">
									<?php echo JHTMLfwView::radioGroup('published', 1, array(
										'wrapper_class' => 'mr-2',
										'buttons' => array(array(
											'active_class' => 'btn-success',
											'title' => JText::_('JYES'),
											'value' => 1
										), array(
											'active_class' => 'btn-danger',
											'title' => JText::_('JNO'),
											'value' => 0
										))
									)); ?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-5 col-form-label clearfix">
									<?php echo JText::_('FWMG_OWNER'); ?>
								</label>
								<div class="col-sm-7">
									<?php echo JHTML::_('select.genericlist', (array)$allusers, 'user_id', 'class="required"', 'id', 'name', $current_user->id); ?>
								</div>
							</div>
							<div class="mt-5 text-center">
								<button type="button" class="btn btn-lg btn-primary"><?php echo JText::_('FWMG_UPLOAD'); ?></button>
							</div>
						</div>
					</div>
					<input type="hidden" name="task" value="" />
				</form>
			</div>
		</div>
	</div>
</div>
<script>
var fwmg_uploaded_files = [];
var fwmg_uploading_counter_success = fwmg_uploading_counter_error = 0;
var max_file_size = <?php echo (int)$max_size; ?>;
var max_post_size = <?php echo (int)$post_size; ?>

function completeHandler(event) {
	$('#fwmg-batch-wait-img').remove();
	$('#fwmg-batch-upload').data('images_uploaded', true);
	var data = $.parseJSON(event.target.responseText);
	if (data) {
		if (data.msg) fwmg_alert(data.msg);
<?php
if ($reload) {
?>
		$('#adminForm').submit();
<?php
} else {
?>
		$('#fwmg-batch-upload').modal('hide');
<?php
}
?>
	} else {
		alert('<?php echo JText::_('FWMG_FILES_NOT_UPLOADED', true); ?>');
	}
}
function handleFileInput(input) {
	var form = document.uploadForm;

	var div = $('<div class="row-fluid"></div>');
	$(form).append(div);
	div.append($('<div class="span10">'+input.val()+'</div>'));
	div.append(input);
	input.css('display', 'none');
	var button = $('<div class="span2"><button type="button" class="btn btn-danger btn-small" onclick="$(this).parent().parent().remove();">-</button></div>');
	div.append(button);

	var input = $('<input type="file" name="images[]" />');
	$(form).append(input);
	input.change(function() {
		handleFileInput($(this));
	});
}
function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$('#fwmg-batch-upload').on('show', function() {
		$(this).addClass('show');
	}).on('hide', function() {
		$(this).removeClass('show');
<?php
if ($reload) {
?>
	}).on('hidden hidden.bs.modal', function() {
		if ($(this).data('images_uploaded')) {
			location = location.replace(/#.*$/, '');
		}
<?php
}
?>
	});
	function fwmg_file_queue_upload($button, form, i = 0) {

		var formdata = new FormData();
		formdata.append('user_id', form.user_id.value);
		formdata.append('published', form.published.value);
		formdata.append('category_id', form.category_id.value);

		formdata.append('format', 'json');
		formdata.append('view', 'file');
		formdata.append('layout', 'batchupload');

		formdata.append('images[]', fwmg_uploaded_files[i]);

		$.ajax({
			url: form.action,
			type: 'post',
			dataType: 'json',
			processData: false,
			contentType: false,
			data: formdata
		}).done(function (data) {
			if (data.msg) {
				var $msg = $('<div><span class="badge badge-warning">'+data.msg+'</span></div>');
				$button.after($msg);
				setTimeout(function() {
					$msg.remove();
				}, 3000);
			}
			$('#fwmg-batch-uploader-drop-zone .fwmg-file-row:eq('+i+')').hide(300);
			if (fwmg_uploaded_files.length > i + 1) {
				fwmg_file_queue_upload($button, form, i + 1);
			} else {
				$('#fwmg-batch-wait-img').remove();
				$button.attr('disabled', false);
				$('#fwmg-batch-uploader-drop-zone .fwmg-file-row a').click();
<?php
if ($reload) {
?>
				setTimeout(function() {
					form.submit();
				}, 1000);
<?php
} else {
?>
				$('#fwmg-batch-upload').modal('hide');
<?php
}
?>
			}
		});
	}
	$('#uploadForm button.btn-primary').click(function() {
		var $button = $(this);
		var form = document.uploadForm;
		if (form.category_id.value == 0) {
			alert('<?php echo JText::_('FWMG_SELECT_GALLERY_FIRST', true); ?>');
			return;
		}
		if (typeof(window.FileReader) == 'undefined') {
			var files_added = false;
			$('input[type=file]', form).each(function(index, input) {
				if (input.value != '') files_added = true;
			});
			if (!files_added) {
				alert('<?php echo JText :: _('FWMG_NOTHING_TO_UPLOAD', true); ?>');
				return false;
			}
		} else {
			if (fwmg_uploaded_files.length > 0) {
				$button.attr('disabled', true);
				var $img = $('<img id="fwmg-batch-wait-img" src="<?php echo JURI :: root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif" />');
				$button.after($img);
				fwmg_file_queue_upload($button, form, 0);
			} else {
				alert('<?php echo JText :: _('FWMG_NOTHING_TO_UPLOAD', true); ?>');
			}
		}
	});
	var form = document.uploadForm;
	var bu = $('#fwmg-batch-uploader');
    if (typeof(window.FileReader) == 'undefined') {
		var input = $('<input type="file" name="images[]" />');
		$(form).append(input);
		input.change(function() {
			handleFileInput($(this));
		});
	} else {
		var div = $('<div id="fwmg-batch-uploader-wrapper"></div>');
		bu.append(div);
		var fr = $('<div id="fwmg-batch-uploader-drop-zone"></div>');
		div.append(fr);
		fr.html('<?php echo JText :: _('FWMG_OR_DRAG_FILES_HERE', true); ?>');

		fr[0].ondragover = function() {
			fr.addClass('fwmg-drag-over');
			return false;
		};
		fr[0].ondragleave = function() {
			fr.removeClass('fwmg-drag-over');
			return false;
		};
		fr[0].ondrop = function(event) {
			event.preventDefault();
			fr.removeClass('fwmg-drag-over');
			fr.addClass('fwmg-drag-drop');

			var uploading_size = 0;
			for (var i = 0; i < fwmg_uploaded_files.length; i++) {
				uploading_size += fwmg_uploaded_files[i].size;
			}

			var errors = [];
			for (var i = 0; i < event.dataTransfer.files.length; i++) {
				var file = event.dataTransfer.files[i];
				if (file.name.match(/\.(<?php echo esc_js(implode('|', $exts)); ?>)$/i)) {
					if (file.size <= max_file_size) {
						if ((file.size + uploading_size) < max_post_size) {
							if (fr.html() == '<?php echo JText :: _('FWMG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
							fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
							var row = $('<div class="fwmg-file-row">'+file.name+'</div>');
							fr.append(row);
							var remove_button = $('<a href="javascript:" title="<?php echo JText :: _('FWMG_REMOVE', true); ?>"><img src="<?php echo FWMG_ADMIN_ASSETS_URI; ?>images/delete16.png" alt="delete"/></a>');
							row.append(remove_button);
							var span = $('<span class="fwmg-file-size">'+humanFileSize(file.size)+'</span>');
							row.append(span);
							remove_button.click(function() {
								var current_button = this;
								var num = 0;
								var count = 0;
								$('.fwmg-file-row a').each(function(index, button) {
									if (button == current_button) num = index;
									count++;
								});
								if (fwmg_uploaded_files[num]) {
									fwmg_uploaded_files.splice(num, 1);
									$(current_button).parent().remove();
								}
								if (count == 1) {
									fr.html('<?php echo JText :: _('FWMG_OR_DRAG_FILES_HERE', true); ?>');
									fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
								}
							});
							fwmg_uploaded_files.push(file);

							uploading_size += file.size;
						} else {
							errors.push(file.name + ' - <?php echo JText :: _('FWMG_MAX_POST_SIZE_LIMIT_REACHED', true); ?>');
						}
					} else {
						errors.push(file.name + ' - <?php echo JText :: _('FWMG_FILE_TOO_BIG', true); ?>');
					}
				} else {
					errors.push(file.name + ' - <?php echo JText :: _('FWMG_NOT_ALLOWED_EXTENSION', true); ?>');
				}
			}
			if (errors.length) {
				alert(errors.join('\n'));
			}
		}
		var input = $('<input type="file" multiple name="files[]" id="fwmg-upload-input" />');
		bu.append($('<p><?php echo JText::sprintf('FWMG_ALLOWED_FILES_TYPES', implode(', ', $exts)); ?>; <?php echo JText :: _('FWMG_FILE_UPLOAD_SIZE_LIMIT', true).', '.fwgHelper::humanFileSize($max_size); ?>, <?php echo JText :: _('FWMG_POST_SIZE_LIMIT', true).' '.fwgHelper::humanFileSize($post_size); ?></p><span><?php echo JText :: _('FWMG_SELECT_FILES', true); ?>&nbsp;</span>')).append(input);

		input[0].onchange = function(event) {
			event.preventDefault();

			var uploading_size = 0;
			for (var i = 0; i < fwmg_uploaded_files.length; i++) {
				uploading_size += fwmg_uploaded_files[i].size;
			}

			var errors = [];
			for (var i = 0; i < event.target.files.length; i++) {
				var file = event.target.files[i];
				if (file.name.match(/\.(<?php echo esc_js(implode('|', $exts)); ?>)$/i)) {
					if (file.size <= max_file_size) {
						if ((file.size + uploading_size) < max_post_size) {
							if (fr.html() == '<?php echo JText :: _('FWMG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
							fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
							var row = $('<div class="fwmg-file-row">'+file.name+'</div>');
							fr.append(row);
							var remove_button = $('<a href="javascript:" title="<?php echo JText :: _('FWMG_REMOVE', true); ?>"><img src="<?php echo FWMG_ADMIN_ASSETS_URI; ?>images/delete16.png" alt="delete"/></a>');
							row.append(remove_button);
							var span = $('<span class="fwmg-file-size">'+humanFileSize(file.size)+'</span>');
							row.append(span);
							remove_button.click(function() {
								var current_button = this;
								var num = 0;
								var count = 0;
								$('.fwmg-file-row a').each(function(index, button) {
									if (button == current_button) num = index;
									count++;
								});
								if (fwmg_uploaded_files[num]) {
									fwmg_uploaded_files.splice(num, 1);
									$(current_button).parent().remove();
								}
								if (count == 1) {
									fr.html('<?php echo JText :: _('FWMG_OR_DRAG_FILES_HERE', true); ?>');
									fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
								}
							});
							fwmg_uploaded_files.push(file);

							uploading_size += file.size;
						} else {
							errors.push(file.name + ' - <?php echo JText :: _('FWMG_MAX_POST_SIZE_LIMIT_REACHED', true); ?>');
						}
					} else {
						errors.push(file.name + ' - <?php echo JText :: _('FWMG_FILE_TOO_BIG', true); ?>');
					}
				} else {
					errors.push(file.name + ' - <?php echo JText :: _('FWMG_NOT_ALLOWED_EXTENSION', true); ?>');
				}
			}
			if (errors.length) {
				alert(errors.join('\n'));
			}
		}
	}
	})(jQuery);
});
</script>
