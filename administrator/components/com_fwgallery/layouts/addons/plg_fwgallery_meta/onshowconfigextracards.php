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
			Meta Data			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG File Meta Data</span>
		</h4>
		<div class="card-subtitle">Imports file meta information from EXIF, IPCT, XML tags info files fields at upload.</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Tags priority				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="<p>Select an order how tags should be processed. If a tag matching a file field is found it a tag all other occurrences will be ignored. </p> <p><em>For example, if Copyright was found in the highest priority IPCT meta data all others will be ignored.</em></p>" data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<select id="configmetadata_priority" name="config[metadata_priority]" class="form-control">
	<option value="exif,ipct,xmp">EXIF, IPCT, XMP</option>
	<option value="exif,xmp,ipct">EXIF, XMP, IPCT</option>
	<option value="xmp,exif,ipct">XMP, EXIF, IPCT</option>
	<option value="xmp,ipct,exif" selected="selected">XMP, IPCT, EXIF</option>
	<option value="ipct,xmp,exif">IPCT, XMP, EXIF</option>
	<option value="ipct,exif,xmp">IPCT, EXIF, XMP</option>
</select>
			</div>
		</div>
	</div>
</div>