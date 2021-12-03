<?php
/**
 * FW Gallery 6.7.2
 * @copyright(C) 2018 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JPluginHelper::importPlugin('fwgallery');
JPluginHelper::importPlugin('fwgallerytmpl');
JPluginHelper::importPlugin('fwgallerytype');

define('FWMG_STORAGE', 'media/com_fwgallery/');
define('FWMG_STORAGE_PATH', JPATH_SITE.'/'.FWMG_STORAGE);

define('FWMG_UPDATE_NAME', 'com_fwgallery');
define('FWMG_UPDATE_SERVER', 'https://fastw3b.net');

/* WP integration */
if (!defined('FWMG_LANGUAGE_SITE')) {
	define('FWMG_LANGUAGE_SITE', JPATH_SITE);
}
if (!defined('FWMG_LANGUAGE_ADMINISTRATOR')) {
	define('FWMG_LANGUAGE_ADMINISTRATOR', JPATH_ADMINISTRATOR);
}
if (!defined('FWMG_COMPONENT_SITE')) {
	define('FWMG_COMPONENT_SITE', JPATH_SITE.'/components/com_fwgallery');
}
if (!defined('FWMG_COMPONENT_ADMINISTRATOR')) {
	define('FWMG_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_fwgallery');
}
if (!defined('FWMG_COMPONENT_ADMINISTRATOR')) {
	define('FWMG_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR.'/components/com_fwgallery');
}
if (!defined('FWMG_ASSETS_URI')) {
	define('FWMG_ASSETS_URI', JURI::root(true).'/components/com_fwgallery/assets/');
}
if (!defined('FWMG_ADMIN_ASSETS_URI')) {
	define('FWMG_ADMIN_ASSETS_URI', JURI::root(true).'/administrator/components/com_fwgallery/assets/');
}

define('FWMG_ASSETS_PATH', FWMG_COMPONENT_SITE.'/assets/');

class fwgView {
	var $layout = 'default';
	function setView($view) {
		$this->view = $view;
	}
	function getView() {
		return $this->view;
	}
	function setLayout($layout) {
		$this->layout = $layout;
	}
	function getLayout() {
		return $this->layout;
	}
	function loadTemplate($tmpl='') {
		$app = JFactory::getApplication();
		if ($tmpl) $tmpl = '_'.$tmpl;
		$path = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/com_fwgallery/'.$this->view.'/'.$this->layout.$tmpl.'.php';
		ob_start();
		if (file_exists($path)) {
			include($path);
		} else {
			$path = FWMG_COMPONENT_SITE.'/views/'.$this->view.'/tmpl/'.$this->layout.$tmpl.'.php';
			include($path);
		}
		return ob_get_clean();
	}
	function display() {
		echo $this->loadTemplate();
	}
	function escape($text) {
		return htmlspecialchars($text);
	}
	function getPaginationLinks($pagination, $options = array()) {
		$list = array(
			'prefix'       => $pagination->prefix,
			'limit'        => $pagination->limit,
			'limitstart'   => $pagination->limitstart,
			'total'        => $pagination->total,
			'limitfield'   => $pagination->getLimitBox(),
			'pagescounter' => $pagination->getPagesCounter(),
			'pages'        => $pagination->getPaginationPages(),
			'pagesTotal'   => $pagination->pagesTotal,
		);
		return fwgHelper::loadTemplate('pagination.links', array('view' => $this, 'list' => $list, 'options' => $options));
	}
}

class fwgParams {
	var $params = null;
	var $parent_categories = null;
	function __construct($params, $parent_categories=null) {
		if (is_object($params)) {
			$this->params = clone($params);
		} else {
			$this->params = new JRegistry($params);
		}
		if ($parent_categories) {
			$this->parent_categories = $parent_categories;
		}
	}
	function load($params) {
		if (is_null($this->params)) {
			$this->params = $params;
		} else {
			$this->params->loadArray($params->toArray());
		}
	}
	function set($key, $val) {
		$this->params->set($key, $val);
	}
	function get($key, $def=null) {
		$val = $this->params->get($key, 'def');
		if ($val == 'def') {
			if ($this->parent_categories) {
				foreach ($this->parent_categories as $category) {
					$val = $category->params->get($key, 'def');
					if ($val != 'def') {
						return $val;
					}
				}
			}
			$app = JFactory::getApplication();
			if ($app->isClient('site') and $app->input->getCmd('option') == 'com_fwgallery') {
				$val = $app->getParams()->get($key, 'def');
				if ($val == 'def') {
					$val = JComponentHelper::getParams('com_fwgallery')->get($key, $def);
				}
			} else {
				$val = JComponentHelper::getParams('com_fwgallery')->get($key, $def);
			}
		} elseif (is_null($val)) {
			$val = JComponentHelper::getParams('com_fwgallery')->get($key, $def);
		}
		return $val;
	}
}

class fwgMessages {
	static function getInstance() {
		static $instance = null;
		if (is_null($instance)) {
			$instance = new fwgMessages();
		}
		return $instance;
	}
	function add($msg, $status='warning') {
		$messages = $this->getAll();
		$messages[] = (object)array(
			'msg' => $msg,
			'status' => $status
		);
		$this->set($messages);
	}
	function getLast() {
		if ($messages = $this->getAll()) {
			return $messages[count($messages)-1];
		}
	}
	function clearAll() {
		$this->set(array());
	}
	function set($messages) {
		JFactory::getApplication()->setUserState('com_fwgallery.messages', $messages);
	}
	function getAll() {
		return JFactory::getApplication()->getUserState('com_fwgallery.messages', array());
	}
}

class fwgButtons {
	var $buttons = array();
	static function getInstance() {
		static $btns;
		if (!is_object($btns)) {
			$btns = new fwgButtons;
		}
		return $btns;
	}
	function addButton($icon, $color, $alt, $task, $listSelect) {
		$btn = (object)array(
			'icon' => $icon,
			'color' => $color,
			'alt' => JText::_($alt),
			'task' => $task,
			'listSelect' => $listSelect
		);
		$this->buttons[] = $btn;
	}
	function getButtons() {
		return $this->buttons;
	}
}
class fwgButtonsHelper {
	static function addNew($task='add', $alt="JTOOLBAR_APPLY") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-plus-circle', $color='green', $alt, $task, $listSelect=false);
	}
	static function custom($icon, $color, $alt, $task, $listSelect) {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon, $color, $alt, $task, $listSelect);
	}
	static function editList($task='edit', $alt="JTOOLBAR_EDIT") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-edit', $color='', $alt, $task, $listSelect=false);
	}
	static function deleteList($listSelect='', $task='remove', $alt="JTOOLBAR_DELETE") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-trash-alt', $color='red', $alt, $task, $listSelect);
	}
	static function publish($task='publish', $alt="JTOOLBAR_PUBLISH", $listSelect=true) {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-check-circle', $color='', $alt, $task, $listSelect);
	}
	static function unpublish($task='unpublish', $alt="JTOOLBAR_UNPUBLISH", $listSelect=true) {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-times-circle', $color='', $alt, $task, $listSelect);
	}
	static function apply($task='apply', $alt="JTOOLBAR_APPLY") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-edit', $color='green', $alt, $task, $listSelect=false);
	}
	static function save($task='save', $alt="JTOOLBAR_SAVE") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-save', $color='', $alt, $task, $listSelect=false);
	}
	static function cancel($task='cancel', $alt="JTOOLBAR_CANCEL") {
		$btns = fwgButtons::getInstance();
		$btns->addButton($icon='fal fa-times', $color='', $alt, $task, $listSelect=false);
	}
}

