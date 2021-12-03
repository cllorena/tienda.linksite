<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$input = JFactory::getApplication()->input;
?>
<div class="container-popup">
	<h4><?php echo JText::_('FWMG_ADD_NEW_IMAGE'); ?></h4>
	<form action="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=images&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>" method="post" name="adminForm" id="adminForm" class="form-inline form-validate">
        <table class="table">
			<tr>
				<td class="key"><?php echo JText::_('FWMG_PUBLISHED'); ?>:</td>
				<td>
					<fieldset class="radio btn-group">
						<div class="controls">
							<label for="published0" id="published0-lbl" class="radio btn">
								<input name="published" id="published0" value="0" type="radio"><?php echo JText::_('JNo'); ?>
							</label>
							<label for="published1" id="published1-lbl" class="radio btn active btn-success">
								<input name="published" id="published1" value="1" checked="checked" type="radio"><?php echo JText::_('JYes'); ?>
							</label>
						</div>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('FWMG_GALLERY'); ?>:</td>
				<td>
					<?php echo JHTML::_('fwsgCategory.getCategories', 'category_id', $input->getInt('category_id'), 'class="required"', $multiple=false, $first_option=''); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="fwmg-batch-uploader"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="fwmg-images-controls">
					<button class="btn btn-primary" type="button" onclick="fwmg_store_image(this);"><?php echo JText::_('FWMG_SAVE'); ?></button>
					<a class="btn" href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&sublayout=images&tmpl=component&category_id='.$input->getInt('category_id').'&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>"><?php echo JText::_('FWMG_CANCEL'); ?></a>
				</td>
			</tr>
        </table>
        <input type="hidden" name="task" value="" />
    	<input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<script>
