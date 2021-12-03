<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class TableTag extends JTable {
    var $id = null,
        $published = null,
        $ordering = null,
        $created = null,
        $user_id = null,
        $name = null,
        $alias = null,
        $hits = null;

    var $_user_name = null,
        $_images_qty = null;

    function __construct(&$db) {
        parent::__construct('#__fwsg_tag', 'id', $db);
    }
    function load($oid = null, $reset = true) {
        if ($oid) {
        	$db = JFactory::getDBO();

        	$user_where = array(
        	    'ft.file_id = f.id',
        	    'ft.tag_id = t.id',
        	    'f.`type` = \'image\''
        	);
        	$user = JFactory::getUser();
        	if (!$user->authorise('core.login.admin')) {
        	    if ($user->id) {
        	        $user_where[] = '(f.`access` = 0 OR f.access IN ('.implode(',', $user->getAuthorisedViewLevels()).'))';
        	    } else {
        	        $user_where[] = '(f.`access` = 0)';
        	    }
        	}

			$app = JFactory::getApplication();
			if ($app->isClient('site')) {
			    $user_where[] = 'f.published = 1';
			}
			$extras = $app->triggerEvent('ongetTagExtraFields', array('com_fwgallery'));

        	$db->setQuery('
SELECT
	t.*,
    u.name AS _user_name,
    (SELECT COUNT(*) FROM #__fwsg_file_tag AS ft, #__fwsg_file AS f WHERE '.implode(' AND ', $user_where).') AS _images_qty
	'.implode('', $extras).'
FROM
	#__fwsg_tag AS t
    LEFT JOIN #__users AS u ON u.id = t.user_id
WHERE
	t.id = '.(int)$oid
			);
//echo $db->getQuery(); die();
        	if ($obj = $db->loadObject()) {
        		foreach ($obj as $key=>$val) $this->$key = $val;
        		$app->triggerEvent('oncalculateTagExtraFields', array('com_fwgallery', $this));
        		return true;
        	}
        } else $this->id = 0;
    }
	function check() {
		if (!$this->name) {
			$this->setError(JText::_('FWMG_NO_TAG_NAME'));
			return;
		}
		if (!$this->alias) {
			$this->alias = $this->name;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		$db = JFactory::getDBO();
		$db->setQuery('SELECT COUNT(*) FROM #__fwsg_tag WHERE alias = '.$db->quote($this->alias).' AND id <> '.(int)$this->id);
		if ($db->loadResult()) {
			$this->setError(JText::_('FWMG_TAG_ALIAS_MUST_BE_UNIQUE'));
			return;
		}
		if (!$this->id) {
			$this->created = JHTML::date('now', 'Y-m-d H:i:s');
			$this->user_id = JFactory::getUser()->id;
			$db->setQuery('SELECT MAX(ordering) FROM #__fwsg_tag');
			$this->ordering = $db->loadResult() + 1;
		}
		return true;
	}
    function delete($oid=null) {
		if (parent::delete($oid)) {
			$db = JFactory::getDBO();
			$db->setQuery('DELETE FROM #__fwsg_file_tag WHERE tag_id = '.(int)$oid);
			$db->execute();
			return true;
		}
    }
}