class fwgHelper {
	static function route($url, $b1=true, $b2=0) {
		static $j4r = null;
		if (is_null($j4r)) {
			$inp = JFactory::getApplication()->input;
			if ($inp->getCmd('option') == 'com_fwgallery' and class_exists('JVersion')) {
				$ver = new JVersion;
				$j4r = $ver->isCompatible('4.0');
			}
			if (is_null($j4r)) $j4r = false;
		}
		if ($j4r) {
			$url = str_replace(array('option=com_fwgallery&amp;', 'option=com_fwgallery&'), '', $url);
		}
		return JRoute::_($url, $b1, $b2);
	}
	static function triggerEvent($event, $params) {
		$data = JFactory::getApplication()->triggerEvent($event, $params);
		static $modals = array();
		$plugins = array(
			'plg_fwgallery_admin',
			'plg_fwgallery_cascading',
			'plg_fwgallery_featured',
			'plg_fwgallery_import',
			'plg_fwgallery_map',
			'plg_fwgallery_meta',
			'plg_fwgallery_seobooster',
			'plg_fwgallery_slideshowview',
			'plg_fwgallery_social',
			'plg_fwgallery_tag',
			'plg_fwgallery_vote',
		);
		$path = FWMG_COMPONENT_ADMINISTRATOR.'/layouts/addons/';
		foreach ($plugins as $plugin) {
			$buff = explode('_', $plugin);
			if (!fwgHelper::pluginEnabled($buff[count($buff) - 1]) and file_exists($path.$plugin.'/'.strtolower($event).'.php')) {
				if (!isset($modals[$plugin])) {
					if (file_exists($path.$plugin.'/modal_advert.php')) {
?>
<div class="modal fade" id="fwmg-modal-advert-<?php echo esc_attr($plugin); ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo JText::_('FWMG_ADDON_'.$plugin); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php include($path.$plugin.'/modal_advert.php'); ?>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    (function ($) {
		$('#fwmg-modal-advert-<?php echo esc_js($plugin); ?>').on('show', function() {
			$(this).addClass('show');
		}).on('hide', function() {
			$(this).removeClass('show');
		});
    })(jQuery);
});
</script>
<?php
					}
					$modals[$plugin] = true;
				}
