<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];

$grid = $view->params->get('files_grid', 'standard');
$cols = (int)$view->params->get('files_columns', 4);
$rows = $view->params->get('files_rows', 4);

$actual_rows = ceil(count($view->files)/$cols);
if ($actual_rows < $rows) $rows = $actual_rows;

$document = JFactory::getDocument();
$style = '#fwgallery .fwmg-files #fwmg-grid.fwmg-grid-standard .fwmg-grid-container {
    grid-template-columns: repeat('.$cols.', calc((100% - '.($cols - 1).'rem)/'.$cols.'));
    grid-template-rows: repeat('.$rows.', auto);
}';
$document->addStyleDeclaration( $style );

echo fwgHelper::loadTemplate('grids.'.$grid, array_merge($displayData, array(
	'return' => 'file',
	'list' => $view->files,
	'cols' => $cols
)));
