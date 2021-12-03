<?php
/**
 * FW Gallery 4.10.0
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(JText::_('FWMG_ADMIN_CSS_TOOLBAR_TITLE'), ' fal fa-file-code');
fwgButtonsHelper::apply('apply', 'FWMG_DOC_ADMIN_CSS_TOOLBAR_BTN_SAVE');

echo JLayoutHelper::render('common.menu_begin', array(
    'view' => $this,
    'title' => JText::_('FWMG_DOC_ADMIN_CSS'),
    'title_hint' => JText::_('FWMG_DOC_ADMIN_CSS_HINT')
), JPATH_COMPONENT);
?>
<form id="adminForm" name="adminForm" class="css-styles" method="post" action="index.php?option=com_fwgallery&view=css" enctype="multipart/form-data">
	<div class="fwa-filter-bar">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item"><a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-custom-css" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_CSS_TAB_CUSTOM') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-standard-css" role="tab"><?php echo JText::_('FWMG_DOC_ADMIN_CSS_TAB_FWG') ?></a></li>
<?php
if ($this->tmpls) {
    foreach ($this->tmpls as $tmpl) {
        if (empty($tmpl->css)) continue;
?>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#fwmg-<?php echo esc_attr($tmpl->element); ?>-css" role="tab"><?php echo esc_html($tmpl->name); ?></a></li>
<?php
    }
}
?>
		</ul>
	</div>
	<div class="tab-content">
		<div class="tab-pane active" id="fwmg-custom-css" role="tabpanel" aria-expanded="false">
			<div class="row fwa-mb-cardbox">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="card-subtitle"><?php echo JText::_('FWMG_DOC_ADMIN_CSS_TAB_CUSTOM_HINT'); ?></div>
						</div>
						<div class="card-body">
							<?php echo $this->editor->display('additional_css', $this->object->params->get('additional_css'), '100%', 400, 80, 7, false); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="tab-pane" id="fwmg-standard-css" role="tabpanel" aria-expanded="true">
			<div class="row fwa-mb-cardbox">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="card-subtitle pb-2"><?php echo JText::sprintf('FWMG_DOC_ADMIN_CSS_TAB_FWMG_HINT'); ?></div>
							<div class="card-subtitle"><?php echo str_replace('\\', '/', $this->path); ?></div>
						</div>
						<div class="card-body">
							<?php echo $this->editor->display('none', $this->content, '100%', 400, 80, 7, false); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
if ($this->tmpls) {
    foreach ($this->tmpls as $i=>$tmpl) {
        if (empty($tmpl->css)) continue;
?>
        <div class="tab-pane" id="fwmg-<?php echo esc_attr($tmpl->element); ?>-css" role="tabpanel" aria-expanded="true">
			<div class="row fwa-mb-cardbox">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="card-subtitle pb-2"><?php echo JText::sprintf('FWMG_FWGALLERY_TEMPLATE_CSS_HINT', $tmpl->name); ?></div>
							<div class="card-subtitle"><?php echo str_replace('\\', '/', $tmpl->path); ?></div>
						</div>
						<div class="card-body">
							<?php echo $this->editor->display('none'.$i, $tmpl->css, '100%', 400, 80, 7, false); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
    }
}
?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="1" />
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
	(function($) {
	$('a[data-toggle="tab"]').click(function() {
		$('.CodeMirror').each(function(i, el){
			setTimeout(function() {
				el.CodeMirror.refresh();
			}, 100);
		});
	});
	})(jQuery);
});
</script>
<?php
echo JLayoutHelper::render('utilites.batchupload', array(
	'allusers'=>$this->users,
	'current_user'=>$this->current_user,
	'category'=>'',
	'reload'=>false
), JPATH_COMPONENT);
echo JLayoutHelper::render('utilites.quickcategories', array(), JPATH_COMPONENT);
echo JLayoutHelper::render('common.menu_end', array(), JPATH_COMPONENT);