?>
<div class="fwa-modal-advert" data-target="#fwmg-modal-advert-<?php echo esc_attr($plugin); ?>" data-toggle="modal" data-bs-toggle="modal"><?php include($path.$plugin.'/'.strtolower($event).'.php'); ?></div>
<?php
			}
		}
		return $data;
	}
	static function fixDescriptionImagesLinks($text) {
		if (preg_match_all('#(src\=")(images/)#', $text, $matches, PREG_SET_ORDER)) {
			$base = JURI::root(true).'/';
			foreach ($matches as $match) {
				$text = str_replace($match[0], $match[1].$base.$match[2], $text);
			}
		}
		return $text;
	}
	static function messageEscape($text) {
		return esc_js(str_replace(array("\r\n", "\n", "\r"), '<br/>', $text));
	}
	static function addMessage($msg, $status='warning') {
		$messages = fwgMessages::getInstance();
		$messages->add($msg, $status);
	}
	static function getMessage() {
		$messages = fwgMessages::getInstance();
		return $messages->getLast();
	}
	static function getMessages() {
		$messages = fwgMessages::getInstance();
		$data = $messages->getAll();
		$messages->clearAll();
		return $data;
	}
	static function clearMessages() {
		$messages = fwgMessages::getInstance();
		return $messages->clearAll();
	}
	static function loadAdminStyles() {
		JHTML::stylesheet('components/com_fwgallery/assets/css/bootstrap.css', array('version'=>'v=101'));
		JHTML::stylesheet('components/com_fwgallery/assets/css/all.min.css', array('version'=>'v=100'));
		JHTML::stylesheet('administrator/components/com_fwgallery/assets/css/fw-back-end.css', array('version'=>'v=102'));
		JHTML::stylesheet('administrator/components/com_fwgallery/assets/css/fwmg-admin.css', array('version'=>'v=103'));
		$doc = JFactory::getDocument();
		if (method_exists($doc, 'getWebAssetManager')) {
			$doc->getWebAssetManager()->useScript('bootstrap.tab');
			$doc->getWebAssetManager()->useScript('bootstrap.modal');
			$doc->getWebAssetManager()->useScript('bootstrap.popover');
		}
	}
	static function loadSiteStyles() {
		if (!defined('FWMG_STYLES_LOADED')) {
			define('FWMG_STYLES_LOADED', true);
			JHTML::_('jquery.framework');
			JHTML::script('components/com_fwgallery/assets/js/list.js', array('version'=>'v=112'));
			$com_params = JComponentHelper::getParams('com_fwgallery');
			if (!$com_params->get('do_not_load_bootstrap')) {
				JHTML::stylesheet('components/com_fwgallery/assets/css/bootstrap.css', array('version'=>'v=101'));
				JHTML::script('components/com_fwgallery/assets/js/tether.min.js');
				JHTML::script('components/com_fwgallery/assets/js/popper.js');
				JHTML::script('components/com_fwgallery/assets/js/bootstrap.min.js');
			}
			if (!$com_params->get('do_not_load_awesome')) {
				JHTML::stylesheet('components/com_fwgallery/assets/css/all.min.css');
			}
			JHTML::stylesheet('components/com_fwgallery/assets/css/fwmg-design-styles.css', array('version'=>'v=104'));
			$buff = $com_params->get('additional_css');
			$font_size = (int)$com_params->get('font_size', 16);
			if (!$font_size) $font_size = 16;
			$buff = 'html {font-size: '.$font_size.'px;}'.$buff;
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration($buff);
/* bs modal j!v4 */
			if (method_exists($doc, 'getWebAssetManager')) {
				$doc->getWebAssetManager()->useScript('bootstrap.modal');
				$doc->getWebAssetManager()->useScript('bootstrap.popover');
			}

			/* komento */
			if ($com_params->get('comments_type') == 'komento' and file_exists(JPATH_ROOT.'/components/com_komento/bootstrap.php')) {
				require_once(JPATH_ROOT.'/components/com_komento/bootstrap.php');
				KT::initialize();
			}
		}
	}
	static function colorpicker() {
		JHTML::_('jquery.framework');
		JHTML::stylesheet('components/com_fwgallery/assets/css/jquery.minicolors.css');
		JHTML::script('components/com_fwgallery/assets/js/jquery.minicolors.min.js');
		JFactory::getDocument()->addScriptDeclaration('
document.addEventListener(\'DOMContentLoaded\', function() {
	(function($) {
	$(\'.minicolors\').each(function() {
		$(this).minicolors({
			control: $(this).attr(\'data-control\') || \'hue\',
			format: $(this).attr(\'data-validate\') === \'color\'
				? \'hex\'
				: ($(this).attr(\'data-format\') === \'rgba\'
					? \'rgb\'
					: $(this).attr(\'data-format\'))
				|| \'hex\',
			keywords: $(this).attr(\'data-keywords\') || \'\',
			opacity: $(this).attr(\'data-format\') === \'rgba\' ? true : false || false,
			position: $(this).attr(\'data-position\') || \'default\',
			theme: \'bootstrap\'
		});
	});
	})(jQuery);
});');
	}
	static function getEditor() {
		$editor = JFactory::getConfig()->get('editor');
		return JEditor::getInstance($editor);
	}
	static function getSearchData() {
		return JFactory::getApplication()->getUserStateFromRequest('com_fwgallery.search', 'search');
	}
	static function checkTime($time) {
		return preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $time);
	}
	static function checkClientContacts() {
		$params = JComponentHelper::getParams('com_fwgallery');
		$update_code = $params->get('update_code');
		$verified_code = $params->get('verified_code');

		$user_name = $params->get('user_name');
		$user_avatar = $params->get('user_avatar');
		$user_email = $params->get('user_email');

		if ($update_code and $update_code == $verified_code and (!$user_name or !$user_avatar or !$user_email)) {
			if ($buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=verify_code&format=raw&package='.FWMG_UPDATE_NAME.'&code='.urlencode($update_code))) {
				$tmp = json_decode($buff);
				if ($tmp) {
					if (!empty($tmp->msg)) JFactory::getApplication()->enqueueMessage($tmp->msg, 'error');
					$params->set('user_name', empty($tmp->user_name)?'':$tmp->user_name);
					$params->set('user_avatar', empty($tmp->user_avatar)?'':$tmp->user_avatar);
					$params->set('user_email', empty($tmp->user_email)?'':$tmp->user_email);
					fwgHelper::storeConfig($params);
				}
			}
		}
	}
	static function getArrayValue($data, $key, $def=null) {
		if (isset($data[$key])) return $data[$key];
		return $def;
	}
	static function getCategoryParams($cid) {
		static $data = array();
		if (!isset($data[$cid])) {
			$cats = fwgHelper::loadCategories();
			$params = null;
			if ($cid) {
				foreach ($cats as $cat) {
					if ($cat->id == $cid) {
						$data[$cid] = new fwgParams($cat->params, fwgHelper::getParentGalleries($cat->parent));
						break;
					}
				}
			}
			if (!isset($data[$cid])) {
				$data[$cid] = new fwgParams(new JRegistry);
			}
		}
		return $data[$cid];
	}
	static function getGridsList() {
		return array(
			JHTML::_('select.option', 'standard', JText::_('FWMG_GRID_STANDARD'), 'id', 'name'),
			JHTML::_('select.option', 'waterfall', JText::_('FWMG_GRID_WATERFALL'), 'id', 'name'),
//			JHTML::_('select.option', 'masonry', JText::_('FWMG_GRID_MASONRY'), 'id', 'name'),
			JHTML::_('select.option', 'justified', JText::_('FWMG_GRID_JUSTIFIED'), 'id', 'name'),
			JHTML::_('select.option', 'custom', JText::_('FWMG_GRID_CUSTOM'), 'id', 'name'),
		);
	}
	static function getGridsSizeLimits() {
		return array(
			'custom' => array(
				'min_cols' => 5,
				'min_rows' => 4
			)
		);
	}
	static function getLimitQty($cols, $rows, $grid) {
		$num = $cols * $rows;
		if ($grid == 'custom') {
			$num -= 8;
		}
		return $num;
	}
	static function getLimitOptions($cols, $rows, $grid, $limit, $total) {
		$data = array();
		$num = fwgHelper::getLimitQty($cols, $rows, $grid);
		if ($total >= $num) {
			for ($i = 1; $i < 4; $i++) {
				$data[] = JHTML::_('select.option', $num * $i, $num * $i, 'id', 'name');
			}
		}
		return $data;
	}
	static function splitSql($sql) {
		$start = 0;
		$open = false;
		$char = '';
		$end = strlen($sql);
		$queries = array();

		for ($i = 0; $i < $end; $i++)
		{
			$current = substr($sql, $i, 1);

			if (($current == '"' || $current == '\''))
			{
				$n = 2;

				while (substr($sql, $i - $n + 1, 1) == '\\' && $n < $i)
				{
					$n++;
				}

				if ($n % 2 == 0)
				{
					if ($open)
					{
						if ($current == $char)
						{
							$open = false;
							$char = '';
						}
					}
					else
					{
						$open = true;
						$char = $current;
					}
				}
			}

			if (($current == ';' && !$open) || $i == $end - 1)
			{
				$queries[] = substr($sql, $start, ($i - $start + 1));
				$start = $i + 1;
			}
		}

		return $queries;
	}
	static function fileDownloadable($file) {
		if ($file->type == 'image') {
			if ($file->_sys_filename) {
				$path = fwgHelper::getImagePath($file->_sys_filename);
				return file_exists($path.$file->_sys_filename);
			}
		} else {
			$buff = JFactory::getApplication()->triggerEvent('onfileDownloadable', array('com_fwgallery.'.$file->type, $file));
			if ($buff) {
				foreach ($buff as $value) {
					if ($value) return true;
				}
			}
		}
	}
	static function stripTags($text, $limit=null, $show_counter=false) {
		if (!$limit) return;
		$text = strip_tags(preg_replace(array('#\{[^\}]+\}#', '#^<p>|</p>$#', '#\r\n\r\n#', '#\n\n#', '#\&nbsp;#'), array('', '', "\n", "\n", ' '), trim($text)));
		if (is_numeric($limit) and $limit > 0 and mb_strlen($text) > $limit) {
			$text = mb_substr($text, 0, $limit).'&hellip;'.($show_counter?(' ['.mb_strlen($text).']'):'');
		}
		return nl2br($text);
	}
	static function loadCategories() {
		static $cats;
		if (!is_array($cats)) {
			$db = JFactory::getDBO();
			$db->setQuery('
SELECT
    c.id,
	c.parent,
	c.published,
	c.name,
	c.params
FROM
    #__fwsg_category AS c
ORDER BY
    `parent`,
    ordering');
			$cats = (array)$db->loadObjectList('id');
			foreach ($cats as $i=>$cat) {
				$cats[$i]->params = new JRegistry($cat->params);
			}
		}
		return $cats;
	}
	static function checkPrevCategory($c) {
		$items = fwgHelper::loadCategories();
		$cats = array();
		foreach ($items as $item) {
			if ($item->parent == $c->parent) {
				$cats[$item->id] = $item;
			}
		}

		$prev_id = 0;
		foreach ($cats as $cat) {
			if ($cat->id == $c->id) {
				return !(!$prev_id or ($prev_id and $cats[$prev_id]->parent != $cat->parent));
			}
			$prev_id = $cat->id;
		}
	}
	static function checkNextCategory($c) {
		$items = fwgHelper::loadCategories();
		$cats = array();
		foreach ($items as $item) {
			if ($item->parent == $c->parent) {
				$cats[$item->id] = $item;
			}
		}

		$ids = array_keys($cats);
		$next_id = 0;
		for ($i = count($ids) - 1; $i >= 0; $i--) {
			if ($cats[$ids[$i]]->id == $c->id) {
				return !(!$next_id or ($next_id and $cats[$next_id]->parent != $cats[$ids[$i]]->parent));
			}
			$next_id = $cats[$ids[$i]]->id;
		}
	}
	static function getInstalledLanguages() {
		static $languages;
		if (!is_array($languages)) {
			$buff = JLanguageHelper::getKnownLanguages();
			foreach ($buff as $row) {
				$lang = new stdclass;
				$lang->id = $row['tag'];
				$lang->tag = $row['tag'];
				$lang->name = $row['name'];
				$languages[$row['tag']] = $lang;
			}
		}
		return $languages;
	}
	static function getLanguage() {
		static $lang = null;
		if (!$lang) {
			$db = JFactory::getDBO();
			$app = JFactory::getApplication();
			$lang = $app->getUserStateFromRequest('com_fwgallery.language', 'lang');
			if ($lang and strlen($lang) == 2) {
				$db->setQuery('SELECT `lang_code` FROM `#__languages` WHERE `sef` = '.$db->quote($lang));
				$lang = $db->loadResult();
			}
			if (!$lang) {
				$lang  = JFactory::getLanguage()->getTag();
			}
			if (!$lang or ($lang and !in_array($lang, array_keys(fwgHelper::getInstalledLanguages())))) {
				$db->setQuery('SELECT `lang_code` FROM `#__languages` WHERE `published` = 1 AND `ordering` = 1');
				$lang = $db->loadResult();
				if (!$lang) {
					$lang = 'en-GB';
				}
			}
		}
		return $lang;
	}
	static function storeConfig($params) {
		$cache = JFactory::getCache('_system', 'callback');
    	$cache->clean();

		fwgHelper::clearImageCache();

    	$db = JFactory::getDBO();
    	$db->setQuery('UPDATE `#__extensions` SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallery\' AND `type` = \'component\'');
    	return $db->execute();
	}
	static function encodeDate($date) {
		if (!$date or (is_string($date) and $date[0]=='0')) {
			return '';
		} else {
			$params = JComponentHelper::getParams('com_fwgallery');
			$date_format = str_replace(array(
				'%Y',
				'%d',
				'%B'
			), array(
				'Y',
				'd',
				'F'
			), $params->get('date_format'));
			return JHTML::date($date, $date_format);
		}
	}
	static function decodeDate($date) {
		if ($buff = explode('/', $date) and count($buff) == 3) {
			return $buff[2].'-'.$buff[0].'-'.$buff[1];
		} elseif ($date = @strtotime($date)) {
			return date('Y-m-d', $date);
		} else {
			return '';
		}
	}
	static function getIniSize($name) {
		$val = ini_get($name);
		if (preg_match('/^(\d+)([MK])$/', $val, $matches)) {
			if ($matches[2] == 'M') $val = $matches[1] * 1024 * 1024;
			elseif ($matches[2] == 'K') $val = $matches[1] * 1024;
		}
		return $val;
	}
    static function loadCategoriesPath($category_id = 0, $itemid = 0) {
		static $categories_above = array();
		if (!isset($categories_above[$category_id])) {
			$categories_above[$category_id] = array();
			if ($category_id) {
				$db = JFactory::getDBO();
				$parent = new stdclass;
				$parent->parent = $category_id;
				do {
					$db->setQuery('SELECT id, parent, name FROM `#__fwsg_category` WHERE id = '.(int)$parent->parent);
					if ($parent = $db->loadObject()) {
                        $parent->link = 'index.php?option=com_fwgallery&view=fwgallery&id='.$parent->id.':'.JFilterOutput::stringURLSafe($parent->name).'&Itemid='.$itemid;
                        $categories_above[$category_id][] = $parent;
                    }
				} while ($parent);

				$categories_above[$category_id] = array_reverse($categories_above[$category_id]);
			}
			$menu = JMenu::getInstance('site');
			if ($active = $menu->getActive()) {
				$ids = (array)$active->getParams()->get('gids');

				$add_root = true;
				if ($ids and $categories_above[$category_id]) {
					$qty = count($categories_above[$category_id]) - 1;
					for ($i = $qty; $i >= 0; $i--) {
						if (!isset($categories_above[$category_id][$i])) break;
						$cat = $categories_above[$category_id][$i];
						if (in_array($cat->id, $ids)) {
							$add_root = false;
							if ($i == $qty) {
								$categories_above[$category_id] = array();
							} else {
								$categories_above[$category_id] = array_slice($categories_above[$category_id], $i);
							}
						}
					}
				}
				if ($add_root) {
					array_unshift($categories_above[$category_id], (object)array(
						'id' => 0,
						'alias' => $active->alias,
						'name' => $active->title,
						'link' => 'index.php?Itemid='.$active->id
					));
				}
			}
		}
		return $categories_above[$category_id];
    }
	static function getImageLink($image) {
		$prefix = substr($image, 0, 2);
		return JURI::root(true).'/'.FWMG_STORAGE.$prefix.'/';
	}
	static function getImagePath($image) {
		$prefix = substr($image, 0, 2);
		return FWMG_STORAGE_PATH.$prefix.'/';
	}
	static function getThemesList() {
		$themes = array(
			JHTML::_('select.option', 'common', JText::_('FWMG_THEME_DEFAULT'), 'id', 'name')
		);
		$db = JFactory::getDBO();
		$db->setQuery("SELECT name, `element` FROM `#__extensions` WHERE `type` = 'plugin' AND `folder` = 'fwgallerytmpl' AND `enabled` = 1");
		if ($list = $db->loadObjectList()) {
			$lang = JFactory::getLanguage();
			foreach ($list as $row) {
				$lang->load('plg_fwgallerytmpl_'.$row->element, JPATH_ADMINISTRATOR);
				$themes[] = JHTML::_('select.option', $row->element, JText::_($row->name), 'id', 'name');
			}
		}
		return $themes;
	}
	static function getLayoutsList() {
		return array(
			JHTML::_('select.option', 'hide', JText::_('FWMG_LAYOUT_HIDE'), 'id', 'name'),
			JHTML::_('select.option', 'top', JText::_('FWMG_LAYOUT_TOP'), 'id', 'name'),
			JHTML::_('select.option', 'right', JText::_('FWMG_LAYOUT_RIGHT'), 'id', 'name'),
			JHTML::_('select.option', 'bottom', JText::_('FWMG_LAYOUT_BOTTOM'), 'id', 'name'),
			JHTML::_('select.option', 'left', JText::_('FWMG_LAYOUT_LEFT'), 'id', 'name'),
			JHTML::_('select.option', 'over_slide_up', JText::_('FWMG_LAYOUT_OVER_SLIDE_UP'), 'id', 'name'),
			JHTML::_('select.option', 'over_full_hover', JText::_('FWMG_LAYOUT_OVER_FULL_HOVER'), 'id', 'name'),
		);
	}
	static function getHoverList() {
		$layouts = array();
		jimport('joomla.filesystem.folder');
		if ($files = JFolder::files(FWMG_COMPONENT_SITE.'/layouts/common/hover')) {
			foreach ($files as $file) {
				$key = str_ireplace('.php', '', $file);
				$value = str_replace('_', ' ', $key);
				$layouts[] = JHTML::_('select.option', $key, ucfirst(strtolower($value)), 'id', 'name');
			}
		}
		return $layouts;
	}
	static function request($url, $method='get', $data=null) {
		$method = strtolower($method);
		if (!in_array($method, array('get', 'post', 'put', 'delete'))) {
			$method = 'get';
		}
		if ($data and in_array($method, array('get', 'delete'))) {
			$buff = http_build_query($data);
			$url .= (strpos($url, '?') === false ? '?' : '&') . $buff;
		}
		$http = JHttpFactory::getHttp();
		$result = '';
		try {
			switch ($method) {
				case 'get':
					$result = $http->get($url);
					break;
				case 'post':
					$result = $http->post($url, $data);
					break;
				case 'put':
					$result = $http->put($url, $data);
					break;
				case 'delete':
					$result = $http->delete($url);
					break;
			}
		} catch (Exception $e) {
			fwgHelper::addMessage($e->getMessage(), 'danger');
		}
		return $result ? $result->body : '';
	}
    static function detectIphone() {
        if ($user_agent = JArrayHelper::getValue($_SERVER, 'HTTP_USER_AGENT')) {
            $mobile_oses = array('iPhone','iPod','iPad','iPaid');
            foreach ($mobile_oses as $wos) if (strpos($user_agent, $wos) !== false) {
                return true;
            }
        }
    }
    static function humanFileSize($val) {
        if ($val > 1073741824) return round($val / 1073741824, 2).' Gb';
        if ($val > 1048576) return round($val / 1048576, 2).' Mb';
        elseif ($val > 1024) return round($val / 1024, 2).' Kb';
        elseif ($val and is_numeric($val)) return $val.' b';
        else return $val;
    }
    static function clearImageCache($id = null) {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
		$cache_path = JPATH_SITE.'/cache/fwgallery/images/';
        if (is_dir($cache_path)) {
            if ($files = JFolder::files($cache_path, $id?('^'.$id.'_'):'.*')) {
                foreach ($files as $file) {
                    JFile::delete($cache_path.$file);
                }
            }
        }
    }
    static function findUnicFilename($path, $filename) {
        jimport('joomla.filesystem.filename');
        $ext = strtolower(JFile::getExt($filename));
        $name = strtolower(trim(JFile::makeSafe(JFile::stripExt($filename)), '-'));
        $result = '';
        $index = 0;
        do {
            $result = $name.(($index > 0)?('-'.$index):'').($ext?('.'.$ext):'');
            $index++;
        } while (file_exists($path.$result));
        return $result;
    }
    static function findRandUnicFilename($path, $ext) {
        $result = '';
        do {
            $result = md5(rand()).($ext?('.'.$ext):'');
        } while (file_exists($path.$result));
        return $result;
    }
    static function getGps($exifCoord, $hemi) {
        $degrees = count($exifCoord) > 0 ? fwgHelper::gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? fwgHelper::gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? fwgHelper::gps2Num($exifCoord[2]) : 0;
        $flip =($hemi == 'W' or $hemi == 'S') ? -1 : 1;
        return floatval($flip *($degrees +($minutes/60)+($seconds/3600)));
    }
    static function gps2Num($coordPart) {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0) {
            return 0;
        } else if (count($parts) == 1) {
            return $parts[0];
        } else {
            return floatval($parts[0]) / floatval($parts[1]);
        }
    }
    static function getIP() {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
            $ip = getenv('HTTP_CLIENT_IP');
        elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
            $ip = getenv('REMOTE_ADDR');
        elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = '127.0.0.1';
        return $ip;
    }
	static function getChildGalleriesIDS($ids) {
		$galleries = fwgHelper::loadCategories();
		if ($ids) {
			do {
				$found = false;
				foreach ($galleries as $gallery) {
					if (!in_array($gallery->id, $ids) and in_array($gallery->parent, $ids)) {
						$ids[] = $gallery->id;
						$found = true;
					}
				}
			} while($found);
		}
		return $ids;
	}
	static function getParentGalleries($id) {
		$galleries = fwgHelper::loadCategories();
		$data = array();
		if ($id) {
			foreach ($galleries as $gallery) {
				if ($gallery->id == $id) {
					$data[(int)$gallery->parent] = $gallery;
				}
			}
			do {
				$found = false;
				$ids = array_keys($data);
				foreach ($galleries as $gallery) {
					if (!isset($data[$gallery->parent]) and in_array($gallery->id, $ids)) {
						$data[$gallery->parent] = $gallery;
						$found = true;
					}
				}
			} while($found);
		}
		return $data;
	}
	static function fileExists($filename) {
		if ($filename) {
			$path = fwgHelper::getImagePath($filename);
			return file_exists($path.$filename);
		}
	}
    static function loadTemplate($view, $displayData, $path=null) {
        $app = JFactory::getApplication();
		$params = null;
		if ($app->isClient('administrator') or $app->input->getCmd('option') != 'com_fwgallery') {
			$params = JComponentHelper::getParams('com_fwgallery');
		} else {
			$params = $app->getParams();
		}

        $tmpl = '';
		$com_path = FWMG_COMPONENT_SITE.'/layouts/';
		$j_path = JPATH_SITE.'/templates/'.$app->getTemplate().'/html/layouts/fwgallery/';

		if ($app->isClient('site') and !defined('FWMG_STYLES_LOADED')) {
			fwgHelper::loadSiteStyles();
		}
		if (is_null($path)) {
			$tmpl = $app->input->getCmd('t', $displayData['view']->params->get('template'));
			$path = JPATH_SITE.'/plugins/fwgallerytmpl/';

			if (!$tmpl or ($tmpl and !is_dir($path.$tmpl))) {
				$tmpl = 'common';
				$path = $com_path;
			} else {
				if (file_exists($path.$tmpl.'/assets/css/fwmg-design-styles.css')) {
					JHTML::stylesheet('plugins/fwgallerytmpl/'.$tmpl.'/assets/css/fwmg-design-styles.css', array('version'=>'v=103'));
				}
			}
		}

        $buff = explode('.', $view);
        $filename = array_pop($buff).'.php';
        $view_path = implode('/', $buff);

		/* if requested file exists in global template, use it */
		if (file_exists($j_path.$tmpl.'/'.$view_path.'/'.$filename)) {
			$path = $j_path;
		}

        $full_path = $path.$tmpl.'/'.$view_path.'/'.$filename;
        $tmpl_found = file_exists($full_path);
        if (!$tmpl_found and $tmpl != 'common') {
            $full_path = $com_path.'common/'.$view_path.'/'.$filename;
            $tmpl_found = file_exists($full_path);
        }
        if ($tmpl_found) {
            ob_start();
            include($full_path);
            return ob_get_clean();
        } else {
            return 'template '.$tmpl.', view '.$view.' not found';
        }
    }
	static function loadUsers() {
        static $users;
        if (!is_array($users)) {
            $db = JFactory::getDBO();
            $db->setQuery('SELECT u.id, u.name FROM `#__users` AS u ORDER BY u.name');
            $users =(array)$db->loadObjectList();
        }
        return $users;
    }
	static function loadviewlevels() {
        static $viewlevels;
        if (!is_array($viewlevels)) {
            $db = JFactory::getDBO();
			$db->setQuery('
SELECT
	id,
	title AS name
FROM
	`#__viewlevels`
ORDER BY
	ordering');
			$viewlevels = $db->loadObjectList();
        }
        return $viewlevels;
    }
    static function loadPlugins() {
        static $plugins;
        if (!is_array($plugins)) {
			$plugins = array();

            $db = JFactory::getDBO();
            $db->setQuery('
SELECT
    `element`,
    `enabled`,
	`folder`
FROM
    #__extensions
WHERE
    `type` = \'plugin\'
    AND
    `folder` IN (\'fwgallery\', \'fwgallerytype\', \'fwgallerytmpl\')');
            if ($data = $db->loadObjectList()) {
				foreach ($data as $row) {
					if (!isset($plugins[$row->folder])) {
						$plugins[$row->folder] = array();
					}
					$plugins[$row->folder][$row->element] = $row;
				}
			}
        }
        return $plugins;
    }
    static function pluginInstalled($name, $type = 'fwgallery') {
        $plugins = fwgHelper::loadPlugins();
        return isset($plugins[$type][$name]);
    }
    static function pluginEnabled($name, $type = 'fwgallery') {
        $plugins = fwgHelper::loadPlugins();
        if (!empty($plugins[$type][$name])) {
            return $plugins[$type][$name]->enabled;
        }
    }
    static function getWatermarkFilename() {
        $params = JComponentHelper::getParams('com_fwgallery');
        if ($watermark = $params->get('watermark_file')) {
            if (file_exists(FWMG_STORAGE_PATH.$watermark)) {
                return FWMG_STORAGE.$watermark;
            }
        }
    }
	static function checkLink($link) {
		$app = JFactory::getApplication();
		if ($app->getCfg('sef') and !$app->getCfg('sef_rewrite') and strpos($link, 'index.php') === false) {
			$root = JURI::root(false);
			if (strpos($link, $root) !== false) {
				$link = str_replace($root, $root.'index.php/', $link);
			} else {
				$link = '/index.php'.$link;
			}
		}
		return $link;
	}
	static function pluginDisabledViaMenu($plugin) {
	    $app = JFactory::getApplication();
	    if ($app->isClient('site')) {
    		$params = $app->getParams();
			if ($params->get('show_addons_menu_settings')) {
				$disabled_addons = $params->get('plugins', array($plugin=>1));
				return empty($disabled_addons[$plugin]);
			}
	    }
	}
	static function escPluginsOutput($plugins_output) {
		static $tags = null;
		if (false and function_exists('wp_kses')) {
			if (is_null($tags)) {
				$tags = wp_kses_allowed_html();
				$tags['h1'] = array('class'=>1, 'id'=>1, 'data-title'=>1, 'data-toggle'=>1, 'itemprop'=>1);
				$tags['form'] = array('class'=>1, 'id'=>1, 'name'=>1, 'action'=>1);
				$tags['input'] = array('id'=>1, 'class'=>1, 'type'=>1, 'name'=>1, 'value'=>1);
				$tags['textarea'] = array('id'=>1, 'class'=>1, 'cols'=>1, 'rows'=>1, 'style'=>1, 'name'=>1, 'value'=>1);
				$tags['select'] = array('id'=>1, 'class'=>1, 'type'=>1, 'name'=>1, 'data-toggle'=>1, 'data-title'=>1);
				$tags['button'] = array('id'=>1, 'class'=>1, 'type'=>1, 'name'=>1, 'data-toggle'=>1, 'data-title'=>1, 'data-dismiss'=>1, 'data-bs-dismiss'=>1, 'data-filter'=>1, 'data-value'=>1, 'data-active-class'=>1);
				$tags['iframe'] = array('id'=>1, 'class'=>1, 'src'=>1, 'name'=>1, 'frameborder'=>1, 'allow'=>1, 'webkitallowfullscreen'=>1, 'mozallowfullscreen'=>1, 'allowfullscreen'=>1, 'allowautoplay'=>1);
				$tags['i'] = array('class'=>1, 'id'=>1);
				$tags['span'] = array('class'=>1, 'id'=>1);
				$tags['div'] = array('id'=>1, 'class'=>1, 'data-toggle'=>1, 'data-target'=>1);
				$tags['p'] = array('id'=>1, 'class'=>1);
				$tags['label'] = array('id'=>1, 'class'=>1);
				$tags['script'] = array();
				$tags['option'] = array('value'=>1);
				$tags['video'] = array('poster', 'preload', 'controls', 'height', 'width', 'class', 'id', 'data-setup');
				$tags['source'] = array('src', 'type');

				$tags['a'] = array('class'=>1, 'id'=>1, 'href'=>1, 'data-layout'=>1, 'data-target'=>1, 'data-selector'=>1, 'data-items-per-page'=>1, 'data-total'=>1, 'data-order'=>1, 'data-tmpl'=>1);
			}
			return str_replace('&amp;&amp;', '&&', wp_kses($plugins_output, $tags));
		} else return $plugins_output;
	}
}
if (!class_exists('JArrayHelper')) {
	class JArrayHelper {
		protected static $sortCase;
		protected static $sortDirection;
		protected static $sortKey;
		protected static $sortLocale;
		static function toInteger(&$data, $default=0) {
			foreach ($data as $i=>$val) {
				$data[$i] = is_numeric($val)?(int)$val:$default;
			}
		}
		static function toString($data) {
			return implode(' ', (array)$data);
		}
		static function getValue($data, $name, $default='') {
			if (isset($data[$name])) return $data[$name];
			else return $default;
		}
		public static function sortObjects(&$a, $k, $direction = 1, $caseSensitive = true, $locale = false) {
			if (!is_array($locale) || !is_array($locale[0]))
			{
				$locale = array($locale);
			}
	
			self::$sortCase = (array) $caseSensitive;
			self::$sortDirection = (array) $direction;
			self::$sortKey = (array) $k;
			self::$sortLocale = $locale;
	
			usort($a, array(__CLASS__, '_sortObjects'));
	
			self::$sortCase = null;
			self::$sortDirection = null;
			self::$sortKey = null;
			self::$sortLocale = null;
	
			return $a;
		}
		protected static function _sortObjects(&$a, &$b)
		{
			$key = self::$sortKey;
	
			for ($i = 0, $count = count($key); $i < $count; $i++)
			{
				if (isset(self::$sortDirection[$i]))
				{
					$direction = self::$sortDirection[$i];
				}
	
				if (isset(self::$sortCase[$i]))
				{
					$caseSensitive = self::$sortCase[$i];
				}
	
				if (isset(self::$sortLocale[$i]))
				{
					$locale = self::$sortLocale[$i];
				}
	
				$va = $a->{$key[$i]};
				$vb = $b->{$key[$i]};
	
				if ((is_bool($va) || is_numeric($va)) && (is_bool($vb) || is_numeric($vb)))
				{
					$cmp = $va - $vb;
				}
				elseif ($caseSensitive)
				{
					$cmp = StringHelper::strcmp($va, $vb, $locale);
				}
				else
				{
					$cmp = StringHelper::strcasecmp($va, $vb, $locale);
				}
	
				if ($cmp > 0)
				{
					return $direction;
				}
	
				if ($cmp < 0)
				{
					return -$direction;
				}
			}
	
			return 0;
		}
	}
}
if (!class_exists('StringHelper')) {
	class StringHelper {
		public static function strcasecmp($str1, $str2, $locale = false)
		{
			if ($locale)
			{
				// Get current locale
				$locale0 = setlocale(LC_COLLATE, 0);
	
				if (!$locale = setlocale(LC_COLLATE, $locale))
				{
					$locale = $locale0;
				}
	
				// See if we have successfully set locale to UTF-8
				if (!stristr($locale, 'UTF-8') && stristr($locale, '_') && preg_match('~\.(\d+)$~', $locale, $m))
				{
					$encoding = 'CP' . $m[1];
				}
				elseif (stristr($locale, 'UTF-8') || stristr($locale, 'utf8'))
				{
					$encoding = 'UTF-8';
				}
				else
				{
					$encoding = 'nonrecodable';
				}
	
				// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
				if ($encoding == 'UTF-8' || $encoding == 'nonrecodable')
				{
					return strcoll(utf8_strtolower($str1), utf8_strtolower($str2));
				}
	
				return strcoll(
					static::transcode(utf8_strtolower($str1), 'UTF-8', $encoding),
					static::transcode(utf8_strtolower($str2), 'UTF-8', $encoding)
				);
			}
	
			return utf8_strcasecmp($str1, $str2);
		}
		public static function strcmp($str1, $str2, $locale = false)
		{
			if ($locale)
			{
				// Get current locale
				$locale0 = setlocale(LC_COLLATE, 0);
	
				if (!$locale = setlocale(LC_COLLATE, $locale))
				{
					$locale = $locale0;
				}
	
				// See if we have successfully set locale to UTF-8
				if (!stristr($locale, 'UTF-8') && stristr($locale, '_') && preg_match('~\.(\d+)$~', $locale, $m))
				{
					$encoding = 'CP' . $m[1];
				}
				elseif (stristr($locale, 'UTF-8') || stristr($locale, 'utf8'))
				{
					$encoding = 'UTF-8';
				}
				else
				{
					$encoding = 'nonrecodable';
				}
	
				// If we successfully set encoding it to utf-8 or encoding is sth weird don't recode
				if ($encoding == 'UTF-8' || $encoding == 'nonrecodable')
				{
					return strcoll($str1, $str2);
				}
	
				return strcoll(static::transcode($str1, 'UTF-8', $encoding), static::transcode($str2, 'UTF-8', $encoding));
			}
	
			return strcmp($str1, $str2);
		}
		public static function transcode($source, $fromEncoding, $toEncoding)
		{
			if (\is_string($source))
			{
				switch (ICONV_IMPL)
				{
					case 'glibc':
						return @iconv($fromEncoding, $toEncoding . '//TRANSLIT,IGNORE', $source);
	
					case 'libiconv':
					default:
						return iconv($fromEncoding, $toEncoding . '//IGNORE//TRANSLIT', $source);
				}
			}
		}
	}
}
if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return($arh);
    }
}
if(!function_exists('mime_content_type')) {
    function mime_content_type($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

		jimport('joomla.filesystem.file');
        $ext = strtolower(JFile::getExt($filename));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}

if (!function_exists('esc_attr')) {
	function esc_attr($text) {
		return htmlspecialchars($text);
	}
}
if (!function_exists('esc_html')) {
	function esc_html($text) {
		return $text;
	}
}
if (!function_exists('esc_js')) {
	function esc_js($text) {
		return addcslashes($text, "'");
	}
}
if (!function_exists('esc_textarea')) {
	function esc_textarea($text) {
		return htmlspecialchars($text, ENT_QUOTES);
	}
}
if (!function_exists('esc_url')) {
	function esc_url($text) {
		return urlencode($text);
	}
}
if (!function_exists('esc_url_raw')) {
	function esc_url_raw($text) {
		return rawurlencode($text);
	}
}
