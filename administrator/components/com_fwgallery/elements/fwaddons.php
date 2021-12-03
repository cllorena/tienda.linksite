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

class JFormFieldFwAddons extends JFormField {
	var	$type = 'fwaddons';

	function getInput() {
		$params = JComponentHelper::getParams('com_fwgallery');
		if (!$params->get('show_addons_menu_settings')) {
			return JText::_('FWMG_ALL_ENABLED_ADDONS_ARE_ENABLED');
		}
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		$path = FWMG_COMPONENT_SITE.'/helpers/helper.php';
		if (!file_exists($path)) return;
		JFactory::getLanguage()->load('com_fwgallery');
		require_once($path);

		if (!is_array($this->value)) {
			$this->value = array();
		}

		$itemid = JFactory::getApplication()->input->getInt('id');
		ob_start();
		if ($list = fwgHelper::loadPlugins() and !empty($list['fwgallery'])) {
			$name = str_replace(array('[', ']'), array('_', ''), $this->name);
			$lang = JFactory::getLanguage();
			foreach ($list['fwgallery'] as $row) {
				$lang->load('plg_'.$row->folder.'_'.$row->element.'.sys', JPATH_ADMINISTRATOR);
				$id = $name.$row->element;
				if (!isset($this->value[$row->element])) {
					$this->value[$row->element] = empty($itemid)?1:0;
				}
?>
<div class="control-group">
	<div class="control-label">
		<?php echo JText::_('PLG_FWGALLERY_'.$row->element); ?>
	</div>
	<div class="controls">
		<fieldset id="jform_<?php echo esc_attr($id); ?>" class="btn-group radio radio-yn">
			<label for="jform_<?php echo esc_attr($id); ?>0" class="btn<?php if (empty($this->value[$row->element])) { ?> active btn-danger<?php } ?>">
				<input style="display:none;" type="radio" id="jform_<?php echo esc_attr($id); ?>0" name="<?php echo esc_attr($this->name); ?>[<?php echo esc_attr($row->element); ?>]" value="0"<?php if (empty($this->value[$row->element])) { ?> checked="checked"<?php } ?>>
				<?php echo JText::_('JNO'); ?>
			</label>
			<label for="jform_<?php echo esc_attr($id); ?>1" class="btn<?php if (!empty($this->value[$row->element])) { ?> active btn-success<?php } ?>">
				<input style="display:none;" type="radio" id="jform_<?php echo esc_attr($id); ?>1" name="<?php echo esc_attr($this->name); ?>[<?php echo esc_attr($row->element); ?>]" value="1"<?php if (!empty($this->value[$row->element])) { ?> checked="checked"<?php } ?>>
				<?php echo JText::_('JYES'); ?>
			</label>
		</fieldset>
	</div>
</div>
<?php
			}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
		$('#fieldset-basic .column-count-lg-3').removeClass('column-count-md-2 column-count-lg-3');

		$('fieldset.radio label').click(function() {
			var $btn = $(this);
			var $wrp = $btn.closest('.radio');
			if ($wrp.hasClass('radio-yn')) {
				var $inp = $btn.find('input');
				if ($inp.val() == 0) {
					$wrp.find('label.active').removeClass('active btn-success');
					$btn.addClass('active btn-danger');
				} else {
					$wrp.find('label.active').removeClass('active btn-danger');
					$btn.addClass('active btn-success');
				}
			} else {
				$wrp.find('label.active').removeClass('active btn-success');
				$btn.addClass('active btn-success');
			}
		});
    })(jQuery);
});
</script>
<?php
		}
		return ob_get_clean();
	}
}
