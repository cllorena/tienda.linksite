<?php
/**
 * ------------------------------------------------------------------------
 * MOD_LGX_FULL_SCREEN_BACKGROUND_IMAGE.php Full Screen  Background Image By LogicHunt.com
 * ------------------------------------------------------------------------
 * Copyright (C) 2014-2021 LogicHunt, All Rights Reserved.
 * license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: LogicHunt
 * Websites: http://logichunt.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die;


// Include the syndicate functions only once
require_once __DIR__ . '/helper/helper.php';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));


// Path assignments
$jebase = JURI::base();


// Basic Settings

$mediaId_img    = $params->get('mediaId_img');

$mediaId = $jebase.'/'.$mediaId_img;


$mediaOverlay                = intval($params->get('mediaOverlay', 1));
$mediaOverlayCss             = $params->get('mediaOverlayCss', 'transparent');
$mediaOverlay_Color          = $params->get('mediaOverlayColor', '#000000');
$mediaOverlayColor_opacity   = floatval($params->get('mediaOverlayColor_opacity', 0.4));
$mediaOverlayColor           = LgxFullScreenBackgroundImageHelper::lgx_color_hex2rgba($mediaOverlay_Color, $mediaOverlayColor_opacity);
$custom_style                = trim($params->get('custom_style'));

// Background Settings
$mediaLink                    = trim($params->get('mediaLink'));
$mediaLinkTarget              = $params->get('mediaLinkTarget', '_blank');


//Page Settings
$pud                    = intval($params->get('pud', 1));
$pudElement             = $params->get('pudElement', '.container');
$pudDown                = intval($params->get('pudDown', 0));
$pudUp                  = intval($params->get('pudUp', 1));
$pudShow                = floatval($params->get('pudShow', 0));

$fio                    = intval($params->get('fio', 1));
$fioElement             = $params->get('fioElement', '#page');
$fioOpacity             = floatval($params->get('fioOpacity', 0.5));
$fioStart               = intval($params->get('fioStart', 0));
$fioEnd                 = intval($params->get('fioEnd', 1));

//Controller Settings
$displayControls         = intval($params->get('displayControls', 1));
$placeControls           = $params->get('placeControls', 'br');

$control_Color          = $params->get('controlColor', '#ffffff');
$controlColor_opacity   = floatval($params->get('controlColor_opacity', 0.8));
$controlColor           = LgxFullScreenBackgroundImageHelper::lgx_color_hex2rgba($control_Color, $controlColor_opacity);

$control_BgColor          = $params->get('controlBgColor', '#27add3');
$controlBgColor_opacity   = floatval($params->get('controlBgColor_opacity', 0.7));
$controlBgColor           = LgxFullScreenBackgroundImageHelper::lgx_color_hex2rgba($control_BgColor, $controlBgColor_opacity);

$enableScrollToTopButton  = intval($params->get('enableScrollToTopButton', 1));



// Include All Default Assets
require(dirname(__FILE__).'/assets/asset.php');


?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        "use strict";

        $('body').umbg({
            'mediaPlayerType': 'image',
            'mediaId': '<?php echo trim($mediaId);?>',
            'mediaLink': '<?php echo trim($mediaLink);?>',
            'mediaLinkTarget': '<?php echo trim($mediaLinkTarget);?>',
            'mediaPosterCss': 'umbg-mobile-poster',
            'mediaOverlay': <?php echo $mediaOverlay;?>,
            'mediaOverlayCss': 'umbg-overlay-<?php echo $mediaOverlayCss;?>',
            'mediaOverlayColor': '<?php echo $mediaOverlayColor;?>',
            'displayControls': <?php echo $displayControls;?>, 
            'placeControls': 'umbg-<?php echo $placeControls;?>', 
            'controlColor': '<?php echo $controlColor;?>',
            'controlBgColor': '<?php echo $controlBgColor;?>',
            'enableScrollToTopButton':<?php echo $enableScrollToTopButton;?>,
            'pud': <?php echo $pud;?>,
            'pudElement': '<?php echo $pudElement;?>',
            'pudDown': <?php echo $pudDown;?>,
            'pudUp': <?php echo $pudUp;?>,
            'pudShow': <?php echo $pudShow;?>,
            'fio': <?php echo $fio;?>,
            'fioElement': '<?php echo $fioElement;?>',
            'fioOpacity':<?php echo $fioOpacity;?>,
            'fioStart': <?php echo $fioStart;?>,
            'fioEnd': <?php echo $fioEnd;?>,
            
        });
        
    });
</script>