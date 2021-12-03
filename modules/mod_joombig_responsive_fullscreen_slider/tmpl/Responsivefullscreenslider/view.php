<?php
/**
* @title		joombig responsive fullscreen slider module
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2013 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

    // no direct access
    defined('_JEXEC') or die;
?>
<script>
jQuery.noConflict();
</script>

<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/css/styles-fullscreen.css" type="text/css" media="all">
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/js/modernizr.custom.79639.js"></script>
<?php
if ($enable_jQuery == 1) {?>
	<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/js/jquery.min.js"></script> 
<?php }?>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/js/jquery.ba-cond.min.js"></script> 
<script>
		var  call_autoplay = <?php echo $auto_play;?>;
		var  call_time_transitions;
		call_time_transitions = <?php echo $time_transitions;?>;
</script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/js/jquery.slitslider.js"></script> 
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_responsive_fullscreen_slider/tmpl/Responsivefullscreenslider/js/responslider.js"></script> 

<style>
	.sl-slider-wrapper {
		height: <?php echo $height_module;?>;
		width: <?php echo $width_module;?>;
		padding-left:<?php echo $left_module;?> !important;
		margin: 0 auto;
	}
	.sl-slide-inner{
		background-color: transparent !important;
	}
	.bg-1 .deco {
		border-color: #fff;
		background: url('<?php echo $imgscroll;?>') center;
		background-size: 100%, 100%;
		background-color: transparent !important;
	}
</style>


<div class="sliderfullscreenresp_main">
<div id="sliderfullscreenresp" class="sl-slider-wrapper">
<div class="sl-slider">
<?php
$count1 =1;
foreach($data as $index=>$value)
{?>
<div class="sl-slide bg-1" data-orientation="horizontal" data-slice-rotation="-15" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
<div class="sl-slide-inner" style="background: url('<?php echo JURI::root().$value['image'] ?>') center center no-repeat; background-size:<?php echo $width_module;?> <?php echo $height_module;?>;">
<?php if($display_ball == 1){?>
	<div class="deco" ></div>
<?php }?>
<?php if($display_title == 1){?>
	<h2><?php echo $value['title']?></h2>
<?php }?>
<?php if($display_des == 1){?>
	<blockquote style="border-left:none;">
	<p><?php echo $value['introtext']?></p>

	<cite><a href="<?php echo $value['Link']?>">Read more</a></cite> 
	</blockquote>
<?php }?>
</div>
</div>
<?php
		$count1++ ; 
} ?>
<nav id="nav-arrows" class="nav-arrows"> <span class="nav-arrow-prev">Previous</span> <span class="nav-arrow-next">Next</span> </nav>
<nav id="nav-dots" class="nav-dots"> <span class="nav-dot-current"></span>
<?php
$count2 =1;
foreach($data as $index=>$value)
{	if ($count2 != 1){?>
		<span></span>
<?php
	}
		$count2++ ; 
} ?>
</nav>
</div>
</div>
</div>
