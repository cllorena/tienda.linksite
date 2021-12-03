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
	<h4><?php echo JText::_('FWMG_ADD_NEW_GALLERY'); ?></h4>
	<form action="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>" method="post" name="adminForm" id="adminForm" class="form-inline form-validate">
        <table class="admintable">
            <tr>
                <td class="key">
                    <label for="fwmg-gallery-name"><?php echo JText::_('FWMG_GALLERY_NAME'); ?></label><span class="required">*</span> :
                </td>
                <td>
                    <input class="inputbox required" id="fwmg-gallery-name" type="text" name="name" size="50" maxlength="100" value="" />
                </td>
            </tr>
	        <tr>
	            <td class="key">
	                <?php echo JText::_('FWMG_PARENT_GALLERY'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('fwsgCategory.parent'); ?>
	            </td>
	        </tr>
        </table>
        <button class="btn btn-primary" type="submit"><?php echo JText::_('FWMG_SAVE'); ?></button>
        <a class="btn" href="<?php echo fwgHelper::checkLink(JRoute::_('index.php?option=com_fwgallery&view=fwgallery&layout=modal&tmpl=component&category_id='.$input->getInt('category_id').'&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>"><?php echo JText::_('FWMG_CANCEL'); ?></a>
        <input type="hidden" name="task" value="" />
    	<input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    (function($) {
    $('#adminForm').submit(function(event) {
        event.preventDefault();
        var form = this;
        var $img = $('<img/>', {
            'src': '<?php echo JURI::root(true); ?>/components/com_fwgallery/assets/images/ajax-loader.gif',
            'style': 'margin: 0 10px;'
        });
        $('button[type="submit"]', $(form)).append($img);
        $.ajax({
            url: '',
            method: 'post',
            data: {
                name: form.name.value,
                parent: form.parent.value,
				published: 1,
                view: 'fwgallery',
                layout: 'save',
                format: 'json'
            }
        }).done(function(html) {
            var data = $.parseJSON(html);
            if (data) {
                if (data.msg) fwmg_alert(data.msg);
                if (data.result > 0) {
                    location = $('a.btn', $(form)).attr('href');
                }
            }
        }).always(function() {
            $img.remove();
        });
    });
    })(jQuery);
});
</script>
