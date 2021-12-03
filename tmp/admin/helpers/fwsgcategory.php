<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class JHTMLfwsgCategory {
    static function parent($row = null, $field_name = 'parent', $attr='', $top='FWMG_TOP') {
		if (is_null($row)) {
			$row = new stdclass;
		}
        if (!isset ($row->$field_name)) {
            $row->$field_name = 0;
        }
        $children = array ();
        if ($citems = fwgHelper::loadCategories()) {
            if ($row and !empty($row->id)) {
                foreach ($citems as $i=>$c) {
                    if ($c->id == $row->id) {
                        unset($citems[$i]);
                        break;
                    }
                }
            }
            foreach ($citems as $c) {
                $pt = $c->parent;
                $list = @ $children[$pt] ? $children[$pt] : array ();
                array_push($list, $c);
                $children[$pt] = $list;
            }
        }
        $list = JHTML::_('fwsgCategory.treerecurse', 0, '', array (), $children, 9999, 0, 0);
		$citems = array ();
		if ($top) {
			$citems[] = JHTML::_('select.option', '0', JText::_($top));
		}
        foreach ($list as $item) {
            $citems[] = JHTML::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;' . $item->treename);
        }

        return JHTML::_('fwsgCategory.genericlist', $citems, $field_name, $attr, 'value', 'text', $row->$field_name);
    }

	static function options($arr, $key = 'value', $text = 'text', $selected = null, $translate = false) {
		$html = '';

		foreach ($arr as $i => $option) {
			$element =& $arr[$i]; // since current doesn't return a reference, need to do this

			$isArray = is_array($element);
			$extra	 = '';
			if ($isArray) {
				$k 		= $element[$key];
				$t	 	= $element[$text];
				$id 	= (isset($element['id']) ? $element['id'] : null);
				if (isset($element['disable']) && $element['disable']) {
					$extra .= ' disabled="disabled"';
				}
			} else {
				$k 		= $element->$key;
				$t	 	= $element->$text;
				$id 	= (isset($element->id) ? $element->id : null);
				if (isset($element->disable) && $element->disable) {
					$extra .= ' disabled="disabled"';
				}
			}

			// This is real dirty, open to suggestions,
			// barring doing a propper object to handle it
			if ($k === '<OPTGROUP>') {
				$html .= '<optgroup label="' . $t . '">';
			} else if ($k === '</OPTGROUP>') {
				$html .= '</optgroup>';
			}
			else
			{
				//if no string after hypen - take hypen out
				$splitText = explode(' - ', $t, 2);
				$t = $splitText[0];
				if (isset($splitText[1])) { $t .= ' - '. $splitText[1]; }

				//$extra = '';
				//$extra .= $id ? ' id="' . $arr[$i]->id . '"' : '';
				if (is_array($selected)) {
					foreach ($selected as $val) {
						$k2 = is_object($val) ? $val->$key : $val;
						if ($k == $k2) {
							$extra .= ' selected="selected"';
							break;
						}
					}
				} else {
					$extra .= ((string)$k == (string)$selected  ? ' selected="selected"' : '');
				}

				//if flag translate text
				if ($translate) {
					$t = JText::_($t);
				}

				// ensure ampersands are encoded
				$k = JFilterOutput::ampReplace($k);
				$t = JFilterOutput::ampReplace($t);

				$html .= '<option value="'. $k .'" '. $extra .'>' . $t . '</option>';
			}
		}

		return $html;
	}

	static function genericlist($arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false) {
		if (is_array($arr)) {
			reset($arr);
		}

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		}

		$id = $name;

		if ($idtag) {
			$id = $idtag;
		}

		$id	= str_replace(array(']','['),'',$id);

		$html = '<select name="'. $name .'" id="'. $id .'" '. $attribs .'>';
		$html .= JHTMLfwsgCategory::Options($arr, $key, $text, $selected, $translate);
		$html .= '</select>';

		return $html;
	}

    static function getCategories($field_name, $product_categories = array(), $attr='SIZE="10"', $multiple=true, $first_option_name = '', $select_first=false, $add_id = false) {
        $children = array ();
        if ($citems = fwgHelper::loadCategories()) {
			if ($add_id) {
				foreach ($citems as $i=>$row) {
					$citems[$i]->name .= ' ['.$row->id.']';
				}
			}
            // first pass - collect children
            foreach ($citems as $c) {
                $pt = $c->parent;
                $list = @ $children[$pt] ? $children[$pt] : array ();
                array_push($list, $c);
                $children[$pt] = $list;
            }
        }
        // second pass - get an indent list of the items
        $list = JHTML::_('fwsgCategory.treerecurse', 0, '', array(), $children, 9999, 0, 0);
        // assemble menu items to the array
        $text = '<select name="'.$field_name.'" '.($multiple?'multiple ':'').$attr.'>';
        if ($first_option_name) $text .= '<option value="">'.JText::_($first_option_name).'</option>';

		$selected = !$select_first;
		if (!$selected and $product_categories) {
	        foreach ($list as $item) {
		        if (is_array($product_categories)) {
					foreach ($product_categories as $pc) {
						if ((is_numeric($pc) and $pc == $item->id) or (is_object($pc) and $pc->id == $item->id)) {
							$selected = true;
							break 2;
						}
					}
		        } elseif ($product_categories == $item->id) {
					$selected = true;
					break;
		        }
	        }
		}

        foreach ($list as $item) {
	        $text .= '<option value="'.$item->id.'"';
	        if (!$selected) {
	        	$text .= ' selected="selected"';
	        	$selected = true;
	        }
	        if (is_array($product_categories)) {
				foreach ($product_categories as $pc) {
					if ((is_numeric($pc) and $pc == $item->id) or (is_object($pc) and $pc->id == $item->id)) {
						$text .= ' selected="selected"';
						break;
					}
				}
	        } elseif ($product_categories == $item->id) {
				$text .= ' selected="selected"';
	        }
			$text .= '>&nbsp;&nbsp;&nbsp;'.$item->treename.'</option>';
        }
        $text .= '</select>';

        return $text;
    }
	static function treerecurse($id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1) {
		if (@$children[$id] && $level <= $maxlevel) {
			foreach ($children[$id] as $c) {
				$id = $c->id;

				if ($type) {
					$pre 	= '&#9492;&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else {
					$pre 	= '- ';
					$spacer = '&nbsp;&nbsp;';
				}

				if ($c->parent == 0) {
					$txt 	= $c->name;
				} else {
					$txt 	= $pre . $c->name;
				}
				$pt = $c->parent;
				$list[$id] = $c;
				$list[$id]->treename = "$indent$txt";
				$list[$id]->children = empty($children[$id])?0:count($children[$id]);
				$list = JHTMLfwsgCategory::treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type);
			}
		}
		return $list;
	}
}
