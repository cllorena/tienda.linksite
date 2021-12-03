<?php
/**
 * FWG Map Module 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldfwgfeatured extends JFormField {
	var	$type = 'fwgfeatured';

	function getInput() {
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		$path = FWMG_COMPONENT_SITE.'/helpers/helper.php';
		if (file_exists($path)) {
			require_once($path);
			if (fwgHelper::pluginEnabled('featured')) {
				JFactory::getLanguage()->load('com_fwgallery', JPATH_ADMINISTRATOR);
				ob_start();
				$name = str_replace(array('[', ']'), array('_', ''), $this->name);
?>
<fieldset id="jform_<?php echo esc_attr($name); ?>" class="btn-group radio">
	<label for="jform_<?php echo esc_attr($name); ?>0" class="btn<?php if (!in_array($this->value, array('hide', 'only'))) { ?> active btn-success<?php } ?>">
		<input style="display:none;" type="radio" id="jform_<?php echo esc_attr($name); ?>0" name="<?php echo esc_attr($this->name); ?>" value="show"<?php if (!in_array($this->value, array('hide', 'only'))) { ?> checked="checked"<?php } ?>>
		<?php echo JText::_('FWMG_SHOW'); ?>
	</label>
	<label for="jform_<?php echo esc_attr($name); ?>1" class="btn<?php if ($this->value == 'hide') { ?> active btn-success<?php } ?>">
		<input style="display:none;" type="radio" id="jform_<?php echo esc_attr($name); ?>1" name="<?php echo esc_attr($this->name); ?>" value="hide"<?php if ($this->value == 'hide') { ?> checked="checked"<?php } ?>>
		<?php echo JText::_('FWMG_HIDE'); ?>
	</label>
	<label for="jform_<?php echo esc_attr($name); ?>2" class="btn<?php if ($this->value == 'only') { ?> active btn-success<?php } ?>">
		<input style="display:none;" type="radio" id="jform_<?php echo esc_attr($name); ?>2" name="<?php echo esc_attr($this->name); ?>" value="only"<?php if ($this->value == 'only') { ?> checked="checked"<?php } ?>>
		<?php echo JText::_('FWMG_ONLY_FEATURED'); ?>
	</label>
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
    })(jQuery);
});
</script>
<?php
				return ob_get_clean();
			} else echo '<div class="alert alert-info">'.JText::_('FWMG_NEED_FEATURED_PLUGIN').'</div>';
		}
	}
}
