<?php
/**
 * FW Gallery SEO Booster Plugin 6.7.2§
 * @copyright (C) 2020 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');
?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title">
			SEO Options			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG SEO Booster</span>
		</h4>
		<div class="card-subtitle">Settings for SEO friendly URLs, file names, ALT tags and Google data.</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				Category URL mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The mask example: {site}_{category}_custom_text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site}_{category}_custom_text" type="text" name="config[seobooster_categoryurlmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				Category image filename mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The category image mask example: {site}_{category}_custom_text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site}_{category}_custom_text" type="text" name="config[seobooster_categorysrcmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				Category image ALT tag mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The mask example: {site} {category} {file} custom text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site} {category} custom text" type="text" name="config[seobooster_categoryaltmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				File URL mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The mask example: {site}_{category}_{file}_custom_text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site}_{category}_{file}_custom_text" type="text" name="config[seobooster_fileurlmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				File image filename mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The file image mask example: {site}_{category}_{file}_custom_text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site}_{category}_{file}_custom_text" type="text" name="config[seobooster_imagesrcmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				File image ALT tag mask				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="The category image mask example: {site} {category} custom text" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<input class="form-control" value="{site} {category} {file} custom text" type="text" name="config[seobooster_imagealtmask]">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-5 col-form-label clearfix">
				Google structured data				<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="" data-original-title="" title=""></i>
			</div>
			<div class="col-7">
				<div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Enable</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Disable</button>
</div>
<input type="hidden" name="config[seobooster_google_struct_data]" value="1">
			</div>
		</div>
	</div>
</div>