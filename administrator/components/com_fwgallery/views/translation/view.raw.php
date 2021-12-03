<?php
/**
 * FW Gallery 6.7.2
 * @copyright C 2019 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGalleryViewTranslation extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
        switch ($this->getLayout()) {
            case 'download' :
            $filename = $model->getLanguageFilename();
            if ($filename and file_exists($filename)) {
                if (!headers_sent()) {
                    header('Content-Type: text/ini');
                    header('Content-Disposition: attachment; filename='.basename($filename));
                    header('Content-Length: '.filesize($filename));
                }
                die(file_get_contents($filename));
            } else $this->setError(JText::_('FWMG_LANGUAGE_FILE_NOT_FOUND').' - '.$filename);
            break;
        }
        parent::display($tmpl);
    }
}
