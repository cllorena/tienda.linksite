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
			Rating &amp; Votes			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG Vote &amp; Rate</span>
		</h4>
		<div class="card-subtitle">Allows to vote or rate files with a selected rating type.</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Non-registered voting				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Allow non-registered visitors to vote on files." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[public_voting]" value="1">
			</div>
		</div>
	</div>
</div>