var form;
var fwmg_uploaded_files = [];
var fwmg_uploading_counter_success = fwmg_uploading_counter_error = 0;
function fwmg_store_image(btn) {
	var $button = $(btn);
	if (typeof(window.FileReader) == 'undefined') {
		var files_added = false;
		$('input[type=file]', $(form)).each(function(index, input) {
			if (input.value != '') files_added = true;
		});
		if (!files_added) {
			fwmg_alert('<?php echo JText::_('FWMG_NOTHING_TO_UPLOAD', true); ?>');
			return false;
		}
	} else {
		if (fwmg_uploaded_files.length > 0) {
			$button.attr('disabled', true);
			var $img = $('<img id="fwmg-batch-wait-img" src="<?php echo JURI :: root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif" />');
			$button.after($img);

			var published = 0;
			$('input[name=published]').each(function(index, input) {
				if (input.checked) published = input.value;
			});

			var formdata = new FormData();

			formdata.append('published', published);
			formdata.append('category_id', form.category_id.value);
			formdata.append('format', 'json');
			formdata.append('layout', 'batchupload');

			for (var i = 0; i < fwmg_uploaded_files.length; i++) {
				formdata.append('images[]', fwmg_uploaded_files[i]);
			}

			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", 'index.php?option=com_fwgallery&view=file');
			ajax.send(formdata);
		} else {
			fwmg_alert('<?php echo JText::_('FWMG_NOTHING_TO_UPLOAD', true); ?>');
		}
		return false;
	}
}
function completeHandler(event) {
	$('#fwmg-batch-wait-img').remove();
	var data = $.parseJSON(event.target.responseText);
	if (data) {
		if (data.msg) fwmg_alert(data.msg);
	}
	location = $('a.btn').attr('href');
}
function handleFileInput(input) {
	var div = $('<div class="row-fluid"></div>');
	$('#fwmg-batch-uploader').append(div);
	div.append($('<div class="span10">'+input.val()+'</div>'));
	div.append(input);
	input.css('display', 'none');
	var button = $('<div class="span2"><button type="button" class="btn btn-danger btn-small" onclick="$(this).parent().parent().remove();">-</button></div>');
	div.append(button);

	var input = $('<input type="file" name="images[]" />');
	$('#fwmg-batch-uploader').append(input);
	input.change(function() {
		handleFileInput($(this));
	});
}
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	var bu = $('#fwmg-batch-uploader');
	form = $('#adminForm')[0];

    if (typeof(window.FileReader) == 'undefined') {
//		var input = $('<input type="file" name="images[]" />');
		bu.append($('<?php echo JText::_('FWS_YOUR_BROWSER_TOO_OLD', true); ?>'));
/*		input.change(function() {
			handleFileInput($(this));
		});*/
	} else {
		var div = $('<div id="fwmg-batch-uploader-wrapper"></div>');
		bu.append(div);
		var fr = $('<div id="fwmg-batch-uploader-drop-zone"></div>');
		div.append(fr);
		fr.html('<?php echo JText::_('FWMG_OR_DRAG_FILES_HERE', true); ?>');

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

			var wrong_files_types = false;
			for (var i = 0; i < event.dataTransfer.files.length; i++) {
				var file = event.dataTransfer.files[i];
				if (file.name.match(/\.(jpg|jpeg|gif|png)$/i)) {
					if (fr.html() == '<?php echo JText::_('FWMG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
					fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
					var row = $('<div class="fwmg-file-row">'+file.name+'</div>');
					fr.append(row);
					var remove_button = $('<a href="javascript:" title="<?php echo JText::_('FWMG_REMOVE', true); ?>"><img src="<?php echo FWMG_ADMIN_ASSETS_URI; ?>images/delete16.png" alt="delete"/></a>');
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
							fr.html('<?php echo JText::_('FWMG_OR_DRAG_FILES_HERE', true); ?>');
							fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
						}
					});
					fwmg_uploaded_files.push(file);
				} else wrong_files_types = true;
			}
			if (wrong_files_types) {
				fwmg_alert('<?php echo JText::_('FWMG_IMAGES_ONLY_ALLOWED', true); ?>');
			}
		}
		var input = $('<input type="file" multiple name="files[]" id="fwmg-upload-input" />');
		bu.append($('<span><?php echo JText::_('FWMG_SELECT_IMAGES', true); ?>&nbsp;</span>')).append(input);

		input[0].onchange = function(event) {
			fwmg_add_files(event);
		}

	}
	$('label.radio').unbind('click').click(function() {
		var label = $(this);
		var input = $('#' + label.attr('for'));

		if (!input.prop('checked')) {
			label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
			if (input.val() == '') {
				label.addClass('active btn-primary');
			} else if (input.val() == 0) {
				label.addClass('active btn-danger');
			} else {
				label.addClass('active btn-success');
			}
			input.prop('checked', true);
			input.trigger('change');
		}
	});
	})(jQuery);
});
function fwmg_add_files(event) {
	event.preventDefault();
	var wrong_files_types = false;
	var fr = $('#fwmg-batch-uploader-drop-zone');
	for (var i = 0; i < event.target.files.length; i++) {
		var file = event.target.files[i];
		if (file.name.match(/\.(jpg|jpeg|gif|png)$/i)) {
			if (fr.html() == '<?php echo JText::_('FWMG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
			fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
			var row = $('<div class="fwmg-file-row">'+file.name+'</div>');
			fr.append(row);
			var remove_button = $('<a href="javascript:" title="<?php echo JText::_('FWMG_REMOVE', true); ?>"><img src="<?php echo FWMG_ADMIN_ASSETS_URI; ?>images/delete16.png" alt="delete"/></a>');
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
					fr.html('<?php echo JText::_('FWMG_OR_DRAG_FILES_HERE', true); ?>');
					fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
				}
			});
			fwmg_uploaded_files.push(file);
		} else wrong_files_types = true;
	}
	if (event.target.files.length > 0) {
		var $input = $(event.target);
		var $span = $input.prev();
		$input.remove();
		var $input = $('<input type="file" multiple name="files[]" id="fwmg-upload-input" />');
		$span.after($input);

		$input[0].onchange = function(event) {
			fwmg_add_files(event);
		}
	}
	if (wrong_files_types) {
		fwmg_alert('<?php echo JText::_('FWMG_IMAGES_ONLY_ALLOWED', true); ?>');
	}
}
function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};
</script>
