(function($) {
    "use strict";

    $(document).ready(function() {

        $('#jform_params_mediaPlayerType option:not(:selected)').attr('disabled', true);
        $('#jform_params_mediaOverlayCss option:not(:selected)').attr('disabled', true);
        $('#jform_params_mediaOverlayColor_opacity').attr('disabled', true);
        $('#jform_params_placeControls option:not(:selected)').attr('disabled', true);
        $('#jform_params_controlColor_opacity').attr('disabled', true);
        $('#jform_params_controlBgColor_opacity').attr('disabled', true);
        $('#jform_params_mediaLink').attr('disabled', true);
        $('#jform_params_mediaLinkTarget option:not(:selected)').attr('disabled', true);

    });//DOM READY

})(jQuery);



