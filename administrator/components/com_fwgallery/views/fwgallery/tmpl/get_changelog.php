<?php
/**
 * FW Real Estate 6.7.2 - Joomla! Property Manager
 * @copyright C 2019 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

if ($this->list) {
	$latest_version = '';
	$latest_type = '';
	foreach ($this->list as $row) {
		if ($latest_version != $row->version) {
			$latest_type = '';

			$buff = explode('.', $row->version);
			$type = JText::_('FWMG_MAINTENANCE');
			if (count($buff) == 3) {
				if ($buff[1] == 0 and $buff[0] == 0) {
					$type = JText::_('FWMG_MAJOR');
				} elseif ($buff[0] == 0) {
					$type = JText::_('FWMG_MINOR');
				} 
			}
			$qty = 0;
			foreach ($this->list as $subrow) {
				if ($subrow->version == $row->version) {
					$qty++;
				}
			}
			if ($latest_version) {
?>
</ul>
<?php
			}
?>
<div class="fws-changelog-version-header">
	<span class="fws-changelog-version-header-info">
		<?php echo JText::_('FWMG_VERSION'); ?> <?php echo esc_html($row->version); ?> (<span class="text-lowercase"><?php echo esc_html($type); ?></span>)
	</span>
	<?php echo JText::_('FWMG_RELEASED_ON'); ?> <?php echo JHTML::date($row->created, 'd F Y'); ?>, <?php echo (int)$qty; ?> <?php echo JText::_('FWMG_MODIFICATIONS'); ?>
</div>
<?php
			$latest_version = $row->version;
		}
		if ($latest_type != $row->type) {
			if ($latest_type) {
?>
</ul>
<?php
			}
			$icon = '';
			switch ($row->type) {
				case 'bugfixes' :
				$icon = 'bug';
				break;
				case 'updates' :
				$icon = 'sync';
				break;
				case 'new_features' :
				$icon = 'star';
				break;
			}
?>
<div class="fws-changelog-version-section fws-changelog-section-<?php echo esc_attr($row->type); ?>">
	<i class="fas fa-<?php echo esc_attr($icon); ?>"></i>
	<?php echo JText::_('FWMG_'.$row->type); ?>
</div>
<ul class="fa-ul fws-changelog-version-list">
<?php
			$latest_type = $row->type;
		}
?>
					<li><span class="fa-li"><i class="fal fa-check-square"></i></span><?php echo fwgHelper::escPluginsOutput($row->descr); ?></li>
<?php
		
	}
}
