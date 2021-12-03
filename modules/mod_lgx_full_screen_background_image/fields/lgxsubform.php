<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2018 LogicHunt All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.filesystem.path');


/**
 * The Field to load the form inside current form
 *
 * @Example with all attributes:
 *    <field name="field-name" type="subform"
 *        formsource="path/to/form.xml" min="1" max="3" multiple="true" buttons="add,remove,move"
 *        layout="joomla.form.field.subform.repeatable-table" groupByFieldset="false" component="com_example" client="site"
 *        label="Field Label" description="Field Description" />
 *
 * @since   3.6
 */
class JFormFieldLgxSubform extends JFormField
{
    public function getInput()
    {
        $path = 'modules/mod_lgx_full_screen_background_image/';

        //JHtml::_('jquery.ui', array('core', 'sortable'));
        //JHtml::_('behavior.framework');
       // JHTML::_('behavior.modal');
        JHTML::_('stylesheet', $path . 'assets/css/admin.css');
        JHTML::_('script', $path . 'assets/js/admin.js');

    }

}
