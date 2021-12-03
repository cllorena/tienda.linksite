<?php

/* ------------------------------------------------------------------------
  # mod_bgmax - Custom parameter for bgMax
  # Create directory define by parameter 'directory' if no exist
  # ------------------------------------------------------------------------
  # author    lomart
  # copyright : Copyright (C) 2011 lomart.fr All Rights Reserved.
  # @license  : http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Website   : http://lomart.fr
  # Technical Support:  Forum - http://forum.joomla.fr
  ------------------------------------------------------------------------- */
// no direct access
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('folderlist');

/**
 * Supports an HTML select list of image
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldFolderListMakeDir extends JFormFieldFolderList {

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'FolderListMakeDir';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions() {
		//-- dossier
		$folder = $this->element['directory'];
		$folder = strtr($folder, "\\", DIRECTORY_SEPARATOR);

		//-- Controle existence folder bgmax
		if (!JFolder::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . $folder)) {
			if (!JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . $folder, 0755)) {
				print_r('Error, fail to create folder: ' . $folder);
				$folder = 'images/stories';  // Pour �viter erreur
			}
		}

		// Get the field options.
		return parent::getOptions();
	}

}
