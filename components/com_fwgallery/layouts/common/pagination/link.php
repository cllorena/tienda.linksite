<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/** @var JPaginationObject $item */
$item = $displayData['data'];
?>
	<a href="<?php if ($item->link) { echo esc_attr($item->link); } else { ?>javascript:<?php } ?>" class="btn btn-secondary<?php if ($item->active) { ?> active<?php } ?>"><?php echo esc_html($item->text); ?></a>
