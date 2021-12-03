<?php
/*------------------------------------------------------------------------
# mod_bgmax - bgMax
# ------------------------------------------------------------------------
# author    lomart
# copyright : Copyright (C) 2011 lomart.fr All Rights Reserved.
# @license  : http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website   : http://lomart.fr
# Technical Support:  Forum - http://forum.joomla.fr
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');

global $bgmaxDebug;

   /**
    *  retourne une couleur en notation hexa sur 6 caracteres sans #
    **/       
   function bg_hex2hex($color)
   {
      $color = trim($color,'#');
      if (strlen($color) == 6) {
        return $color;
      } elseif (strlen($color) == 3) {
        return $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
      } else {
        return "";
      }
   }
  
   /**
    *  retourne une couleur en notation hexa (#RGB ou #RRGGBB ou RGB) 
    *  sous la forme d'une chaine "r,v,b"
    **/       
   function bg_hex2rgb($color)
   {
      $color = trim($color,'#');
      if (strlen($color) == 6) {
        list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
      } elseif (strlen($color) == 3) {
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
      } else {
        return false;
      }
      return hexdec($r).','.hexdec($g).','.hexdec($b);
   }

   /**
    *  Retourne la couleur du pixel en bas a gauche de l'image
    **/         
   function colorImageBottom($imageName) {
   
      // recuperer le type et la taille de l'image
      // Pb sur certains JPG qui retourne ''
      list($imgW, $imgH, $imgTyp) = getimagesize($imageName);
      switch ( $imgTyp ) {
        case 1 : $im = imagecreatefromgif($imageName); break;
        case 2;
        case ' ' : $im = imagecreatefromjpeg($imageName); break;
        case 3 : $im = imagecreatefrompng($imageName); break;
        default: {
          $app = JFactory::getApplication();
          $app->enqueueMessage(JTEXT::_('IMGNAME_ERROR').'[name='.$imageName.'] [ type='.$imgTyp.'] [ format= '.$imgW.'x'.$imgH,'error');
          var_dump(gd_info() );
          return "";
          }
      }
      $rgb = imagecolorat($im, 2, ($imgH-2));
      $hex = sprintf("%06X", $rgb);
      return $hex;
   }

   /**
    * Retourne une date JJ-MM-AAAA au format AAAA-MM-JJ
	* 
	**/
   function bg_formatDate($date) {
		$tmp = explode('-',$date);
		$tmp = array_reverse($tmp);
		return implode('-',$tmp);
   }
   
   /**
    *  Retourne une image au hasard - adaptation de ja_purity_ii    	
    **/    
    function getRandomImage ($img_folder) {
     
    $imglist=array();

		if ($dh = @opendir($img_folder)) {
			while (($f = readdir($dh)) !== false) {
        $ext = substr(trim(strtolower($f)),-3);
				if (($ext == 'jpg') || ($ext == 'gif') || ($ext == 'png')) {
				  $imglist[] = $f;
				}
			}
			closedir($dh);
		} 

    if(!count($imglist)) return '';

		$random = rand(0, count($imglist)-1);
		$image = $imglist[$random];

		return $image;
	}

	/**
	 * Retourne vrai si execution sur mobile
	 **/
    function bg_isMobile() {
		return preg_match("/(avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

class modBgMaxHelper
{
  /**
   *  Retourne un tableau avec les arguments a ajouter dans la page
   *  $bgmax["head"]  : chaine a ajouter dans head
   *  $bgmax["body"]  : chaine a ajouter dans body a la position module  
   **/     
   public static function getBgMaxInfos(&$params, $modTitle)
   {
		$app = JFactory::getApplication();
    
		// tableau pour retour 
		$bgmax = array("head" => "", "body" => "");

		// DEBUG affiche la totalite des parametres pour analyse
		$bgmaxDebug = $params->get('bgmaxDebug');
		if ($bgmaxDebug=='2') {
			$user = JFactory::getUser();
			if (!$user->id) $bgmaxDebug = false;
		}
	  
		/****
		*  PERIODE : DOIT-ON PUBLIER ?
		****/  
		// attention, ambiguite si date fin vide et heure indiquee: 
		// on traite comme periode horaire journaliere	
		
		$ok = false;
		$debDate = str_replace('/','-',substr($params->get('debDate'),0,10)); // compatibilite ancienne version
		$debDate = bg_formatDate($debDate); 
		$debTime=$params->get('debTime','00:00');
		$endDate = str_replace('/','-',substr($params->get('endDate'),0,10)); // compatibilite ancienne version
		$endDate = bg_formatDate($endDate); 
		$endTime=$params->get('endTime','23:59');

		// Pour faciliter les calculs, on force la date debut/fin à une date infinie si une date de fin/debut complete est indiquee. le cas d'une heure sans date n'est pas geree
		if ((strlen($endDate)==10) && (strlen($debDate)==0)) { $debDate='1900-01-01'; }
		if ((strlen($debDate)==10) && (strlen($endDate)==0)) { $endDate='2900-01-01'; }

		// il faut que les 2 dates soient exprimees de la même maniere!
		if (strlen($debDate)!= strlen($endDate)) {
			$app->enqueueMessage('Les dates DEBUT et FIN doivent avoir le meme format'); 
		}
		// la date et heure actuelle au format pour comparaison
		// $nowDate = (strlen($debDate)==1) ? date('N'): bg_formatDate(substr(date('d-m-Y'),0, strlen($debDate)));
    	// $nowTime = date('H:i'); 

		$config = JFactory::getConfig();
		$now = JFactory::getDate('now',$config->get('offset'));
		$nowDate = (strlen($debDate)==1) ? $now->format('N',true): bg_formatDate(substr($now->format('d-m-Y',true),0, strlen($debDate)));
		$nowTime = $now->format('H:i',true); 
			
		// Analyse
		if (strlen($debDate)==0) {// permanent ou période horaire
			if ($debTime>$endTime) {  //18:00 -> 8:00 sur 2 jours
				$ok = (($nowTime>=$debTime) || ($nowTime<=$endTime));
				$period_msg = 'Day 1 '.$debTime.' <= '.$nowTime.' <= Day 2 '.$endTime.'<br>';			
			} else {   // 08:00 ->  18:00 sur la journee
				$ok = (($nowTime>=$debTime) && ($nowTime<=$endTime));
				$period_msg = 'Same day '.$debTime.' <= '.$nowTime.' <= '.$endTime.'<br>';			
			}
		} else {
			// maj date pour calcul sur 2 ans
			if ($debDate>$endDate) {
				$inc=array(0,7,31,0,0,12);
				$tmp = explode('-',$endDate);
				$tmp[0] += $inc[strlen($debDate)];
				$endDate = implode('-',$tmp);
				// actu date courante
				if ($nowDate<$debDate) {
					$tmp = explode('-',$nowDate);
					$tmp[0] += $inc[strlen($debDate)];
					$nowDate = implode('-',$tmp);
				}
			}
			if ($params->get('period_mode')) {
				$ok = (($debDate<=$nowDate) && ($nowDate<=$endDate)) && (($debTime<=$nowTime) && ($nowTime<=$endTime));
				$period_msg =($debDate.' <= '.$nowDate.' <= '.$endDate).' AND '.($debTime.' <= '.$nowTime.' <= '.$endTime).'<br>';
			} else {
				$ok = (($debDate.$debTime)<=($nowDate.$nowTime)) && (($nowDate.$nowTime)<=($endDate.$endTime));
				$period_msg =($debDate.$debTime).' <= '.($nowDate.$nowTime).' <= '.($endDate.$endTime).'<br>';
			}
		}
		if (!$ok) {
			if ($bgmaxDebug) {
				$msg = '#NO# BGMAX - '.$modTitle.': <br>'.$period_msg; 
				$app->enqueueMessage($msg);
			}
			return;
		}

    /****
	 *  MOBILE  : DOIT-ON PUBLIER ?
     ****/

    switch ($bgFilter=$params->get('filterMobile')) {
		case 'always':
			$ok = true;
			break;
		case 'mobile':
			$ok = bg_isMobile();
			break;
		case 'desktop':
			$ok = !bg_isMobile();
			break;
	}
	if (!$ok) {
		if ($bgmaxDebug) {
			$msg = '#NO# BGMAX - '.$modTitle.': '; 
			$msg.= 'view only on '.$params->get('filterMobile');
			$app->enqueueMessage($msg);
      }
		return;
	}
		
	/****
     *  CONTENU  : DOIT-ON PUBLIER ?
     ****/
    
    if ( ($bgFilter=$params->get('filterContent')) || ($bgmaxDebug) ) {
      $bg_id = JRequest::getVar( 'id', 0, 'get', 'int');
      $bg_menuid = JRequest::getVar( 'Itemid', 0, 'get', 'int');
      $bg_option = trim(JRequest::getVar( 'option', 0 ) );
      $bg_layout = trim(JRequest::getVar( 'layout', 0 ) );
      $bg_view = trim(JRequest::getVar( 'view', 0 ) );
      $bg_artid=''; $bg_catid=''; 
      switch ($bg_view) {
        case 'article':
          $bg_artid = $bg_id; $bg_id = ''; 
          $database = JFactory::getDBO();
          $query = "SELECT catid FROM #__content WHERE id=".$bg_artid;
          $database->setQuery($query);
          $row = $database->loadObject();
          $bg_catid = (($row!=null) ? $row->catid : '');
          break;
          
         case 'categories' :
          	$bg_catid = intval(JRequest::getVar( 'id', 0 ) );
            break;
      }
      $context = $bg_option;
      $context.= '+menuid='.$bg_menuid;
      $context.= '+view='.$bg_view;
      if ($bg_layout) $context.= '+layout='.$bg_layout;
      if ($bg_id) $context.= '+id='.$bg_id;
      if ($bg_artid) $context.= '+artid='.$bg_artid;
      if ($bg_catid) $context.= '+catid='.$bg_catid;

      /*
      Si une des lignes de critere correspond, le module sera affiche 
      un '-' inverse la condition
      exemple, on affiche le module si :
      view=blog -menuid=2  // vue blog non appell� par menu 2
      catid=3    // OU articles de categorie 3
      -artid=2   // MAIS PAS si article d'ID 2 
      */
      if ($bgFilter) {
        $context = '+'.$context.'+';  // pour recherche
        $arr = explode("\n",$bgFilter);
        foreach ($arr as &$lign) {
            $ok = true;
            $mots = explode(" ",$lign);
            foreach ( $mots as $mot ) {
               if ($mot) {
                  if ($mot[0]=="-") {
                    if (stristr($context, '+'.substr($mot,1).'+')) {
                        $ok=false; break;
                    }
                  } elseif ($mot[0]=="+") {
                    if (!stristr($context, '+'.substr($mot,1).'+')) {
                        $ok=false; break;
                    }
                  } else {
                    if (!stristr($context, '+'.$mot.'+')) {
                        $ok=false; break;
                    }
                 }   
               }
            } // foreach mot
            if ($ok) break;   // si ligne OK, on affiche      
        }
        if (!$ok) {
          if ($bgmaxDebug) {
			      $msg = '#ERR# BGMAX - '.$modTitle.': <br>'; 
            $msg.= 'Context:'.$context;
            $msg.= '<br />Filters: '.nl2br($bgFilter, ' || ');
            $app->enqueueMessage($msg);}
          return;
        }  
      } // if debug or Filtercontent
    } // if critere ou debug

    /*************************************
     *            ON AFFICHE 
     *************************************/         

    if ($bgmaxDebug) {
      $msg = JText::_('INFO_DEBUG').'#OK# BGMAX - '.$modTitle.': <br>'.$period_msg; 
      if (isset($context)) $msg.= 'Context:'.$context;
      $app->enqueueMessage($msg);
    }

		/****
		*   QUELLE IMAGE AFFICHER ?  
		*   Ordre des priorites :
		*   1 - celle indiquee dans la zone texte 
		*   2 - au hasard dans le dossier indique
		*   3 - aucune, uniquement la couleur               		
		****/                            

    /* 1 */
  	$bgImage = $params->get('image_path', '');
  	if ($bgImage) { 
		// chemin relatif a la racine 
		$bgImageAbs = JPATH_ROOT.strtr('/'.$bgImage, '/', DIRECTORY_SEPARATOR);
		$bgImage = trim(JURI::base(),'/').'/'.$bgImage;
    /* 2 */
		} elseif ($params->get('image_url', '')) {
		$bgImageAbs = $params->get('image_url', '');
		$bgImage = $bgImageAbs;
    /* 3 */
		} elseif ($params->get('RandomFolder', '-1')!='-1') {
		    $rep = '/images/bgmax/'.$params->get('RandomFolder').'/';
		    $bgImage = getRandomImage(JPATH_ROOT.strtr($rep, '/', DIRECTORY_SEPARATOR));
        if ($bgImage) {
			    $bgImageAbs = JPATH_ROOT.strtr($rep.$bgImage, '/', DIRECTORY_SEPARATOR);
        		$bgImage = trim(JURI::base(),'/').$rep.$bgImage;        
        } 
    }
		
		if ($bgmaxDebug) {$app->enqueueMessage("Image (abs): ".$bgImageAbs);}
	
	  /****
	   *  couleur de fond
	   ****/     	  
		$bodyColor = bg_hex2hex($params->get('bodyColor', '#FFFFFF'));
		if ( ($bgImage) && ($params->get('bodyColorAuto', '0')=='1') ) {
			$bodyColor = colorImageBottom($bgImageAbs);
		}

    /****
     *   TAILLE, POSITION ET EFFETS 
     ****/         
		$bgMode  = $params->get('mode', 'max');         // max, full ou none
		$bgEnlarge = $params->get('enlarge', '1');
		$bgReduce = $params->get('reduce', '1');
		$bgPosition = $params->get('position', 'absolute');
		$bgHAlign = $params->get('align', 'center');
		$bgVAlign = $params->get('vertAlign', 'top');
		$bgFadeActive = $params->get('fadeActive', '0');
		$bgFadeAfter = $params->get('fadeAfter', '0');
		$bgFadeDuration = $params->get('fadeDuration', '1000');
		$bgFadeFrame = $params->get('fadeframeRate', '30');
		$bgZIndex = $params->get('zIndex', '-1');
		$bgFFHack = $params->get('ffHack', '0px');
	  
    /****
     *  BLOC CONTENU
     ****/          
		$contentSelector = $params->get('contentSelector', '');  
		$contentColor = bg_hex2hex($params->get('contentColor', ''));  
		$contentOpacity = trim($params->get('contentOpacity', '100'),'%');  
		$contentWidth = $params->get('contentWidth', '');  
		$contentAlign = $params->get('contentAlign', '');
    
    /****
     * CODE COMPLEMENTAIRE
     ****/           
    if ($headOther = $params->get('headOther', '')) {
        $headOther = "<style type='text/css'>".$headOther."</style>";
        if ($bgmaxDebug) { 
             $app->enqueueMessage('Complementary code: <code>'.htmlspecialchars($headOther).'</code>');
        }
    }

    $headFile = $params->get('headFile', '-1');
    if ($headFile!='-1') {
	    $headFile = JPATH_ROOT.strtr('/images/bgmax/'.$headFile, '/', DIRECTORY_SEPARATOR);
      if (file_exists($headFile)) {
        $code =  file($headFile);
        $headOther = implode('', $code); 
        if ($bgmaxDebug) { 
          $app->enqueueMessage('headfile: '.$headFile.'<code>'.htmlspecialchars($headOther).'</code>');
        }
      } else {
    	 $app->enqueueMessage('headFile: '.$headFile.' **NOT FIND**','error'); 
      }
    }      

    /****
     *   Traitement de l'ajout image
     ****/  
                         
     if ( (strstr($bgMode, 'repeat')) || ($bgMode == 'cover') || ($bgImage == "") ) {
        //-------------------------------------------
        //-----> Affichage image SANS le script bgmax
        //-------------------------------------------
        if ($bgImage) {  
          $str = 'background:';
          $str.= '#'.$bodyColor;
          $str.= ' url('.$bgImage.')';
		  if ($bgMode == 'cover') {
			$str.= ' no-repeat';
			$str2 = ' background-size:cover !important;';
		  } else {
			$str.= ' '.$bgMode;
			$str2 = '';
		  }	
          $str.= ' '.$bgHAlign.' '.$bgVAlign;
          if ($bgPosition=='fixed') {$str.= ' fixed';}
          $str.= ' !important;';

          $bgmax["head"] .= '<style type="text/css">body {'.$str.$str2.'} </style>';
        }
     } else {
        //-------------------------------------------
        //-----> Affichage image AVEC le script bgmax
        //-------------------------------------------
        // Appel du script JS dans HEAD
        $site_base = JURI::base();
        if(substr($site_base, -1)=="/") {$site_base = substr($site_base, 0, -1);}
        $bgmax["head"] = '<script type="text/javascript" src="'.$site_base.'/modules/mod_bgmax/bgMax.min.js"></script>';

        // Definir la couleur sous image
        if ($bodyColor) {
          $bgmax["head"] .= '<style type="text/css">body {background-color:#'.$bodyColor.' !important;}</style>';
        }
        // Appel de la fonction JS dans BODY
        $str = "";
			  if ($bgMode == 'full') {$str.= 'mode:"full",';}
			  if ($bgEnlarge != '1') {$str.= 'enlarge:0,';}
			  if ($bgReduce  != '1') {$str.= 'reduce:0,';}
			  if ($bgPosition != 'absolute') {$str.= 'position:"fixed",';}
			  if ($bgHAlign != 'center') {$str.= 'align:"'.$bgHAlign.'",';}
			  switch ($bgVAlign) {
			  	case "center" : $str.= 'vertAlign:"middle",'; break;
			  	case "bottom" : $str.= 'vertAlign:"bottom",'; break;
			  }
			  if ($bgZIndex != '-1') {$str.= 'zIndex:'.$bgZIndex.',';}
			  if ($bgFFHack != '0px') {$str.= 'ffHack:"'.$bgFFHack.'",';}
		                                     
			  if ($bgFadeActive == '1') {           
  				$str .= 'fadeAfter:'.$bgFadeAfter.',';                 
  				$str .= 'fadeOptions:{duration:'.$bgFadeDuration.',';   
  				$str .= 'frameRate:'.$bgFadeFrame.'}';      
  			}                                          
			  $str = trim($str,",");
			  if ($str) {$str = ", {".$str."}";}
			  
			  $bgmax["body"]= '<script type="text/javascript">bgMax.init("'.$bgImage.'"'.$str.');</script>';
     } // if 
     
     /*****
      *   STYLE COMMUN (AVEC ou SANS BGMAX)
      *****/  
                          
      $str = ""; 
       // bloc qui contient tout le contenu 
      if ($contentSelector) {
        $str.= '<style type="text/css">';
        $str.= $contentSelector.' {';
        if ($contentWidth) {
          $str.= 'width:'.$contentWidth.';';
          switch ($contentAlign) {
            case 'left'   : $str.= 'margin-left: 0;'; break;
            case 'center' : $str.= 'margin: 0 auto;'; break;
            case 'right'  : $str.= 'margin-right: 0; margin-left: auto;'; break;
          }
        }
        if ($contentColor) {
          $str.= 'background-color: #'.$contentColor.';';
        }
        if ($contentOpacity == '0') {
			$str.= 'background-color: transparent;';      
		} else {
			if ($contentOpacity != '100') {
			  $str.= 'background-color: rgba(';      
			  $str.= bg_hex2rgb($contentColor).',';
			  $str.= ($contentOpacity / 100).') !important;';   
			}
		}
        $str.= '}</style>'; 
        // si transparence: hack pour IE
        if (($contentOpacity != '0') && ($contentOpacity != '100')) {
          $sval = dechex($contentOpacity * 2.55);
          $sval.= $contentColor;
          $str.= '<!--[if lte IE 8]> <style type="text/css">';
          $str.= $contentSelector.' {';
          $str.= 'background:transparent; '; 
          $str.= 'filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#'.$sval.',endColorstr=#'.$sval.');'; 
          $str.= 'zoom: 1;';
          $str.= '} </style> <![endif]-->';
        } 
      } // fin if $contentSelector
      
      /*******
       * COMPLEMENT DE CODE POUR HEAD
       *******/
       
      if ($headOther) {
          $str.= $headOther;          
      }

      $bgmax["head"] .= $str;

      if ($bgmaxDebug) {
         $app->enqueueMessage('----------------------------');
         foreach ($bgmax as $key=>$value) {
            $app->enqueueMessage($key.' => <code>'.htmlentities($value).'</code>');
         }
      }


    return $bgmax;
    } // fin function getBgMaxInfos
           
} // class
