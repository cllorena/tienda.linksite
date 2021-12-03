<?php

/* ------------------------------------------------------------------------
  # mod_bgmax - Custom parameter Joomla! 1.6 for bgMax
  # Displays a title on the total width of the column parameter
  # type=title
  # label="text to display" (translatable)
  # style="color=red" (translatable)(optional)
  # 20-10-17: ajout class et chargement feuille de style custom.css
  # ------------------------------------------------------------------------
  # author    lomart
  # copyright : Copyright (C) 2011 lomart.fr All Rights Reserved.
  # @license  : http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Website   : http://lomart.fr
  # Technical Support:  Forum - http://forum.joomla.fr
  ------------------------------------------------------------------------- */
// no direct access
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldxTitle extends JFormField {

	protected $type = 'xTitle';

	protected function getInput() {
		return '';
	}

	protected function getLabel() {

		// AJOUT FEUILLE CSS
		$cssPath = substr(__dir__, strlen(JPATH_ROOT) + 1) . '/custom.css';
		JHtml::stylesheet($cssPath);

		$class = $this->element['class'] ? (string) $this->element['class'] : "param-title-default";

		$style = $this->element['style'] ? (string) $this->element['style'] : "";
		$style = $this->translateLabel ? JText::_($style) : $style;

		$label = $this->element['title'] ? (string) $this->element['title'] : (string) $this->element['name'];
		$label = $this->translateLabel ? JText::_($label) : $label;

		$desc = $this->element['subtitle'];
		$desc = $this->translateLabel ? JText::_($desc) : $desc;

		$html = array();
		$html[] = '</div>';  // on ferme le control-label pour creer un bloc sur la largeur totale
		if ($this->element['label'])
			$html[] = '<span style="color:red">Dont use LABEL, but TITLE and SUBTITLE';
		$out = '<div id="' . $this->id . '-lbl" class="' . $class . '"';
		if ($style)
			$out .= ' style="' . $style . '"';
		$html[] = $out . '>';
		$html[] = ($label) ? ' <h3>' . $label . '</h3>' : '';
		$html[] = ($desc) ? '<small>' . $desc . '</small>' : '';
//		$html[] = '</div>';  // inutile, on récupère celui fermé au début

		return implode('', $html);
	}

	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 *
	 * @since   11.1
	 */
	protected function getTitle() {
		return $this->getLabel();
	}

}
