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

class JFormFieldxNote extends JFormField {

	protected $type = 'xNote';

	protected function getInput() {
		return '';
	}

	protected function getLabel() {

		// AJOUT FEUILLE CSS
		$cssPath = substr(__dir__, strlen(JPATH_ROOT) + 1) . '/custom.css';
		JHtml::stylesheet($cssPath);

		$class = $this->element['class'] ? (string) $this->element['class'] : "param-note-default";

		$style = $this->element['style'] ? (string) $this->element['style'] : "";
		$style = $this->translateLabel ? JText::_($style) : $style;
		
		$hx = $this->element['heading'] ? (string) $this->element['heading'] : "p";

		$note = $this->element['note'] ? (string) $this->element['note'] : (string) $this->element['name'];
		$note = $this->translateLabel ? JText::_($note) : $note;

		$html = array();
		$html[] = '</div>';  // on ferme le control-label pour creer un bloc sur la largeur totale
		if ($this->element['label'])
			$html[] = '<span style="color:red">Dont use LABEL, but TITLE and SUBTITLE';
		$out = '<div id="' . $this->id . '-lbl" class="' . $class . '"';
		if ($style)
			$out .= ' style="' . $style . '"';
		$html[] = $out . '>';
		$html[] = ($note) ? ' <'.$hx.'>' . $note . '</'.$hx.'>' : '';
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
