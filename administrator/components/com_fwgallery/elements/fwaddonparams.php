<?php
/**
 * FW Gallery 6.7.2
 * @copyright C 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldFwAddonParams extends JFormField {
	var	$type = 'fwaddonparams';

	function getInput() {
		if (!defined('FWMG_COMPONENT_SITE')) {
			define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
		}
		require_once(FWMG_COMPONENT_SITE.'/helpers/helper.php');
		JFactory::getLanguage()->load('com_fwgallery');

		fwgHelper::loadAdminStyles();

		if (!defined('FWMG_COMPONENT_ADMINISTRATOR')) {
			define('FWMG_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_fwgallery');
		}
		require_once(FWMG_COMPONENT_ADMINISTRATOR.'/helpers/fwview.php');

		if (!is_array($this->value)) {
			$this->value = (array)$this->value;
		}

		ob_start();

		$buff = JFactory::getApplication()->triggerEvent('ongetGalleryMenuParams', array('com_fwgallery', $this->name, $this->value));

		if ($buff) {
			echo implode('', $buff);
		} else {
			/** @todo hide this row */
		}
		return ob_get_clean();
	}
}
