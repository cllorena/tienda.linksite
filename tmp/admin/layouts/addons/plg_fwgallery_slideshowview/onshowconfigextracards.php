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
			Slideshow Layout			<span class="float-right badge badge-default"><i class="fal fa-puzzle-piece mr-1"></i>FWG Slideshow Layout</span>
		</h4>
		<div class="card-subtitle">Category files slideshow can be used as a category menu item view or in Layout Anywhere module.</div>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-sm-5">
                File ordering                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Category files ordering in a slideshow." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <select id="configslideshow_ordering" name="config[slideshow_ordering]" class="form-control">
	<option value="order">Ordering</option>
	<option value="alpha">Aplhabetically</option>
	<option value="new" selected="selected">Newest first</option>
	<option value="old">Oldest First</option>
	<option value="random">Random</option>
</select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Files quantity                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="A number of files to load for thumbnails at slideshow load." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="config[slideshow_qty]" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Thumbnails position                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define where category files thumbnails must be located in relation to slideshow area." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="bottom" data-active-class="btn-success">Bottom</button>
	<button type="button" class="btn" data-value="top" data-active-class="btn-success">Top</button>
	<button type="button" class="btn" data-value="left" data-active-class="btn-success">Left</button>
	<button type="button" class="btn" data-value="right" data-active-class="btn-success">Right</button>
	<button type="button" class="btn" data-value="none" data-active-class="btn-danger">None</button>
</div>
<input type="hidden" name="config[slideshow_position]" value="bottom">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Thumbnails type                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Thumbnails can be clickable image buttons or can be a carousel slider with files image preview moving along with a mouse or a finger move." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="buttons" data-active-class="btn-success">Buttons</button>
	<button type="button" class="btn" data-value="carousel" data-active-class="btn-success">Carousel</button>
</div>
<input type="hidden" name="config[slideshow_navigation]" value="buttons">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Thumbnail size                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Thumbnails will be square and will fit all area to look nice in thumnail line." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="config[slideshow_thumb]" value="">
                    <div class="input-group-append">
                        <span class="input-group-text small">px</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-5">
                Show main image                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Hiding main image allows to keep only thumbnail slider and have it as a slider with quick preview of category files." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[slideshow_show_big_image]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Image fit                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if image must fill all space or fit it leaving blank spaces if it doesn't fit slidehsow area perfectly by proportions." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="fill" data-active-class="btn-success">Fill</button>
	<button type="button" class="btn" data-value="fit" data-active-class="btn-success">Fit</button>
</div>
<input type="hidden" name="config[slideshow_sizing]" value="fill">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Slideshow height                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Slideshow area height on a page on laptops and large mobile screens." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="config[slideshow_height]" value="">
                    <div class="input-group-append">
                        <span class="input-group-text small">px</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Mobile medium height                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Slideshow area height on a page on medium mobile screens." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="config[slideshow_height_medium]" value="">
                    <div class="input-group-append">
                        <span class="input-group-text small">px</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Mobile small height                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Slideshow area height on a page on small mobile screens." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="config[slideshow_height_small]" value="">
                    <div class="input-group-append">
                        <span class="input-group-text small">px</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Slideshow delay                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Set a delay between slideshow files scroll." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="config[slideshow_pause]" value="">
                    <div class="input-group-append">
                        <span class="input-group-text small">sec</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Autoplay                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Define if slideshow needs to start automatically after a page is loaded." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[slideshow_autostart]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Slideshow info                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="A block of a file description that include all text and icons information. Here you may specify if this block is visible on a front-end." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_info]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Design                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Defines a design for sub-categories and files in slideshow. Default design is Bootstrap." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
         <div class="chzn-container chzn-container-single chzn-container-single-nosearch" style="width: 100%;" title="" id="configslideshow_template_chzn"><a class="chzn-single"><span>Bootstrap</span><div><b></b></div></a><div class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off" readonly=""></div><ul class="chzn-results"></ul></div></div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Category info                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="A block of a category description on top of slideshow. Information will include all items visible by a category settings like category name, description, counters, etc." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_category_info]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Show sub-categories                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Allows to hide sub-categories for a selected files category to keep slideshow view clean." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Yes</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">No</button>
</div>
<input type="hidden" name="config[slideshow_show_subcategories]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                File name                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Hides or show a file name." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_files_name]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Upload date                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Displays a date when a file was uploaded." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn active btn-danger" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_files_update]" value="0">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Owner user                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Shows Joomla! user name who created a file or was assigned as an owner." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn active btn-danger" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_files_owner]" value="0">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Description length                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Maximum number of characters for text version of description to shown. The rest will be trimmed. HTML formatting is removed." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <input type="text" class="form-control mb-0" name="config[slideshow_image_description_length]" value="" size="20">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                Download button                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Shows Download but for files which are physically stored on a website and can be downloaded as a file." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_files_download]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                File info                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="File resolution and size information." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_files_info]" value="1">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-5">
                File type icon                <i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-bs-toggle="popover" data-placement="top" data-content="Shows an icon at the bottom right corner of info block that represents a file type." data-original-title="" title=""></i>
            </div>
            <div class="col-sm-7">
                <div class="btn-group mr-2" role="group">
	<button type="button" class="btn active btn-success" data-value="1" data-active-class="btn-success">Show</button>
	<button type="button" class="btn" data-value="0" data-active-class="btn-danger">Hide</button>
</div>
<input type="hidden" name="config[slideshow_show_file_icon]" value="1">
            </div>
        </div>
    </div>
</div>