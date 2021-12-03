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
			Front-end Manager Parameters			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG Front-end Manager</span>
		</h4>
		<div class="card-subtitle">Setting for Front-end Manager operation on a website.</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Front-end Manager access				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define a Joomla! user level can acess Front-end Manager section on a website." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn" data-value="admin" data-active-class="btn-success">Admin</button>
	<button type="button" class="btn active btn-success" data-value="registered" data-active-class="btn-success">Registered</button>
	<button type="button" class="btn" data-value="none" data-active-class="btn-danger">None</button>
</div>
<input type="hidden" name="config[frontend_access]" value="registered">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Allow manage categories				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if a user in a Manager can edit existing categories." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[allow_frontend_galleries_management]" value="1">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				Allow new categories				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if a user in a Manager can create new categories." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn active btn-danger" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[allow_new_galleries]" value="0">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				User access				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if a user in a Manager can access other categories or only stay in his own." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn" data-value="only_own" data-active-class="btn-success">Only own</button>
	<button type="button" class="btn active btn-success" data-value="any" data-active-class="btn-success">Any</button>
</div>
<input type="hidden" name="config[frontend_gallery_access]" value="any">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-5 col-form-label clearfix">
				New files verification				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if all new files need manual approbval from Admin and must be stored unpublished or they may be published automatically withot a review." data-original-title="" title=""></i>
			</label>
			<div class="col-sm-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="manual" data-active-class="btn-success">Manual</button>
	<button type="button" class="btn" data-value="auto" data-active-class="btn-success">Auto</button>
</div>
<input type="hidden" name="config[frontend_file_publish]" value="manual">
			</div>
		</div>
	</div>
</div>