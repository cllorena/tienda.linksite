<?php
/**
 * FW Gallery Slideshow Layout Plugin 6.7.2
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title">
			Map Parameters			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG Files on Map</span>
		</h4>
		<div class="card-subtitle">Google Maps is the only map available for map at this point.</div>
	</div>
	<div class="card-body">
		<div class="form-group d-flex">
			<label class="col-sm-5 col-form-label clearfix">
				Google Maps key				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Google Maps key registered for your website to allow map presentation." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<input class="form-control" type="text" name="config[map_key]" value="">
			</div>
		</div>
	</div>
</div>