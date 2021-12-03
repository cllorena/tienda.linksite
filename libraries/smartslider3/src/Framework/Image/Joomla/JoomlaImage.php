<?php

namespace Nextend\Framework\Image\Joomla;

use JHtml;
use Nextend\Framework\Image\AbstractPlatformImage;

class JoomlaImage extends AbstractPlatformImage {

    public function initLightbox() {
        if (version_compare(JVERSION, '4', '<')) {
            JHtml::_('behavior.modal');
        }
    }
}