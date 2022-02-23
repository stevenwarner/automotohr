<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?></span>
                        </div>
                        <div class="job-title-text">                
                            <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                        </div>
                    </div>
                    <div class="form-wrp">
                        <?php echo form_open_multipart('', array('id' => 'myprofile')); ?>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 pull-right">
                                    <div class="img-thumbnail emply-picture pull-right">
                                        <p class="image-preview-text">Company Logo</p> 
                                        <?php if (!empty($company['Logo'])) { ?>
                                            <img src="<?php echo AWS_S3_BUCKET_URL . $company['Logo']; ?>" class="img-responsive img-rounded"> 
                                        <?php } else { ?>
                                            <img src="<?php echo AWS_S3_BUCKET_URL; ?>default_pic-ySWxT.jpg" class="img-responsive img-rounded"> 
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php echo form_label('Company Name <span class="hr-required">*</span>', 'CompanyName'); ?>
                                                <?php echo form_input('CompanyName', set_value('CompanyName', $company['CompanyName']), 'class="form-control"'); ?>
                                                <?php echo form_error('CompanyName'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <?php echo form_label('Contact Person <span class="hr-required">*</span>', 'ContactName'); ?>
                                                <?php echo form_input('ContactName', set_value('ContactName', $company['ContactName']), 'class="form-control"'); ?>
                                                <?php echo form_error('ContactName'); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight choose-file-wrp">
                                                <label>Company Logo:</label>
                                                <input name="Logo" id="logo" type="file" class="form-control choose-file" data-placeholder="No file"/>
                                                <?php echo form_error('Logo'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group">
                                <?php echo form_label('Company E-Mail', 'email'); ?>
                                <input type="email" name="email" value="<?php echo set_value('email', $company['email']); ?>" class="form-control">
                                <?php echo form_error('email'); ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <div class="form-group">
                                <?php echo form_label('Company Website', 'WebSite'); ?>									
                                <?php echo form_input('WebSite', set_value('WebSite', $company['WebSite']), 'class="form-control"'); ?>
                                <?php echo form_error('WebSite'); ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <?php echo form_label('Company EIN Number  <span class="hr-required">*</span>', 'ssn'); ?>									
                                <?php echo form_input('ssn', set_value('ssn', $company['ssn']), 'class="form-control"'); ?>
                                <?php echo form_error('ssn'); ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <?php echo form_label('Company Description', 'CompanyDescription'); ?>
                                <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60" ><?php echo set_value('CompanyDescription', $company['CompanyDescription']); ?></textarea>
                                <?php echo form_error('CompanyDescription'); ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Enable Company Logo <small class="help_text">You can show or hide Company logo.</small>
                                    <input class="select-domain" type="checkbox" name="enable_company_logo" value="1" <?php if ($portal['enable_company_logo'] == 1) { echo "checked"; } ?>>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Application Tracking System Job Search<small class="help_text">Enable this option to only list active jobs in search filter.</small>
                                    <input class="select-domain" type="checkbox" name="ats_active_job_flag" value="1" <?php if ($portal['ats_active_job_flag'] == 1) { echo "checked"; } ?>>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Display Job Location <small class="help_text">You can show or hide job location with Job Title.</small>
                                    <input class="select-domain" type="checkbox" name="job_title_location" value="1" <?php if ($portal['job_title_location'] == 1) { echo "checked"; } ?>>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Enable Contact Us Page <small class="help_text">You can show or hide Contact Us page at Career Website.</small>
                                    <input class="select-domain" type="checkbox" name="contact_us_page" value="1" <?php if ($portal['contact_us_page'] == 1) { echo "checked"; } ?>>
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Mandatory Resume <small class="help_text">Make resume mandatory for job applicants. (Deluxe Theme Only)</small>
                                    <input class="" type="checkbox" id="is_resume_mandatory" name="is_resume_mandatory" value="1" <?php echo ($portal['is_resume_mandatory'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Enable E.E.O. Form <small class="help_text">Enable "EQUAL EMPLOYMENT OPPORTUNITY" form on Apply Now Dialog Box</small>
                                    <input class="" type="checkbox" id="eeo_form_status" name="eeo_form_status" value="1" <?php echo ($portal['eeo_form_status'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Enable Print Button <small class="help_text">Enable Print option for "APPLICANT FULL EMPLOYMENT APPLICATION" Form</small>
                                    <input class="" type="checkbox" id="full_employment_app_print" name="full_employment_app_print" value="1" <?php echo ($portal['full_employment_app_print'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Allow Special Characters <small class="help_text">Allow following Characters # $ & ( ) - / % to be used in job Titles</small>
                                    <input class="" type="checkbox" id="job_title_special_chars" name="job_title_special_chars" value="1" <?php echo ($portal['job_title_special_chars'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <?php if (check_company_ems_status($this->session->userdata('logged_in')["company_detail"]["sid"])) { ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Enable E.E.O Form for On-boarding <small class="help_text">Enable "E.E.O" form at On-Boarding</small>
                                        <input class="" type="checkbox" id="onboarding_eeo_form_status" name="onboarding_eeo_form_status" value="1" <?php echo (isset($onboarding_eeo_form_status) && $onboarding_eeo_form_status == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Enable Safety data sheet at On-Boarding<small class="help_text">Enable Safety Sheets</small>
                                        <input class="" type="checkbox" id="safety_sheet" name="safety_sheet" value="1" <?php echo (isset($safety_sheet) && $safety_sheet == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Enable E.E.O. Form For Profile <small class="help_text">Enable "EQUAL EMPLOYMENT OPPORTUNITY" form in Employee and Applicant Profile Right Nav Bar</small>
                                    <input class="" type="checkbox" id="eeo_form_profile_status" name="eeo_form_profile_status" value="1" <?php echo ($portal['eeo_form_profile_status'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <h3>Full Employment Application <i class="fa fa-question-circle-o" aria-hidden="true"></i></h3>
                                <p>Make the following selected options mandatory on the full employment form.</p>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Send To Applicant <small class="help_text">Send a Full employment application Form when a new applicant applies on the job.</small>
                                    <input class="" type="checkbox" id="FEA_sent_to_an_applicant" name="FEA_sent_to_an_applicant" value="1" <?php echo (isset($portal['sent_to_an_applicant']) && $portal['sent_to_an_applicant'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Make Social Security Number Mandatory <small class="help_text">Make Social Security Number Mandatory</small>
                                    <input class="" type="checkbox" id="onboarding_ssn_status" name="onboarding_ssn_status" value="1" <?php echo (isset($portal['ssn_required']) && $portal['ssn_required'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group autoheight">
                                <label class="control control--checkbox">
                                    Make Date Of Birth Mandatory<small class="help_text">Make Date Of Birth Mandatory</small>
                                    <input class="" type="checkbox" id="onboarding_dob_status" name="onboarding_dob_status" value="1" <?php echo (isset($portal['dob_required']) && $portal['dob_required'] == 1 ? 'checked="checked" ' : '' ); ?> />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Are you 18 years or older?<small class="help_text">Make Mandatory</small>
                                        <input class="" type="checkbox" id="18_plus" name="18_plus" value="1" <?php echo (isset($eight_plus) && $eight_plus == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                    Have you ever been employed with our company or our Affiliate companies?<small class="help_text">Make Mandatory</small>
                                        <input class="" type="checkbox" id="affiliate" name="affiliate" value="1" <?php echo (isset($affiliate) && $affiliate == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Driver's License<small class="help_text">Make Mandatory</small>
                                        <input class="" type="checkbox" id="d_license" name="d_license" value="1" <?php echo (isset($d_license) && $d_license == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label class="control control--checkbox">
                                        Employment Current / Most Recent Employer<small class="help_text">Make Mandatory</small>
                                        <input class="" type="checkbox" id="l_employment" name="l_employment" value="1" <?php echo (isset($l_employment) && $l_employment == 1 ? 'checked="checked" ' : '' ); ?> />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            </div>
                            <?php if(IS_TIMEZONE_ACTIVE) { ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 js-timezone-row">
                                <div class="form-group autoheight">
                                    <label>Timezone:</label>
                                    <div class="select">
                                        <?=timezone_dropdown(
                                            $portal['company_timezone'], 
                                            array(
                                                "name" => "company_timezone",
                                                "id" => "company_timezone",
                                                "class" => "form-control"
                                            )
                                        );?>
                                    </div>
                                    <?php echo form_error('company_timezone'); ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if($complynet_status && $access_level_plus){?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>ComplyNet Dashboard Link:</label>
                                    <input class="form-control"  type="text" id="complynet_link" name="complynet_link" value="<?php echo set_value('complynet_link', $complynet_link); ?>" placeholder="ComplyNet Dashboard Link" >
                                </div>
                            </div>
                            <?php }?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                <?php $video_source = $company['video_source']; ?>
                                    <div class="row">
                                        <div class="col-lg-12 mb-2"><label>Video Source</label></div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                <?php echo YOUTUBE_VIDEO; ?>
                                                <input <?php echo set_radio('video_source', 'youtube', $video_source == 'youtube'); ?> class="video_source" type="radio" id="video_source_youtube" name="video_source" value="youtube" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                <?php echo VIMEO_VIDEO; ?>
                                                <input <?php echo set_radio('video_source', 'vimeo', $video_source == 'vimeo'); ?> class="video_source" type="radio" id="video_source_vimeo" name="video_source" value="vimeo" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <label class="control control--radio">
                                                <?php echo UPLOAD_VIDEO; ?>
                                                <input <?php echo set_radio('video_source', 'uploaded', $video_source == 'uploaded'); ?> class="video_source" type="radio" id="video_source_uploaded" name="video_source" value="uploaded" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div id="yt_video_container" class="form-group autoheight">
                                    <?php $youtube_video_url = !empty($company['YouTubeVideo']) && !is_null($company['YouTubeVideo']) && $video_source == 'youtube' ? 'https://www.youtube.com/watch?v=' . $company['YouTubeVideo'] : ''; ?>
                                    <label>Company Youtube Video:</label>
                                    <input class="form-control"  type="text" name="YouTubeVideo" id="youtubevideo" value="<?php echo set_value('YouTubeVideo', $youtube_video_url); ?>" placeholder="Youtube Video URL" >
                                    <div class="video-link" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX </div>
                                    <?php echo form_error('YouTubeVideo'); ?>
                                    <div style='font-style: italic; color: red;' id="video_link" ></div>
                                </div>
                                <div id="vm_video_container" class="form-group autoheight">
                                    <?php $vimeo_video_url = !empty($company['YouTubeVideo']) && !is_null($company['YouTubeVideo']) && $video_source == 'vimeo' ? 'https://vimeo.com/' . $company['YouTubeVideo'] : ''; ?>
                                    <label>Company Vimeo Video:</label>
                                    <input class="form-control"  type="text" name="VimeoVideo" id="vimeovideo" value="<?php echo set_value('VimeoVideo', $vimeo_video_url); ?>" placeholder="Vimeo Video URL" >
                                    <div class="video-link" style='font-style: italic;'><b>e.g.</b> https://vimeo.com/XXXXXXX </div>
                                    <?php echo form_error('Vimeo_Video'); ?>
                                    <div style='font-style: italic; color: red;' id="video_link" ></div>
                                </div>
                                <div id="up_video_container" class="form-group autoheight">
                                <?php $uploaded_video_url = !empty($company['YouTubeVideo']) && !is_null($company['YouTubeVideo']) && $video_source == 'uploaded' ? $company['YouTubeVideo'] : ''; ?>
                                    <label>Upload Video:</label>
                                    <input class="choose-file" type="file" name="UploadedVideo" value="<?php echo set_value('UploadedVideo', $uploaded_video_url); ?>" id="uploadedvideo" onchange="check_file('uploadedvideo')" >
                                </div>
                            </div>
                        <?php $video_source = $company['video_source']; ?>
                        <?php if (isset($video_source)) { ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Video Preview </label><br>
                                        <?php if ($video_source == 'youtube') { ?>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $company['YouTubeVideo'] ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            </div>
                                        <?php } elseif ($video_source == 'vimeo') { ?>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $company['YouTubeVideo'] ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            </div>
                                        <?php } else { ?>
                                            <div class="embed-responsive embed-responsive-16by9">
                                            <video controls>
                                                <source src="assets/uploaded_videos/<?php echo $company['YouTubeVideo'] ?>" type='video/mp4'>
                                            </video>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>  
                        <?php } ?>                               

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 pull-right">
                            <input type="hidden" name="image_extension" id="image_extension" value="">
                            <input type="hidden" name="old_Logo" value="<?php echo $company['Logo']; ?>">
                            <input type="hidden" name="id" value="<?php echo $company['sid']; ?>">
                            <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn pull-right">
                            <input type="button" value="Cancel" class="submit-btn btn-cancel pull-right" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />      
                        </div>
                    <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
        function validate_form() {
            $("#myprofile").validate({
                ignore: ":hidden:not(select)",
                rules: {
                    CompanyName: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- ]+$/
                    },
                    ContactName: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\- .]+$/
                    },
                    ssn: {
                        required: true,
                        number: true,
                        maxlength: 9,
                        minlength: 9,
                    },
                    WebSite: {
                        url: true
                    }
                },
                messages: {
                    CompanyName: {
                        required: 'Company Name is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    ContactName: {
                        required: 'Contact person is required',
                        pattern: 'Letters, numbers, and dashes only please'
                    },
                    ssn: {
                        required: 'EIN number is required',
                        pattern: 'Only digits are allowed',
                        maxlength: 'EIN should consists on 9 digits',
                        minlength: 'EIN should consists on 9 digits'
                    },
                    WebSite: {
                        url: 'Please provide valid URL including http://'
                    }
                },
                submitHandler: function (form) {
                    <?php if($complynet_status && $access_level_plus){ ?>
                        var url = $('#complynet_link').val();
                        if(url != ''){
                            url_validate = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                            if(!url_validate.test(url)){
                                alertify.error('Please Enter a Valid Link');
                                return false;
                            }
                        }
                    <?php }?>
                    ///

                    form.submit();
                }
            });
        }

        function check_logo() {
            var fileName = $("#logo").val();
            if (fileName.length > 0) {
                $('#name_logo').html(fileName.substring(12, fileName.length));
                var ext = fileName.split('.').pop();
                ext = ext.toLowerCase();
                $("#image_extension").val(ext);
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#logo").val(null);
                    $('#name_logo').html('Only (.png .jpeg .jpg) allowed! Please Select again.');
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_logo').html(original_selected_file);
                    return true;
                }
            }
        }

        function youtube_check() {
            var matches = $('#youtubevideo').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
            data = $('#youtubevideo').val();
            if (matches || data == '') {
                $("#video_link_error").html("");
                return true;
            } else {
                $("#video_link_error").html("Please enter a Valid Youtube Link");
                return false;
            }
        }

        $(document).ready(function () {
            $('.video_source').on('click', function () {
                var selected = $(this).val();
                if (selected == 'youtube') {
                    $('#yt_video_container input').prop('disabled', false);
                    $('#yt_video_container').show();
                    $('#vm_video_container input').prop('disabled', true);
                    $('#vm_video_container').hide();
                    $('#up_video_container input').prop('disabled', true);
                    $('#up_video_container').hide();
                } else if (selected == 'vimeo') {
                    $('#yt_video_container input').prop('disabled', true);
                    $('#yt_video_container').hide();
                    $('#up_video_container input').prop('disabled', true);
                    $('#up_video_container').hide();
                    $('#vm_video_container input').prop('disabled', false);
                    $('#vm_video_container').show();
                } else if (selected == 'uploaded') {
                    $('#yt_video_container input').prop('disabled', true);
                    $('#yt_video_container').hide();
                    $('#vm_video_container input').prop('disabled', true);
                    $('#vm_video_container').hide();
                    $('#up_video_container input').prop('disabled', false);
                    $('#up_video_container').show();
                }
            });

            $('.video_source:checked').trigger('click');
        });

        function check_file(val) {
            var fileName = $("#" + val).val();
            if (fileName.length > 0) {
                $('#name_' + val).html(fileName.substring(0, 45));
                var ext = fileName.split('.').pop();
                var ext = ext.toLowerCase();

                if (val == 'uploadedvideo') {
                    if (ext != "mp4" && ext != "m4a" && ext != "m4v" && ext != "f4v" && ext != "f4a" && ext != "m4b" && ext != "m4r" && ext != "f4b" && ext != "mov") {
                        $("#" + val).val(null);
                        alertify.error("Please select a valid video format.");
                        $('#name_' + val).html('<p class="red">Only (.mp4, .m4a, .m4v, .f4v, .f4a, .m4b, .m4r, .f4b, .mov) allowed!</p>');
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

        <?php if(IS_TIMEZONE_ACTIVE) { ?>
        $('#company_timezone').select2();
        <?php } ?>
</script>