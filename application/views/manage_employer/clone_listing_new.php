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
                            <span
                                class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a class="dashboard-link-btn" href="<?php echo base_url('my_listings') ?>"><i
                                        class="fa fa-chevron-left"></i>My Jobs</a>
                                Clone Job
                            </span>
                        </div>
                        <div class="create-job-wrap">
                            <div class="job-title-text">
                                <p>Enter information about your job. Fields marked with asterisk (<span
                                        class="staric">*</span> ) are mandatory.</p>
                            </div>
                            <div class="universal-form-style-v2">
                                <ul>
                                    <form id="employers_add_job" action="" method="POST" enctype="multipart/form-data">
                                        <li class="form-col-50-left">
                                            <label>Title:<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" name="Title" id="Title"
                                                value="<?php echo set_value('Title', $listing["Title"]); ?>">
                                            <?php echo form_error('Title'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Job Type:</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="JobType">
                                                    <option value="Full Time" <?php if ($listing["JobType"] == "Full Time") { ?>selected<?php } ?>>Full Time</option>
                                                    <option value="Part Time" <?php if ($listing["JobType"] == "Part Time") { ?>selected<?php } ?>>Part Time </option>
                                                    <option value="Seasonal" <?php if ($listing["JobType"] == "Seasonal") { ?>selected<?php } ?>>Seasonal </option>

                                                </select>
                                            </div>
                                        </li>
                                        <?php if (form_error('Title') != null) { ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="box box-default">
                                                        <div class="box-body">
                                                            <div class="alert alert-danger alert-dismissable">
                                                                "Hint:" We suggest that each job have a unique title, if you
                                                                have multiple Sales positions listed, add the store name at
                                                                the end of the title.
                                                                Example: "Automotive Sales Position Joe's Toyota"
                                                            </div>
                                                        </div><!-- /.box-body -->
                                                    </div><!-- /.box -->
                                                </div><!-- /.col -->
                                            </div> <!-- /.row -->
                                        <?php } ?>
                                        <li class="form-col-100">
                                            <label>Job Category(s):<span class="staric">*</span></label>
                                            <div class="Category_chosen">
                                                <select data-placeholder="- Please Select -" multiple="multiple"
                                                    onchange="multiselectbox()" name="JobCategory[]" id="Category"
                                                    class="chosen-select">
                                                    <?php $JobCategoryArray = explode(',', $listing['JobCategory']);
                                                    foreach ($data_list as $data) { ?>
                                                        <option value="<?= $data['id']; ?>" <?php if (isset($listing['JobCategory']) && in_array($data['id'], $JobCategoryArray)) { ?> selected <?php } ?>>
                                                            <?= $data['value']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div style="display: none;" id="choiceLimit">5</div>
                                            <span class="available"><samp id="choicelimitavailable">5</samp>
                                                available</span>
                                            <?php echo form_error('JobCategory'); ?>
                                        </li>

                                        <li class="form-col-50-left">
                                            <label>Country: <span class="staric">*</span></label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="Location_Country"
                                                    onchange="getStates(this.value, <?php echo $states; ?>)">
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <option value="<?= $active_country['sid']; ?>" <?php if ($listing['Location_Country'] == $active_country['sid']) { ?>
                                                                selected <?php $country_id = $active_country['sid'];
                                                          } ?>>
                                                            <?= $active_country['country_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <?php echo form_error('Location_Country'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>State: <span class="staric">*</span></label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="Location_State" id="state">
                                                    <?php if (empty($country_id)) { ?>
                                                        <option value="">Select State </option>
                                                    <?php } else {
                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                            <option value="<?= $active_state['sid'] ?>" <?php if (isset($listing['Location_State']) && $listing['Location_State'] == $active_state['sid']) { ?> selected
                                                                <?php } ?>>
                                                                <?= $active_state['state_name'] ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <?php echo form_error('Location_State'); ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>City: <span class="staric">*</span></label>
                                            <input class="invoice-fields" type="text" name="Location_City" id="city"
                                                value="<?php echo set_value('Location_City', $listing["Location_City"]); ?>">
                                            <?php echo form_error('Location_City'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Zip Code: <span class="staric">*</span></label>
                                            <input class="invoice-fields" type="text" name="Location_ZipCode"
                                                id="zip_code"
                                                value="<?php echo set_value('Location_ZipCode', $listing["Location_ZipCode"]); ?>">
                                            <?php echo form_error('Location_ZipCode'); ?>
                                        </li>
                                        <input type="hidden" value="1" name="active">

                                        <?php if (!empty($templates)) { ?>
                                            <li class="form-col-100 autoheight">
                                                <label class="autoheight" for="select_template">Select Job Description and
                                                    Job Requirement Templates ( Please read through the templates and edit
                                                    or review any text that is in "QUOTATIONS" so that it reflects your
                                                    company branding and message.)</label>
                                                <div class="csSelect2WithBg">
                                                    <select class="invoice-fields" id="select_template"
                                                        style="background-color:#fd7a2a">
                                                        <option value="" style="background-color:#f7f7f7">Select Template
                                                        </option>
                                                        <?php foreach ($templates as $template) { ?>
                                                            <option id="template_<?php echo $template['sid'] ?>"
                                                                data-description="<?php echo $template['description'] ?>"
                                                                data-requirements="<?php echo $template['requirements'] ?>"
                                                                value="<?php echo $template['sid'] ?>"
                                                                style="background-color:#f7f7f7">
                                                                <?php echo $template['title'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <div class="description-editor">
                                            <label>Job Description:<span class="staric">*</span></label>
                                            <div style='margin-bottom:5px;'>
                                                <?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?>
                                            </div>
                                            <textarea class="ckeditor" name="JobDescription" id="JobDescription"
                                                cols="67" rows="6"><?php echo $listing["JobDescription"]; ?></textarea>
                                            <?php echo form_error('JobDescription'); ?>
                                        </div>
                                        <div class="description-editor">
                                            <label>Job Requirements:</label>
                                            <div style='margin-bottom:5px;'>
                                                <?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?>
                                            </div>
                                            <textarea class="ckeditor" name="JobRequirements" id="JobRequirements"
                                                cols="67" rows="6"><?php echo $listing["JobRequirements"]; ?></textarea>
                                        </div>

                                        <li class="form-col-100 autoheight">
                                            <p class="text-danger" style="margin-bottom: -10px; font-size: 16px;">
                                                <strong>Note: Your State may have recently mandated a Required Salary
                                                    Range be added to all jobs that you post.<br> Please Add a Salary or
                                                    Salary Range here. <a href="#" class=" jsSalaryInfo"
                                                        style="text-decoration: underline;">Click Here for More
                                                        Details</a></strong>
                                            </p></label>
                                        </li>


                                        <li class="form-col-50-left">
                                            <div class="form-col-50-left">
                                                <label>Salary From:
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1">$</span>
                                                        <input class="invoice-fields" type="text" name="minSalary"
                                                            id="minSalary"
                                                            value="<?php echo set_value('minSalary', $listing["minSalary"]); ?>">
                                                        <?php echo form_error('minSalary'); ?>
                                                    </div>
                                            </div>
                                            <div class="form-col-50-right">
                                                <label>Salary To:
                                                    <div class="input-group">
                                                        <span class="input-group-addon" id="basic-addon1">$</span>
                                                        <input class="invoice-fields" type="text" name="maxSalary"
                                                            id="maxSalary"
                                                            value="<?php echo set_value('maxSalary', $listing["maxSalary"]); ?>">
                                                        <?php echo form_error('maxSalary'); ?>
                                                    </div>
                                            </div>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Salary Type:</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="SalaryType" id="SalaryType">
                                                    <option value="">Select Salary Type</option>
                                                    <option value=" per_hour" <?php if ($listing["SalaryType"] == "per_hour") { ?>selected<?php } ?>>per
                                                        hour</option>
                                                    <option value="per_week" <?php if ($listing["SalaryType"] == "per_week") { ?>selected<?php } ?>>per
                                                        week</option>
                                                    <option value="per_month" <?php if ($listing["SalaryType"] == "per_month") { ?>selected<?php } ?>>per
                                                        month</option>
                                                    <option value="per_year" <?php if ($listing["SalaryType"] == "per_year") { ?>selected<?php } ?>>per
                                                        year</option>
                                                </select>
                                            </div>
                                        </li>
                                        <?php if (!empty($screening_questions)) { ?>
                                            <li class="form-col-100">
                                                <label>Screening Questionnaire:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="questionnaire_sid">
                                                        <option value="">Select Screening Questionnaire</option>
                                                        <?php foreach ($screening_questions as $screening_question) { ?>
                                                            <option value="<?= $screening_question['sid'] ?>" <?php if (isset($listing['questionnaire_sid']) && $listing['questionnaire_sid'] == $screening_question['sid']) { ?>
                                                                    selected <?php } ?>>
                                                                <?= $screening_question['caption'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <?php if (!empty($interview_questionnaires)) { ?>
                                            <li class="form-col-100 autoheight">
                                                <?php $selected_questionnaire = (isset($listing['interview_questionnaire_sid']) ? $listing['interview_questionnaire_sid'] : ''); ?>
                                                <label>Interview Questionnaire:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="interview_questionnaire_sid"
                                                        name="interview_questionnaire_sid">
                                                        <option value="">Select Interview Questionnaire</option>
                                                        <?php if (!empty($interview_questionnaires['default'])) { ?>
                                                            <optgroup label="Default">
                                                                <?php foreach ($interview_questionnaires['default'] as $questionnaire) { ?>
                                                                    <?php $default_selected = ($questionnaire['sid'] == $selected_questionnaire ? true : false); ?>
                                                                    <option <?php echo set_select('interview_questionnaire_sid', $questionnaire['sid'], $default_selected); ?>
                                                                        value="<?php echo $questionnaire['sid']; ?>">
                                                                        <?php echo $questionnaire['title']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>
                                                        <?php if (!empty($interview_questionnaires['custom'])) { ?>
                                                            <optgroup label="Custom">
                                                                <?php foreach ($interview_questionnaires['custom'] as $questionnaire) { ?>
                                                                    <?php $default_selected = ($questionnaire['sid'] == $selected_questionnaire ? true : false); ?>
                                                                    <option <?php echo set_select('interview_questionnaire_sid', $questionnaire['sid'], $default_selected); ?>
                                                                        value="<?php echo $questionnaire['sid']; ?>">
                                                                        <?php echo $questionnaire['title']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                        <?php } ?>

                                        <li class="form-col-50-left">
                                            <?php $selected_logo = $listing['pictures']; ?>
                                            <label>Listing Logo:</label>
                                            <select name="select_from_logo" title="Select Listing Logo"
                                                class="selectpicker">
                                                <option value="<?php echo $listing['pictures']; ?>">Select From Existing
                                                </option>
                                                <option data-thumbnail="<?= base_url('assets') ?>/images/NoLogo.jpg"
                                                    value="no_logo">No Logo</option>

                                                <?php if (!empty($all_job_logos)) { ?>
                                                    <?php foreach ($all_job_logos as $key => $job_logo) {
                                                        $default_selected = ($job_logo['pictures'] == $selected_logo ? ' selected="selected"' : ''); ?>
                                                        <option value="<?php echo $job_logo['pictures']; ?>"
                                                            data-thumbnail="<?php echo AWS_S3_BUCKET_URL . $job_logo['pictures']; ?>">
                                                            <?php echo $key; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </li>

                                        <li class="form-col-50-right">
                                            <label>Upload New Listing Logo:</label>
                                            <input type="hidden" value="<?= $listing['pictures'] ?>" name="old_picture">
                                            <input type="hidden" name="delete_image" id="delete_image" value="0">
                                            <div class="upload-file invoice-fields">
                                                <?php if (isset($listing['pictures']) && $listing['pictures'] != "") { ?>
                                                    <div id="remove_image" class="profile-picture">
                                                        <img src="<?php echo AWS_S3_BUCKET_URL . $listing['pictures']; ?>">
                                                    </div>
                                                <?php } ?>
                                                <span class="selected-file" id="name_pictures">No file selected</span>
                                                <input class="customImage" type="file" name="pictures" id="pictures"
                                                    onchange="check_file('pictures')">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                        </li>

                                        <li class="form-col-100" style="margin-bottom: 60px;">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"
                                                            style="padding: 0px;">
                                                            <label for="YouTubeVideo">Select Video:</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="row">
                                                                <?php $video_source = $listing['video_source']; ?>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label
                                                                        class="control control--radio"><?php echo NO_VIDEO; ?>
                                                                        <input type="radio" name="video_source"
                                                                            class="video_source" value="no_video"
                                                                            checked="">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label
                                                                        class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                                        <input type="radio" name="video_source"
                                                                            class="video_source" value="youtube" <?php echo $video_source == 'youtube' && !empty($listing['YouTube_Video']) ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label
                                                                        class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                                        <input type="radio" name="video_source"
                                                                            class="video_source" value="vimeo" <?php echo $video_source == 'vimeo' && !empty($listing['YouTube_Video']) ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                    <label
                                                                        class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                                        <input type="radio" name="video_source"
                                                                            class="video_source" value="uploaded" <?php echo $video_source == 'uploaded' && !empty($listing['YouTube_Video']) ? 'checked="checked"' : ''; ?>>
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight"
                                                id="youtube_vimeo_input">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <?php if (!empty($listing['YouTube_Video']) && $listing['video_source'] == 'youtube') {
                                                            $video_link = 'https://www.youtube.com/watch?v=' . $listing['YouTube_Video'];
                                                        } else if (!empty($listing['YouTube_Video']) && $listing['video_source'] == 'vimeo') {
                                                            $video_link = 'https://vimeo.com/' . $listing['YouTube_Video'];
                                                        } else {
                                                            $video_link = '';
                                                        } ?>
                                                        <label for="YouTube_Video" id="label_youtube">Youtube Video For
                                                            This Job:</label>
                                                        <label for="Vimeo_Video" id="label_vimeo"
                                                            style="display: none">Vimeo Video For This Job:</label>
                                                        <input type="text" name="yt_vm_video_url"
                                                            value="<?php echo $video_link; ?>" class="invoice-fields"
                                                            id="yt_vm_video_url">
                                                        <div id="YouTube_Video_hint" class="video-link"
                                                            style='font-style: italic;'><b>e.g.</b>
                                                            https://www.youtube.com/watch?v=XXXXXXXXXXX </div>
                                                        <div id="Vimeo_Video_hint" class="video-link"
                                                            style='font-style: italic; display: none'><b>e.g.</b>
                                                            https://vimeo.com/XXXXXXX </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight"
                                                id="upload_input" style="display: none">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <?php if (!empty($listing['YouTube_Video']) && $listing['video_source'] == 'uploaded') { ?>
                                                            <input type="hidden" id="pre_upload_video_url"
                                                                name="pre_upload_video_url"
                                                                value="<?php echo $listing['YouTube_Video']; ?>">
                                                        <?php } else { ?>
                                                            <input type="hidden" id="pre_upload_video_url"
                                                                name="pre_upload_video_url" value="">
                                                        <?php } ?>
                                                        <label for="YouTubeVideo">Upload Video For This Job:</label>
                                                        <div class="upload-file invoice-fields">
                                                            <span class="selected-file" id="name_upload_video">No video
                                                                selected</span>
                                                            <input name="upload_video" id="upload_video"
                                                                onchange="upload_video_checker('upload_video')"
                                                                type="file">
                                                            <a href="javascript:;">Choose Video</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="autoheight">
                                            <?php if (!empty($listing['YouTube_Video'])) { ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="padding: 0px;">
                                                    <div class="well well-sm">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <?php if ($listing['video_source'] == 'youtube') { ?>
                                                                <iframe class="embed-responsive-item"
                                                                    src="https://www.youtube.com/embed/<?php echo $listing['YouTube_Video']; ?>"
                                                                    frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                                    allowfullscreen></iframe>
                                                            <?php } elseif ($listing['video_source'] == 'vimeo') { ?>
                                                                <iframe class="embed-responsive-item"
                                                                    src="https://player.vimeo.com/video/<?php echo $listing['YouTube_Video']; ?>"
                                                                    frameborder="0" webkitallowfullscreen mozallowfullscreen
                                                                    allowfullscreen></iframe>
                                                            <?php } else { ?>
                                                                <video controls>
                                                                    <source
                                                                        src="<?php echo base_url() . 'assets/uploaded_videos/' . $listing['YouTube_Video']; ?>"
                                                                        type='video/mp4'>
                                                                </video>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </li>

                                        <?php if (in_array('full_access', $security_details) || in_array('listing_visibility_event', $security_details) || in_array($logged_in_user_sid, $employeesArray)) { ?>
                                            <li class="form-col-100">
                                                <label>Make this listing Only Visible to Following Employees:<span
                                                        class="staric">*</span></label>

                                                <div class="Category_chosen">
                                                    <select data-placeholder="Please Select" multiple="multiple" onchange=""
                                                        name="employees[]" id="employees" class="chosen-select">
                                                        <?php if (empty($employeesArray)) {
                                                            $employeesArray = set_value('employees');
                                                        }

                                                        foreach ($current_employees as $current_employee) { ?>
                                                            <option <?php if (in_array($current_employee['sid'], $employeesArray)) { ?> selected <?php } ?>
                                                                value="<?php echo $current_employee['sid']; ?>">
                                                                <?php echo remakeEmployeeName($current_employee); ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php echo form_error('employees'); ?>
                                            </li>
                                        <?php } else { ?>
                                            <input type="hidden" value="none" name="visibility_perms"
                                                id="visibility_perms" />
                                        <?php } ?>

                                        <?php                                   //if($per_job_listing_charge == 0) { 
                                        ?>
                                        <li class="form-col-50-left autoheight">
                                            <div class="form-group autoheight">
                                                <?php $field_id = 'expiration_date'; ?>
                                                <?php $stored_value = isset($listing[$field_id]) ? DateTime::createFromFormat('Y-m-d H:i:s', $listing['expiration_date'])->format('m/d/Y') : ''; ?>
                                                <?php echo form_label('Expiration Date', $field_id); ?>
                                                <?php echo form_input($field_id, set_value($field_id, $stored_value), 'class="invoice-fields" id="' . $field_id . '" autocomplete="off"'); ?>
                                                <?php echo form_error($field_id); ?>
                                                <div class="help-block text-warning"><b>Caution:</b> Only set an
                                                    expiration date if you want the job to auto deactivate on a
                                                    specified date, Otherwise please leave it blank.</div>
                                            </div>
                                        </li>
                                        <?php                               //} 
                                        ?>


                                        <li class="form-col-100 autoheight send-email">
                                            <label class="control control--checkbox">
                                                Display this job on my Career page
                                                <input id="published_on_career_page" type="checkbox"
                                                    name="published_on_career_page" value="1" <?php echo $listing['published_on_career_page'] == 1 ? 'checked="checked"' : ''; ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </li>

                                        <?php if ($per_job_listing_charge == 0 && $career_site_listings_only == 0) { ?>
                                            <li class="form-col-100 autoheight send-email">
                                                <label class="control control--checkbox">Advertise This Job With The Major
                                                    Job Aggregators
                                                    <!--: <a rel="popover" data-img="//placehold.it/100x50">Major job Aggregators</a>-->
                                                    <input type="checkbox" name="organic_feed" value="1" <?php if ($listing['organic_feed'] == 1) { ?> checked <?php } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </li>
                                        <?php } else if (($per_job_listing_charge == 1 && $career_site_listings_only == 0) || ($per_job_listing_charge == 1 && $career_site_listings_only == 1)) { ?>
                                                <li class="form-col-100">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"
                                                                    style="padding: 0px;">
                                                                    <label>Sponsor This Job With The Major Job
                                                                        Aggregators:</label>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <label class="control control--radio">No
                                                                                <input type="radio" name="sponsor_this_job"
                                                                                    class="sponsor_this_job"
                                                                                    value="not_required" checked="">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                            <label class="control control--radio">Yes
                                                                                <input type="radio" name="sponsor_this_job"
                                                                                    class="sponsor_this_job" value="sponsor_it">
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <div class="produt-block" id="sponsor_container" style="display: none;">
                                                    <header class="form-col-100">
                                                        <h2 class="section-title">Products</h2>
                                                    </header>

                                                    <div class="pre-purchased-products advertising-boxes">
                                                    <?php if (!empty($purchasedProducts)) {
                                                        foreach ($purchasedProducts as $product) { ?>
                                                                <article class="purchased-product">
                                                                    <input style="display: none;" name="pay_per_job_details"
                                                                        id="ppj_<?php echo $product['product_sid']; ?>"
                                                                        class="product-checkbox"
                                                                        value="<?php echo $product['product_sid']; ?>" type="radio" />
                                                                    <p class="remaining-qty num-of-days">No of Days:
                                                                    <?php echo $product['no_of_days']; ?>
                                                                    </p>
                                                                    <p class="remaining-qty">Remaining Qty:
                                                                    <?php echo $product['remaining_qty']; ?>
                                                                    </p>
                                                                    <h2 class="post-title"><?php echo $product['name']; ?></h2>
                                                                    <figure><img
                                                                            src="<?php echo $product['product_image'] != NULL ? AWS_S3_BUCKET_URL . $product['product_image'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg'; ?>"
                                                                            alt="Category images"></figure>

                                                                    <div class="button-panel incart-btn-fixed">
                                                                        <div class="cart-btns">
                                                                            <input style="position: relative; margin: auto; left: auto;"
                                                                                type="button"
                                                                                class="site-btn outCart outCart_<?php echo $product['product_sid']; ?>"
                                                                                id="<?php echo $product['product_sid']; ?>"
                                                                                onclick="add_to_cart(this.id, 'out')" value="Sponsor">
                                                                            <a class="submit-btn inCart" style="display: none"
                                                                                id="inCart_<?php echo $product['product_sid']; ?>"
                                                                                href="javascript:;">Sponsor</a>
                                                                        </div>
                                                                    </div>
                                                                </article>
                                                        <?php }
                                                    } ?>

                                                    <?php if (!empty($notPurchasedProducts)) {
                                                        foreach ($notPurchasedProducts as $product) { ?>
                                                                <article class="purchased-product">
                                                                    <p class="remaining-qty num-of-days">No of Days:
                                                                    <?php echo $product['expiry_days']; ?>
                                                                    </p>
                                                                    <p class="remaining-qty">Purchased Qty: 0</p>
                                                                    <h2 class="post-title"><?php echo $product['name']; ?></h2>
                                                                    <figure><img
                                                                            src="<?php echo $product['product_image'] != NULL ? AWS_S3_BUCKET_URL . $product['product_image'] : AWS_S3_BUCKET_URL . 'default_pic-ySWxT.jpg'; ?>"
                                                                            alt="Category images" /></figure>
                                                                    <div class="count-box">
                                                                        $<?php echo $product['price'];
                                                                        if ($product['daily'] == 1)
                                                                            echo '/day'; ?></div>

                                                                    <div class="button-panel incart-btn-fixed">
                                                                        <div class="cart-btns">
                                                                            <input style="display: none;" type="radio"
                                                                                name="pay_per_job_details"
                                                                                id="ppj_<?php echo $product['sid']; ?>"
                                                                                value="<?php echo $product['sid']; ?>">
                                                                            <input style="position: relative; margin: auto; left: auto;"
                                                                                type="button"
                                                                                class="site-btn outCart outCart_<?php echo $product['sid']; ?>"
                                                                                id="<?php echo $product['sid']; ?>"
                                                                                onclick="add_to_cart(this.id, 'cart')"
                                                                                value="Add to Cart">
                                                                            <a class="submit-btn inCart" style="display: none"
                                                                                id="inCart_<?php echo $product['sid']; ?>"
                                                                                href="javascript:;">Sponsor</a>
                                                                        </div>
                                                                    </div>
                                                                </article>
                                                        <?php }
                                                    } ?>
                                                    </div>

                                                    <div class="universal-form-style-v2 payment-area">
                                                        <div id="free_no_payment_mini">
                                                            <div class="form-col-100">
                                                                <div id="free_checkout_mini_cart"></div>
                                                                <div id="maincartcouponarea_mini_free"></div>
                                                                <div class="col-xs-12">
                                                                    <div id="free_spinner" class="spinner hide"><i
                                                                            class="fa fa-refresh fa-spin"></i> Processing...
                                                                    </div>
                                                                    <input type="hidden" id="is_free_checkout_mini"
                                                                        name="is_free_checkout_mini" value="0" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="cr_card_payment_mini" style="display: none;">
                                                            <ul>
                                                                <div class="row">
                                                                    <div class="form-col-100">
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <li>
                                                                                <label>Payment with</label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select name="p_with_main" id="p_with_mini"
                                                                                        onchange="payment_ccd(this.value)"
                                                                                        class="invoice-fields">
                                                                                        <option value="new">Add new credit card
                                                                                        </option>
                                                                                        <?php
                                                                                        $get_data = $this->session->userdata('logged_in');
                                                                                        $cards = db_get_card_details($get_data['company_detail']['sid']);

                                                                                        if (!empty($cards)) {
                                                                                            foreach ($cards as $card) {
                                                                                                echo '<option value="' . $card['sid'] . '">' . $card['number'] . ' - ' . $card['type'] . ' ';
                                                                                                echo ($card['is_default'] == 1) ? '(Default)' : '';
                                                                                                echo '</option>';
                                                                                            }
                                                                                        } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </li>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <div class="payment-method"><img
                                                                                    src="<?= base_url() ?>assets/images/payment-img.jpg">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-col-100 savedccd">
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <span>(<span class="staric hint-str">*</span>)
                                                                                Denotes required fields</span>
                                                                        </div>
                                                                    </div>

                                                                    <input type="hidden" name="process_credit_card"
                                                                        id="process_credit_card" value="1">
                                                                    <div id="novalidatemain"></div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                                                        <header class="payment-heading">
                                                                            <h2>Credit Card Details</h2>
                                                                        </header>
                                                                        <div class="form-col-100">
                                                                            <li>
                                                                                <label>Number<span
                                                                                        class="staric">*</span></label>
                                                                                <input id="cc_sponsor_card_no" type="text"
                                                                                    name="cc_card_no" value=""
                                                                                    class="invoice-fields">
                                                                                <div id="cc_card_no_response"></div>
                                                                            </li>
                                                                        </div>
                                                                        <div class="form-col-100">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                    <li>
                                                                                        <label>Expiration Month<span
                                                                                                class="staric">*</span></label>

                                                                                        <div class="hr-select-dropdown">
                                                                                            <select id="cc_sponsor_expire_month"
                                                                                                name="cc_expire_month"
                                                                                                class="invoice-fields">
                                                                                                <option value=""></option>
                                                                                                <option value="01">01</option>
                                                                                                <option value="02">02</option>
                                                                                                <option value="03">03</option>
                                                                                                <option value="04">04</option>
                                                                                                <option value="05">05</option>
                                                                                                <option value="06">06</option>
                                                                                                <option value="07">07</option>
                                                                                                <option value="08">08</option>
                                                                                                <option value="09">09</option>
                                                                                                <option value="10">10</option>
                                                                                                <option value="11">11</option>
                                                                                                <option value="12">12</option>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div id="cc_expire_month_response">
                                                                                        </div>
                                                                                    </li>
                                                                                </div>
                                                                                <div
                                                                                    class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                                    <li>
                                                                                        <label>Year<span
                                                                                                class="staric">*</span></label>

                                                                                        <div class="hr-select-dropdown">
                                                                                        <?php $current_year = date('Y'); ?>
                                                                                            <select id="cc_sponsor_expire_year"
                                                                                                name="cc_expire_year"
                                                                                                class="invoice-fields">
                                                                                                <option value=""></option>
                                                                                            <?php for ($i = $current_year; $i <= $current_year + 10; $i++) { ?>
                                                                                                    <option
                                                                                                        value="<?php echo $i; ?>">
                                                                                                    <?php echo $i; ?>
                                                                                                    </option>
                                                                                            <?php } ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div id="cc_expire_year_response"></div>
                                                                                    </li>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 savedccd">
                                                                        <header class="payment-heading">
                                                                            <h2>&nbsp;</h2>
                                                                        </header>
                                                                        <div class="form-col-100">
                                                                            <li>
                                                                                <label>Type<span class="staric">*</span></label>
                                                                                <div class="hr-select-dropdown">
                                                                                    <select id="cc_sponsor_type" name="cc_type"
                                                                                        class="invoice-fields">
                                                                                        <option value=""></option>
                                                                                        <option value="visa">Visa</option>
                                                                                        <option value="mastercard">Mastercard
                                                                                        </option>
                                                                                        <option value="discover">Discover
                                                                                        </option>
                                                                                        <option value="amex">Amex</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div id="cc_type_response"></div>
                                                                            </li>
                                                                        </div>
                                                                        <div class="form-col-100">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col-lg-7 col-md-7 col-xs-12 col-sm-6">
                                                                                    <li>
                                                                                        <label class="small-case">ccv</label>
                                                                                        <input id="cc_ccv" type="text"
                                                                                            name="cc_ccv" value=""
                                                                                            class="invoice-fields">
                                                                                    </li>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!--                                                                    <div class="form-col-100 autoheight">
                                                                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                                                                            <div class="checkbox-field savedccd">
                                                                                                                                                <input type="checkbox" name="cc_future_payment" id="future-payment">
                                                                                                                                                <label for="future-payment">Save this card for future payment</label>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>-->
                                                                </div>
                                                                <input type="hidden" id="cc_validate" value="1">
                                                                <div class="modal-footer">
                                                                    <div class="row">
                                                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-6">
                                                                            <div class="media-content">
                                                                                <h3 class="details-title">Secure payment</h3>
                                                                                <p class="details-desc">This is a secure 256-bit
                                                                                    SSL encrypted payment</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                            <span class="payment-secured">powered by
                                                                                <strong>Paypal</strong></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                        <input type="hidden" id="ppj_purchased" value="1" />
                                        <input id="listing_status" type="hidden" name="listing_status" value="1" />
                                        <li class="form-col-100 autoheight submit_action_btn">
                                            <input type="submit" value="Clone" onclick="return validate_form()"
                                                class="submit-btn clone-sponsor">
                                            <input type="button" value="Cancel" class="submit-btn btn-cancel"
                                                onClick="document.location.href = '<?= base_url('my_listings') ?>'" />
                                        </li>

                                        <!--                                        <div class="submit_action_btn">-->
                                        <!--                                            <input type="button" value="Publish" onclick="validate_form('save');" class="submit-btn">-->
                                        <!--                                        </div>-->

                                        <div class="form-col-100 autoheight submit_action_paypal"
                                            style="display: none;">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div id="maincartcouponarea_mini"></div>
                                                <div class="checkout-popup-cart"></div>
                                                <div id="cc_spinner" class="spinner hide"><i
                                                        class="fa fa-refresh fa-spin"></i> Processing... </div>
                                                <div class="btn-panel">
                                                    <input type="button" value="Cancel" class="submit-btn btn-cancel"
                                                        onClick="document.location.href = '<?= base_url('my_listings') ?>'" />
                                                    <input type="button" id="cc_sponsor_send"
                                                        value="Confirm payment & Sponsor" class="submit-btn">
                                                </div>
                                                <p id="checkout_error_message"></p>
                                            </div>
                                        </div>
                                    </form>
                                </ul>
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
<script language="JavaScript" type="text/javascript"
    src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript"
    src="<?= base_url('assets') ?>/bootstrap_select/js2/bootstrap-select.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        $('#expiration_date').datepicker();
        $('#youtube_vimeo_input').hide();
        $('#upload_input').hide();
        $('.video_source:checked').trigger('click');

        $('#select_template').on('change', function () {
            var template_sid = $(this).val();
            var selectedTemplate = $('#template_' + template_sid);
            var sDescription = $(selectedTemplate).attr('data-description');
            var sRequirements = $(selectedTemplate).attr('data-requirements');

            if (template_sid != '') {
                CKEDITOR.instances.JobDescription.setData(sDescription);
                CKEDITOR.instances.JobRequirements.setData(sRequirements);
            } else {
                CKEDITOR.instances.JobDescription.setData('');
                CKEDITOR.instances.JobRequirements.setData('');
            }
        });

        // Initiate with custom caret icon
        $('select.selectpicker').selectpicker({
            caretIcon: 'glyphicon glyphicon-menu-down'
        });
    });

    $('a[rel=popover]').popover({
        html: true,
        trigger: 'hover',
        placement: 'bottom',
        content: function () {
            return '<img src="<?php echo base_url('assets/images/logo-5-small.jpg') ?>" />\n\<img src="<?php echo base_url('assets/images/logo-6-small.jpg') ?>" />';
        }
    });

    CKEDITOR.replace('JobDescription');
    CKEDITOR.replace('JobRequirements');

    function image_remove(id) {
        url = "<?= base_url() ?>job_listings/job_task";
        alertify.confirm('Confirmation', "Are you sure you want to delete this picture?",
            function () {
                $('#remove_image').hide();
                $.post(url, {
                    action: 'delete_img',
                    sid: id
                })
                    .done(function (data) {
                        document.getElementById("delete_image").value = '1';
                    });
                alertify.success('Image removed.');
            },
            function () {

            });
    }

    function getStates(val, states) {
        var html = '';

        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';

            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }

            $('#state').html(html);
            $('#state').trigger('change');
        }
    }

    function validate_form() {
        var allow_validate = true;

        if (allow_validate) {
            $("#employers_add_job").validate({
                ignore: ":hidden:not(select)",
                rules: {
                    Title: {
                        required: true,
                        <?php echo ($job_title_special_chars == 1 ? 'pattern: /^[a-zA-Z0-9\#\$\&\(\)\-\/\%\s]+$/' : 'pattern: /^[a-zA-Z0-9\- ]+$/'); ?>
                    },
                    Location_Country: {
                        required: true,
                    },
                    Location_State: {
                        required: true,
                    },
                    Location_City: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    Location_ZipCode: {
                        required: true,
                        pattern: /^[0-9a-zA-Z\- ]+$/
                    }
                },
                messages: {
                    Title: {
                        required: 'Job title is required',
                        <?php echo ($job_title_special_chars == 1 ? 'pattern: "Only Alphabets Numerals Space and # $ & ( ) - / % are Allowed."' : 'pattern: "Alphabets Numerals space and - are allowed!"'); ?>
                    },
                    Location_Country: {
                        required: 'Country name is required',
                    },
                    Location_State: {
                        required: 'State name is required',
                    },
                    Location_City: {
                        required: 'City name is required',
                        pattern: 'Letters, numbers, and dashes only please',
                    },
                    Location_ZipCode: {
                        required: 'Zip Code is required',
                        pattern: 'Numeric values only'
                    }
                },
                submitHandler: function (form) {
                    var sponsor_this_job = $('input[name="sponsor_this_job"]:checked').val();
                    console.log(sponsor_this_job);

                    if (sponsor_this_job == 'sponsor_it') {
                        var ppj_id = $("input[name='pay_per_job_details']:checked").val();

                        if (ppj_id === undefined) {
                            alertify.alert('Error! Sponsor This Job Error', 'Please Select Pay Per Job Product to Proceed!');
                            return false;
                        } else {
                            console.log(ppj_id);
                        }


                        if ($("#ppj_purchased").val() == 0) {
                            alertify.alert('Error! Sponsor This Job Error', 'Please Purchase The Product to Proceed!');
                            return false;
                        }
                    }
                    //                    console.log(ppj_id);
                    //                    return false;

                    var items_length = $('#Category :selected').length;

                    if (items_length == 0) {
                        alertify.alert('Error! Job Category Missing', "Job Category cannot be Empty");
                        return false;
                    }

                    var text_pass = $.trim(CKEDITOR.instances.JobDescription.getData());

                    if (text_pass.length === 0) {
                        alertify.alert('Error! Job Description Missing', "Job Description cannot be Empty");
                        return false;
                    }

                    // //
                    let minSalary = $("#minSalary").val()
                    if (minSalary && minSalary.match(/[^0-9.,]/) !== null) {
                        alertify.alert(
                            "Error! Salary from can only be a number."
                        );
                        return false;
                    }

                    let maxSalary = $("#maxSalary").val()
                    if (maxSalary && maxSalary.match(/[^0-9.,]/) !== null) {
                        alertify.alert(
                            "Error! Salary to can only be a number."
                        );
                        return false;
                    }

                    // //
                    // let minSalary = $("#minSalary").val()
                    // if (!minSalary) {
                    //     return alertify.alert(
                    //         "Error! Please add a minimum salary."
                    //     );
                    // }

                    // let salaryType = $("#SalaryType option:selected").val()
                    // if (!salaryType) {
                    //     return alertify.alert(
                    //         "Error! Please select a salary type."
                    //     );
                    // }

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
                                    success: function (data) {
                                        if (data == false) {
                                            alertify.error('Not a Valid Vimeo URL');
                                            flag = 0;
                                            return false;
                                        } else {
                                            flag = 1;
                                        }
                                    },
                                    error: function (data) { }
                                });
                            } else {
                                flag = 0;
                                alertify.error('Please add valid vimeo video link.');
                                return false;
                            }
                        } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {
                            var old_uploaded_video = $('#pre_upload_video_url').val();

                            if (old_uploaded_video != '') {
                                flag = 1;
                            } else {
                                var file = upload_video_checker('upload_video');

                                if (file == false) {
                                    flag = 0;
                                    return false;
                                } else {
                                    flag = 1;
                                }
                            }
                        }

                        if (flag == 1) {
                            $('#my_loader').show();
                            form.submit();
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }

                    //                    $('#my_loader').show();
                    //                    form.submit();
                }
            });
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

    function multiselectbox() {
        var items_length = $('#Category :selected').length;
        var total_allowed = parseInt($('#choiceLimit').html());
        var total_left = total_allowed - items_length;

        if (total_left < 0) {
            total_left = 0;
        }

        $('#choicelimitavailable').html(total_left);
        var no_error = 0;
        var i = 1;

        if (items_length > total_allowed) {
            $('#Category option:selected').each(function () {
                if (i > total_allowed) {
                    $(this).removeAttr("selected");
                    no_error = 1;
                }
                i++;
            });
        }

        if (no_error) {
            alertify.alert("You can only select " + total_allowed + " values");
        }
    }

    $('.video_source').on('click', function () {
        var selected = $(this).val();

        if (selected == 'youtube') {
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#YouTube_Video_hint').show();
            $('#Vimeo_Video_hint').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#YouTube_Video_hint').hide();
            $('#Vimeo_Video_hint').show();
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

    function upload_video_checker(val) {
        var fileName = $("#" + val).val();

        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();

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

    $('.sponsor_this_job').on('click', function () {
        var selected = $(this).val();

        if (selected == 'not_required') {
            $('#sponsor_container').hide();
            $(".submit_action_btn").css('display', 'block');
            $(".submit_action_paypal").css('display', 'none');
            $(".sponsor_container").css('display', 'none');
            $("#cr_card_payment_mini").css('display', 'none');
            $("#ppj_purchased").val(0);
            $('.clone-sponsor').val('Clone');
        } else if (selected == 'sponsor_it') {
            $('#sponsor_container').show();
            $(".outCart").css('display', 'block');
            $(".inCart").css('display', 'none');
            $('.clone-sponsor').val('Sponsor');
        }
    });

    function add_to_cart(product_id, status) {
        $(".outCart").css('display', 'block');
        $(".outCart_" + product_id).css('display', 'none');
        $(".inCart").css('display', 'none');
        $("#inCart_" + product_id).css('display', 'block');
        $("#ppj_" + product_id).attr('checked', 'checked').trigger('click');
        var id = $("input[name='pay_per_job_details']:checked").val();

        if (status == 'cart') {
            $("#cr_card_payment_mini").css('display', 'block');
            $(".submit_action_btn").css('display', 'none');
            $(".submit_action_paypal").css('display', 'block');
            $("#ppj_purchased").val(0);
        } else {
            $("#cr_card_payment_mini").css('display', 'none');
            $(".submit_action_btn").css('display', 'block');
            $(".submit_action_paypal").css('display', 'none');
            $("#ppj_purchased").val(1);
        }
    }

    $('#cc_sponsor_send').on('click', function () {

        $("#employers_add_job").validate({
            ignore: ":hidden:not(select)",
            rules: {
                Title: {
                    required: true,
                    <?php echo ($job_title_special_chars == 1 ? 'pattern: /^[a-zA-Z0-9\#\$\&\(\)\-\/\%\s]+$/' : 'pattern: /^[a-zA-Z0-9\- ]+$/'); ?>
                },
                Location_Country: {
                    required: true,
                },
                Location_State: {
                    required: true,
                },
                Location_City: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9a-zA-Z\- ]+$/
                }
                //                salary: {
                //                    pattern: /^[0-9\-]+$/
                //                }
            },
            messages: {
                Title: {
                    required: 'Job title is required',
                    <?php echo ($job_title_special_chars == 1 ? 'pattern: "Only Alphabets Numerals Space and # $ & ( ) - / % are Allowed."' : 'pattern: "Alphabets Numerals space and - are allowed!"'); ?>
                },
                Location_Country: {
                    required: 'Country name is required',
                },
                Location_State: {
                    required: 'State name is required',
                },
                Location_City: {
                    required: 'City name is required',
                    pattern: 'Letters, numbers, and dashes only please',
                },
                Location_ZipCode: {
                    pattern: 'Numeric values only'
                }
                //                salary: {
                //                    pattern: 'Numeric values only'
                //                }
            }
        });
        var cc_validate = $("#cc_validate").val();
        var cc_card_no = document.getElementById("cc_sponsor_card_no");
        var cc_type = document.getElementById("cc_sponsor_type");
        var cc_expire_month = document.getElementById("cc_sponsor_expire_month");
        var cc_expire_year = document.getElementById("cc_sponsor_expire_year");
        var ppj_id = $("input[name='pay_per_job_details']:checked").val();
        var payment_type = $("#p_with_mini :selected").val();
        var cc_ccv = document.getElementById("cc_ccv");
        var cc_card_no_error = true;
        var cc_type_error = true;
        var cc_expire_month_error = true;
        var cc_expire_year_error = true;
        var invalid_card = false;
        var is_error = false;
        var items_length = $('#Category :selected').length;

        if (items_length == 0) {
            alertify.alert('Error! Job Category Missing', "Job Category cannot be Empty");
            is_error = true;
        }

        var text_pass = $.trim(CKEDITOR.instances.JobDescription.getData());

        if (text_pass.length === 0) {
            alertify.alert('Error! Job Description Missing', "Job Description cannot be Empty");
            is_error = true;
        }

        if (cc_validate == 1) {
            if (isEmpty(cc_card_no.value)) {
                $('#cc_sponsor_card_no').addClass('error');
                $('#cc_card_no_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Credit Card No Required!</p>');
                cc_card_no_error = true;
            } else {
                cc_card_no_error = false;
            }

            cc_card_no.addEventListener("input", function () {
                if ((this.value).length < 12 || (this.value).length > 19) {
                    $('#cc_sponsor_card_no').addClass('error');
                    $('#cc_card_no_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Invalid Credit Card No!</p>');
                    cc_card_no_error = true;
                } else {
                    $('#cc_sponsor_card_no').removeClass('warning');
                    $('#cc_card_no_response').html('');
                    cc_card_no_error = false;
                }
            });

            if (isEmpty(cc_type.value)) {
                $('#cc_sponsor_type').addClass('error');
                $('#cc_type_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Credit Card Type Required!</p>');
                cc_type_error = true;
            } else {
                cc_type_error = false;
            }

            cc_type.addEventListener("input", function () {
                if ((this.value).length < 1) {
                    $('#cc_sponsor_type').addClass('error');
                    $('#cc_type_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Credit Card Type Required!</p>');
                    cc_type_error = true;
                } else {
                    $('#cc_sponsor_type').removeClass('warning');
                    $('#cc_type_response').html('');
                    cc_type_error = false;
                }
            });

            if (isEmpty(cc_expire_month.value)) {
                $('#cc_sponsor_expire_month').addClass('error');
                $('#cc_expire_month_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Expiration Month Required!</p>');
                cc_expire_month_error = true;
            } else {
                cc_expire_month_error = false;
            }

            cc_expire_month.addEventListener("input", function () {
                if ((this.value).length < 1) {
                    $('#cc_sponsor_expire_month').addClass('error');
                    $('#cc_expire_month_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Expiration Month Required!</p>');
                    cc_expire_month_error = true;
                } else {
                    $('#cc_sponsor_expire_month').removeClass('warning');
                    $('#cc_expire_month_response').html('');
                    cc_expire_month_error = false;
                }
            });

            if (isEmpty(cc_expire_year.value)) {
                $('#cc_sponsor_expire_year').addClass('error');
                $('#cc_expire_year_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Expiration Year Required!</p>');
                cc_expire_year_error = true;
            } else {
                cc_expire_year_error = false;
            }

            cc_expire_year.addEventListener("input", function () {
                if ((this.value).length < 1) {
                    $('#cc_sponsor_expire_year').addClass('error');
                    $('#cc_expire_year_response').html('<p class="error"><i class="fa fa-exclamation-circle"></i> Expiration Year Required!</p>');
                    cc_expire_year_error = true;
                } else {
                    $('#cc_sponsor_expire_year').removeClass('warning');
                    $('#cc_expire_year_response').html('');
                    cc_expire_year_error = false;
                }
            });


            if (cc_expire_year_error == false && cc_expire_month_error == false && cc_type_error == false && cc_card_no_error == false) {
                invalid_card = false;
            } else {
                invalid_card = true;
            }
        } else {
            invalid_card = false;
        }

        if (!invalid_card && !is_error) {
            $('#cc_sponsor_send').prop('disabled', true);
            $('#cc_sponsor_send').addClass("disabled-btn");
            $('#cc_spinner').removeClass("hide");
            $.ajax({
                url: "<?= base_url() ?>misc/pay_per_job",
                type: "POST",
                data: '&cc_card_no=' + cc_card_no.value + '&cc_type=' + cc_type.value + '&cc_expire_month=' + cc_expire_month.value + '&cc_expire_year=' + cc_expire_year.value + '&cc_ccv=' + cc_ccv.value + '&ppj_id=' + ppj_id + '&payment_type=' + payment_type,
                success: function (response) {
                    response = JSON.parse(response);
                    $('#cc_sponsor_send').prop('disabled', false);
                    $('#cc_sponsor_send').removeClass("disabled-btn");
                    $('#cc_spinner').addClass("hide");
                    console.log(response);
                    if (response[0] == 'error') {
                        error_coupon = response[2];
                        error_card = response[3];

                        if (error_card != 'no_error') {
                            $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>' + error_card + '</div></div>');
                        } else {
                            $('#checkout_error_message').html('');
                        }
                    } else {
                        $('#checkout_error_message').html('');
                        $("#submit_action").val('save');
                        $("#employers_add_job").submit();
                    }
                },
                error: function (request, status, error) {
                    $('#checkout_error_message').html('<div class="flash_error_message"><div class="alert alert-info alert-dismissible error" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Error: Your card could not be processed, Please check card details and try again!</div></div>');
                    $('#checkout_error_message_coupon').html('');
                    $('#cc_sponsor_send').prop('disabled', false);
                    $('#cc_sponsor_send').removeClass("disabled-btn");
                    $('#cc_spinner').addClass("hide");
                }
            });
        } else {
            console.log('form validation error');
            console.log('fix the bug: ' + invalid_card);
        }

    });

    function isEmpty(str) {
        return !str.replace(/\s+/, '').length;
    }

    function payment_ccd(val) {
        if (val == 'new') {
            $('.savedccd').show();
            $("#cc_validate").val(1);
        } else {
            $('.savedccd').hide();
            $("#cc_validate").val(0);
        }
    }
</script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $("body").ready(function () {
        // var mylimit = parseInt($('#choiceLimit').html());
        // multiselectbox();
        // $(".chosen-select").chosen({max_selected_options: mylimit});
        // $(".chosen-select").bind("liszt:maxselected", function () {});
        // $(".chosen-select").chosen().change(function () {});

        $('#employees').select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            tags: false
        });
        $("#Category").select2({
            closeOnSelect: false,
            allowHtml: true,
            allowClear: true,
            tags: false
        });

        var copiedCategories = $("#Category").val();
        copiedCategories = copiedCategories == null ? 0 : copiedCategories.length;
        $('#choicelimitavailable').html(parseInt($('#choicelimitavailable').html()) - copiedCategories);

        $("#Category").on('select2:select', function (e) {
            var items_length = $('#Category :selected').length;
            var total_allowed = parseInt($('#choiceLimit').html());
            var total_left = total_allowed - items_length;


            if (total_left <= 0) {
                total_left = 0;
            }


            $('#choicelimitavailable').html(total_left);
            var no_error = 0;
            var i = 1;

            if (items_length > total_allowed) {
                var el = $('#Category').val();
                el.pop();
                $('#Category').select2('val', el);
                no_error = 1;
            }

            if (no_error) {
                alertify.alert("You can only select " + total_allowed + " values");
            }
        })

        $("#Category").on('select2:unselect', function (e) {
            $('#choicelimitavailable').text(parseInt($('#choiceLimit').text()) - ($("#Category").val() != null ? $("#Category").val().length : 0));
        })
    });
</script>


<style>
    .select2-container {
        min-width: 400px;
    }

    .select2-results__option {
        padding-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option:before {
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option[aria-selected=true]:before {
        font-family: fontAwesome;
        content: "\f00c";
        color: #fff;
        background-color: #81b431;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }

    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }

    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #81b431;
        border-width: 2px;
    }

    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }

    .select2-container--open .select2-dropdown--below {

        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    }

    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }

    /* select with icons badges single*/
    .select-icon .select2-selection__placeholder .badge {
        display: none;
    }

    .select-icon .placeholder {
        display: none;
    }

    .select-icon .select2-results__option:before,
    .select-icon .select2-results__option[aria-selected=true]:before {
        display: none !important;
        /* content: "" !important; */
    }

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }
</style>

<script>
    //$('#select_template').select2();
</script>

<style>
    .csSelect2WithBg .select2-selection__rendered {
        background-color: #fd7a2a;
        color: #fff !important;
    }
</style>

<?php $this->load->view('2022/modals/salary'); ?>