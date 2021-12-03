<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class fwGalleryModelAddon extends JModelLegacy {
	function loadAddonDescription() {
		$update_name = JFactory::getApplication()->input->getString('update_name');
		if ($update_name) {
			$params = JComponentHelper::getParams('com_fwgallery');
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=get_addon_description&format=json&update_name='.urlencode($update_name).'&access_code='.urlencode($params->get('update_code'))));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->html)) {
					return $buff->html;
				}
			} else {
				$this->setError(JText::_('FWA_NO_RESPONSE_FROM_FASTWEB_WEBSITE'));
			}
		} else $this->setError(JText::_('FWA_NO_ADDON_NAME'));
	}
	function loadDealDescription() {
		$id = JFactory::getApplication()->input->getString('id');
		if ($id) {
			$params = JComponentHelper::getParams('com_fwgallery');
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=get_deal_description&format=json&deal_id='.urlencode($id).'&access_code='.urlencode($params->get('update_code'))));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->html)) {
					return $buff->html;
				}
			} else {
				$this->setError(JText::_('FWA_NO_RESPONSE_FROM_FASTWEB_WEBSITE'));
			}
		} else $this->setError(JText::_('FWA_NO_ADDON_NAME'));
	}
	function getPluginsData() {
		static $data;
		if (!is_array($data)) {
			$lang = JFactory::getLanguage();
			$db = JFactory::getDBO();
			$db->setQuery('
SELECT
	`name`,
	`element`,
	`type`,
	`type` AS subtype,
	`folder`,
	`enabled`,
	1 AS installed,
	manifest_cache,
	5 AS _ordering,
	\'\' AS loc_version,
	\'\' AS rem_version,
	\'\' AS update_name,
	\'\' AS description,
	\'\' AS image
FROM
	#__extensions
WHERE
	manifest_cache LIKE \'%Fastw3b%\'
	AND
	(name LIKE \'%gallery%\' OR name LIKE \'%fwg%\' OR name LIKE \'%fwmg%\')
	AND
	NOT (`type` IN (\'package\', \'template\') OR `folder` = \'editors-xtd\' OR (`element` = \'map\' and `folder` = \'fwgallery\'))
ORDER BY
	`type`,
	`folder`,
	`name`');
			$data = $db->loadObjectList();
			foreach ($data as $i=>$row) {
				$data[$i]->_badges = array();
				$data[$i]->update_name = ($row->type == 'plugin')?('plg_'.$row->folder.'_'.$row->element):$row->element;
				$path = ($row->type == 'module')?JPATH_SITE:JPATH_ADMINISTRATOR;
				$lang->load($data[$i]->update_name, $path);
				$data[$i]->name = JText::_($row->name);
				if ($row->element == 'com_fwgallery') {
					$data[$i]->image = JURI::base(true).'/components/com_fwgallery/assets/images/icon_fw_gallery_logo_200.png';
				} else {
					$data[$i]->image = JURI::base(true).'/components/com_fwgallery/assets/images/'.$data[$i]->update_name.'.jpg';
				}
				$manifest = json_decode($row->manifest_cache);

				$filename = '';
				if ($row->type == 'component') {
					$filename = JPATH_ADMINISTRATOR.'/components/'.$row->element.'/'.str_replace('com_', '', $row->element).'.xml';
					if ($row->element == 'com_fwgallery') {
						$data[$i]->update_name = urldecode(FWMG_UPDATE_NAME);
					}
				} elseif ($row->type == 'plugin') {
					$filename = JPATH_SITE.'/plugins/'.$row->folder.'/'.$row->element.'/'.$row->element.'.xml';
				} elseif ($row->type == 'module') {
					$filename = JPATH_SITE.'/modules/'.$row->element.'/'.$row->element.'.xml';
				}
				if ($filename) {
					$filename = strtolower($filename);
					if (file_exists($filename)) {
						$buff = file_get_contents($filename);
						if (preg_match('#<description><\!\[CDATA\[(.*?)\]\]></description>#msi', $buff, $match)) {
							$data[$i]->description = $match[1];
						}
						if (preg_match('#<version>(.*?)</version>#msi', $buff, $match)) {
							$data[$i]->loc_version = $match[1];
						}
					}
				}

				if ($row->folder == 'fwgallerytype') {
					$row->subtype = 'data type';
				} elseif ($row->folder == 'fwgallerytmpl') {
					$row->subtype = 'design';
				} elseif ($row->type == 'component') {
					$row->subtype = 'component';
				}
				if (empty($data[$i]->description) and !empty($manifest->description)) {
					$data[$i]->description = $manifest->description;
				}
			}
			$params = JComponentHelper::getParams('com_fwgallery');
			$remote_data = json_decode($params->get('extensions_data'));
			$time = $params->get('extensions_data_time');

			if ($time < time()) {
				$remote_data = array();
				$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=get_addons&format=json&product='.FWMG_UPDATE_NAME.'&cms='.(class_exists('JVersion')?'joomla':'wp').'&access_code='.urlencode($params->get('update_code'))));
				if (isset($buff->deals)) {
					$params->set('deals_data', json_encode($buff->deals));
				}
				if (isset($buff->subscr)) {
					$params->set('subscr_data', json_encode($buff->subscr));
				}
				if (isset($buff->months)) {
					$params->set('subscription_months', $buff->months);
				}
				if (isset($buff->days)) {
					$params->set('subscription_days', $buff->days);
				}

				if (!empty($buff->result)) {
					foreach ($buff->result as $row) {
						$remote_data[] = $row;
					}
					$params->set('extensions_data', json_encode($remote_data));
				}
				$params->set('extensions_data_time', time() + 60);

				fwgHelper::storeConfig($params);
				if (!empty($buff->msg)) {
					JFactory::getApplication()->enqueueMessage($buff->msg);
				}
			}

			if ($remote_data) {
				foreach ($remote_data as $r_row) {
					if ($r_row->update_name == 'pkg_fwgallery_map') {
						$r_row->update_name = 'mod_fwg_map';
					}
					$product_found = false;
					foreach ($data as $i=>$row) {
						$l_ext = explode('_', $row->update_name);
						$r_ext = explode('_', $r_row->update_name);

						if ($row->update_name == $r_row->update_name or ($r_ext[0] == 'pkg' and $l_ext[count($l_ext) - 1] == $r_ext[count($r_ext) - 1])) {
							if ($r_row->frontend_demo_link) {
								$data[$i]->frontend_demo_link = $r_row->frontend_demo_link;
							}
							if ($r_row->doc_link) {
								$data[$i]->doc_link = $r_row->doc_link;
							}
							if ($r_row->_badges) {
								$data[$i]->_badges = $r_row->_badges;
							}
							if ($r_row->_ordering) {
								$data[$i]->_ordering = $r_row->_ordering;
							}
							if ($r_row->short) {
								$data[$i]->description = $r_row->short;
							}
							if ($r_row->image) {
								$data[$i]->image = $r_row->image;
							}
							if ($r_row->name) {
								$data[$i]->name = $r_row->name;
							}
							if ($r_row->_related) {
								$data[$i]->_related = $r_row->_related;
							}
							if ($r_row->_required) {
								$data[$i]->_required = $r_row->_required;
							}
							$data[$i]->days = $r_row->days;
							$data[$i]->price = $r_row->price;
							$data[$i]->version_price = $r_row->version_price;
							if ($r_row->_addon_type) {
								$data[$i]->subtype = strtolower($r_row->_addon_type);
							}
							if ($r_row->_version) {
								$data[$i]->rem_version = $r_row->_version;
							}
							$data[$i]->_comp_version = empty($r_row->_comp_version)?'':$r_row->_comp_version;
							if (!empty($r_row->_installable)) {
								$data[$i]->installable = $r_row->_installable;
							}
							$product_found = true;
							break;
						}
					}
					if (!$product_found) {
						$type = '';
						$buff = explode('_', $r_row->update_name, 2);
						switch ($buff[0]) {
							case 'com' : $type = 'component'; break;
							case 'mod' : $type = 'module'; break;
							case 'plg' : $type = 'plugin'; break;
						}
						$data[] = (object)array(
							'name' => $r_row->name,
							'subtype' => strtolower($r_row->_addon_type),
							'type' => $type,
							'_ordering' => $r_row->_ordering,
							'_badges' => $r_row->_badges,
							'enabled' => 0,
							'installed' => 0,
							'frontend_demo_link' => $r_row->frontend_demo_link,
							'doc_link' => $r_row->doc_link,
							'update_name' => $r_row->update_name,
							'description' => $r_row->short,
							'image' => $r_row->image,
							'days' => $r_row->days,
							'price' => $r_row->price,
							'version_price' => $r_row->version_price,
							'_required' => $r_row->_required,
							'_related' => $r_row->_related,
							'loc_version' => '',
							'rem_version' => $r_row->_version,
							'_comp_version' => empty($r_row->_comp_version)?'':$r_row->_comp_version,
							'installable' => !empty($r_row->_installable),
							'link' => $r_row->link
						);
					}
				}
	            $data = JArrayHelper::sortObjects($data, 'name');
			}
		}
		return $data;
	}
	function getDeals() {
		$params = JComponentHelper::getParams('com_fwgallery');
		return @json_decode($params->get('deals_data'));
	}
	function getInstalledExtensionPath($row) {
		$path = JPATH_SITE.'/';
		switch (empty($row->type)?$row->subtype:$row->type) {
			case 'plugin' :
			$path .= 'plugins/fwgallery/';
			break;
			case 'module' :
			$path .= 'modules/';
			break;
			case 'design' :
			$path .= 'plugins/fwgallerytmpl/';
			break;
		}
		$path .= $row->update_name.'/'.$row->update_name.'.xml';
		return $path;
	}
	function getInstalledExtensionId($row) {
		$path = $this->getInstalledExtensionPath($row);
		if (file_exists($path)) {
			$db = JFactory::getDBO();
			$query = '';
			switch ($row->type) {
				case 'plugin' :
				$query = '`type`=\'plugin\' AND `folder` = \'fwgallery\' AND `element`='.$db->quote($row->update_name);
				break;
				case 'module' :
				$query = '`type`=\'module\' AND `element`='.$db->quote($row->update_name);
				break;
				case 'design' :
				$query = '`type`=\'plugin\' AND `folder` = \'fwgallerytmpl\' AND `element`='.$db->quote($row->update_name);
				break;
			}
			if ($query) {
				$query = 'SELECT extension_id FROM `#__extensions` WHERE '.$query;
				$db->setQuery($query);
				return $db->loadResult();
			}
		}
	}
	function uninstall() {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_fwgallery');
		$code = $params->get('update_code');
		$cid = $app->input->getVar('cid');
		$list = $this->getPluginsData();
		foreach ($list as $row) {
			if (in_array($row->id, $cid)) {
				$type = '';
				switch ($row->type) {
					case 'plugin' :
					case 'module' :
					$type = $row->type;
					break;
					case 'design' :
					$type = 'plugin';
					break;
				}
				if ($type) {
					$ext_id = $this->getInstalledExtensionId($row);
					$installer = JInstaller::getInstance();
					$installer->uninstall($type, $ext_id);
				}
			}
		}
	}
	function installAll($is_update=false) {
		$app = JFactory::getApplication();
		$addons = $this->getPluginsData();
		$qty = 0;
		if ($addons) {
			foreach ($addons as $row) {
				if ($is_update) {
					if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
						$app->input->set('ext', array($row->update_name));
						if ($this->install(true)) {
							$qty++;
						}
					}
				} else {
					if ($row->installable) {
						$app->input->set('ext', array($row->update_name));
						if ($this->install()) {
							$qty++;
						}
					}
				}
			}
		}
		$this->setError(JText::sprintf($is_update?'FWMG_ADDONS_UPDATED_QTY':'FWMG_ADDONS_INSTALLED_QTY', $qty));
	}
	function install($is_update=false) {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_fwgallery');
		$code = $params->get('update_code');
		if (!$code) {
			$this->setError(JText::_('FWMG_NO_UPDATE_CODE_NO_UPDATES'));
			return;
		}
		$ext = $app->input->getVar('ext');
		$list = $this->getPluginsData();
		$result = array();
		foreach ($list as $row) {
			if (in_array($row->update_name, $ext)) {
				if ($row->update_name == 'mod_fwg_map') {
					$row->update_name = 'pkg_fwgallery_map';
				}
				$buff = fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=updates&layout=package&format=raw&package='.urlencode($row->update_name).'&access_code='.urlencode($params->get('update_code')).'&dummy=extension.xml');
				if (preg_match('#detailsurl="([^"]*)"#', $buff, $match)) {
					$url = str_replace('&amp;', '&', $match[1]);
					if ($buff = fwgHelper::request($url)) {
						if (preg_match('#<downloadurl[^>]*>([^<]+)</downloadurl>#', $buff, $match)) {
							$url = str_replace('&amp;', '&', $match[1]).'&code='.urlencode($code);
							if ($buff = fwgHelper::request($url)) {
								jimport('joomla.filesystem.file');
								jimport('joomla.filesystem.folder');
								$path = JPATH_SITE.'/tmp/';

								$filename = '';
								do {
									$filename = 'inst'.rand().'.zip';
								} while (file_exists($path.$filename));

								if (JFile::write($path.$filename, $buff)) {
									$package = JInstallerHelper::unpack($path.$filename, true);
									if (!empty($package['dir'])) {
										$installer = JInstaller::getInstance();
										if ($installer->install($package['dir'])) {
											/* enable addon after installing/updating */
											$db = JFactory::getDBO();
											$folder = '';
											$element = $row->update_name;
											if (strpos($element, 'plg_') === 0) {
												$buff = explode('_', $row->update_name, 3);
												if (count($buff) == 3) {
													$folder = $buff[1];
													$element = $buff[2];
												}
											}
											$db->setQuery('UPDATE `#__extensions` SET enabled = 1  WHERE `element` = '.$db->quote($element).($folder?(' AND `folder` = '.$db->quote($folder)):''));
											$db->execute();

											if ($row->update_name == urldecode(FWMG_UPDATE_NAME)) {
												$this->setError(JText::sprintf('FWMG_MAIN_COMPONENT_UPDATED_SUCCESFULLY', $row->name));
											} else {
												$this->setError(JText::sprintf($is_update?'FWMG_UPDATED_SUCCESFULLY':'FWMG_INSTALLED_SUCCESFULLY', ucfirst(JText::_('FWMG_'.str_replace(' ', '', $row->subtype))), $row->name, ucfirst(JText::_('FWMG_'.$row->type))));
											}
											if (file_exists($path.$filename)) JFile::delete($path.$filename);
											if (file_exists($package['dir'])) JFolder::delete($package['dir']);
											$result[] = array(
												'update_name'=>$row->update_name,
												'rem_version' => $row->rem_version,
												'icon' => 'trash-alt',
												'action' => 'disable',
												'add_class' => 'enabled',
												'remove_class' => ($is_update?'update':'available'),
												'button' => JText::_('FWMG_ADDONS_STATUS_DISABLE'),
												'title' => JText::_('FWMG_DISABLE')
											);
										} else {
											$this->setError(JText::sprintf($is_update?'FWMG_NOT_UPDATED':'FWMG_NOT_INSTALLED', ucfirst(JText::_('FWMG_'.str_replace(' ', '', $row->subtype))), $row->name));
										}
										if (file_exists($package['dir'])) JFolder::delete($package['dir']);
									} elseif (preg_match('#<title>([^<]+)<#msi', $buff, $match)) {
										$this->setError($match[1]);
									}
									if (file_exists($path.$filename)) JFile::delete($path.$filename);
								} else $this->setError(JText::_('FWMG_CANT_WRITE_FILE'));
							} else $this->setError(JText::_('FWMG_SERVER_REFUSES_DOWNLOAD'));
						} else $this->setError(JText::_('FWMG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
					} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
				} else $this->setError(JText::_('FWMG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
			}
		}
		return $result;
	}
	function enable() {
		$input = JFactory::getApplication()->input;
		$exts = $input->getVar('ext');
		if (!empty($exts[0])) {
			$ext = $exts[0];
			$lang = JFactory::getLanguage();
			$db = JFactory::getDBO();
			if (strpos($ext, 'plg_') === 0) {
				$buff = explode('_', $ext);
				if (count($buff) == 3) {
					$db->setQuery('SELECT extension_id, name FROM `#__extensions` WHERE `folder` = '.$db->quote($buff[1]).' AND `element` = '.$db->quote($buff[2]));
					$lang->load($ext, JPATH_ADMINISTRATOR);
				} else $this->setError(JText::_('FWMG_INCORRECT_ADDON_NAME'));
			} else {
				$db->setQuery('SELECT extension_id, name FROM `#__extensions` WHERE `element` = '.$db->quote($ext));
				$lang->load($ext, JPATH_SITE);
			}
			if ($extension = $db->loadObject()) {
				$db->setQuery('UPDATE `#__extensions` SET `enabled` = 1 WHERE extension_id = '.(int)$extension->extension_id);
				if ($db->execute()) {
					if ($ext == 'mod_fwg_map') {
						$db->setQuery('UPDATE `#__extensions` SET `enabled` = 1 WHERE `folder` = \'fwgallery\' AND `element` = \'map\'');
						$db->execute();
					}
					$this->setError(JText::sprintf('FWMG_ADDON_NAME_SUCCESSFULLY_ENABLED', JText::_($extension->name)));
					return array(array(
						'icon' => 'trash-alt',
						'action' => 'disable',
						'button' => JText::_('FWMG_ADDONS_STATUS_DISABLE'),
						'title' => JText::_('FWMG_DISABLE')
					));
				} else $this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
			} else $this->setError(JText::_('FWMG_ADDON_NOT_FOUND'));
		} else $this->setError(JText::_('FWMG_NO_ADDON_NAME_TO_ENABLE'));
	}
	function disable() {
		$input = JFactory::getApplication()->input;
		$exts = $input->getVar('ext');
		if (!empty($exts[0])) {
			$ext = $exts[0];
			$lang = JFactory::getLanguage();
			$db = JFactory::getDBO();
			if (strpos($ext, 'plg_') === 0) {
				$buff = explode('_', $ext);
				if (count($buff) == 3) {
					$db->setQuery('SELECT extension_id, name FROM `#__extensions` WHERE `folder` = '.$db->quote($buff[1]).' AND `element` = '.$db->quote($buff[2]));
					$lang->load($ext, JPATH_ADMINISTRATOR);
				} else $this->setError(JText::_('FWMG_INCORRECT_ADDON_NAME'));
			} else {
				$db->setQuery('SELECT extension_id, name FROM `#__extensions` WHERE `element` = '.$db->quote($ext));
				$lang->load($ext, JPATH_SITE);
			}
			if ($extension = $db->loadObject()) {
				$db->setQuery('UPDATE `#__extensions` SET `enabled` = 0 WHERE extension_id = '.(int)$extension->extension_id);
				if ($db->execute()) {
					if ($ext == 'mod_fwg_map') {
						$db->setQuery('UPDATE `#__extensions` SET `enabled` = 0 WHERE `folder` = \'fwgallery\' AND `element` = \'map\'');
						$db->execute();
					}
					$this->setError(JText::sprintf('FWMG_ADDON_NAME_SUCCESSFULLY_DISABLED', JText::_($extension->name)));
					return array(array(
						'icon' => 'download',
						'action' => 'enable',
						'button' => JText::_('FWMG_ADDONS_STATUS_ENABLE'),
						'title' => JText::_('FWMG_ENABLE')
					));
				} else $this->setError(JText::_('FWMG_DB_ERROR').' '.$db->getError());
			} else $this->setError(JText::_('FWMG_ADDON_NOT_FOUND'));
		} else $this->setError(JText::_('FWMG_NO_ADDON_NAME_TO_DISABLE'));
	}
	function checkNotInstalled($list) {
		$qty = 0;
		if ($list) {
			foreach ($list as $row) {
				if (empty($row->installed) and !empty($row->installable)) {
					$qty++;
				}
			}
		}
		return $qty;
	}
	function checkNotUpdated($list) {
		$qty = 0;
		if ($list) {
			foreach ($list as $row) {
				if ($row->loc_version and $row->rem_version and $row->loc_version != 'x.x.x' and $row->loc_version != $row->rem_version) {
					$qty++;
				}
			}
		}
		return $qty;
	}
	function loadCart() {
		$params = JComponentHelper::getParams('com_fwgallery');
		if ($code = $params->get('update_code')) {
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=get_cart&format=json&access_code='.urlencode($code)));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->result)) {
					return $buff->result;
				}
			}
		}
	}
	function addCart() {
		$params = JComponentHelper::getParams('com_fwgallery');
		if ($code = $params->get('update_code')) {
			$input = JFactory::getApplication()->input;
			$exts = $input->getVar('ext');
			if (!empty($exts[0])) {
				$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=add_cart&format=json&access_code='.urlencode($code).'&is_version='.(int)$input->getInt('is_version').'&ext='.urlencode($exts[0])));
				if ($buff) {
					if (!empty($buff->msg)) {
						$this->setError($buff->msg);
					}
					if (!empty($buff->result)) {
						return $buff->result;
					}
				} else $this->setError(JText::_('FWMG_NO_PRODUCT_ID'));
			} else $this->setError(JText::_('FWMG_NO_PRODUCT_ID'));
		} else $this->setError(JText::_('FWMG_NO_UPDATE_CODE'));
	}
	function clearCart() {
		$params = JComponentHelper::getParams('com_fwgallery');
		if ($code = $params->get('update_code')) {
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=clear_cart&format=json&access_code='.urlencode($code)));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->result)) {
					return $buff->result;
				}
			}
		} else $this->setError(JText::_('FWMG_NO_UPDATE_CODE'));
	}
	function login() {
		$input = JFactory::getApplication()->input;
		if ($email = $input->getString('email')) {
			if ($passwd = $input->getRaw('passwd')) {
				$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=login&format=json&email='.urlencode($email).'&passwd='.urlencode($passwd)));
				if ($buff) {
					if (!empty($buff->msg)) {
						$this->setError($buff->msg);
					}
					if (!empty($buff->result)) {
						$input->set('code', $buff->result->access_code);
						$model = JModelLegacy::getInstance('fwgallery', 'fwGalleryModel');
						$model->verifyCode();
						return $buff->result;
					}
				} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
			} else $this->setError(JText::_('FWMG_NO_LOGIN_PASSWORD'));
		} else $this->setError(JText::_('FWMG_NO_LOGIN_EMAIL'));
	}
	function register() {
		$input = JFactory::getApplication()->input;
		if ($email = $input->getString('email')) {
			if ($name = $input->getString('name')) {
				$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=register&format=json&email='.urlencode($email).'&name='.urlencode($name)));
				if ($buff) {
					if (!empty($buff->msg)) {
						$this->setError($buff->msg);
					}
					if (!empty($buff->result)) {
						if (!empty($buff->result->access_code)) {
							$input->set('code', $buff->result->access_code);
							$model = JModelLegacy::getInstance('fwgallery', 'fwGalleryModel');
							$model->verifyCode();
						}
						return $buff->result;
					}
				} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
			} else $this->setError(JText::_('FWMG_NO_FULLNAME'));
		} else $this->setError(JText::_('FWMG_NO_REGISTER_EMAIL'));
	}
	function confirm() {
		$input = JFactory::getApplication()->input;
		if ($code = $input->getString('code')) {
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=fwsales&layout=confirm&format=json&code='.urlencode($code)));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->result)) {
					$input->set('code', $buff->result->access_code);
					$model = JModelLegacy::getInstance('fwgallery', 'fwGalleryModel');
					$model->verifyCode();
					return $buff->result;
				}
			} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		} else $this->setError(JText::_('FWMG_NO_CONFIRMATION_CODE'));
	}
	function resetPassword() {
		$input = JFactory::getApplication()->input;
		if ($email = $input->getString('email')) {
			$buff = json_decode(fwgHelper::request(FWMG_UPDATE_SERVER.'/index.php?option=com_fwsales&view=support&layout=reset_password&format=json&email='.urlencode($email)));
			if ($buff) {
				if (!empty($buff->msg)) {
					$this->setError($buff->msg);
				}
				if (!empty($buff->result)) {
					return $buff->result;
				}
			} else $this->setError(JText::_('FWMG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		} else $this->setError(JText::_('FWMG_NO_LOGIN_EMAIL'));
	}
}
