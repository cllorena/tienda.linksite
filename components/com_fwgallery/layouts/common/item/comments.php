<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$view = $displayData['view'];
$params = JComponentHelper::getParams('com_fwgallery');
$comments_type = $params->get('comments_type');

if ($comments_type == 'disqus') {
	$item_uri = JURI::getInstance()->toString();
	$disqus_domain =  str_replace(array('http://','https://','.disqus.com/','.disqus.com'), '', $params->get('disqus_domain'));
	if ($disqus_domain) {
?>
<script>
(function() {
	var d = document, s = d.createElement('script');
	s.src = 'https://<?php echo esc_js($disqus_domain); ?>.disqus.com/embed.js';
	s.setAttribute('data-timestamp', +new Date());
	(d.head || d.body).appendChild(s);
})();
</script>
<div id="disqus_thread"></div>
<?php
		if ($view->app->input->getCmd('format') == 'raw') {
?>
<script>
if (window.DISQUS) {
	DISQUS.reset({
	  reload: true,
	  config: function () {
		this.page.identifier = "<?php echo substr(md5($disqus_domain),0,10); ?>_id<?php echo (int)$view->item->id; ?>";
		this.page.url = "<?php echo esc_attr($item_uri); ?>";
	  }
	});
} else {
	var disqus_url= "<?php echo esc_attr($item_uri); ?>";
	var disqus_identifier = "<?php echo substr(md5($disqus_domain),0,10); ?>_id<?php echo (int)$view->item->id; ?>";
<?php
			if (strpos($item_uri, 'http://localhost/') === 0) {
?>
	var disqus_developer = true;
<?php
			}
?>
}
</script>
<noscript>
	<a href="https://<?php echo esc_attr($disqus_domain); ?>.disqus.com/?url=ref"><?php echo JText::_("FWMG_VIEW_THE_DISCUSSION_THREAD"); ?></a>
</noscript>
<?php
		} else {
?>
<script>
//<![CDATA[
var disqus_url= '<?php echo esc_js($item_uri); ?>';
var disqus_identifier = '<?php echo esc_js(substr(md5($disqus_domain),0,10)); ?>_id<?php echo (int)$view->item->id; ?>';
<?php
			if (strpos($item_uri, 'http://localhost/') === 0) {
?>
var disqus_developer = true;
<?php
			}
?>
//]]>
</script>
<noscript>
	<a href="https://<?php echo esc_attr($disqus_domain); ?>.disqus.com/?url=ref"><?php echo JText::_("FWMG_VIEW_THE_DISCUSSION_THREAD"); ?></a>
</noscript>
<?php
		}
	} else {
?>
Please enter your Disqus subdomain in order to use the Disqus Comment System!
If you don't have a Disqus.com account <a target="_blank" href="http://disqus.com/comments/register/">register for one here</a>
<?php
	}
} elseif ($comments_type == 'komento') {
	if (!file_exists(JPATH_ROOT.'/components/com_komento/bootstrap.php')) {
?>
<div class="alert alert-error"><?php echo JText::_('FWMG_KOMENTO_NOT_INSTALLED'); ?></div>
<?php
	} else {
		if (!file_exists(JPATH_ROOT.'/components/com_komento/komento_plugins/com_fwgallery.php')) {
			JFile::copy(JPATH_ROOT.'/components/com_fwgallery/helpers/komento/com_fwgallery.php', JPATH_ROOT.'/components/com_komento/komento_plugins/com_fwgallery.php');
		}
		
		if (!file_exists(JPATH_ROOT.'/components/com_komento/komento_plugins/com_fwgallery.php')) {
?>
<div class="alert alert-error"><?php echo JText::_('FWMG_FWGALLERY_KOMENTO_PLUGIN_NOT_COPIED'); ?></div>
<?php
		} else {
			require_once(JPATH_ROOT.'/components/com_komento/bootstrap.php');
			echo KT::commentify('com_fwgallery', $view->item, $options=array());
		}
	}
}
