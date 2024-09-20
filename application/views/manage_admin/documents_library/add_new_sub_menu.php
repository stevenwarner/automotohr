<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>

                                        <?php if ($parent_type == 'sub_menu') { ?>
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/documents_library/view_details/' . $loc_id) ?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Back</a>
                                        <?php } else { ?>
                                            <a id="back_btn" href="<?php echo base_url('manage_admin/documents_library/view_sub_heading/' . $lib_id . '/' . $menu_id) ?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Back</a>
                                        <?php } ?>
                                    </div>

                                    <div class="add-new-company" id="menu-form">
                                        <form action="" method="POST" id="add_ques" autocomplete="off" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title">
                                                        <h1 class="page-title">
                                                            <?php if ($parent_type == 'sub_menu') { ?>
                                                                <span class="text-success"> <?= $parent_name ?></span>
                                                                <?php   } else {
                                                                if (sizeof($parent_name) > 0) { ?>
                                                                    <span class="text-success"><?= $parent_name[0]['name'] ?></span> > <span class="text-success"><?= $parent_name[0]['title'] ?></span>
                                                            <?php       }
                                                            } ?>
                                                            <?php /* = $form == 'add' ? 'Add New Sub Menu' : 'Add New Sub Heading'*/ ?>
                                                        </h1>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="type">Type</label>
                                                        <select name="type" id="type" class="hr-form-fileds">
                                                            <option value="content">Content Type</option>
                                                            <option value="anchor">Anchor Type</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="status">Status <span class="hr-required">*</span></label>
                                                        <select name="status" class="hr-form-fileds">
                                                            <option value="1">Active</option>
                                                            <option value="0">In Active</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div id="anchor-section" style="display: none">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label for="anchor-title">Anchor Title <span class="hr-required">*</span></label>
                                                            <?php echo form_input('anchor-title', set_value('anchor-title'), 'class="hr-form-fileds" id="anchor-title"'); ?>
                                                            <?php echo form_error('anchor-title'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label for="anchor-href">Anchor Link <span class="hr-required">*</span></label>
                                                            <?php echo form_input('anchor_href', set_value('anchor_href'), 'class="hr-form-fileds" id="anchor_href"'); ?>
                                                            <?php echo form_error('anchor_href'); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="content-section">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row">
                                                            <label for="title">Sub <?php echo $parent_type == 'sub_menu' ? 'Menu' : 'Heading' ?> Title <span class="hr-required">*</span></label>
                                                            <?php echo form_input('title', set_value('title'), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('title'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="description">Sub <?php echo $parent_type == 'sub_menu' ? 'Menu' : 'Heading' ?> Description<span class="hr-required">*</span></label>
                                                            <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                            <textarea class="ckeditor textarea" name="description" rows="8" cols="60" required>
                                                                <?php echo set_value('description'); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Upload Banner Image :</label>
                                                            <div class="upload-file form-control">
                                                                <span class="selected-file" id="name_banner_image">No file selected</span>
                                                                <input name="banner_image" id="banner_image" onchange="check_file_banner('banner_image')" type="file">
                                                                <a href="javascript:;">Choose File</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <label for="banner_status">
                                                            <input type="checkbox" id="banner_status" name="banner_status" value="1">
                                                            Enable Banner Image
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <h4 class="hr-registered">Manage Video</h4>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="field-row field-row-autoheight">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <div class="field-row">
                                                                                <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                                                                    <input type="radio" name="video_source" class="video_source" value="no_video" checked="">
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <div class="field-row">
                                                                                <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                                                    <input type="radio" name="video_source" class="video_source" value="youtube">
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <div class="field-row field-row-autoheight">
                                                                                <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                                                    <input type="radio" name="video_source" class="video_source" value="vimeo">
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <div class="field-row">
                                                                                <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                                                    <input type="radio" name="video_source" class="video_source" value="uploaded">
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <div class="field-row field-row-autoheight" id="youtube_vimeo_input">
                                                                                <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                                                                <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                                                                <input type="text" name="yt_vm_video_url" value="" class="form-control" id="yt_vm_video_url">
                                                                            </div>
                                                                            <div class="field-row field-row-autoheight" id="upload_input" style="display: none">
                                                                                <label for="UploadedVideo">Upload Video:</label>
                                                                                <div class="upload-file form-control">
                                                                                    <span class="selected-file" id="name_upload_video">No video selected</span>
                                                                                    <input name="upload_video" id="upload_video" onchange="upload_video_checker('upload_video')" type="file">
                                                                                    <a href="javascript:;">Choose Video</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <label for="video_status">
                                                                                <input type="checkbox" id="video_status" name="video_status" value="1">
                                                                                Enable Video
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php //$this->load->view('static-pages/common_option_for_video'); 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="hr-box">
                                                            <div class="hr-box-header">
                                                                <h4 class="hr-registered">Upload Related Documents </h4>
                                                            </div>
                                                            <div class="hr-innerpadding">
                                                                <div class="field-row field-row-autoheight">
                                                                    <!-- <label>Upload Related Documents :</label>-->
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <div class="field-row">
                                                                                <label for="file_name">Doc Name <span class="hr-required">*</span></label>
                                                                                <?php echo form_input('file_name', set_value('file_name'), 'class="hr-form-fileds" id="file_name"'); ?>
                                                                                <?php echo form_error('file_name'); ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <div class="field-row">
                                                                                <label for="sort_order">Sort Order <span class="hr-required">*</span></label>
                                                                                <input type="number" name="sort_order" class="hr-form-fileds" id="sort_order">
                                                                                <!--                                                                        --><?php //echo form_input('sort_order', set_value('sort_order'), 'class="hr-form-fileds" id="sort_order"'); 
                                                                                                                                                                ?>
                                                                                <!--                                                                        --><?php //echo form_error('sort_order'); 
                                                                                                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <div class="field-row field-row-autoheight">
                                                                                <label for="sort_order">Federal <span class="hr-required">*</span></label>
                                                                                <select class="invoice-fields" name="federal_check" id="federal_check">
                                                                                    <option value="1">Yes</option>
                                                                                    <option value="0">No</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                            <div class="field-row">
                                                                                <label for="anchor-href">Country <span class="hr-required">*</span></label>
                                                                                <select class="invoice-fields" name="country" id="country" data-attr="<?php echo $states; ?>">
                                                                                    <?php if (sizeof($active_countries) > 0) { ?>
                                                                                        <option value="0">All</option>
                                                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                                                            <option value="<?= $active_country["sid"]; ?>">
                                                                                                <?= $active_country["country_name"]; ?>
                                                                                            </option>
                                                                                    <?php }
                                                                                    } else {
                                                                                        echo '<option value="">No Country Allowed</option>';
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <div class="field-row field-row-autoheight">
                                                                                <label for="anchor-href">State <span class="hr-required">*</span></label>

                                                                                <div class="hr-select-dropdown">
                                                                                    <select class="chosen-select" multiple="multiple" name="states[]" id="state">
                                                                                        <?php if (empty($country_id)) { ?>
                                                                                            <option value="all">All States</option>
                                                                                            <?php foreach ($active_states[$country_id] as $active_state) { ?>
                                                                                                <option value="<?= $active_state["id"] ?>" <?php if ($active_state["id"] == $applicant_info['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                                                            <?php
                                                                                            } ?>
                                                                                        <?php   } else {
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <div class="field-row field-row-autoheight">
                                                                                <label for="word_editor">Document Description</label>
                                                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                                <textarea class="ckeditor textarea" name="word_editor" id="doc_editor" rows="8" cols="60">
                                                                                    <?php echo set_value('word_editor'); ?>
                                                                                </textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="field-row field-row-autoheight">
                                                                    <div class="upload-file form-control">
                                                                        <span class="selected-file" id="name_docs">No file selected</span>
                                                                        <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                                        <a href="javascript:;">Choose File</a>
                                                                    </div>
                                                                    <div id="file-upload-div" class="file-upload-box"></div>
                                                                    <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                                                </div>
                                                                <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Files One After Another </div>
                                                                <div class="custom_loader">
                                                                    <div id="loader" class="loader" style="display: none">
                                                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                        <span>Uploading...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if ($parent_type != 'sub_menu') { ?>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 " style="float: right;">
                                                            <div class="field-row">
                                                                <input type="button" class="btn btn-success pull-right" id="hybrid-doc" value="Hybrid Documents">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($parent_type != 'sub_menu') { ?>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12" style="float: right;">
                                                            <div class="field-row">
                                                                <input type="button" class="btn btn-success pull-right" id="generate-doc" value="Generate Documents">
                                                            </div>
                                                        </div>


                                                    <?php } ?>
                                                </div>

                                                <input type="hidden" name="video_type" value="youtube">
                                                <input type="hidden" name="pre_id" id="pre_id" value="0">

                                                <?php if ($parent_type == 'sub_menu') { ?>
                                                    <input type="hidden" name="category_id" value="<?= $loc_id ?>">
                                                <?php } else { ?>
                                                    <input type="hidden" name="menu_id" value="<?= $menu_id ?>">
                                                <?php } ?>

                                                <input type="hidden" name="parent_type" value="<?= $parent_type ?>">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <input type="submit" class="search-btn" id="form-submit" value="Save and Return" name="form-submit">
                                                    <input type="submit" class="search-btn btn-warning" id="more" value="Save and Add More" name="more">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                            <div class="add-new-company" id="generate_form" style="display: none">
                                                <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="heading-title">
                                                                <h1 class="page-title">Word Documents</h1>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="generate_file_name">Doc Name <span class="hr-required">*</span></label>
                                                                <?php echo form_input('generate_file_name', set_value('generate_file_name'), 'class="hr-form-fileds" id="generate_file_name"'); ?>
                                                                <?php echo form_error('generate_file_name'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="generate_sort_order">Sort Order <span class="hr-required">*</span></label>
                                                                <input type="number" name="generate_sort_order" class="hr-form-fileds" id="generate_sort_order">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="sort_order">Federal <span class="hr-required">*</span></label>
                                                                <select class="invoice-fields" name="generate_federal_check" id="generate_federal_check">
                                                                    <option value="1">Yes</option>
                                                                    <option value="0">No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="anchor-href">Country <span class="hr-required">*</span></label>
                                                                <select class="invoice-fields" name="country" id="generate_country" data-attr="<?php echo $states; ?>">
                                                                    <?php if (sizeof($active_countries) > 0) { ?>
                                                                        <option value="0">All</option>
                                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                                            <option value="<?= $active_country["sid"]; ?>">
                                                                                <?= $active_country["country_name"]; ?>
                                                                            </option>
                                                                    <?php }
                                                                    } else {
                                                                        echo '<option value="">No Country Allowed</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="anchor-href">State <span class="hr-required">*</span></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select multiple="multiple" name="states[]" id="generate_state">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="all">All States</option>
                                                                            <?php foreach ($active_states[$country_id] as $active_state) { ?>
                                                                                <option value="<?= $active_state["id"] ?>" <?php if ($active_state["id"] == $applicant_info['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                                            <?php
                                                                            } ?>
                                                                        <?php } else {
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="word_editor">Word Document Editor<span class="hr-required">*</span></label>
                                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                <textarea class="ckeditor textarea" name="word_editor" id="word_editor" rows="8" cols="60" required>
                                                                    <?php echo set_value('word_editor'); ?>
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" id="upload-word-div" style="display: none;">
                                                            <div class="hr-box">
                                                                <div class="hr-box-header">
                                                                    <h4 class="hr-registered">Uploaded Word Documents </h4>
                                                                </div>
                                                                <div class="hr-innerpadding">
                                                                    <div class="field-row field-row-autoheight">
                                                                        <div class="attached-files" id="uploaded-word"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="video-link" style="font-style: italic;"><b>Note.</b> Add one after other </div>
                                                            <div class="custom_loader">
                                                                <div id="word-loader" class="loader" style="display: none">
                                                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                    <span>Adding...</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                            <input type="button" class="search-btn" id="word-doc-submit" value="Add Word Document" name="form-submit">
                                                            <input type="button" class="search-btn black-btn" id="generate-cancel" value="Cancel">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12" id="generate_form_tags" style="display: none">
                                            <div class="offer-letter-help-widget" style="top: 0;">
                                                <div class="how-it-works-insturction">
                                                    <strong>How it's Works :</strong>
                                                    <p class="how-works-attr">1. Insert multiple tags where applicable</p>
                                                    <p class="how-works-attr">2. Update the Document</p>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Company Information Tags :</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_address}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_phone}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{career_site_url}}"></li>
                                                    </ul>
                                                </div>


                                                <div class="tags-arae">
                                                    <strong>Employee / Applicant Tags :</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{first_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{last_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{email}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{job_title}}"></li>
                                                    </ul>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Signature tags:</strong>

                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" id="abcde" value="{{inital}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{sign_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox_required}}"></li>
                                                    </ul>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Pay Plan / Offer Letter tags:</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" id="abcde" value="{{flat_rate_technician}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                            <div class="add-new-company" id="hybrid_form" style="display: none">
                                                <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="heading-title">
                                                                <h1 class="page-title">Hybrid Documents</h1>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="hybrid_file_name">Doc Name</label>
                                                                <?php echo form_input('hybrid_file_name', set_value('hybrid_file_name'), 'class="hr-form-fileds" id="hybrid_file_name"'); ?>
                                                                <?php echo form_error('hybrid_file_name'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="hybrid_sort_order">Sort Order</label>
                                                                <input type="number" name="hybrid_sort_order" class="hr-form-fileds" id="hybrid_sort_order">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="sort_order">Federal</label>
                                                                <select class="invoice-fields" name="hybrid_federal_check" id="hybrid_federal_check">
                                                                    <option value="1">Yes</option>
                                                                    <option value="0">No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <div class="field-row">
                                                                <label for="anchor-href">Country</label>
                                                                <select class="invoice-fields" name="country" id="hybrid_country" data-attr="<?php echo $states; ?>">
                                                                    <?php if (sizeof($active_countries) > 0) { ?>
                                                                        <option value="0">All</option>
                                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                                            <option value="<?= $active_country["sid"]; ?>">
                                                                                <?= $active_country["country_name"]; ?>
                                                                            </option>
                                                                    <?php }
                                                                    } else {
                                                                        echo '<option value="">No Country Allowed</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="anchor-href">State</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select multiple="multiple" name="states[]" id="hybrid_state">
                                                                        <?php if (empty($country_id)) { ?>
                                                                            <option value="all">All States</option>
                                                                            <?php foreach ($active_states[$country_id] as $active_state) { ?>
                                                                                <option value="<?= $active_state["id"] ?>" <?php if ($active_state["id"] == $applicant_info['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                                            <?php
                                                                            } ?>
                                                                        <?php   } else {
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="field-row field-row-autoheight">
                                                                <label for="word_editor">Word Document Editor<span class="hr-required">*</span></label>
                                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                <textarea class="ckeditor textarea" name="word_editor_hybrid" id="word_editor_hybrid" rows="8" cols="60" required>
                                                                    <?php echo set_value('word_editor'); ?>
                                                                </textarea>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <div class="video-link" id="msg-div" style="font-style: italic;"><b>Note.</b> Add one after other </div>
                                                            <div class="custom_loader">
                                                                <div id="word-loader" class="loader" style="display: none">
                                                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                    <span>Adding...</span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                                            <div class="field-row field-row-autoheight">
                                                                <label>Upload Documents :</label>
                                                                <div class="upload-file form-control">
                                                                    <span class="selected-file" id="name_hybrid_docs">No file selected</span>
                                                                    <input name="hybrid_docs" id="hybrid_docs" onchange="check_file_hybrid('hybrid_docs')" type="file">
                                                                    <a href="javascript:;">Choose File</a>
                                                                </div>
                                                                <div id="hybrid-file-upload-div" class="file-upload-box"></div>
                                                                <div class="attached-files" id="hybrid-uploaded-files" style="display: none;"></div>
                                                            </div>

                                                            <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Another </div>
                                                            <div class="custom_loader">
                                                                <div id="loader" class="loader" style="display: none">
                                                                    <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                                    <span>Uploading...</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                            <a class="search-btn black-btn" href="<?php echo current_url(); ?>">Cancel</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12" id="hybrid_form_tags" style="display: none">
                                            <div class="offer-letter-help-widget" style="top: 0;">
                                                <div class="how-it-works-insturction">
                                                    <strong>How it's Works :</strong>
                                                    <p class="how-works-attr">1. Insert multiple tags where applicable</p>
                                                    <p class="how-works-attr">2. Update the Document</p>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Company Information Tags :</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_address}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{company_phone}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{career_site_url}}"></li>
                                                    </ul>
                                                </div>


                                                <div class="tags-arae">
                                                    <strong>Employee / Applicant Tags :</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{first_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{last_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{email}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{job_title}}"></li>
                                                    </ul>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Signature tags:</strong>

                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" id="abcde" value="{{inital}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{sign_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{authorized_editable_date}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{short_text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{text_area_required}}"></li>
                                                        <li style="background-color: transparent; border: 0px; display: block;"><input type="text" class="form-control tag" readonly="" value="{{checkbox_required}}"></li>
                                                    </ul>
                                                </div>

                                                <div class="tags-arae">
                                                    <strong>Pay Plan / Offer Letter tags:</strong>
                                                    <ul class="tags">
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_rate}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{hourly_technician}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" id="abcde" value="{{flat_rate_technician}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_salary}}">
                                                        </li>
                                                        <li style="background-color: transparent; border: 0px; display: block;">
                                                            <input type="text" class="form-control tag" readonly="" value="{{semi_monthly_draw}}">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            <?php echo VIDEO_LOADER_MESSAGE; ?>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    var job_select;
    $(document).ready(function() {
        $('#youtube_vimeo_input').hide();
        $('#upload_input').hide();

        $('#generate-doc').click(function() {
            $('#word-doc-submit').val('Add Word Document');
            //            $('#generate_country').val(0);
            //            $('#generate_state').val('All States');
            $('#generate_file_name').val('');
            $('#generate_sort_order').val('');
            $('#generate_federal_check').val(1);
            $('#word_editor').val('');
            $('#generate_form').show();
            $('#generate_form_tags').show();
            $('#menu-form').hide();
        });



        var hybrid_state = $('#hybrid_state').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });


        var hybrid_sel = hybrid_state[0].selectize;

        CKEDITOR.replace('word_editor_hybrid');

        //
        $('#hybrid-doc').click(function() {

            $('#word-doc-submit').val('Add Word Document');
            $('#word-doc-edit').val('Add Word Document');
            $('#word-doc-edit').attr('id', 'word-doc-submit');
            $('#hybrid_country').val(0);
            $('#generate_file_name').val('');
            $('#generate_sort_order').val('');
            $('#generate_federal_check').val(1);
            $('#word_editor_hybrid').val('');
            CKEDITOR.instances.word_editor.setData('');
            hybrid_sel.clearOptions();
            hybrid_sel.load(function(callback) {
                var arr = [{}];
                var j = 0;
                arr[j] = {
                    value: 'all',
                    text: 'All States'
                }
                callback(arr);
                hybrid_sel.addItems('all');
                hybrid_sel.refreshItems();
            });

            $('#hybrid_form').show();
            $('#hybrid_form_tags').show();
           // $('#doc_form').hide();
            $('#upload-word-div').hide();
            $('#menu-form').hide();

            $('#msg-div').show();
            $('#uploaded-word').html('');

        });

        $('#generate-cancel').click(function() {
            $('#generate_form').hide();
            $('#generate_form_tags').hide();
            $('#menu-form').show();
        });

        var generate_state = $('#generate_state').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        var gen_sel = generate_state[0].selectize;
        var state_select = $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        var job_select = state_select[0].selectize;
        CKEDITOR.replace('description');
        CKEDITOR.replace('word_editor');
        CKEDITOR.replace('doc_editor');

        $('#type').on('change', function() {
            var type = $(this).val();

            if (type == 'content') {
                $('#content-section').show();
                $('#anchor-section').hide();
            } else {
                $('#content-section').hide();
                $('#anchor-section').show();
            }
        });

        $(document).on('change', '#generate_country', function() {
            var val = $(this).val();
            var states = $(this).attr('data-attr');

            if (val == '' || val == 'all' || val == null || val == 0) {
                gen_sel.clearOptions();
                gen_sel.load(function(callback) {
                    var arr = [{}];
                    arr[0] = {
                        value: 'all',
                        text: 'All States'
                    }
                    callback(arr);
                    gen_sel.addItems('all');
                    gen_sel.refreshItems();
                });
            } else {
                var allstates = $.parseJSON(states)[val];
                gen_sel.clearOptions();
                gen_sel.load(function(callback) {

                    var arr = [{}];
                    var j = 0;

                    for (var i = 0; i < allstates.length; i++) {
                        arr[j++] = {
                            value: allstates[i].sid,
                            text: allstates[i].state_name
                        }
                    }

                    callback(arr);
                    gen_sel.refreshItems();
                });
            }
        });

        $(document).on('change', '#country', function() {
            var val = $(this).val();
            var states = $(this).attr('data-attr');

            if (val == '' || val == 'all' || val == null || val == 0) {
                job_select.clearOptions();
                job_select.load(function(callback) {
                    var arr = [{}];
                    arr[0] = {
                        value: 'all',
                        text: 'All States'
                    }
                    callback(arr);
                    job_select.addItems('all');
                    job_select.refreshItems();
                });
            } else {
                var allstates = $.parseJSON(states)[val];
                job_select.clearOptions();
                job_select.load(function(callback) {
                    var arr = [{}];
                    var j = 0;

                    for (var i = 0; i < allstates.length; i++) {
                        arr[j++] = {
                            value: allstates[i].sid,
                            text: allstates[i].state_name
                        }
                    }
                    callback(arr);
                    job_select.refreshItems();
                });
            }
        });

        $(document).on('click', '#word-doc-submit', function() {
            var name = $('#generate_file_name').val();
            var sort_order = $('#generate_sort_order').val();
            var federal_check = $('#generate_federal_check').val();
            var country = $('#generate_country').val();
            var state = $('#generate_state').val();
            var word_content = $.trim(CKEDITOR.instances.word_editor.getData());

            if (name == '') {
                alertify.alert('Error! File Name Missing', "File Name is required");
                return false;
            } else if (country == '') {
                alertify.alert('Error! Country Missing', "Country is required");
                return false;
            } else if (state == '' || state == null) {
                alertify.alert('Error! States Missing', "States are required");
                return false;
            } else if (sort_order == '') {
                alertify.alert('Error! Sort Order Missing', "Sort Order is required");
                return false;
            } else if (word_content == '') {
                alertify.alert('Error! Word Document Missing', "Word Document is required");
                return false;
            }

            var form_data = new FormData();
            form_data.append('id', <?php echo $parent_type == 'sub_menu' ? $loc_id : $menu_id; ?>);
            form_data.append('country', country);
            form_data.append('states', state);
            form_data.append('sort_order', sort_order);
            form_data.append('file_name', name);
            form_data.append('federal_check', federal_check);
            form_data.append('word_content', word_content);
            form_data.append('parent_type', '<?php echo $parent_type == 'sub_menu' ? 'category_id' : 'menu_id'; ?>');
            form_data.append('parent_id', <?php echo $parent_type == 'sub_menu' ? 0 : $lib_id; ?>);

            if ($('#pre_id').val() != 0) {
                form_data.append('pre_id', $('#pre_id').val());
            }

            $.ajax({
                url: '<?= base_url('manage_admin/documents_library/generate_ajax_handler') ?>',
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                success: function(data) {
                    $('#loader').hide();
                    $('#word-doc-submit').removeClass('disabled-btn');
                    $('#word-doc-submit').prop('disabled', false);
                    alertify.success('New word document uploaded successfully');
                    if (data != "error") {
                        $('#pre_id').val(data);
                        $('#upload-word-div').show();
                        $('#uploaded-word').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"><div id="uploaded-word-name"><b>Name:</b>' + name + '</div></div><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"><span><b>Status:</b> Saved</span></div></div>');
                        $('#word-doc-submit').val('Add Another Document');
                    } else {
                        alertify.alert('Doc error');
                    }
                },
                error: function() {}
            });
        });



    $(document).on('change', '#hybrid_country', function() {
            var val = $(this).val();
            var states = $(this).attr('data-attr');
            if (val == '' || val == 'all' || val == null || val == 0) {

                hybrid_sel.clearOptions();
                hybrid_sel.load(function(callback) {
                    var arr = [{}];
                    arr[0] = {
                        value: 'all',
                        text: 'All States'
                    }
                    callback(arr);
                    hybrid_sel.addItems('all');
                    hybrid_sel.refreshItems();
                });
            } else {
                var allstates = $.parseJSON(states)[val];
                hybrid_sel.clearOptions();
                hybrid_sel.load(function(callback) {

                    var arr = [{}];
                    var j = 0;

                    for (var i = 0; i < allstates.length; i++) {
                        arr[j++] = {
                            value: allstates[i].sid,
                            text: allstates[i].state_name
                        }
                    }
                    callback(arr);
                    hybrid_sel.refreshItems();
                });
            }
        });


    $('.video_source').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
    });
    
    });


    $(function() {
        $.validator.setDefaults({
            debug: true,
            success: "valid"
        });

        $("#add_ques").validate({
            ignore: ":hidden:not(select)",
            rules: {
                description: {
                    required: function() {
                        CKEDITOR.instances.description.updateElement();
                    }
                },
                type: {
                    required: true
                },
                title: {
                    required: true
                },
                video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                description: {
                    required: 'Description is required'
                },
                type: {
                    required: 'Sub Menu Type is required'
                },
                title: {
                    required: 'Title is required'
                },
                video: {
                    pattern: 'Invalid pattern'
                }
            },
            submitHandler: function(form) {
                var instances = $.trim(CKEDITOR.instances.description.getData());
                var type = $('#type').val();

                if (type == 'content') {
                    if (instances.length === 0) {
                        alertify.alert('Error! Description Missing', "Description is required");
                        return false;
                    }

                    if ($('#banner_status').is(":checked") && $("#banner_image").val().length <= 0) {
                        alertify.alert('Please Provide Banner Image');
                        return false;
                    }

                    if ($('input[name="video_source"]:checked').val() != 'no_video') {
                        var flag = 0;
                        if ($('input[name="video_source"]:checked').val() == 'youtube') {

                            if ($('#yt_vm_video_url').val() != '') {

                                var p = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
                                if (!$('#yt_vm_video_url').val().match(p)) {
                                    alertify.error('Not a Valid Youtube URL');
                                    flag = 0;
                                    return false;
                                } else {
                                    flag = 1;
                                }
                            } else {
                                flag = 0;
                                alertify.error('Please add valid youtube video link.');
                                return false;
                            }
                        } else if ($('input[name="video_source"]:checked').val() == 'vimeo') {

                            if ($('#yt_vm_video_url').val() != '') {
                                var flag = 0;
                                var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                                $.ajax({
                                    type: "POST",
                                    url: myurl,
                                    data: {
                                        url: $('#yt_vm_video_url').val()
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == false) {
                                            alertify.error('Not a Valid Vimeo URL');
                                            flag = 0;
                                            return false;
                                        } else {
                                            flag = 1;
                                        }
                                    },
                                    error: function(data) {}
                                });
                            } else {
                                flag = 0;
                                alertify.error('Please add valid vimeo video link.');
                                return false;
                            }
                        } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {

                            var file = upload_video_checker('upload_video');
                            if (file == false) {
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        }

                        if (flag == 1) {
                            $('#my_loader').show();
                        } else {
                            return false;
                        }
                    }
                } else {
                    if ($('#anchor-title').val() == '') {
                        alertify.alert('Please Provide Anchor Title');
                        return false;
                    }

                    if ($('#anchor_href').val() == '') {
                        alertify.alert('Please Provide Anchor Link');
                        return false;
                    }
                }

                $('#my_loader').show();
                form.submit();
            }
        });
    });

    function check_file_banner(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val == 'docs') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "xlsx" && ext != "xls") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                } else {
                    $('#name_' + val).html(fileName.substring(0, 45));
                    $('.upload-file').hide();
                    $('#uploaded-files').hide();
                    $('#file-upload-div').append('<div class="form-control btn-upload"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
                }
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();

        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }

        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }


    function check_file_hybrid(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val != '') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "PDF" && ext != "DOCX" && ext != "DOC" && ext != "xlsx" && ext != "xls") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                } else {
                    $('#name_' + val).html(fileName.substring(0, 45));
                    $('.upload-file').hide();
                    $('#hybrid-uploaded-files').hide();
                    $('#hybrid-file-upload-div').append('<div class="form-control btn-upload"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUploadHybrid()"> <input class="btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUploadHybrid();"> </div> </div>');
                }
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }



    function DoUpload() {
        if ($('#file_name').val() == '') {
            alertify.alert('Error! File Name Missing', "File Name is required");
            return false;
        } else if ($('#country').val() == '') {
            alertify.alert('Error! Country Missing', "Country is required");
            return false;
        } else if ($('#state').val() == '' || $('#state').val() == null) {
            alertify.alert('Error! States Missing', "States are required");
            return false;
        } else if ($('#sort_order').val() == '') {
            alertify.alert('Error! Sort Order Missing', "Sort Order is required");
            return false;
        }

        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('id', <?php echo $parent_type == 'sub_menu' ? $loc_id : $menu_id; ?>);
        form_data.append('country', $('#country').val());
        form_data.append('states', $('#state').val());
        form_data.append('sort_order', $('#sort_order').val());
        form_data.append('file_name', $('#file_name').val());
        form_data.append('federal_check', $('#federal_check').val());
        form_data.append('word_content', $.trim(CKEDITOR.instances.doc_editor.getData()));
        form_data.append('parent_type', '<?php echo $parent_type == 'sub_menu' ? 'category_id' : 'menu_id'; ?>');
        form_data.append('parent_id', <?php echo $parent_type == 'sub_menu' ? 0 : $lib_id; ?>);

        if ($('#pre_id').val() != 0) {
            form_data.append('pre_id', $('#pre_id').val());
        }

        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('manage_admin/documents_library/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                //                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if (data != "error") {
                    $('#pre_id').val(data);
                } else {
                    alertify.alert('Doc error');
                }
            },
            error: function() {}
        });
    }



    function DoUploadHybrid() {
        if ($('#hybrid_file_name').val() == '') {
            alertify.alert('Error! File Name Missing', "File Name is required");
            return false;
        } else if ($('#hybrid_country').val() == '') {
            alertify.alert('Error! Country Missing', "Country is required");
            return false;
        } else if ($('#hybrid_state').val() == '' || $('#hybrid_state').val() == null) {
            alertify.alert('Error! States Missing', "States are required");
            return false;
        } else if ($('#hybrid_sort_order').val() == '') {
            alertify.alert('Error! Sort Order Missing', "Sort Order is required");
            return false;
        } else if ($.trim(CKEDITOR.instances.word_editor_hybrid.getData()) == '') {
            alertify.alert('Error! Word Document', "Word Document is required");
            return false;
        }


        var file_data = $('#hybrid_docs').prop('files')[0];


        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('pre_id', $('#pre_id').val());
        form_data.append('country', $('#hybrid_country').val());
        form_data.append('states', $('#hybrid_state').val());
        form_data.append('federal_check', $('#hybrid_federal_check').val());
        form_data.append('sort_order', $('#hybrid_sort_order').val());
        form_data.append('word_content', $.trim(CKEDITOR.instances.word_editor_hybrid.getData()));
        form_data.append('file_name', $('#hybrid_file_name').val());
        form_data.append('parent_type', '<?php echo $parent_type == 'sub_menu' ? 'category_id' : 'menu_id'; ?>');

        form_data.append('id', <?php echo $menu_id; ?>);
        form_data.append('parent_id', <?php echo $parent_type == 'sub_menu' ? 0 : $lib_id; ?>);
        form_data.append('doc_type', 'Hybrid');


        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('manage_admin/documents_library/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#hybrid-uploaded-files').show();
                $('#hybrid-uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="hybrid-uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                // $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#hybrid-file-upload-div').html("");
                $('#name_hybrid_docs').html("No file selected");
                if (data != "error") {
                    $('#pre_id').val(data);
                } else {
                    alertify.alert('Doc error');
                }
            },
            error: function() {}
        });
    }



    function upload_video_checker(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();

            if (val == 'upload_video') {
                if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid video format.");
                    $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                    return false;
                } else {
                    var file_size = Number(($("#" + val)[0].files[0].size / 1024 / 1024).toFixed(2));
                    var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                    if (video_size_limit < file_size) {
                        $("#" + val).val(null);
                        alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                        $('#name_' + val).html('');
                        return false;
                    } else {
                        var selected_file = fileName;
                        var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                        $('#name_' + val).html(original_selected_file);
                        return true;
                    }
                }

            }
        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
            return false;

        }
    }
</script>