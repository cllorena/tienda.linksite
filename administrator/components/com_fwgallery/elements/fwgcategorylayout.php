<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldFWGCategoryLayout extends JFormField {
    var $type = 'fwgcategorylayout';

    function getInput() {
        if (!defined('FWMG_COMPONENT_SITE')) {
            define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
        }
        $path = FWMG_COMPONENT_SITE.'/helpers/helper.php';
        if (!file_exists($path)) return;
        require_once($path);
        JFactory::getLanguage()->load('com_fwgallery');

        $layouts = array(
            JHTML::_('select.option', 'list', JText::_('DEFAULT'), 'id', 'name'),
            JHTML::_('select.option', 'flat', JText::_('FLAT'), 'id', 'name')
        );
        JFactory::getApplication()->triggerEvent('ongetCategoryLayouts', array('com_fwgallery', &$layouts));

        $name = str_replace(array('[', ']'), array('_', ''), $this->name);
        $is_module_params = (JFactory::getApplication()->input->getString('option')=='com_modules');
        $lang_source = $is_module_params?'MODLAYOUT':'LAYOUTCATEGORY';

        ob_start();
?>
        <fieldset id="jform_<?php echo esc_attr($name); ?>" class="btn-group radio">
<?php
            foreach ($layouts as $i=>$layout) {
                $id = $name.$layout->id;
                $active = ($this->value == $layout->id or (!$this->value and !$i));
?>
            <label for="jform_<?php echo esc_attr($id); ?>" class="btn<?php if ($active) { ?> active btn-success<?php } ?>"
            data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" title="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_'.$lang_source.'_LAYOUT_'.$layout->name.'_TITLE')); ?>" data-content="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_'.$lang_source.'_LAYOUT_'.$layout->name.'_HINT')); ?>">
                <input style="display:none;" type="radio" id="jform_<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($this->name); ?>" value="<?php echo esc_attr($layout->id); ?>"<?php if ($active) { ?> checked="checked"<?php } ?>>
<?php
                /** this is the text output from the language file - no escape check required, there is no user input here */
                echo JText::_('FWMG_DOC_ADMIN_'.$lang_source.'_LAYOUT_'.$layout->name);
?>
            </label>
<?php
            }
?>
        </fieldset>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
        var $wrp = $('#jform_<?php echo esc_js($name); ?>').closest('.column-count-lg-3');
        if ($wrp.length) {
            $wrp.removeClass('column-count-md-2 column-count-lg-3');
        }

        $('#jform_<?php echo esc_js($name); ?> label').click(function() {
            var $btn = $(this);
            $btn.closest('.radio').find('label.active').removeClass('active btn-success');
            $btn.addClass('active btn-success');
        });

        $('#jform_<?php echo esc_js($name); ?> input').change(function() {
            var $input = $(this);
            var $wrp = $input.closest('form');
            $wrp.find('.fwmg-layout').hide();
            $wrp.find('.fwmg-layout-'+$input.val()).show();
        });
         $('#jform_<?php echo esc_attr($name); ?> input:checked').change();
    })(jQuery);
});
</script>
<?php
        return ob_get_clean();
	}
}
