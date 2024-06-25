<style>
    ul.select2-selection__rendered li {
        height: auto !important;
    }


    .universal-form-style-v2 ul li {
        margin: 0 0 15px 0;
        position: relative;
        height: auto !important;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><a class="dashboard-link-btn" href="<?php echo base_url('appearance') ?>"><i class="fa fa-chevron-left"></i>Back To Themes</a>Customize Your Theme</span>
                        </div>
                        <div id="carousel">
                            <div class="carousel">
                                <?php if ($theme["theme_name"] == 'theme-4') { ?>
                                    <div class="customize_portal">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="tabs-wrp paid-theme">
                                                    <ul id="pages_tabs" class="tabs">
                                                        <li class="active" rel="tab1"><a href="javascript:;">Home</a></li>
                                                        <li rel="tab2"><a href="javascript:;">Testimonials</a></li>
                                                        <li rel="tab3"><a href="javascript:;">Jobs</a></li>
                                                        <?php $count = 4;

                                                        foreach ($pages as $page) {
                                                            echo '<li rel="tab' . $count . '"><a href="javascript:;">' . $page['page_title'] . '</a></li>';
                                                            $count++;
                                                        } ?>
                                                    </ul>

                                                    <div class="tab_container">
                                                        <div id="tab1" class="tab_content">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <?php if ($show_checkbox) { ?>
                                                                        <label for="page_status">
                                                                            <input type="checkbox" page_name="Home" class="home_page_status" name="page_status" <?= !in_array('home', $inactive_pages) ? 'checked="checked"' : '' ?>>
                                                                            Published
                                                                        </label>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <div class="text-right">
                                                                        <a class="btn btn-success" href="<?php echo base_url('appearance/add_additional_sections/' . $theme_id) ?>"><i class="fa fa-plus-square"></i> Add Additional Boxes</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <div class="panel-group-wrp">
                                                                        <div class="panel-group" id="accordion">
                                                                            <?php $this->load->view('appearance/customize_appearance/customize_home'); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- #tab1 -->
                                                        <div id="tab2" class="tab_content">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="pannel-title">
                                                                        <p>Testimonials</p>
                                                                    </h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <form id="form_save_testimonials" enctype="multipart/form-data" method="post">
                                                                                <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                <input type="hidden" name="page_name" id="page_name" value="home" />
                                                                                <input type="hidden" id="sid" name="sid" value="" />
                                                                                <input type="hidden" id="image" name="image" value="" />
                                                                                <input type="hidden" name="perform_action" value="save_testimonial" />
                                                                                <div class="universal-form-style-v2">
                                                                                    <div class="row">
                                                                                        <div class="row">
                                                                                            <ul>
                                                                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                                                                    <li class="form-col-100 autoheight">
                                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                                            <label for="txt_author_name">Author <span class="staric">*</span></label>
                                                                                                            <input id="txt_author_name" name="txt_author_name" type="text" class="invoice-fields" placeholder="Author Name" />
                                                                                                        </div>
                                                                                                    </li>
                                                                                                    <li class="form-col-100 autoheight">
                                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                                            <label for="txt_short_description">Short Description <span class="staric">*</span></label>
                                                                                                            <textarea id="txt_short_description" name="txt_short_description" class="invoice-fields-textarea" rows="4" placeholder="Description"></textarea>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                </div>
                                                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                                                    <div class="col-xs-12">
                                                                                                        <div class="thumbnail">
                                                                                                            <img class="image-responsive" id="current-image" src="<?php echo AWS_S3_BUCKET_URL; ?>default_pic-ySWxT.jpg" alt="current_image" />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <ul>
                                                                                            <div class="col-xs-12">
                                                                                                <li class="form-col-100 autoheight">
                                                                                                    <label for="txt_full_description">Full Description <span class="staric">*</span></label>
                                                                                                    <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                                                                    <script>
                                                                                                        $(document).ready(function() {
                                                                                                            CKEDITOR.replace('txt_full_description');
                                                                                                        });
                                                                                                    </script>
                                                                                                    <textarea id="txt_full_description" name="txt_full_description" class="invoice-fields-textarea" rows="8" placeholder="Full Description"></textarea>
                                                                                                </li>
                                                                                                <li class="form-col-100 autoheight">
                                                                                                    <label for="file_image">Image</label>
                                                                                                    <div class="upload-file invoice-fields">
                                                                                                        <span class="selected-file" id="selected_file_file_image" name="selected_file_file_image">No file selected</span>
                                                                                                        <input onchange="fUpdateOnChangeStatic(this);" id="file_image" name="file_image" type="file" placeholder="Image" />
                                                                                                        <a href="javascript:;">Choose File</a>
                                                                                                    </div>
                                                                                                </li>
                                                                                            </div>
                                                                                            <div class="col-xs-12">
                                                                                                <li class="form-col-100 autoheight">
                                                                                                    <label for="txt_youtube_video">Youtube Video</label>
                                                                                                    <input id="txt_youtube_video" name="txt_youtube_video" type="text" class="invoice-fields" placeholder="Paste your youtube Video URL here." />
                                                                                                </li>
                                                                                                <div class="btn-panel">
                                                                                                    <button type="submit" class="submit-btn" onclick="fValidateTestimonialsForm();"><i class="fa fa-floppy-o"></i>&nbsp;Save</button>
                                                                                                    &nbsp;
                                                                                                    <button type="button" class="submit-btn" onclick="fClearTestimonialForm();"><i class="fa fa-refresh"></i>&nbsp;Clear</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            <div class="table-responsive table-outer">
                                                                                <div class="table-wrp">
                                                                                    <table class="table table-bordered table-striped table-hover">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th class="col-xs-2">Author</th>
                                                                                                <th class="col-xs-5">Description</th>
                                                                                                <th class="col-xs-2">Image</th>
                                                                                                <th class="col-xs-1">Status</th>
                                                                                                <th class="col-xs-2"></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <!-- <?php //echo '<pre>'; print_r($testimonials); die(); 
                                                                                                    ?> -->
                                                                                            <?php foreach ($testimonials as $testimonial) : ?>
                                                                                                <tr id="testimonial-row-<?php echo $testimonial['sid']; ?>">
                                                                                                    <td><?php echo $testimonial['author_name']; ?></td>
                                                                                                    <td><?php echo $testimonial['short_description']; ?></td>
                                                                                                    <td>
                                                                                                        <?php $img_url = isset($testimonial['resource_name']) && !empty($testimonial['resource_name']) ? AWS_S3_BUCKET_URL . $testimonial['resource_name'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg';

                                                                                                        ?>
                                                                                                        <img id="img-testimonial-<?php echo $testimonial['sid']; ?>" width="100" class="img-responsive" src="<?php echo $img_url ?>" alt="<?php echo $testimonial['resource_name']; ?>" />
                                                                                                    </td>
                                                                                                    <td><?php echo ($testimonial['status'] == 1 ? 'Published' : 'Un-Published'); ?></td>
                                                                                                    <td>
                                                                                                        <button data-status="<?php echo $testimonial['status']; ?>" data-record="<?php echo $testimonial['sid']; ?>" class="btn btn-sm btn-default" onclick="fDeleteTestimonial(this);"><i class="fa fa-trash"></i></button>
                                                                                                        <button data-status="<?php echo $testimonial['status']; ?>" data-record="<?php echo $testimonial['sid']; ?>" class="btn btn-sm btn-default" onclick="fSwitchStatus(this);"><i class="fa <?php echo ($testimonial['status'] == 1 ? 'fa-ban' : 'fa-check-square-o') ?>"></i></button>
                                                                                                        <button data-author="<?php echo htmlentities($testimonial['author_name']); ?>" data-short_description="<?php echo htmlentities($testimonial['short_description']); ?>" data-full_description="<?php echo htmlentities($testimonial['full_description']); ?>" data-youtube_video_id="<?php echo htmlentities($testimonial['youtube_video_id']); ?>" data-image="<?php echo htmlentities($testimonial['resource_name']); ?>" data-status="<?php echo htmlentities($testimonial['status']); ?>" data-record="<?php echo htmlentities($testimonial['sid']); ?>" class="btn btn-sm btn-default" onclick="fEditTestimonial(this);"><i class="fa fa-edit"></i></button>
                                                                                                        <!--
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['author_name'];   
                                                                                                                                            ?>" id="author-<?php echo $testimonial['sid']; ?>" name="author-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['short_description'];   
                                                                                                                                            ?>" id="short_description-<?php echo $testimonial['sid']; ?>" name="short_description-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['full_description'];   
                                                                                                                                            ?>" id="full_description-<?php echo $testimonial['sid']; ?>" name="full_description-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['youtube_video_id'];   
                                                                                                                                            ?>" id="youtube_video_id-<?php echo $testimonial['sid']; ?>" name="youtube_video_id-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['resource_name'];   
                                                                                                                                            ?>" id="image-<?php echo $testimonial['sid']; ?>" name="image-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['status'];   
                                                                                                                                            ?>" id="status-<?php echo $testimonial['sid']; ?>" name="status-<?php echo $testimonial['sid']; ?>" />
                                                                                                               <input type="hidden" value="<?php //echo $testimonial['sid'];  
                                                                                                                                            ?>" id="record-<?php echo $testimonial['sid']; ?>" name="record-<?php echo $testimonial['sid']; ?>" />
                                                                                                        -->
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php endforeach; ?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- #tab2 -->
                                                        <div id="tab3" class="tab_content">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="pannel-title">
                                                                        <p>Jobs</p>
                                                                    </h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <div class="universal-form-style-v2">
                                                                                        <form id="form_jobs_page_title" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                                            <input type="hidden" name="perform_action" value="update_jobs_page_title" />
                                                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid ?>" />
                                                                                            <input type="hidden" id="theme_name" name="theme_name" value="<?php echo $theme_name ?>" />
                                                                                            <ul>
                                                                                                <li class="form-col-100">
                                                                                                    <label>Page Title:</label>
                                                                                                    <input class="invoice-fields" name="jobs_page_title" id="jobs_page_title" value="<?php echo isset($jobs_page_title) && $jobs_page_title != '' ? $jobs_page_title : 'Join Our Team';  ?>" type="text">
                                                                                                </li>
                                                                                                <?php
                                                                                                if ($job_fair_configuration) { ?>
                                                                                                    <li class="form-col-50-left autoheight">
                                                                                                        <label for="job_fair">Job Fair Forms</label>
                                                                                                        <div class="">
                                                                                                            <select class="jsSelect2" name="job_fair_career_page_url[]" multiple>
                                                                                                                <?php if (empty($job_fair_multiple_forms)) {
                                                                                                                    echo '<option value="">Default</option>';
                                                                                                                } ?>
                                                                                                                <?php
                                                                                                                $jobFairMatches = !empty($theme['job_fair_career_page_url']) ? explode(',', $theme['job_fair_career_page_url']) : [];
                                                                                                                foreach ($job_fair_multiple_forms as $jfmf) { ?>
                                                                                                                    <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $jobFairMatches) ? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                                                                                                <?php                       } ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                    <li class="form-col-50-right">
                                                                                                        <label class="empty"></label>
                                                                                                        <label for="job_fair">
                                                                                                            <input type="checkbox" id="job_fair" name="job_fair" <?php echo ($theme['theme4_enable_job_fair_careerpage'] == 1 ? 'checked="checked"' : ''); ?> />
                                                                                                            Enable Job Fair
                                                                                                        </label>
                                                                                                    </li>
                                                                                                <?php   } else { ?>
                                                                                                    <input type="hidden" name="job_fair_career_page_url" value="">
                                                                                                <?php   } ?>
                                                                                                <li class="form-col-100">
                                                                                                    <button type="submit" class="btn btn-success">Save</button>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <hr />

                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <div class="well well-sm">
                                                                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $jobs_page_banner['jobs_page_banner']; ?>" alt="Jobs Banner Image" />
                                                                                    </div>

                                                                                    <div class="universal-form-style-v2">
                                                                                        <form method="post" id="save_jobs_banner_image" enctype="multipart/form-data">
                                                                                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                            <input type="hidden" name="page_name" id="page_name" value="jobs" />
                                                                                            <input type="hidden" name="perform_action" value="save_jobs_banner" />

                                                                                            <div class="form-group">
                                                                                                <label>Image <span class="staric">*</span></label>
                                                                                                <div class="upload-file invoice-fields">
                                                                                                    <span class="selected-file" id="selected_file_jobs_page_banner" name="selected_file_jobs_page_banner">No file selected</span>
                                                                                                    <input onchange="fUpdateOnChangeStatic(this)" class="invoice-fields" type="file" placeholder="Select Banner Image" name="jobs_page_banner" />
                                                                                                    <a href="javascript:;">Choose File</a>
                                                                                                </div>
                                                                                                <p class="help-block text-right">Image Dimensions : W 1400px X H 900px.</p>
                                                                                            </div>
                                                                                            <div class="btn-panel">
                                                                                                <button type="button" class="delete-all-btn active-btn" onclick="fSaveJobsPageBanner();">
                                                                                                    <i class="fa fa-refresh"></i>&nbsp;Update
                                                                                                </button>
                                                                                                &nbsp;&nbsp;
                                                                                                <button data-pageid="" data-banner="main" data-page="jobs" data-def_image="banner-1-kvinR.jpg" data-theme="<?php echo $theme['theme_name']; ?>" type="button" class="delete-all-btn active-btn" onclick="fRestoreDefaultBanner(this);"><i class="fa fa-reply"></i>&nbsp;Restore Default Banner</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="pannel-title">
                                                                        <p>Jobs Detail Banner</p>
                                                                    </h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <hr />

                                                                            <div class="row">
                                                                                <div class="col-xs-12">
                                                                                    <?php if (isset($jobs_detail_page_banner['jobs_detail_page_banner']) && !empty($jobs_detail_page_banner['jobs_detail_page_banner'])) { ?>
                                                                                        <div class="well well-sm">
                                                                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $jobs_detail_page_banner['jobs_detail_page_banner']; ?>" alt="Jobs Banner Image" />
                                                                                        </div>
                                                                                    <?php } ?>

                                                                                    <div class="universal-form-style-v2">
                                                                                        <form method="POST" action="<?php echo current_url() ?>" id="save_jobs_detail_banner_image" enctype="multipart/form-data">
                                                                                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                            <input type="hidden" name="page_name" id="page_name" value="jobs_detail" />
                                                                                            <input type="hidden" name="perform_action" value="save_jobs_detail_banner" />
                                                                                            <input type="hidden" id="current_job_details_banner" value="<?php echo isset($jobs_detail_page_banner['jobs_detail_page_banner']) && !empty($jobs_detail_page_banner['jobs_detail_page_banner']) ? $jobs_detail_page_banner['jobs_detail_page_banner'] : ''; ?>" />

                                                                                            <div class="form-group">
                                                                                                <label>Banner Type</label>

                                                                                                <label class="control control--radio">
                                                                                                    Default
                                                                                                    <input type="radio" name="job_detail_banner_type" id="job_detail_banner_type_default" class="job_detail_banner_type" value="default" <?php echo (isset($jobs_detail_page_banner['banner_type']) && $jobs_detail_page_banner['banner_type'] == 'default' ? 'checked="checked"' : ''); ?> checked />
                                                                                                    <div class="control__indicator"></div>
                                                                                                </label>

                                                                                                <label class="control control--radio" style="margin-left: 10px;">
                                                                                                    Custom
                                                                                                    <input type="radio" name="job_detail_banner_type" id="job_detail_banner_type_custom" class="job_detail_banner_type" value="custom" <?php echo (isset($jobs_detail_page_banner['banner_type']) && $jobs_detail_page_banner['banner_type'] == 'custom' ? 'checked="checked"' : ''); ?> />
                                                                                                    <div class="control__indicator"></div>
                                                                                                </label>


                                                                                            </div>

                                                                                            <div class="form-group">
                                                                                                <label>Image <span class="staric">*</span></label>
                                                                                                <div class="upload-file invoice-fields">
                                                                                                    <span class="selected-file" id="selected_file_jobs_detail_page_banner" name="selected_file_jobs_detail_page_banner">No file selected</span>
                                                                                                    <input onchange="fUpdateOnChangeStatic(this)" class="invoice-fields" type="file" placeholder="Select Image" name="jobs_detail_page_banner" />
                                                                                                    <a href="javascript:;">Choose File</a>
                                                                                                </div>
                                                                                                <p class="help-block text-right">Image Dimensions : W 1400px X H 900px.</p>
                                                                                            </div>
                                                                                            <div class="btn-panel">
                                                                                                <button type="submit" class="delete-all-btn active-btn" onclick="return fSaveJobsDetailPageBanner();">Save
                                                                                                </button>
                                                                                                &nbsp;&nbsp;
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- #tab2 -->
                                                        <?php
                                                        $count = 4;
                                                        foreach ($pages as $page) {
                                                            echo '<div id="tab' . $count . '" class="tab_content" >'; ?>
                                                            <div class="panel-group-wrp">
                                                                <div class="panel-group" id="<?php echo $page['page_name']; ?>-accordion">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a data-toggle="collapse" data-parent="#<?php echo $page['page_name']; ?>-accordion" href="#<?php echo $page['page_name']; ?>-collapseTwo"><span class="glyphicon glyphicon-plus"></span>Content
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="<?php echo $page['page_name']; ?>-collapseTwo" class="panel-collapse collapse in">
                                                                            <div class="panel-body">
                                                                                <div class="universal-form-style-v2">
                                                                                    <ul>
                                                                                        <form id="form_<?php echo $page['page_name']; ?>" method="post" enctype="multipart/form-data">
                                                                                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                            <input type="hidden" name="perform_action" value="save_page_data" />
                                                                                            <input type="hidden" id="sid_<?= $page['page_name'] ?>" name="sid" value="<?php echo $page['sid'] ?>" />
                                                                                            <input type="hidden" id="page_name" name="page_name" value="<?php echo $page['page_name'] ?>" />

                                                                                            <li class="form-col-100 autoheight">
                                                                                                <label>Page Title <span class="staric">*</span></label>
                                                                                                <input class="invoice-fields" type="text" id="page_title_<?= $page['page_name'] ?>" name="page_title" value="<?php echo $page['page_title'] ?>" />
                                                                                            </li>
                                                                                            <!-- <li class="form-col-100 autoheight"> class="ckeditor"  class="invoice-fields-textarea" type="text" rows="4" 
                                                                                                <label for="page_content">Page Content <span class="staric">*</span></label>
                                                                                                <textarea name="page_content" id="page_content">
                                                                                                <?php //echo $page['page_content']  
                                                                                                ?>
                                                                                                </textarea>
                                                                                            </li>-->
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <label for="page_content">Full Description <span class="staric">*</span></label>
                                                                                                <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                                                                <script>
                                                                                                    $(document).ready(function() {
                                                                                                        CKEDITOR.replace('page_content_<?php echo $page['page_name']; ?>');
                                                                                                    });
                                                                                                </script>
                                                                                                <textarea id="page_content_<?php echo $page['page_name']; ?>" name="page_content" class="invoice-fields-textarea" rows="8"><?php echo $page['page_content'] ?></textarea>
                                                                                            </li>
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <label for="page_status">
                                                                                                    <input type="checkbox" id="page_status" name="page_status" <?php echo ($page['page_status'] == 1 ? 'checked="checked"' : ''); ?> />
                                                                                                    Published
                                                                                                </label>
                                                                                            </li>
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <label for="job_opportunities">
                                                                                                    <input type="checkbox" id="job_opportunities" name="job_opportunities" <?php echo ($page['job_opportunities'] == 1 ? 'checked="checked"' : ''); ?> />
                                                                                                    Enable Job Opportunities button
                                                                                                </label>
                                                                                            </li>
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <label for="job_opportunities_text">Job Opportunities button text <span class="staric">*</span></label>
                                                                                                <input type="text" name="job_opportunities_text" id="job_opportunities_text" value="<?php echo (isset($page['job_opportunities_text']) && $page['job_opportunities_text'] != '') ? $page['job_opportunities_text'] : 'View Job Opportunities'; ?>" class="invoice-fields" />
                                                                                            </li>
                                                                                            <?php if ($job_fair_configuration) {
                                                                                                //
                                                                                                $matchURLs = !empty($page['job_fair_page_url']) ? explode(',', $page['job_fair_page_url']) : []
                                                                                            ?>
                                                                                                <li class="form-col-50-left autoheight">
                                                                                                    <label for="job_fair">Job Fair Forms</label>
                                                                                                    <div class="">
                                                                                                        <select class="jsSelect2" name="job_fair_page_url[]" multiple>
                                                                                                            <?php if (empty($job_fair_multiple_forms)) {
                                                                                                                echo '<option value="">Default</option>';
                                                                                                            } ?>
                                                                                                            <?php foreach ($job_fair_multiple_forms as $jfmf) { ?>
                                                                                                                <option value="<?php echo $jfmf['page_url']; ?>" <?php echo (in_array($jfmf['page_url'], $matchURLs) ? 'selected' : ''); ?>><?php echo $jfmf['title']; ?></option>
                                                                                                            <?php                       } ?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </li>
                                                                                                <li class="form-col-50-right">
                                                                                                    <label class="empty"></label>
                                                                                                    <label for="job_fair">
                                                                                                        <input type="checkbox" id="job_fair" name="job_fair" <?php echo ($page['job_fair'] == 1 ? 'checked="checked"' : ''); ?> />
                                                                                                        Enable Job Fair
                                                                                                    </label>
                                                                                                </li>
                                                                                            <?php   } else { ?>
                                                                                                <input type="hidden" name="job_fair_page_url" value="">
                                                                                            <?php   } ?>
                                                                                        </form>
                                                                                    </ul>
                                                                                </div>
                                                                                <div class="btn-panel">
                                                                                    <button type="submit" class="delete-all-btn active-btn" onclick="fValidatePageForm(this);" data-form-id="form_<?php echo $page['page_name']; ?>">
                                                                                        <i class="glyphicon glyphicon-floppy-disk"></i>
                                                                                        &nbsp;Save
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a data-toggle="collapse" data-parent="#<?php echo $page['page_name']; ?>-accordion" href="#<?php echo $page['page_name']; ?>-collapseOne"><span class="glyphicon glyphicon-plus"></span>Banner Image</a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="<?php echo $page['page_name']; ?>-collapseOne" class="panel-collapse collapse">
                                                                            <div class="panel-body">
                                                                                <div class="well well-sm">
                                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $page['page_banner']; ?>" alt="current banner image" />
                                                                                </div>
                                                                                <div class="universal-form-style-v2">
                                                                                    <ul>
                                                                                        <form id="form_banner_<?php echo $page['page_name']; ?>" enctype="multipart/form-data" method="post">
                                                                                            <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                            <input type="hidden" name="perform_action" value="save_page_banner" />
                                                                                            <input type="hidden" id="sid" name="sid" value="<?php echo $page['sid'] ?>" />
                                                                                            <input type="hidden" id="page_name" name="page_name" value="<?php echo $page['page_name'] ?>" />
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <div class="upload-file invoice-fields">
                                                                                                    <span class="selected-file" id="selected_file_<?php echo $page['page_name']; ?>" name="selected_file_<?php echo $page['page_name']; ?>">No file selected</span>
                                                                                                    <input data-page="<?php echo $page['page_name']; ?>" data-form-id="form_banner_<?php echo $page['page_name']; ?>" onchange="fUpdateOnChange(this)" type="file" name="file_page_banner" id="file_page_banner" />
                                                                                                    <a href="javascript:;">Choose File</a>
                                                                                                </div>
                                                                                            </li>
                                                                                            <li class="form-col-100 autoheight">
                                                                                                <div class="form-group">
                                                                                                    <label for="page_banner_status">
                                                                                                        <input type="checkbox" id="page_banner_status" name="page_banner_status" <?php if ($page['page_banner_status'] == 1) {
                                                                                                                                                                                        echo 'checked="checked"';
                                                                                                                                                                                    } ?> />
                                                                                                        &nbsp;Enable Banner </label>
                                                                                                </div>
                                                                                            </li>
                                                                                        </form>
                                                                                    </ul>
                                                                                </div>
                                                                                <div class="btn-panel">
                                                                                    <button id="btn_save_<?php echo $page['page_name']; ?>" data-banner_changed="0" type="button" class="delete-all-btn active-btn" data-form-id="form_banner_<?php echo $page['page_name']; ?>" onclick="fSavePageBanner(this);"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                                                                                    <button data-pageid="<?php echo $page['sid'] ?>" data-banner="<?php echo $page['page_name'] ?>" data-page="<?php echo $page['page_name'] ?>" data-def_image="<?php echo $page['page_default_banner'] ?>" data-theme="<?php echo $theme['theme_name']; ?>" type="button" class="delete-all-btn active-btn" onclick="fRestoreDefaultBanner(this);"><i class="fa fa-reply"></i>&nbsp;Restore Default Banner</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a data-toggle="collapse" data-parent="#<?php echo $page['page_name']; ?>-accordion" href="#<?php echo $page['page_name']; ?>-collapseThree"><span class="glyphicon glyphicon-plus"></span>Youtube Video</a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="<?php echo $page['page_name']; ?>-collapseThree" class="panel-collapse collapse">


                                                                            <div class="panel-body">
                                                                                <div class="row">
                                                                                    <div class="col-xs-12">
                                                                                        <div class="well well-sm">
                                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                                <?php if ($page['video_type'] == 'youtube') { ?>
                                                                                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $page['page_youtube_video']; ?>"></iframe>
                                                                                                <?php } ?>

                                                                                                <?php if ($page['video_type'] == 'vimeo') { ?>
                                                                                                    <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $page['page_youtube_video']; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                                                <?php } ?>

                                                                                                <?php if ($page['video_type'] == 'upload') { ?>
                                                                                                    <video controls width="100%">
                                                                                                        <source src="<?php echo base_url('assets/uploaded_videos/' . $page['page_youtube_video']); ?>" type='video/mp4'>
                                                                                                    </video>
                                                                                                <?php } ?>

                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="universal-form-style-v2">
                                                                                            <ul>
                                                                                                <form id="form_youtube_video_<?php echo $page['page_name']; ?>" enctype="multipart/form-data" method="post">
                                                                                                    <input type="hidden" name="theme_name" id="theme_name" value="<?php echo $theme['theme_name']; ?>" />
                                                                                                    <input type="hidden" name="perform_action" value="save_page_youtube_video" />
                                                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $page['sid'] ?>" />
                                                                                                    <input type="hidden" id="page_name" name="page_name" value="<?php echo $page['page_name'] ?>" />
                                                                                                    <li class="form-col-100 autoheight">
                                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                                            <div class="video-link">
                                                                                                                <label for="video_source">Video Source</label>
                                                                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                                                    <?php echo YOUTUBE_VIDEO; ?>
                                                                                                                    <input <?php echo (isset($page['video_type']) && (trim($page['video_type']) == 'youtube' || trim($page['video_type']) == '') ? 'checked="checked"' : ''); ?> class="video_source_page" type="radio" id="video_source_youtube" name="video_source_section_04" value="youtube">
                                                                                                                    <div class="control__indicator"></div>
                                                                                                                </label>
                                                                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                                                    <?php echo VIMEO_VIDEO; ?>
                                                                                                                    <input <?php echo (isset($page['video_type']) && trim($page['video_type']) == 'vimeo' ? 'checked="checked"' : ''); ?> type="radio" id="video_source_vimeo_section_04" name="video_source_section_04" value="vimeo" class="video_source_page">
                                                                                                                    <div class="control__indicator"></div>
                                                                                                                </label>
                                                                                                                <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                                                                    <?php echo UPLOAD_VIDEO; ?>
                                                                                                                    <input <?php echo (isset($page['video_type']) && trim($page['video_type']) == 'upload' ? 'checked="checked"' : ''); ?> class="video_source_page" type="radio" id="video_source_upload" name="video_source_section_04" value="upload">
                                                                                                                    <div class="control__indicator"></div>
                                                                                                                </label>
                                                                                                            </div>
                                                                                                            <div class="row">
                                                                                                                <input type="hidden" id="old_upload_video" name="old_upload_video" value="<?php echo $pre_source == 'upload' ? $job_fair_data['video_id'] : ''; ?>">

                                                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 yt_vm_video_container" id="yt_vm_video_container" <?php echo (isset($page['video_type']) && (trim($page['video_type']) == 'youtube' || trim($page['video_type']) == 'vimeo' || trim($page['video_type']) == '') ? '' : 'style="display: none"'); ?>>
                                                                                                                    <label for="YouTube_Video_Section_04" id="label_youtube" class="label_youtube" <?php echo  trim($page['video_type'] == 'youtube' || $page['video_type'] == '' ? '' : 'style="display: none"'); ?>>Youtube Video URL <span class="staric">*</span></label>
                                                                                                                    <label for="Vimeo_Video_Section_04" id="label_vimeo" class="label_vimeo" <?php echo (isset($page['video_type']) &&  trim($page['video_type']) == 'vimeo' ? '' : 'style="display: none"'); ?>>Vimeo Video <span class="staric">*</span></label>
                                                                                                                    <input type="text" name="yt_vm_video_url_section_04" value="<?php echo $previous_video_id; ?>" class="invoice-fields" id="yt_vm_video_url_section_04">
                                                                                                                </div>

                                                                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 up_video_container_section_04" id="up_video_container_section_04" <?php echo (isset($page['video_type']) && trim($page['video_type']) == 'upload' ? '' : 'style="display: none"'); ?>>
                                                                                                                    <label>Upload Video <span class="hr-required">*</span></label>
                                                                                                                    <div class="upload-file invoice-fields">
                                                                                                                        <span class="selected-file name_video_upload_section_04" id="name_video_upload_section_04"></span>
                                                                                                                        <input type="file" name="video_upload_section_04" id="video_upload_section_04" class="video_upload_page">
                                                                                                                        <a href="javascript:;">Choose Video</a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>

                                                                                                    <li class="form-col-100 autoheight">
                                                                                                        <div class="form-group">
                                                                                                            <label for="page_youtube_video_status">
                                                                                                                <input type="checkbox" id="page_youtube_video_status" name="page_youtube_video_status" <?php if ($page['page_youtube_video_status'] == 1) {
                                                                                                                                                                                                            echo 'checked="checked"';
                                                                                                                                                                                                        } ?> />
                                                                                                                &nbsp;Enable Video </label>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                    <li class="form-col-100 autoheight">
                                                                                                        <p>Video Location</p>
                                                                                                        <div class="form-group">
                                                                                                            <label>
                                                                                                                <input type="radio" id="video_location" name="video_location" value="top" <?php if ($page['video_location'] == 'top') {
                                                                                                                                                                                                echo 'checked="checked"';
                                                                                                                                                                                            } ?> />&nbsp;Top
                                                                                                            </label>
                                                                                                        </div>
                                                                                                        <div class="form-group">
                                                                                                            <label>
                                                                                                                <input type="radio" id="video_location" name="video_location" value="bottom" <?php if ($page['video_location'] == 'bottom') {
                                                                                                                                                                                                    echo 'checked="checked"';
                                                                                                                                                                                                } ?> />&nbsp;Bottom
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                </form>
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="btn-panel">
                                                                                            <button type="button" class="delete-all-btn active-btn" data-form-id="form_youtube_video_<?php echo $page['page_name']; ?>" onclick="fSavePageYoutubeVideo(this);"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php echo '</div>';
                                                            $count++;
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Modal -->
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/additional-methods.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url('assets'); ?>/mFileUploader/index.css">
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/mFileUploader/index.js"></script>

<script>
    $(function() {
        $("#settings-tabs").tabs();
        $("#home-accordion").accordion({
            collapsible: true
        });

        $('#file_image').on('change', function() {
            $('#image').val('');
        });

        $(".tab_content").hide();
        $(".tab_content:first").show();

        $("ul.tabs li").click(function() {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();
            var activeTab = $(this).attr("rel");
            $("#" + activeTab).fadeIn();
        });

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $(".home_page_status").change(function() {
            var msg = '';
            var page_name = $(this).attr('page_name');
            var is_published = 0;
            msg = page_name + " Page Unpublished";
            if ($(this).is(":checked")) {
                is_published = 1;
                msg = page_name + " Page Published";
            }
            var myUrl = "<?php echo base_url('/appearance/ajax_change_page_status') ?>";
            var myRequest = $.ajax({
                url: myUrl,
                data: {
                    'page_name': page_name,
                    'is_published': is_published
                },
                type: 'POST'
            });

            myRequest.success(function(response) {
                if (response == 'success') {
                    alertify.success(msg + ' Successfully!');
                }
            });
        });
    });

    function fValidateAboutUsForm() {
        var $myForm = $('#form-about-us');
        $myForm.validate({
            ignore: ":hidden:not(select)",
            rules: {
                txt_about_us_heading: {
                    required: true,
                    minlength: 4,
                    maxlength: 150
                },
                txt_about_us_text: {
                    required: true,
                    minlength: 4
                }
            },
            messages: {
                txt_about_us_heading: {
                    required: 'You Must Type in an About Us Heading!',
                    minlength: 'Heading Must be atleast 4 Characters.',
                    maxlength: 'Heading Cannot Exceed 150 Characters.'
                },
                txt_about_us_text: {
                    required: 'You Must Type in an About Us Text.',
                    minlength: 'Text Must be atleast 4 Characters.'
                }
            }
        });
    }

    function fSaveAboutUsData() {
        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
        var $myForm = $('#form-about-us');

        fValidateAboutUsForm();

        if ($myForm.valid()) {
            var DataToSend = $('#form-about-us').serialize();

            DataToSend += '&perform_action=save-about-us'
            $('#btn_about_us_save').attr('disabled', 'disabled');
            alertify.message('Saving....');
            var myRequest = $.ajax({
                url: myUrl,
                data: DataToSend,
                type: 'POST'
            });

            myRequest.success(function(response) {
                if (response == 'success') {
                    alertify.success('Successfully Saved!');
                    $('#btn_about_us_save').removeAttr('disabled');
                }
            });
        }
    }

    function fValidateAddNewPartnerForm() {
        $('#form_add_new_partner').validate({
            ignore: ":hidden:not(select)",
            rules: {
                txt_partner_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 150
                },
                txt_partner_url: {
                    pattern: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/,
                    minlength: 4,
                    maxlength: 400
                },
                file_partner_logo: {
                    required: true,
                    extension: 'jpg|jpeg|png|jpe'
                }
            },
            messages: {
                txt_partner_name: {
                    required: 'You Must Enter Partner Name.',
                    minlength: 'Partner Name Must Be atleast 4 Characters.',
                    maxlength: 'Partner Name Cannot exceed 150 Characters.'
                },
                txt_partner_url: {
                    minlength: 'Partner Url Must Be atleast 4 Characters.',
                    maxlength: 'Partner Url Cannot exceed 400 Characters.'
                },
                file_partner_logo: {
                    required: 'Please Select Logo Image.',
                    extension: 'Allowed Image Types are jpg, jpeg, png & jpe.'
                }
            }
        });
    }

    function fSaveAddNewPartnerForm() {
        var $myForm = $('#form_add_new_partner');
        fValidateAddNewPartnerForm();

        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";

        if ($myForm.valid()) {
            $myForm.submit();
        }

    }

    function fDeletePartner(partner, callingSource) {
        alertify.confirm(
            'Please Confirm Delete',
            'Are you sure you want to delete!',
            function() {
                var $myForm = $('#form-trash');
                var DataToSend = $myForm.serialize();

                DataToSend += '&partner_array_index=' + partner;
                //console.log(DataToSend);

                var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: DataToSend
                });

                myRequest.success(function(response) {
                    if (response == 'success') {
                        rowNumber = $(callingSource).attr('data-row');
                        $('#row-' + rowNumber).hide();
                        alertify.success('Partner Deleted Successfully!');
                    }
                });
            },
            function() {
                alertify.warning('Cancelled!');
            }).set({
            'labels': {
                'ok': 'Yes!'
            }
        });
    }

    function fValidateBannerImageForm() {
        $('#save_main_banner_image').validate({
            ignore: ":hidden:not(select)",
            rules: {
                file_main_banner_image: {
                    required: true,
                    extension: 'jpg|jpeg|jpe|png'
                }
            },
            messages: {
                file_main_banner_image: {
                    required: 'You Must Select a Banner Image File.',
                    extension: 'You can only use .jpg, .jpeg, .jpe & .png Files.'
                }
            }
        });
    }

    function fSaveBannerImage() {
        fValidateBannerImageForm();
        if ($('#save_main_banner_image').valid()) {
            $('#save_main_banner_image').submit();
        }
    }

    function fValidateAboutUsBannerImageForm() {
        $('#save_about_us_banner_image').validate({
            ignore: ":hidden:not(select)",
            rules: {
                file_about_us_banner_image: {
                    required: true,
                    extension: 'jpg|jpeg|jpe|png'
                }
            },
            messages: {
                file_about_us_banner_image: {
                    required: 'You Must Select a Banner Image File.',
                    extension: 'You can only use .jpg, .jpeg, .jpe & .png Files.'
                }
            }
        });
    }

    function fSaveAboutUsBannerImage() {
        fValidateAboutUsBannerImageForm();
        if ($('#save_about_us_banner_image').valid()) {
            $('#save_about_us_banner_image').submit();
        }
    }

    function fValidateTestimonialsForm() {
        $('#form_save_testimonials').validate({
            //ignore: ":hidden:not(select)",
            ignore: [],
            rules: {
                txt_author_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 150
                },
                txt_short_description: {
                    //pattern : /^[A-Za-z0-9\s\-_'",.@%:;?#!$\^&*()+[\]{}<>|~\/\\`�]+$/,
                    required: true,
                    minlength: 4,
                    maxlength: 400
                },
                file_image: {
                    extension: 'jpg|jpeg|jpe|png'
                },
                txt_youtube_video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                },
                txt_full_description: {
                    required: true
                }
            },
            messages: {
                txt_author_name: {
                    required: 'Author Name is Required.',
                    minlength: 'Author Name Must be Atleast 4 Characters.',
                    maxlength: 'Author Name Cannot Exceed 150 Characters.'
                },
                txt_short_description: {
                    //pattern : 'Short Description can contain Characters A-Z, a-z, ?, ., \' and blank space only.',
                    required: 'Description Is Required.',
                    minlength: 'Description Must Be Atleast 4 Characters.',
                    maxlength: 'Description Cannot Exceed 400 Characters.'
                },
                file_image: {
                    required: 'Image is Required.',
                    extension: 'You can only upload Images of Type jpg, jpeg, jpe and png.'
                },
                txt_youtube_video: {
                    pattern: 'Provide a valid Youtube video Url(i.e. https://www.youtube.com/watch?v=xxxxxxxxxxx )'
                },
                txt_full_description: {
                    required: 'Full Description is Required.'
                }
            }
        });
    }

    function fDeleteTestimonial(source) {
        var iRecordId = $(source).attr('data-record');
        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
        var DataToSend = '&perform_action=delete_testimonial&sid=' + iRecordId;
        alertify.confirm(
            'Confirm Delete',
            'Are you Sure You Want to Delete This Testimonial?',
            function() {
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: DataToSend
                });

                myRequest.success(function(response) {
                    if (response == 'success') {
                        $('#testimonial-row-' + iRecordId).hide();
                    }
                });
            },
            function() {
                //console.log('no');
            }).set({
            'labels': {
                'ok': 'Yes',
                'cancel': 'No'
            }
        });
    }

    function fSwitchStatus(source) {
        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
        var iRecordId = $(source).attr('data-record');
        var iCurrentStatus = parseInt($(source).attr('data-status'));
        var iNewStatus;

        if (iCurrentStatus == 1) {
            iNewStatus = 0;
        } else {
            iNewStatus = 1;
        }

        $(source).attr('data-record', iNewStatus);
        var DataToSend = '&perform_action=switch_testimonial_status&sid=' + iRecordId + '&new_status=' + iNewStatus;
        var myRequest;

        myRequest = $.ajax({
            url: myUrl,
            type: 'POST',
            data: DataToSend
        });

        myRequest.success(function(response) {
            if (response == 'success') {
                var myIcon = $(source).find('i').get(0);
                if ($(myIcon).hasClass('fa-ban')) {
                    $(myIcon).removeClass('fa-ban').addClass('fa-check-square-o');
                } else if ($(myIcon).hasClass('fa-check-square-o')) {
                    $(myIcon).removeClass('fa-check-square-o').addClass('fa-ban');
                }
            }
        });
    }

    function fEditTestimonial(source) {
        var sid = $('<div />').html($(source).attr('data-record')).text();
        var authorName = $('<div />').html($(source).attr('data-author')).text();
        var short_description = $('<div />').html($(source).attr('data-short_description')).text();
        var full_description = $('<div />').html($(source).attr('data-full_description')).text();
        var youtube_video_id = $('<div />').html($(source).attr('data-youtube_video_id')).text();
        var image = $('<div />').html($(source).attr('data-image')).text();
        var imageUrl = $('#img-testimonial-' + sid).attr('src');

        $('#sid').val(sid);
        $('#txt_author_name').val(authorName);
        $('#txt_short_description').val(short_description);

        if (youtube_video_id.length > 0) {
            $('#txt_youtube_video').val('https://www.youtube.com/watch?v=' + youtube_video_id);
        } else {
            $('#txt_youtube_video').val('');
        }

        CKEDITOR.instances.txt_full_description.insertHtml(full_description);
        $('#image').val(image);
        $('#current-image').attr('src', imageUrl).show();
        $('html, body').animate({
            scrollTop: $("#pages_tabs").offset().top
        }, 1000);
    }

    function fClearTestimonialForm() {
        $('#sid').val('');
        $('#txt_author_name').val('');
        $('#txt_short_description').val('');
        CKEDITOR.instances.txt_full_description.setData('');
        $('#image').val('');
        $('#current-image').attr('src', '<?php echo AWS_S3_BUCKET_URL; ?>default_pic-ySWxT.jpg');
    }

    function fValidatePageForm(source) {
        var formId = $(source).attr('data-form-id');
        var sid = $('#sid_' + formId.substr(5)).val();
        var page_id = 'page_content_' + formId.substr(5);
        var page_title = $('#page_title_' + formId.substr(5)).val().toLowerCase();

        $('#' + formId).validate({
            ignore: [],
            rules: {
                page_title: {
                    //pattern : /^[A-Za-z0-9\s\-_'",.@%:;?#!$\^&*()+[\]{}<>|~\/\\`�]+$/,
                    required: true,
                    minlength: 4,
                    maxlength: 150
                },
                page_content: {
                    required: true
                },
                job_opportunities_text: {
                    required: true
                }
            },
            messages: {
                page_title: {
                    //pattern: 'Title can only have Characters A - Z, a - z and Space.',
                    required: 'Page Title is Required.',
                    minlength: 'Page Title Must Be Atleast 4 Characters.',
                    maxlength: 'Page Title Cannot Exceed 150 Characters.',
                },
                page_content: {
                    required: 'Page content is required',
                },
                job_opportunities_text: {
                    required: 'Job Opportunities is required',
                }
            }
        });

        CKEDITOR.instances[page_id].updateElement();
        if ($('#' + formId).valid()) {
            var myUrl = "<?php echo base_url('/appearance/get_pages_name') ?>";
            nameFlag = false;
            $.ajax({
                url: myUrl,
                async: false,
                data: {
                    sid: sid
                },
                type: 'GET',
                success: function(data) {
                    var names = JSON.parse(data);

                    if (names.length > 0) {
                        var safe_page_title = replace_special_chars(page_title);

                        $.each(names, function(key, val) {

                            var safe_page_name = replace_special_chars(val.page_name);

                            /*
                            if (val.page_title.toLowerCase() == page_title) {
                                alertify.error('You already have a page with same name');
                                nameFlag = true;
                            }
                            */
                            if (safe_page_name == safe_page_title) {
                                alertify.error('You already have a page with same name');
                                nameFlag = true;
                            }

                        });
                    }

                },
                error: function() {
                    console.log("error");
                }
            });
            if (nameFlag) {
                return false;
            } else {
                $('#' + formId).submit();
            }
        }
    }

    function replace_special_chars(my_string) {
        my_string = my_string.replace(/\s/gi, '-');
        my_string = my_string.replace(/&/gi, '-and-');
        my_string = my_string.replace(/[^\w\-]/gi, '');
        my_string = my_string.replace(/-+/gi, '-');

        my_string = my_string.toLowerCase();

        return my_string;
    }

    function fValidatePageBanner(source) {
        var formId = $(source).attr('data-form-id');
        $('#' + formId).validate({
            rules: {
                file_page_banner: {
                    required: true,
                    extension: 'jpg|jpeg|jpe|png'
                }
            }
        });
    }

    function fSavePageBanner(source) {
        var formId = $(source).attr('data-form-id');
        var bannerChanged = $(source).attr('data-banner_changed');

        if (bannerChanged == 1) {
            fValidatePageBanner(source);

            if ($('#' + formId).valid()) {
                //console.log('valid');
                $('#' + formId).submit();
            }
        } else {
            $('#' + formId).submit();
        }
    }

    function fValidatePageYoutubeVideo(source) {
        var formId = $(source).attr('data-form-id');

        $('#' + formId).validate({
            rules: {
                page_youtube_video: {
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                page_youtube_video: {
                    pattern: 'Please Enter a Valid Youtube Video Url.'
                }
            }
        })
    }

    function fSavePageYoutubeVideo(source) {

        var formId = $(source).attr('data-form-id');
        //  fValidatePageYoutubeVideo(source);

        // if ($('#' + formId).valid()) {
        $('#' + formId).submit();
        // }
    }

    function fUpdateOnChange(source) {
        var pageName = $(source).attr('data-page');
        var CurrentValue = $(source).val();
        $('#selected_file_' + pageName).html(CurrentValue);
        $('#btn_save_' + pageName).attr('data-banner_changed', 1);
    }

    function fUpdateOnChangeStatic(source) {
        var fieldName = $(source).attr('name');
        var CurrentValue = $(source).val();
        $('#selected_file_' + fieldName).html(CurrentValue);
    }

    function fRestoreDefaultBanner(source) {
        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
        alertify.confirm(
            'Are you Sure?',
            'Are you Sure you want to Restore Default Banner?',
            function() {
                var pageid = $(source).attr('data-pageid');
                var page = $(source).attr('data-page');
                var banner = $(source).attr('data-banner');
                var def_image = $(source).attr('data-def_image');
                var theme = $(source).attr('data-theme');
                var sDataToSend = '&perform_action=restore_default_banner&page=' + page + '&banner=' + banner + '&def_image=' + def_image + '&theme=' + theme + '&pageid=' + pageid;
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: sDataToSend
                });

                myRequest.success(function(response) {
                    if (response == 'success') {
                        alertify
                            .alert('Success', "Default Image Restored Successfully", function() {
                                window.location = window.location.href;
                            });
                    }
                });
            },
            function() {

            });
    }

    function fRestoreDefaultImageForSection(source, section) {
        var myUrl = "<?php echo base_url('/appearance/ajax_responder') ?>";
        alertify.confirm(
            'Are you Sure?',
            'Are you Sure you want to Restore Default Banner?',
            function() {
                var pageid = $(source).attr('data-pageid');
                var page = $(source).attr('data-page');
                var banner = $(source).attr('data-banner');
                var def_image = $(source).attr('data-def_image');
                var theme = $(source).attr('data-theme');
                var sDataToSend = '&perform_action=restore_default_image_for_section&page=' + page + '&banner=' + banner + '&def_image=' + def_image + '&theme=' + theme + '&pageid=' + pageid + '&section=' + section;
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: sDataToSend
                })

                myRequest.success(function(response) {
                    if (response == 'success') {
                        alertify
                            .alert('Success', "Default Image Restored Successfully", function() {
                                window.location = window.location.href;
                            });
                    }
                });
            },
            function() {

            });
    }

    function fValidateMainBannerText() {
        $('#form_main_banner_text').validate({
            rules: {
                main_banner_title: {
                    pattern: /^[A-Za-z0-9\s\-_'",.@%:;?#!$\^&*()+[\]{}<>|~\/\\`�]+$/,
                    minlength: 4,
                    maxlength: 50
                },
                main_banner_tag_line: {
                    pattern: /^[A-Za-z0-9\s\-_'",.@%:;?#!$\^&*()+[\]{}<>|~\/\\`�]+$/,
                    minlength: 4,
                    maxlength: 50
                }
            },
            messages: {
                main_banner_title: {
                    pattern: 'Title can only contain Characters A-Z, a-z, ?, . and Space only.',
                    required: 'Title is a Required Field.',
                    minlength: 'Title must be atleast 4 Characters.',
                    maxlength: 'Title can only contain Maximum 30 Characters.'
                },
                main_banner_tag_line: {
                    pattern: 'Tag Line can only contain Characters A-Z, a-z, ?, ., \' and Space only.',
                    required: 'Tag Line is a Required Field.',
                    minlength: 'Tag Line must be atleast 4 Characters.',
                    maxlength: 'Tag Line can only contain Maximum 30 Characters.'
                }
            }
        });
    }

    function fSaveMainBannerText() {
        fValidateMainBannerText();

        if ($('#form_main_banner_text').valid()) {
            $('#form_main_banner_text').submit();
        }
    }

    function fValidateJobsPageBannerForm() {
        $('#save_jobs_banner_image').validate({
            rules: {
                jobs_page_banner: {
                    required: true,
                    extension: 'jpg|jpeg|jpe|png'
                }
            },
            messages: {
                jobs_page_banner: {
                    required: 'You Must Select a Banner Image.',
                    extension: 'Allowed Image types are jpg, jpeg, jpe and png.'
                }
            }
        });
    }

    function fSaveJobsPageBanner() {
        fValidateJobsPageBannerForm();
        if ($('#save_jobs_banner_image').valid()) {
            $('#save_jobs_banner_image').submit();
        }
    }

    function fSaveJobsDetailPageBanner() {
        $('#save_jobs_detail_banner_image').validate({
            rules: {
                jobs_detail_page_banner: {
                    required: function() {
                        return $('.job_detail_banner_type:checked').val() === 'custom' && $('#current_job_details_banner').val() === '';
                    },
                    extension: 'jpg|jpeg|jpe|png'
                }
            },
            messages: {
                jobs_detail_page_banner: {
                    required: 'You Must Select an Image.',
                    extension: 'Allowed Image types are jpg, jpeg, jpe and png.'
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    function fValidateHomePageYoutubeVideo() {
        $('#form_home_page_youtube_video').validate({
            rules: {
                home_page_youtube_video: {
                    required: true,
                    pattern: /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/
                }
            },
            messages: {
                home_page_youtube_video: {
                    required: 'Youtube Video Url Required.',
                    pattern: 'Please Enter a Valid Youtube Video Url.'
                }
            }
        });
    }

    function fSaveHomePageYoutubeVideo() {
        fValidateHomePageYoutubeVideo();

        if ($('#form_home_page_youtube_video').valid()) {
            $('#form_home_page_youtube_video').submit();
        }
    }

    function check_file(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();

            if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }


    //

    function check_video_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
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

        } else {
            $('#name_' + val).html('No video selected');
            alertify.error("No video selected");
            $('#name_' + val).html('<p class="red">Please select video</p>');
        }
    }




    $('.video_source_page').on('click', function() {
        var selected = $(this).val();
        if (selected == 'youtube') {
            $('.label_youtube').show();
            $('.label_vimeo').hide();
            $('.yt_vm_video_container').show();
            $('.up_video_container_section_04').hide();
        } else if (selected == 'vimeo') {
            $('.label_youtube').hide();
            $('.label_vimeo').show();
            $('.yt_vm_video_container').show();
            $('.up_video_container_section_04').hide();
        } else if (selected == 'upload') {
            $('.yt_vm_video_container').hide();
            $('.up_video_container_section_04').show();
        }
    });

    //
    $('.video_upload_page').on('change', function() {

        var fileName = $(this).val();
        if (fileName.length > 0) {
            $('.name_video_upload_section_04').html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                $(".name_video_upload_section_04").val(null);
                alertify.error("Please select a valid video format.");
                $('.name_video_upload_section_04').html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
                return false;
            } else {
                var file_size = Number(($(this)[0].files[0].size / 1024 / 1024).toFixed(2));
                var video_size_limit = Number('<?php echo UPLOAD_VIDEO_SIZE; ?>');
                if (video_size_limit < file_size) {
                    $(this).val(null);
                    alertify.error('<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>');
                    $('.name_video_upload_section_04').html('');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('.name_video_upload_section_04').html(original_selected_file);
                    return true;
                }
            }

        } else {
            $('.name_video_upload_section_04').html('No video selected');
            alertify.error("No video selected");
            $('.name_video_upload_section_04').html('<p class="red">Please select video</p>');
        }
    });



    /*
        let text = "https://www.youtube.com/watch?v=4mv2Un66tps";
        let pattern = /(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.)?youtube\.com\/watch(?:\.php)?\?.*v=)([a-zA-Z0-9\-_]+)/;
        let result = pattern.test(text);
        alert(result);
    */



    //
    $(document).ready(function() {
        $('.jsSelect2').select2({
            closeOnSelect: false
        });
    });
</script>