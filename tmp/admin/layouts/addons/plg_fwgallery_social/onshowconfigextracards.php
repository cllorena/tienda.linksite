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
			Social Options			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG Social</span>
		</h4>
		<div class="card-subtitle">Turn on/off social share buttons to allow visitors share files with their friends.</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Twitter				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://twitter.com to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_twitter_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Facebook				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://facebook.com to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_facebook_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Pinterest				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://pinterest.com to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_pinterest_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Tumblr				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://tumblr.com to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_tumblr_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Odnoklassniki				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://odnoklassniki.ru to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_ok_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				VKontakte				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a new window at https://vk.com to let you log in and share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_vk_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Viber				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a Viber to let you share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_viber_sharing]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				What's App				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Opens a What's App to let you share file name and a link to it." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[display_whatsapp_sharing]" value="1">
			</div>
		</div>
	</div>
</div>