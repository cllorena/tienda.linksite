<?php
/**
 * FW Gallery 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$extra_link = empty($displayData['extra_link'])?'':$displayData['extra_link'];
?>
<div id="fwmg-quick-categories" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK'); ?>
                    <div class="modal-subtitle">
                        <?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_HINT'); ?></div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal"
                    aria-label="<?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_BTN_CLOSE'); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_TEXT'); ?></div>
                <textarea name="quick_categories" rows="10" class="form-control"
                    placeholder="<?php echo esc_attr(JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_TEXT_HINT')); ?>"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn mr-2" data-dismiss="modal"
                    data-bs-dismiss="modal"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_BTN_CLOSE'); ?></button>
                <button type="button"
                    class="btn btn-success"><?php echo JText::_('FWMG_DOC_ADMIN_CATEGORIES_MODAL_QUICK_BTN_PROCESS'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    (function ($) {
        $('#fwmg-quick-categories').on('show', function () {
            $(this).addClass('show');
            $(this).find('textarea').val('');
        }).on('hide', function () {
            $(this).removeClass('show');
        });
        $('#fwmg-quick-categories button.btn-success').click(function () {
            var $btn = $(this).attr('disabled', true);
            var $popup = $('#fwmg-quick-categories');
            $.ajax({
                dataType: 'json',
                data: {
                    format: 'json',
                    view: 'category',
                    task: '',
                    layout: 'quick_categories',
                    text: $popup.find('textarea').val()
                }
            }).done(function (data) {
                $btn.attr('disabled', false);
                if (data.msg) {
                    fwmg_alert(data.msg);
                }
                if (data.result) {
                    location = 'index.php?option=com_fwgallery&view=category' +
                        '<?php echo str_replace('&amp;', '&', $extra_link); ?>';
                }
            });
        });
    })(jQuery);
});
</script>
