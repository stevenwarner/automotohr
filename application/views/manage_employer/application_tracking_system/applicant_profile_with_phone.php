<?php 
    $field_phone = 'PhoneNumber';
    $field_sphone = 'secondary_PhoneNumber';
    $field_ophone = 'other_PhoneNumber';
    // Replace '+1' with ''
    if(isset($employer[$field_phone]) && $employer[$field_phone] != ''){
        $employer[$field_phone] = str_replace('+1', '', $employer[$field_phone]);
    }
    if(isset($employer[$field_sphone]) && $employer[$field_sphone] != ''){
        $employer[$field_sphone] = str_replace('+1', '', $employer[$field_sphone]);
    }
    if(isset($employer[$field_ophone]) && $employer[$field_ophone] != ''){
        $employer[$field_ophone] = str_replace('+1', '', $employer[$field_ophone]);
    }
?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();

                            $("ul.tabs li").click(function () {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });
                        });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="application-header">
                        <article>
                            <figure>
                                <img src="<?php if(isset($applicant_info['pictures']) && $applicant_info['pictures'] != '') {
                                    echo AWS_S3_BUCKET_URL.$applicant_info['pictures'];
                                } else {  
                                    echo AWS_S3_BUCKET_URL;?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                            </figure>
                            <div class="text">
                                <h2><?php echo $applicant_info['first_name']; ?> <?= $applicant_info['last_name'] ?></h2>
                                <div class="start-rating">
                                    <input readonly="readonly"
                                           id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                           type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                           data-size="xs">
                                </div>
                                <?php if(check_blue_panel_status() && $applicant_info['is_onboarding'] == 1) { ?>
                                     <?php $send_notification = checkOnboardingNotification($id); ?>
                                    <?php if ($send_notification) { ?>
                                        <span class="badge" style="padding:8px; background-color: green;">On-boarding Request Sent</span>
                                    <?php } else { ?>
                                        <span class="badge" style="padding:8px; background-color: red;">On-boarding Request Pending</span>
                                    <?php } ?>
                                    <span class="badge" style="padding:8px; background-color: blue;"><a href="<?php echo $onboarding_url;?>" style="color:#fff;" target="_black">Preview On-boarding</a></span>
                                    <?php if (!$send_notification) { ?>
                                    <p class="" style="padding:18px; color: red;">
                                        <strong>
                                        <?php echo onboardingNotificationPendingText($id); ?>
                                        </strong>
                                    </p>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="" style="padding:8px;"><?php echo $applicant_info['applicant_type']; ?></span>
                                <?php }  ?>
                            </div>
                        </article>
                    </div>
                    <div id="HorizontalTab" class="HorizontalTab">
                        <ul class="resp-tabs-list hor_1">
                            <li><a href="javascript:;">Personal Info</a></li>
                            <li><a href="javascript:;">Questionnaire</a></li>
                            <li><a href="javascript:;">Notes</a></li>
                            <li><a href="javascript:;">Messages</a></li>
                            <li id="tab5_nav"><a href="javascript:;">reviews</a></li>
                            <li id="js-calendar-btn"><a href="javascript:;">Calendar</a></li>
                            <?php if($phone_sid != ''){ ?>
                            <li id="js-sms-btn"><a href="javascript:void(0)">SMS</a></li>
                            <?php } ?>
                        </ul>
                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div class="universal-form-style-v2 info_view" <?php if ($edit_form) { ?> style="display: none;" <?php } ?>>
                                    <ul>
                                        <div class="form-title-section">
                                            <h2>Personal Information</h2>
                                            <div class="form-btns">
                                                <input type="submit" value="edit" id="edit_button">
                                            </div>
                                        </div>
                                        <li class="form-col-50-left">
                                            <label>first name:</label>
                                            <p><?php echo $applicant_info["first_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>last name:</label>
                                            <p><?php echo $applicant_info["last_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>email:</label>
                                            <p><?php echo $applicant_info["email"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>mobile number:</label>
                                            <p><?=phonenumber_format($applicant_info["phone_number"]);?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>address:</label>
                                            <p><?php echo $applicant_info["address"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>city:</label>
                                            <p><?php echo $applicant_info["city"] ?></p>
                                        </li>
                                        <!--<li class="form-col-50-left">
                                            <label>date applied:</label>

                                            <p><?php /*echo $applicant_info["date_applied"] */?></p>
                                        </li>-->
                                        <li class="form-col-50-left">
                                            <label>zipcode:</label>
                                            <p> <?php echo $applicant_info["zipcode"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>state:</label>
                                            <p><?php echo $applicant_info["state_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>country:</label>
                                            <p><?php echo $applicant_info["country_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-right"></li>
                                        <li class="form-col-50-left">
                                            <label>Secondary Email:</label>
                                            <?php if (isset($extra_info["secondary_email"]) && $extra_info["secondary_email"] != '') { ?>
                                                <p><?php echo $extra_info["secondary_email"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Secondary Mobile Number:</label>
                                            <?php if (isset($extra_info["secondary_PhoneNumber"]) && $extra_info["secondary_PhoneNumber"] != '') { ?>
                                                <p><?=phonenumber_format($extra_info["secondary_PhoneNumber"]);?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Other Email:</label>
                                            <?php if (isset($extra_info["other_email"]) && $extra_info["other_email"] != '') { ?>
                                                <p><?php echo $extra_info["other_email"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Telephone Number:</label>
                                            <?php if (isset($extra_info["other_PhoneNumber"]) && $extra_info["other_PhoneNumber"] != '') { ?>
                                                <p><?=phonenumber_format($extra_info["other_PhoneNumber"]);?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Referred By:</label>
                                            <?php if (isset($applicant_info["referred_by_name"]) && $applicant_info["referred_by_name"] != null && $applicant_info["referred_by_name"] != '') { ?>
                                                <p><?php echo $applicant_info["referred_by_name"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Referrer Email:</label>
                                            <?php if (isset($applicant_info["referred_by_email"]) && $applicant_info["referred_by_email"] != null && $applicant_info["referred_by_email"] != '') { ?>
                                                <p><?php echo $applicant_info["referred_by_email"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Linkedin public Profile URL</label>
                                            <?php if (isset($applicant_info["linkedin_profile_url"]) && $applicant_info["linkedin_profile_url"] != '' ) { ?>
                                                <p><a href="<?php echo $applicant_info["linkedin_profile_url"]; ?>" target="_blank"> <?php echo $applicant_info["linkedin_profile_url"]; ?></a></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Employee Number:</label>
                                            <?php if (isset($applicant_info["employee_number"]) && $applicant_info["employee_number"] != '' ) { ?>
                                                <p><?php echo $applicant_info["employee_number"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Social Security Number:</label>
                                            
                                            <?php if (isset($applicant_info["ssn"]) && $applicant_info["ssn"] != '' ) { ?>
                                                <p><?php echo $applicant_info["ssn"]; ?></p>
                                            <?php } else { ?>
                                                <p>Not Specified</p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Date of Birth:</label>
                                            <p><?php echo (isset($applicant_info["dob"]) && !empty($applicant_info["dob"]) && $applicant_info["dob"] != '0000-00-00') ? date('m-d-Y', strtotime(str_replace('-', '/', $applicant_info["dob"]))) : 'Not Specified'; ?></p>
                                        </li>
                                    </ul>
                                    <?php if (isset($applicant_info["YouTube_Video"]) && $applicant_info["YouTube_Video"] != "") { ?>
                                        <div class="applicant-video">
                                            <div class="well well-sm">
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <?php if($applicant_info['video_type'] == 'youtube') { ?>
                                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $applicant_info['YouTube_Video']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                    <?php } elseif($applicant_info['video_type'] == 'vimeo') { ?>
                                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $applicant_info['YouTube_Video']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                    <?php } else {?> 
                                                        <video controls>
                                                            <source src="<?php echo base_url().'assets/uploaded_videos/'.$applicant_info['YouTube_Video']; ?>" type='video/mp4'>
                                                        </video>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>    
                                </div>
                                <!--Edit part-->
                                <div <?php if ($edit_form) { ?>style="display: block;" <?php } else { ?>style="display: none;" <?php } ?> class="universal-form-style-v2 info_edit">
                                    <ul>
                                        <form method="POST" enctype="multipart/form-data" id="applicant_profile_form">
                                            <div class="form-title-section">
                                                <h2>Personal Information</h2>
                                                <div class="form-btns">
                                                    <input type="button" value="save" class="add_edit_submit">
                                                    <input type="submit" value="cancel" class="view_button">
                                                </div>
                                            </div>
                                            <li class="form-col-50-left">
                                                <label>first name:<samp class="red"> * </samp></label>
                                                <input class="invoice-fields  <?php if (form_error('first_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('first_name', $applicant_info["first_name"]); ?>" type="text" name="first_name">
                                                    <?php echo form_error('first_name'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>last name:<samp class="red"> * </samp></label>
                                                <input class="invoice-fields  <?php if (form_error('last_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('last_name', $applicant_info["last_name"]); ?>" type="text" name="last_name">
                                                    <?php echo form_error('last_name'); ?>

                                            </li>
                                            <li class="form-col-50-left">
                                                <label>email:<samp class="red"> * </samp></label>
                                                <input class="invoice-fields <?php if (form_error('email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('email', $applicant_info["email"]); ?>" type="email" name="email">
                                                    <?php echo form_error('email'); ?>

                                            </li>
                                            <li class="form-col-50-right">
                                                <label>mobile number:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text" id="basic-addon1">+1</span>
                                                    </div>
                                                    <input class="invoice-fields"
                                                       id="PhoneNumber"
                                                       value="<?php echo set_value('phone_number', phonenumber_format($applicant_info["phone_number"], true)); ?>"
                                                       type="text" name="phone_number">
                                                </div>
                                            </li>
                                            <li class="form-col-100">
                                                <label>address:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('address', $applicant_info["address"]); ?>"
                                                       type="text" name="address">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>city:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('city', $applicant_info["city"]); ?>"
                                                       type="text" name="city">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>zipcode:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('zipcode', $applicant_info["zipcode"]); ?>"
                                                       type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>country:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="country" id="country"
                                                            onchange="getStates(this.value, <?php echo $states; ?>)">
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?= $active_country["sid"]; ?>"
                                                            <?php if ($applicant_info['country'] == $active_country["sid"]) { ?>
                                                                        selected
                                                                    <?php } ?> >
                                                                        <?= $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>state:</label>
                                                <p style="display: none;" id="state_id"><?php echo $applicant_info['state']; ?></p>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="state" id="state">
                                                        <?php if (empty($country_id)) { ?>
                                                                    <option value="">Select State</option>
                                                        <?php } else {
                                                                foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <option value="<?= $active_state["sid"] ?>"
                                                                    <?php if ($active_state["sid"] == $applicant_info['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Job Fit Category:</label>
                                                <?php $job_fit_categories = explode(',', $applicant_info['job_fit_category_sid']); ?>
                                                <div class="Category_chosen">
                                                    <select data-placeholder="- Please Select -" multiple="multiple" onchange="multiselectbox()" name="job_fit_category_sid[]" id="job_fit_category_sid"  class="chosen-select">
                                                        <?php foreach ($job_categories as $category) { ?>
                                                            <?php $default_selected = ( in_array($category['id'], $job_fit_categories ) ? true : false ); ?>
                                                            <option <?php echo set_select('job_fit_category_sid', $category['id'], $default_selected ); ?> value="<?php echo $category["id"] ?>" ><?php echo $category["value"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div style="display: none;" id="choiceLimit">5</div>
                                                <span class="available"><samp id="choicelimitavailable">5</samp> available</span>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>secondary email:</label>
                                                <input class="invoice-fields <?php if (form_error('secondary_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('secondary_email', $extra_info["secondary_email"]); ?>" type="email" name="secondary_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>secondary mobile number:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text" id="basic-addon1">+1</span>
                                                    </div>
                                                    <input class="invoice-fields"
                                                       id="secondary_PhoneNumber"
                                                       value="<?php echo set_value('secondary_PhoneNumber', phonenumber_format($extra_info["secondary_PhoneNumber"], true)); ?>"
                                                       type="text" name="secondary_PhoneNumber">
                                                </div>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>other email:</label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('other_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('other_email', $extra_info["other_email"]); ?>"
                                                    type="email" name="other_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>telephone number:</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <span class="input-group-text" id="basic-addon1">+1</span>
                                                    </div>
                                                    <input class="invoice-fields"
                                                       id="other_PhoneNumber"
                                                       value="<?php echo set_value('other_PhoneNumber', $extra_info["other_PhoneNumber"]); ?>"
                                                       type="text" name="other_PhoneNumber">
                                                </div>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Referred By:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('referred_by_name', $applicant_info["referred_by_name"]); ?>"
                                                       type="text" name="referred_by_name" id="referred_by_name">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Referred By email:</label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('referred_by_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('referred_by_email', $applicant_info["referred_by_email"]); ?>"
                                                    type="email" name="referred_by_email" id="referred_by_email">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Linkedin Public Profile URL:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('linkedin_profile_url', $applicant_info["linkedin_profile_url"]); ?>" type="text" name="linkedin_profile_url">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Employee Number:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('employee_number', phonenumber_format($applicant_info["employee_number"], true)); ?>" type="text" name="employee_number">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Social Security Number:</label>
                                                <input class="invoice-fields" type="text" name="SSN" value="<?php echo isset($applicant_info["ssn"]) ? $applicant_info["ssn"] : ''; ?>">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Date of Birth:</label>
                                                <input class="invoice-fields startdate" readonly="" type="text" name="DOB" value="<?php echo (isset($applicant_info["dob"]) && !empty($applicant_info["dob"]) && $applicant_info["dob"] != '0000-00-00') ? date('m-d-Y', strtotime(str_replace('-', '/', $applicant_info["dob"]))) : ''; ?>">
                                                
                                            </li>
                                            <li class="form-col-100">
                                                <label>Profile picture:</label>
                                                <div class="upload-file invoice-fields">
                                                    <span class="selected-file" id="name_pictures">No file selected</span>
                                                    <input type="file" name="pictures" id="pictures"
                                                           onchange="check_file_all('pictures')"
                                                           accept=".jpg,.jpeg,.jpe,.png">
                                                    <a href="javascript:;" style="background: #549809;">Choose File</a>
                                                </div>
                                            </li>
                                            <li class="form-col-100">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label for="YouTubeVideo">Select Video:</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio"><?php echo NO_VIDEO; ?>
                                                                    <input type="radio" name="video_source" class="video_source" value="no_video"  checked="">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio"><?php echo YOUTUBE_VIDEO; ?>
                                                                    <input type="radio" name="video_source" class="video_source" value="youtube" <?php echo $applicant_info['video_type'] == 'youtube' ? 'checked="checked"':''; ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio"><?php echo VIMEO_VIDEO; ?>
                                                                    <input type="radio" name="video_source" class="video_source" value="vimeo" <?php echo $applicant_info['video_type'] == 'vimeo' ? 'checked="checked"':''; ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <label class="control control--radio"><?php echo UPLOAD_VIDEO; ?>
                                                                    <input type="radio" name="video_source" class="video_source" value="uploaded" <?php echo $applicant_info['video_type'] == 'uploaded' ? 'checked="checked"':''; ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <div id="youtube_vimeo_input">
                                                    <?php 
                                                        if (!empty($applicant_info['YouTube_Video']) && $applicant_info['video_type'] == 'youtube') {
                                                            $video_link = 'https://www.youtube.com/watch?v='.$applicant_info['YouTube_Video'];
                                                        } else if (!empty($applicant_info['YouTube_Video']) && $applicant_info['video_type'] == 'vimeo') {
                                                            $video_link = 'https://vimeo.com/'.$applicant_info['YouTube_Video'];
                                                        } else {
                                                            $video_link = '';
                                                        }
                                                    ?>
                                                    <label for="YouTube_Video" id="label_youtube">Youtube Video:</label>
                                                    <label for="Vimeo_Video" id="label_vimeo" style="display: none">Vimeo Video:</label>
                                                    <input type="text" name="yt_vm_video_url" value="<?php echo $video_link; ?>" class="invoice-fields" id="yt_vm_video_url">
                                                </div>
                                                <div id="upload_input" style="display: none">
                                                    <label for="YouTubeVideo">Upload Video:</label>
                                                    <div class="upload-file invoice-fields">
                                                        <?php 
                                                            if (!empty($applicant_info['YouTube_Video']) && $applicant_info['video_type'] == 'uploaded') {
                                                        ?>
                                                                <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="<?php echo $applicant_info['YouTube_Video']; ?>">
                                                        <?php        
                                                            } else {
                                                        ?>
                                                            <input type="hidden" id="pre_upload_video_url" name="pre_upload_video_url" value="">
                                                        <?php        
                                                            }
                                                        ?>
                                                        <span class="selected-file" id="name_upload_video">No video selected</span>
                                                        <input name="upload_video" id="upload_video" onchange="upload_video_checker('upload_video')" type="file">
                                                        <a href="javascript:;">Choose Video</a>
                                                    </div>
                                                </div>
                                                <?php if(!empty($applicant_info['YouTube_Video'])) { ?>
                                                    <div class="applicant-video autoheight">
                                                        <div class="well well-sm">
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                            <?php if($applicant_info['video_type'] == 'youtube') { ?>
                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $applicant_info['YouTube_Video']; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } elseif($applicant_info['video_type'] == 'vimeo') { ?>
                                                                <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/<?php echo $applicant_info['YouTube_Video']; ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                            <?php } else {?> 
                                                                <video controls>
                                                                    <source src="<?php echo base_url().'assets/uploaded_videos/'.$applicant_info['YouTube_Video']; ?>" type='video/mp4'>
                                                                </video>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                            <div class="form-title-section" style="margin-top: 50px;">
                                                <div class="form-btns">
                                                    <input type="button" value="save" class="add_edit_submit">
                                                    <input type="submit" value="cancel" class="view_button">
                                                </div>
                                            </div>                        
                                        </form>
                                    </ul>
                                </div>
                                <!--Edit part Ends-->
                                <!--<pre>
                                    <?php //print_r($applicant_jobs); exit;?>
                                </pre>-->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!-- applicant jobs all -->
                                        <div class="hr-box applied-jobs margin-top">
                                            <div class="hr-box-header">
                                                <strong>List Of Applied jobs</strong>
                                            </div>
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-4">Job Title / Desired Job Title</th>
                                                        <th class="col-xs-1 text-center">Applicant Type</th>
                                                        <th class="col-xs-3 text-center">Applicant Status</th>
                                                        <th class="col-xs-1 text-center">Screening Questionnaire</th>
                                                        <th class="col-xs-2 text-center">Job Specific Interview Questionnaire</th>
                                                        <?php if(check_access_permissions_for_view($security_details, 'send_documents_onboarding_request')){ ?>
                                                            <th class="col-xs-1 text-center">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (sizeof($applicant_jobs) > 0) { ?>
                                                        <?php foreach ($applicant_jobs as $applicant_job) { ?>
                                                            <tr>
                                                                <td>
                                                                    <p><strong><?php echo $applicant_job['job_title']; ?></strong></p>
                                                                    <p><small><span>Date Applied: </span>
                                                                            <span><?=reset_datetime(array( 'datetime' => $applicant_job['date_applied'], '_this' => $this));?></span>
                                                                        </small>
                                                                    </p>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if ($applicant_job['archived'] == 1) { ?>
                                                                            Archived
                                                                    <?php } else { ?>
                                                                            Active
                                                                    <?php } ?>
                                                                    &nbsp;
                                                                    <?php echo $applicant_job['applicant_type']; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="label-wrapper-outer">
                                                                        <?php if ($have_status == false) { ?>
                                                                            <?php if ($applicant_job["status"] == 'Contacted') { ?>
                                                                                <div class="selected contacted"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Candidate Responded') { ?>
                                                                                <div class="selected responded"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Qualifying') { ?>
                                                                                <div class="selected qualifying"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Submitted') { ?>
                                                                                <div class="selected submitted"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Interviewing') { ?>
                                                                                <div class="selected interviewing"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Offered Job') { ?>
                                                                                <div class="selected offered"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Not In Consideration') { ?>
                                                                                <div class="selected notin"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Client Declined') { ?>
                                                                                <div class="selected decline"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Placed/Hired' || $applicant_job["status"] == 'Ready to Hire') { ?>
                                                                                <div class="selected placed">Ready to Hire</div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Not Contacted Yet') { ?>
                                                                                <div class="selected not_contacted"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Future Opportunity') { ?>
                                                                                <div class="selected future_opportunity"><?= $applicant_job["status"] ?></div>
                                                                            <?php } elseif ($applicant_job["status"] == 'Left Message') { ?>
                                                                                <div class="selected left_message"><?= $applicant_job["status"] ?></div>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <div <?php echo $applicant_job['status_type'] == 'custom' ? ' style= "background-color: '.$applicant_job['bar_bgcolor'].'"' : '' ?> class="selected <?php echo (isset($applicant_job['status_css_class'])) ? $applicant_job['status_css_class'] : ''; ?>">
                                                                                <?php echo (isset($applicant_job['status_name'])) ? $applicant_job['status_name'] : ''; ?>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <div class="lable-wrapper">
                                                                            <div id="id" style="display:none;"><?= $applicant_job['sid'] ?></div>
                                                                            <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                                                            <?php if ($have_status == false) { ?>
                                                                                <div data-status_sid="1" data-status_class="not_contacted" data-status_name="Not Contacted Yet" class="label applicant not_contacted">
                                                                                    <div id="status">Not Contacted Yet</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="2" data-status_class="left_message" data-status_name="Left Message" class="label applicant left_message">
                                                                                    <div id="status">Left Message</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="3" data-status_class="contacted" data-status_name="Contacted" class="label applicant contacted">
                                                                                    <div id="status">Contacted</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="4" data-status_class="responded" data-status_name="Candidate Responded" class="label applicant responded">
                                                                                    <div id="status">Candidate Responded</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="5" data-status_class="qualifying" data-status_name="Interviewing" class="label applicant interviewing">
                                                                                    <div id="status">Interviewing</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="6" data-status_class="submitted" data-status_name="Submitted" class="label applicant submitted">
                                                                                    <div  id="status">Submitted</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="7" data-status_class="interviewing" data-status_name="Qualifying" class="label applicant qualifying">
                                                                                    <div  id="status">Qualifying</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="11" data-status_class="placed" data-status_name="Ready to Hire" class="label applicant placed">
                                                                                    <div  id="status">Ready to Hire</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="8" data-status_class="offered" data-status_name="Offered Job" class="label applicant offered">
                                                                                    <div  id="status">Offered Job</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="10" data-status_class="decline" data-status_name="Client Declined" class="label applicant decline">
                                                                                    <div  id="status">Client Declined</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="9" data-status_class="notin" data-status_name="Not In Consideration" class="label applicant notin">
                                                                                    <div  id="status">Not In Consideration</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                                <div data-status_sid="12" data-status_class="future_opportunity" data-status_name="Future Opportunity" class="label applicant future_opportunity">
                                                                                    <div  id="status">Future Opportunity</div>
                                                                                    <i class="fa fa-check-square check"></i>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <?php foreach ($company_statuses as $status) { ?>
                                                                                    <div <?php echo $status['status_type'] == 'custom' ? ' style= "background-color: '.$status['bar_bgcolor'].'"' : '' ?> data-status_sid="<?php echo $status['sid']; ?>" data-status_class="<?php echo $status['css_class']; ?>" data-status_name="<?php echo $status['name']; ?>"f class="label applicant <?php echo $status['css_class']; ?>">
                                                                                        <div id="status"><?php echo $status['name']; ?></div>
                                                                                        <i class="fa fa-check-square check"></i>
                                                                                    </div>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                    <?php                           if($applicant_job['questionnaire'] == '' || $applicant_job['questionnaire'] == NULL) { 
                                                                        echo '<p class="fail">N/A</p>';
                                                                    } else { 
                                                                            $my_questionnaire = unserialize($applicant_job['questionnaire']);

                                                                            if(isset($my_questionnaire['applicant_sid'])) {
                                                                                      $questionnaire_type = 'new';
                                                                                      $questionnaire_name = $my_questionnaire['questionnaire_name']; 
                                                                                      $questionnaire_result = $applicant_job['questionnaire_result'];
                                                                                      echo $applicant_job['score'].'&nbsp;';//.'/'.$applicant_job['passing_score'].'&nbsp;';
                                                                                      echo '<p class="'.strtolower($questionnaire_result).'"> ('.$questionnaire_result.')</p>';
                                                                            } else {
                                                                                $questionnaire_type = 'old';
                                                                                echo $applicant_job['score'];
                                                                                if ($applicant_job['score'] >= $applicant_job['passing_score']) {
                                                                                    echo '<p class="pass">(Pass)</p>';
                                                                                } else {
                                                                                    echo '<p class="fail">(Fail)</p>';
                                                                                }
                                                                            } ?>
                                                                    <?php } 
                                                                            $manual_questionnaire_history = $applicant_job['manual_questionnaire_history'];
                                                                            
                                                                            if(!empty($manual_questionnaire_history)) {
                                                                                $manual_questionnaire_history_count = count($manual_questionnaire_history);

                                                                                foreach($manual_questionnaire_history as $man_key => $man_value) {
                                                                                    $manual_questionnaire = $man_value['questionnaire'];
                                                                                    $questionnaire_sent_date = $man_value['questionnaire_sent_date'];
                                                                                    $man_questionnaire_result = $man_value['questionnaire_result'];
                                                                                    $man_score = $man_value['score'];
                                                                                    $man_passing_score = $man_value['passing_score'];
                                                                                    echo '<br>Resent on: '.reset_datetime(array( 'datetime' => $questionnaire_sent_date, '_this' => $this)).'<hr style="margin-top: 5px; margin-bottom: 5px;">';

                                                                                    if($manual_questionnaire != '' || $manual_questionnaire != NULL) {
                                                                                       $manual_questionnaire_array = unserialize($manual_questionnaire);

                                                                                       if(empty($manual_questionnaire_array)) {
                                                                                           echo '<p class="fail">N/A</p>';
                                                                                       } else {
                                                                                           if($man_questionnaire_result != '' || $man_questionnaire_result != NULL) {
                                                                                               echo $man_score.'&nbsp;';
                                                                                               echo '<p class="'.strtolower($man_questionnaire_result).'"> ('.$man_questionnaire_result.')</p>';
                                                                                           } else {
                                                                                               echo $man_score.'&nbsp;';
                                                                                               if ($man_score >= $man_passing_score) {
                                                                                                    echo '<p class="pass">(Pass)</p>';
                                                                                                } else {
                                                                                                    echo '<p class="fail">(Fail)</p>';
                                                                                                }
                                                                                           }
                                                                                       }
                                                                                    } else {
                                                                                        echo '<p class="fail">N/A</p>';
                                                                                    }
                                                                                }
                                                                            }  ?>
                                                                <?php if(check_access_permissions_for_view($security_details, 'resend_screening_questionnaire')) { ?>
                                                                    <p>
                                                                        <span>
                                                                            <a class="btn btn-sm btn-success" href="javascript:0;" data-toggle="modal" data-target="#job-id-<?php echo $applicant_job['sid'];?>">Resend</a>
                                                                        <!-- <a class="btn btn-sm btn-success" href="--><?php //echo base_url('resend_screening_questionnaire') . '/' . $applicant_info['sid'] . '/' . $applicant_job['sid'] . '/' . $applicant_job['job_sid'] ; ?><!--">Resend</a>-->
                                                                        </span>
                                                                    </p>
                                                                <?php } ?>

                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if($applicant_job['interview_questionnaire_sid'] > 0) { ?>
                                                                        <span>
                                                                    <?php $interview_score_count = count_interview_score_records($applicant_info['sid'], $applicant_job['job_sid']); ?>
                                                                            <?php if(intval($interview_score_count) > 0) { ?>
                                                                                <img style=" width: 22px; height: 22px; margin-right:5px; display: inline-block;" title="Interview Conducted" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                                            <?php } else { ?>
                                                                                <img style=" width: 22px; height: 22px; margin-right:5px; display: inline-block;" title="Interview Not Conducted" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                                            <?php } ?>
                                                                        </span>
                                                                        <span>
                                                                            <a class="btn btn-sm btn-success" href="<?php echo base_url('interview_questionnaires') . '/applicant/' . $applicant_info['sid'] . '/' . $applicant_job['job_sid']; ?>">Launch / View</a>
                                                                        </span>
                                                                    <?php } else { ?>
                                                                        <span class="" data-toggle="tooltip" title="Interview Questionnaire Not Assigned">Not Applicable</span>
                                                                    <?php } ?>
                                                                </td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'send_documents_onboarding_request')){ ?>
                                                                    <td>
                                                                        <?php if ($applicant_job['archived'] == 0) { ?>
                                                                            <?php if ($applicants_approval_module_status == 0) { ?>
                                                                                <div class="">
                                                                                    <!--<button class="btn btn-success btn-sm" onclick="myPopup()"><i
                                                                                            class="fa fa-refresh fa-check-square"></i>&nbsp;Send Docs
                                                                                    </button>-->
                                                                                    <?php if(check_blue_panel_status_for_view()) { //TODO Remove This Check After Completion?>
                                                                                        <?php if($is_onboarding_configured == false) { ?>
                                                                                            <a href="<?php echo base_url('onboarding/configuration/'); ?>" class="btn btn-success btn-sm">Configure Onboarding</a>
                                                                                        <?php } else { ?>
                                                                                            <?php if(!empty($onboarding_status) && $onboarding_status['onboarding_status'] == 'in_process'){ ?>
                                                                                                <a href="<?php echo base_url('onboarding/setup/applicant/' . $applicant_info['sid']) . '/' . $applicant_job['sid'];?>" class="btn btn-success btn-sm">Resend Onboarding Request</a>
                                                                                            <?php }  else { ?>
                                                                                                <a href="<?php echo base_url('onboarding/setup/applicant/' . $applicant_info['sid']) . '/' . $applicant_job['sid'];?>" class="btn btn-success btn-sm">Send Onboarding Request</a>
                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                    <?php } else { ?>
                                                                                        <button class="btn btn-success btn-sm" onclick="func_get_hire_applicant_form(<?php echo $applicant_job['company_sid']; ?>, <?php echo $applicant_job['portal_job_applications_sid']; ?>, <?php echo $applicant_job['job_sid']; ?>, '<?php echo $applicant_info['email']; ?>');"><i class="fa fa-refresh fa-check-square"></i>&nbsp;Send Docs</button>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            <?php } elseif ($applicants_approval_module_status == 1) { ?>
                                                                                <?php if ($applicant_job['approval_status'] == null || $applicant_job['approval_status'] == 'NULL' || $applicant_job['approval_status'] == 'null') { ?>
                                                                                    <div class="">
                                                                                        <button class="btn btn-success btn-sm" onclick="fSetApplicantForApproval(<?php echo $applicant_job['portal_job_applications_sid']; ?>, <?php echo $applicant_job['job_sid']; ?>);"><i class="fa fa-refresh fa-check-square"></i>&nbsp;Get Hiring Approval</button>
                                                                                    </div>
                                                                                <?php } elseif ($applicant_job['approval_status'] == 'pending') { ?>
                                                                                    <div class="">
                                                                                        <div class="btn btn-success btn-sm" onclick=""><i class="fa fa-hourglass-half"></i>&nbsp;Approval Pending</div>
                                                                                    </div>
                                                                                <?php } elseif ($applicant_job['approval_status'] == 'approved') { ?>
                                                                                    <div class="">
                                                                                        <!--<button class="btn btn-success btn-sm" onclick="myPopup()"><i
                                                                                                class="fa fa-refresh fa-check-square"></i>&nbsp;Send Docs
                                                                                        </button>-->
                                                                                        <?php if(check_blue_panel_status_for_view()) { //TODO Remove This Check After Completion?>
                                                                                            <?php if($is_onboarding_configured == false) { ?>
                                                                                                <a href="<?php echo base_url('onboarding/configuration/'); ?>" class="btn btn-success btn-sm">Configure Onboarding</a>
                                                                                            <?php } else { ?>
                                                                                                <?php if(!empty($onboarding_status) && $onboarding_status['onboarding_status'] == 'in_process'){ ?>
                                                                                                    <a href="<?php echo base_url('onboarding/setup/applicant/' . $applicant_info['sid'] . '/' . $applicant_job['sid']);?>" class="btn btn-success btn-sm">Resend Onboarding Request</a>
                                                                                                <?php }  else { ?>
                                                                                                    <a href="<?php echo base_url('onboarding/setup/applicant/' . $applicant_info['sid'] . '/' . $applicant_job['sid']);?>" class="btn btn-success btn-sm">Send Onboarding Request</a>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?> np jere
                                                                                            <button class="btn btn-success btn-sm" onclick="func_get_hire_applicant_form(<?php echo $applicant_job['company_sid']; ?>, <?php echo $applicant_job['portal_job_applications_sid']; ?>, <?php echo $applicant_job['job_sid']; ?>, '<?php echo $applicant_info['email']; ?>');"><i class="fa fa-refresh fa-check-square"></i>&nbsp;Send Documents</button>
                                                                                        <?php } ?>
                                                                                    </div>
                                                                                <?php } elseif ($applicant_job['approval_status'] == 'rejected') { ?>
                                                                                    <?php if($applicant_job['approval_status_type'] == 'rejected_unconditionally') { ?>
                                                                                        <div class="">
                                                                                            <button class="btn btn-success btn-sm" onclick="func_show_permanent_rejection_info();"><i class="fa fa-ban"></i>&nbsp;<?php echo ucwords($applicant_job['approval_status']); ?> Click for Details</button>
                                                                                        </div>
                                                                                    <?php } else if( $applicant_job['approval_status_type'] == 'rejected_conditionally') { ?>
                                                                                        <div class="">
                                                                                            <button class="btn btn-success btn-sm" onclick="func_show_rejection_information(<?php echo $applicant_job['portal_job_applications_sid']; ?>, <?php echo $applicant_job['company_sid']; ?>, <?php echo $applicant_job['job_sid']; ?>);"><i class="fa fa-ban"></i>&nbsp;<?php echo ucwords($applicant_job['approval_status']); ?> Click for Details</button>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>

                                                            <tr>
                                                                <td colspan="6">
                                                                    <p>
                                                                        <small>
                                                                            <span>Applicant Source: </span>
                                                                            <span><?php echo ($applicant_job['applicant_source']); ?></span>
                                                                        </small>
                                                                    </p>
                                                                    <p>
                                                                        <small>
                                                                            <span>Applicant IP Address: </span>
                                                                            <span><?php echo ($applicant_job['ip_address']); ?></span>
                                                                        </small>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr class="text-center"><td colspan="6">No job found.</td></tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- applicant jobs all -->
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!-- Interview Questionnaires -->
                                        <div class="hr-box">
                                            <div class="hr-box-header">
                                                <strong>Interview Questionnaires</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-10">
                                                        <?php if(!empty($interview_questionnaires)) { ?>
                                                            <div class="hr-select-dropdown">
                                                                <select id="interview_questionnaire" name="interview_questionnaire" class="invoice-fields">
                                                                    <?php foreach($interview_questionnaires as $questionnaire) { ?>
                                                                        <option value="<?php echo base_url('interview_questionnaire/launch_interview/' . $applicant_sid . '/' . $questionnaire['sid']); ?>"><?php echo $questionnaire['title']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        <?php } else { ?>
                                                            <p>No Interview Questionnaires Found</p>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <a href="#" id="btn_launch_interview" class="btn btn-success btn-block">Launch</a>
                                                    </div>
                                                </div>
                                                <?php if(!empty($interview_questionnaire_scores)) { ?>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th class="col-xs-10">Interview Questionnaire</th>
                                                                    <th class="col-xs-2 text-center">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach($interview_questionnaire_scores as $interview_questionnaire_score) { ?>
                                                                        <tr>
                                                                            <td><?php echo $interview_questionnaire_score['title']; ?></td>
                                                                            <td><a class="btn btn-success btn-block" href="<?php echo base_url('interview_questionnaire/launch_interview/' . $applicant_sid . '/' . $interview_questionnaire_score['questionnaire_sid']); ?>">View</a> </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- Interview Questionnaires -->
                                    </div>
                                </div>
                            </div>
                            <!-- #tab1 -->
                            <div id="tab2" class="tabs-content">
                                <?php $this->load->view('manage_employer/application_tracking_system/screening_questionnaire_partial');?>
                            </div>
                            <!-- #tab2 -->
                            <div id="tab3" class="tabs-content">
        <?php               $notes_popup_view = check_access_permissions_for_view($security_details, 'notes_popup');
                        
                            if($notes_popup_view == true) { ?>
                                <div class="universal-form-style-v2" id="show_hide">
                                    <form action="<?php echo base_url('applicant_profile/insert_notes') ?>" method="POST" id="note_form" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="add_note">
                                        <input type="hidden" name="applicant_job_sid" value="<?php echo $id; ?>">
                                        <input type="hidden" name="job_list_sid" value="<?php echo $job_list_sid;?>">
                                        <input type="hidden" name="applicant_email" value="<?php echo $email; ?>">
                                        <div class="form-title-section">
                                            <h2>Applicant Notes</h2>
                                            <div class="form-btns">
                                                <input type="submit" style="display: none;" class="note_div" value="save">
                                                <input type="button" id="cancel_note" style="display: none;" class="note_div" value="cancel">
                                                <input type="submit" class="no_note" id="add_notes" value="Add note">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <div class="hr-ck-editor note_div" style="display: none;">
                                                <textarea class="ckeditor" id="notes" name="notes" rows="8" cols="60"></textarea>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <label>Attachment</label>
                                                        <input type="file" class="filestyle" id="insert_notes_attachment" name="notes_attachment" />
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="notes-not-found  no_note"
                                                  <?php if (empty($applicant_notes)) { ?>style="display: block;" <?php } else { ?> style="display: none;"<?php } ?>>No Notes Found</span>
                                                  <?php foreach ($applicant_notes as $note) { ?>
                                                <article class="notes-list" id="notes_<?= $note['sid'] ?>">
                                                    <h2>
                                                        <div class="col-xs-6 col-sm-6 col-md-12 col-lg-6 text-left">
                                                            <span id="<?= $note['sid'] ?>"><?= $note['notes'] ?></span>
                                                            <p class="postdate"><?=reset_datetime(array('datetime' => $note['insert_date'], '_this' => $this, 'from_format' => 'b d Y H:i a', 'format' => 'default')); ?></p>
                                                        </div>
                                                        <?php if(!empty($note['attachment'])) { ?>
                                                            <div class="col-xs-6 col-sm-6 col-md-12 col-lg-6 text-right">
                                                                <?php if ($note['attachment_extension'] == 'png' || $note['attachment_extension'] == 'jpg' || $note['attachment_extension'] == 'jpe' || $note['attachment_extension'] == 'jpeg' || $note['attachment_extension'] == 'gif') { ?>
                                                                    <div class="img-thumbnail" style="max-width: 800px;">
                                                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $note['attachment']; ?>" />
                                                                    </div>
                                                                <?php } elseif($note['attachment_extension'] == 'doc' || $note['attachment_extension'] == 'docx') { ?>
                                                                    <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://view.officeapps.live.com/op/embed.aspx?src=<?php echo AWS_S3_BUCKET_URL . $note['attachment'] ?>" frameborder="0"></iframe>
                                                                <?php } elseif($note['attachment_extension'] == 'mp3' || $note['attachment_extension'] == 'aac') { ?>
                                                                    <audio width="800" controls>
                                                                        <?php if($note['attachment_extension'] == 'mp3') { ?>
                                                                            <source src="<?php echo AWS_S3_BUCKET_URL . $note['attachment']; ?>" type="audio/mpeg">
                                                                        <?php } else if($note['attachment_extension'] == 'ogg') { ?>
                                                                            <source src="<?php echo AWS_S3_BUCKET_URL . $note['attachment']; ?>" type="audio/ogg">
                                                                        <?php } else if($note['attachment_extension'] == 'wav') { ?>
                                                                            <source src="<?php echo AWS_S3_BUCKET_URL . $note['attachment']; ?>" type="audio/wav">
                                                                        <?php } ?>
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                <?php } elseif($note['attachment_extension'] == 'pdf') { ?>
                                                                    <iframe style="width: 100%; height: 600px" class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . $note['attachment'] ?>&embedded=true" frameborder="0"></iframe>
                                                                <?php } ?>
                                                                <br />
                                                                <br />
                                                                <a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL . $note['attachment']; ?>" download="download">Download Attachment</a>
                                                                <br />
                                                                <br />
                                                            </div>
                                                        <?php } ?>
                                                        <div class="edit-notes">
                                                            <a href="javascript:;"
                                                               style="height: 20px; line-height: 0; color: white; font-size: 10px;"
                                                               class="grayButton siteBtn notes-btn"
                                                               onclick="modify_note(<?= $note['sid'] ?>)">View /
                                                                Edit</a>
                                                            <a href="javascript:;"
                                                               style="height: 20px; line-height: 0; color: white; font-size: 10px;"
                                                               class="siteBtn notes-btn btncancel"
                                                               onclick="delete_note(<?= $note['sid'] ?>)">Delete</a>
                                                        </div>
                                                    </h2>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                                <div class="universal-form-style-v2" style="display: none" id="edit_notes">
                                    <form name="edit_note" action="<?php echo base_url('applicant_profile/insert_notes') ?>" method="POST" enctype="multipart/form-data">
                                        <div class="form-title-section">
                                            <h2>Applicant Notes</h2>
                                            <div class="form-btns">
                                                <input type="submit" name="note_submit" value="Update">
                                                <input onclick="cancel_notes()" type="button" name="cancel" value="Cancel">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <textarea class="ckeditor" name="my_edit_notes" id="my_edit_notes" cols="67" rows="6"></textarea>
                                        <input type="hidden" name="action" value="edit_note">
                                        <input type="hidden" name="applicant_job_sid" value="<?php echo $id; ?>">
                                        <input type="hidden" name="job_list_sid" value="<?php echo $job_list_sid;?>">
                                        <input type="hidden" name="applicant_email" value="<?php echo $email; ?>">
                                        <input type="hidden" name="sid" id="sid" value="">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <label>Attachment</label>
                                                <input type="file" class="filestyle" id="update_notes_attachment" name="notes_attachment" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
            <?php           } else { ?>
                                <div class="universal-form-style-v2" id="show_hide">
                                    <div class="form-title-section">
                                        <h2>Applicant Notes</h2>
                                    </div>
                                    <div class="tab-header-sec">
                                        <p class="questionnaire-heading">You are not Authorised</p>
                                    </div>
                                </div>
            <?php           } ?>
                            </div>
                            <!-- #tab3 -->
                            <div id="tab4" class="tabs-content">
                                <form enctype="multipart/form-data" action="<?php echo base_url('applicant_profile/applicant_message') ?>" method="post">
                                    <div class="compose-message">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <label>Email Template</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="template" id="template">
                                                            <option id="" data-name=""  data-subject="" data-body="" value="">Please Select</option>
                                                            <?php if(!empty($portal_email_templates)) { ?>
                                                                <?php foreach($portal_email_templates as $template) { ?>
                                                                    <option id="template_<?php echo $template['sid']; ?>" data-name="<?php echo $template['template_name']?>"  data-subject="<?php echo $template['subject']; ?>" data-body="<?php echo htmlentities($template['message_body']); ?>"  value="<?php echo $template['sid']; ?>"><?php echo $template['template_name']; ?></option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option id="template_" data-name=""  data-subject="" data-body="" value="">No Custom Template Defined</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="form-title-section">
                                        <h2>Attachments</h2>
                                    </div>
                                    <?php if(!empty($portal_email_templates)) {
                                        foreach ($portal_email_templates as $template) {?>
                                            <ul id="<?php echo $template['sid']; ?>" class="temp-attachment list-group" style="display: none; float: left; width: 100%;">
                                                <?php if (sizeof($template['attachments']) > 0){
                                                    foreach ($template['attachments'] as $attachment) { ?>
                                                        <li class="list-group-item"><?php echo $attachment['original_file_name'] ?></li>
                                                    <?php }
                                                }
                                                else{ ?>
                                                    <li class="list-group-item">No Attachments</li>
                                                <?php }?>
                                            </ul>
                                            <?php
                                        }
                                    }?>
                                    <ul class="list-group" id="empty-attachment" style="float: left; width: 100%;">
                                        <li class="list-group-item">No Attachments</li>
                                    </ul>

                                    <div class="form-title-section">
                                        <h2>messages</h2>
                                        <div class="form-btns message">
                                            <div class="btn-inner">
                                                <input type="file" name="message_attachment" class="choose-file-filed">
                                                <a href="" class="select-photo">Add Attachment</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-div">
                                        <div class="comment-box">
                                            <div class="textarea">
                                                <input type="hidden" name="to_id" value="<?= $email ?>">
                                                <input type="hidden" name="from_type" value="employer">
                                                <input type="hidden" name="to_type" value="applicant">
                                                <input type="hidden" name="applicant_name" value="<?= $applicant_info["first_name"] ?> <?= $applicant_info["last_name"] ?>">
                                                <input type="hidden" name="job_id" value="<?= $id ?>">
                                                <input type="hidden" name="users_type" value="applicant">
                                                <input id="applicantSubject" class="message-subject" required="required" name="subject" type="text" placeholder="Enter Subject (required)"/>
                                                <textarea style="padding:5px; height:200px; width:100%;" class="ckeditor" cols="40" id="applicantMessage" required="required" name="message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-btn">
                                        <input type="submit" value="Send Message">
                                    </div>                                    
                                </form>
                                <div class="respond">
                <?php               if (count($applicant_message) > 0) {
                                        foreach ($applicant_message as $message) { ?>
                                            <article <?php if ($message['outbox'] == 1) { ?>class="reply"<?php } ?> id="delete_message<?php echo $message['id']; ?>">
                                                <figure>
                                                    <img <?php if (empty($message['profile_picture'])) { ?>
                                                            src="<?= base_url() ?>assets/images/attachment-img.png"
                                                        <?php } else { ?>
                                                            src="<?php echo AWS_S3_BUCKET_URL . $message['profile_picture']; ?>" width="48"
                                                        <?php } ?> >
                                                </figure>
                                                <div class="text">
                                                    <div class="message-header">
                                                        <div class="message-title">
                                                            <h2><?php   if (!empty($message['first_name'])) {
                                                                            echo ucfirst($message['first_name']);
                                                                            if (!empty($message['last_name'])) {
                                                                                echo " " . ucfirst($message['last_name']);
                                                                            }
                                                                        } else {
                                                                            echo $message['username'];
                                                                        } ?>
                                                            </h2>
                                                        </div>
                                                        <ul class="message-option">
                                                            <li>
                                                                <time><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this)); ?></time>
                                                            </li>
                                                            <?php if ($message['outbox'] == 1) { ?>
                                                                <!--  <li>
                                                                        <a class="action-btn" onclick="resend_message(<?php //echo $message['id']; ?>)" href="javascript:;">
                                                                            <i class="fa fa-refresh"></i>
                                                                            <span class="btn-tooltip">Resend</span>
                                                                        </a>
                                                                    </li>-->
                                                            <?php } ?>
                                                            <?php if ($message['attachment']) { ?>
                                                                <li>
                                                                    <a class="action-btn"
                                                                       href="<?php echo AWS_S3_BUCKET_URL . $message['attachment']; ?>">
                                                                        <i class="fa fa-download"></i>
                                                                        <span class="btn-tooltip">Download File</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <li>
                                                                <a class="action-btn remove"
                                                                   onclick="delete_message(<?php echo $message['id']; ?>)"
                                                                   href="javascript:;">
                                                                    <i class="fa fa-remove"></i>
                                                                    <span class="btn-tooltip">Delete</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <span><?php echo ucfirst($message['subject']); ?></span>
                                                    <p><?php echo ucfirst($message['message']); ?></p>
                                                </div>
                                            </article>
                                            <?php
                                        }
                                    } ?>
                                </div>
                            </div>
                            <!-- #tab4 -->
                            <div id="tab5" class="tabs-content">
                         <?php  $this->load->view('manage_employer/application_tracking_system/review_rating_partial');?>
                            </div>
                            <!-- #tab5 -->
                            <div id="tab6" class="tabs-content">
                                <?php $this->load->view('manage_employer/application_tracking_system/'.( $is_new_calendar ? 'calendar_events_partial_ajax' : 'calendar_events_partial' ).'');?>
                            </div>
                            <!-- #tab6 -->
                            <?php if($phone_sid != ''){ ?>
                            <div id="tab7" class="tabs-content">
                                <?php $this->load->view('manage_employer/application_tracking_system/sms_partial');?>
                            </div>
                            <!-- #tab7 -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->

<!--file opener modal starts-->
<form id="loginForm" style="display: none">
    <fieldset class="confirm-hireed-employee">
        <input type="checkbox" required id="myCheckbox" onclick="check_status(this);"/>
        <label for="myCheckbox">Are you sure you want to Send Onboarding Docs to this applicant? </label>
        <div class="btn-panel">
            <ul>
                <li>
                    <input id="yes-btn" class="submit-btn" type="submit" value="Yes!"/>
                </li>
            </ul>
            <label>Note: When you click "Yes" and confirm that you want to send HR Docs to this candidate their profile
                will be moved out of the Applicant tracking system and into the Employee/Team member onboarding
                area.</label>
        </div>
    </fieldset>
    <div class="clear"></div>
</form>
<!-- Modal for Rejection Information -->
<div id="rejection_info_modal" class="modal modal-danger fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Rejection Details</h4>
            </div>
            <div id="" class="modal-body">
                <div class="row">
                    <div id="modal_body" class="col-xs-12"></div>
                </div>

            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if (sizeof($applicant_jobs) > 0) {?>
    <?php foreach ($applicant_jobs as $applicant_job) { ?>
        <div id="job-id-<?php echo $applicant_job['sid']; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-header-bg">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
    <?php                   if($applicant_job['Title'] != "") {
                                echo $applicant_job['Title'];
                            } else {
                                echo $applicant_job['job_title'];
                            } ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="compose-message">
                            <div class="universal-form-style-v2">
                                <form id="resendQueForm-<?php echo $applicant_job['sid'];?>" action="<?php echo base_url('resend_screening_questionnaire' . '/' . $applicant_info['sid'] . '/' . $applicant_job['sid'] . '/' . $applicant_job['job_sid'])?>" method="post">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <label>Questionnaire:</label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="questionnaire" id="questionnaire" required>
                                                        <option value="">Select Questionnaire</option>
                                                <?php   foreach($questionnaires as $questionnaire){
                                                            $select = $applicant_job['questionnaire_sid'] == $questionnaire['sid'] ? 'selected="selected"' : '';
                                                            if($questionnaire['que_count']>0)
                                                                echo '<option value="'.$questionnaire['sid'].'" '.$select.' >'.$questionnaire['name'].'</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <div class="btn-panel">
                                                <input type="submit" class="submit-btn" value="Send">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" data-dismiss="modal"/>
                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <script language="JavaScript" type="text/javascript">
            $(document).ready(function(){
                $("#resendQueForm-<?php echo $applicant_job['sid'];?>").validate({
                    ignore: ":hidden:not(select)",
                    rules: {
                        questionnaire: {
                            required: true
                        }
                    },
                    messages: {
                        questionnaire: {
                            required: 'Please Select Questionnaire'
                        }
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
            });
        </script>
    <?php }
} ?>

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
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/chosen.jquery.js"></script>
<script language="JavaScript" type="text/javascript">
    var mylimit = parseInt($('#choiceLimit').html());
    multiselectbox();
    $(".chosen-select").chosen({max_selected_options: mylimit});
    $(".chosen-select").bind("liszt:maxselected", function () {});
    $(".chosen-select").chosen().change(function () {});

    function multiselectbox() {
        var items_length = $('#job_fit_category_sid :selected').length;
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

    $(document).ready(function () {
        <?php if(check_access_permissions_for_view($security_details, 'review_score')) { ?>
                CKEDITOR.replace('rating_comment');
        <?php } ?>
        CKEDITOR.replace('applicantMessage');
        $('#interview_questionnaire').on('change',function(){
            $('#btn_launch_interview').attr('href', $(this).val());
        }).trigger('change');

        $('#form_rejection_response').hide();

        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function () {
            }
        });
        $('#template').on('change', function() {
            var template_sid = $(this).val();
            var msg_subject = $('#template_' + template_sid).attr('data-subject');
            var msg_body = $('#template_' + template_sid).attr('data-body');
            $('#applicantSubject').val(msg_subject);
//            $('#applicantMessage').html($(msg_body).text());
            CKEDITOR.instances.applicantMessage.setData(msg_body);
            $('.temp-attachment').hide();
            $('#empty-attachment').hide();
            $('#'+template_sid).show();
            if(template_sid == ''){
                $('#empty-attachment').show();
            }
        });

        var pre_selected = '<?php echo !empty($applicant_info['YouTube_Video']) ? $applicant_info['video_type'] : ''; ?>';
        if(pre_selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (pre_selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if(pre_selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        } else {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
    });

    function fShowRejectionResponseForm(source) {
        if ($(source).prop('checked')) { //console.log('checked');
            $('#form_rejection_response').show();
        } else { //console.log('unchecked');
            $('#form_rejection_response').hide();
        }
    }

    function fValidateRejectionResponseForm() {
        $('#form_rejection_response').validate();

        if ($('#form_rejection_response').valid()) {
            $('#form_rejection_response').submit();
        }
    }

    CKEDITOR.replace('my_edit_notes');
    CKEDITOR.replace('notes');

    function remove_event(event_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this event?',
            function () {
                var my_request;
                my_request = $.ajax({
                    url: '<?php echo base_url('calendar/tasks');?>',
                    type: 'POST',
                    data: {
                        'action': 'delete_event',
                        'event_sid': event_sid
                    }
                });

                my_request.success(function(response){
                    $('#remove_li'+event_sid+'').remove();
                    $('.btn').removeClass('disabled').prop('disabled', false);
                    // window.location.reload();
                });
            },
            function () {
                alertify.error('Canceled!');
            });
    }

    function delete_message(val) {
        var sid = val;
        alertify.defaults.glossary.title = 'Delete Message';
        alertify.confirm("Are you sure you want to delete this message?",
            function () {
                $.ajax({
                    url: "<?= base_url('applicant_profile/deleteMessage') ?>?action=delete_message&sid=" + sid,
                    success: function (data) {
                        $('#delete_message' + val).hide();
                        alertify.success('Message deleted successfully.');
                    }
                });
            },
            function () {
            });
    }

    function resend_message(id) {
        url = "<?= base_url() ?>applicant_profile/resend_message";
        alertify.dialog('confirm').set({
            'title ': 'Confirmation',
            'labels': {ok: 'Yes', cancel: 'No'},
            'message': 'Are you sure you want to Resend this Message?',
            'onok': function () {
                $.post(url, {id: id})
                    .done(function (data) {
                       // console.log(data);
                    });
            }
        }).show();
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 28));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            
            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function check_file_all(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html('<span>' + fileName + '</span>'); //console.log(fileName);
        } else {
            $('#name_' + val).html('Please Select'); //console.log('in else case');
        }
    }

    $(document).ready(function () {
        var myid = $('#state_id').html();
        
        setTimeout(function () {
            $("#country").change();
        }, 1000);

        if (myid) {
            setTimeout(function () {
                $('#state').val(myid);
            }, 1200);
        }

        <?php if ($notes_view == '1') { ?>
                $('#tab1,#tab2,#tab4,#tab5').css('display', 'none');
                $('#tab3').css('display', 'block');
                $('#tab1_nav,#tab2_nav,#tab4_nav,#tab5_nav').removeClass("active");
                $('#tab3_nav').addClass('active');
        <?php } ?>

        <?php if ($show_event == '1') { ?>
                $('#tab1,#tab2,#tab4,#tab5,#tab3').css('display', 'none');
                $('#tab6').css('display', 'block');
                $('#tab1_nav,#tab2_nav,#tab4_nav,#tab5_nav,#tab3_nav').removeClass("active");
                $('#tab6_nav').addClass('active');
        <?php } ?>

        <?php if ($show_message == '1') { ?>
                $('#tab1,#tab2,#tab6,#tab5,#tab3').css('display', 'none');
                $('#tab4').css('display', 'block');
                $('#tab1_nav,#tab2_nav,#tab6_nav,#tab5_nav,#tab3_nav').removeClass("active");
                $('#tab4_nav').addClass('active');
        <?php } ?>

        $('.interviewer_comment').click(function () {
            if ($('.interviewer_comment').is(":checked")) {
                $('.comment-div').fadeIn();
                $('#interviewerComment').prop('required', true);
            } else {
                $('.comment-div').hide();
                $('#interviewerComment').prop('required', false);
            }
        });

        $('.goto_meeting').click(function () {
            if ($('.goto_meeting').is(":checked")) {
                $('.meeting-div').fadeIn();
                $('#meetingId').prop('required', true);
                $('#meetingCallNumber').prop('required', true);
                $('#meetingURL').prop('required', true);
            } else {
                $('.meeting-div').hide();
                $('#meetingId').prop('required', false);
                $('#meetingCallNumber').prop('required', false);
                $('#meetingURL').prop('required', false);
            }
        });

        $('#candidate_msg').click(function () {
            if ($('#candidate_msg').is(":checked")) {
                $('.message-div').fadeIn();
                $('#applicantMessage').prop('required', true);
            } else {
                $('.message-div').hide();
                $('#applicantMessage').prop('required', false);
            }
        });

        $('.contact_id').select2({
            placeholder: "Select interviewer",
            allowClear: true
        });

        $('.select2-dropdown').css('z-index', '99999999999999999999999');

        $('.eventendtime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                time = $('.eventstarttime').val();
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                        minTime: $('.eventstarttime').val() ? timeFinal : false
                    }
                )
            }
        });

        $('.eventstarttime').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                this.setOptions({
                        maxTime: $('.eventendtime').val() ? $('.eventendtime').val() : false
                    }
                )
            }
        });

        $('.eventendtime1').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {

//                console.log($('.in').find('.eventstarttime1').val());
                time = $('.in').find('.eventstarttime1').val();
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2)) + 15;
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                        minTime: $('.in').find('.eventstarttime1').val() ? timeFinal : false
                    }
                )
            }
        });

        $('.eventstarttime1').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15,
            onShow: function (ct) {
                this.setOptions({
                        maxTime: $('.in').find('.eventendtime1').val() ? $('.in').find('.eventendtime1').val() : false
                    }
                )
            }
        });

        $('.eventdate').datepicker({dateFormat: 'mm-dd-yy'}).val();
        $('#eventdate').datepicker({dateFormat: 'mm-dd-yy'}).val();
        $("#eventdate").datepicker("setDate", new Date());
        $('.selected').click(function () {
            $(this).next().css("display", "block");
        });

        $('#edit_button').click(function (event) {
            event.preventDefault();
            $('.info_edit').fadeIn();
            $('.info_view').hide();
        });

        $('#add_notes').click(function (event) {
            event.preventDefault();
            $('.note_div').fadeIn();
            $('.no_note').hide();
        });

        $('#cancel_note').click(function (event) {
            event.preventDefault();
            $('.note_div').hide();
            $('.no_note').fadeIn();
        });

        $('.view_button').click(function (event) {
            event.preventDefault();
            $('.info_edit').hide();
            $('.info_view').fadeIn();
        });

        $('#add_event').click(function () {
            $('.event_create').fadeIn();
            $('.event_detail').hide();
        });
    });

    function getStates(val, states) {
        var html = '';
        
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var sid = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + sid + '">' + name + '</option>';
            }
            
            $('#state').html(html);
        }
    }

    function modify_note(val) {
        var edit_note_text = document.getElementById(val).innerHTML;
        document.getElementById("sid").value = val;
        CKEDITOR.instances.my_edit_notes.setData(edit_note_text);
        $('#edit_notes').show();
        $('#show_hide').hide();
    }

    function cancel_notes() {
        $('#show_hide').show();
        $('#edit_notes').hide();
    }

    function cancel_event() {
        $('.event_detail').fadeIn();
        $('.event_create').hide();
    }

    function delete_note(id) {
        url = "<?= base_url() ?>applicant_profile/delete_note";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Note?",
            function () {
                $.post(url, {sid: id})
                    .done(function (data) {
                        location.reload();
                    });
            },
            function () {
                alertify.error('Canceled');
            });
    }

    function validate_form() {
        $("#event_form").validate({
            ignore: [],
            rules: {
                interviewer: {
                    required: true,
                }
            },
            messages: {
                interviewer: {
                    required: 'Please select an interviewer',
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    function hire_applicant() {
        alertify.confirm("Please Confirm Hire", "Are you sure you want to Hire applicant?",
            function () {
                $.ajax({
                    url: "<?= base_url() ?>hire_applicant/hire_applicant",
                    type: "POST",
                    data: {
                        id: "<?php echo $applicant_info['sid']; ?>",
                        email: "<?php echo $applicant_info['email']; ?>",
                        cid: "<?php echo $applicant_info['employer_sid']; ?>",
                        action: "hire_now"
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response[0] == 'error') {
                            alertify.error(response[1]);
                        } else {
                            alertify.success(response[1]); //console.log(response[2]);
                            window.location.assign(response[2]);
                        }
                    },
                    error: function (request, status, error) {
                        //console.log(request.responseText);
                    }
                });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function myPopup() {
    //$('#cc_send').addClass("disabled-btn");
        $('#loginForm').css('display', 'block');
        alertify.genericDialog || alertify.dialog('genericDialog', function () {
            return {
                main: function (content) {
                    this.setContent(content);
                },
                setup: function () {
                    return {
                        focus: {
                            element: function () {
                                return this.elements.body.querySelector(this.get('selector'));
                            },
                            select: true
                        },
                        options: {
                            basic: false,
                            maximizable: false,
                            resizable: false,
                            padding: false,
                            title: "Please Confirm Send Docs"
                        }
                    };
                },
                settings: {
                    selector: undefined
                }
            };
        });
        //force focusing password box
        alertify.genericDialog($('#loginForm')[0]);
    }

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        if ($('#myCheckbox').is(":checked")) {
            myFunctionAjax();
        }       
    });

    function check_status(source) {
        if ($(source).is(':checked')) {
            $('#yes-btn').removeAttr('disabled');
            $('#yes-btn').removeClass('disabled');
        } else {
            $('#yes-btn').attr('disabled', 'disabled');
            $('#yes-btn').addClass('disabled');
        }
    }

    $(document).ready(function () {
        $('#yes-btn').attr('disabled', 'disabled');
        $('#yes-btn').addClass('disabled-btn');
        $('.applied-jobs .selected').click(function () {
            $(this).closest("tr").css({'height': '420px'});
        });
        $('.applied-jobs .cross, .applied-jobs .label').click(function () {
            $(this).closest("tr").css({'height': 'auto'});
        });
    });

    function fSetApplicantForApproval(applicant_id, job_sid) {
        alertify.confirm(
            'Are You Sure!',
            'Are You Sure you Want to Get this Applicant Approved For Hiring?',
            function () { //console.log(applicant_id);
                var url = '<?php echo base_url('applicant_profile/ajax_responder'); ?>';
                var dataToSend = {
                    'applicant_id': applicant_id,
                    'job_sid': job_sid,
                    'perform_action': 'set_applicant_for_approval'
                };

                var myRequest;
                myRequest = $.ajax({
                    url: url,
                    data: dataToSend,
                    type: 'POST'
                });

                myRequest.done(function (response) {
                    if (response == 'success') {
                        url = '<?php echo base_url('applicant_profile') ?>' + '/' + applicant_id;
                        window.location = url;
                    }
                });
            },
            function () {
                //Cancel
            }
        );
    }

    function fShowRejectionInformation() {
        $('#rejection_info_modal').find('#modal_body').html('testing');
        $('#rejection_info_modal').modal('toggle');
    }

    function func_show_rejection_information(applicant_sid, company_sid, job_sid) {
        var my_request;

        my_request = $.ajax({
            url: '<?php echo base_url('application_tracking_system/ajax_responder'); ?>',
            type: 'POST',
            data: {
                'perform_action': 'get_applicant_approval_response_form',
                'applicant_sid': applicant_sid,
                'job_sid': job_sid,
                'company_sid': company_sid
            },
            responseType:'json'
        });

        my_request.done(function (response) {
            if(response != ''){
                response = JSON.parse(response);
            }
            console.log(response);
            if(response.status == 'success'){
                $('#popupmodal #popupmodalbody').html(response.view);
                $('#popupmodal').modal('toggle');
            }
        });
    }

    function func_toggle_approval_response_textarea(source){
        if($(source).prop('checked') == true) {
            if( $('#response_container').css('display') == 'none') {
                $('#response_container').show('blind');
                $('#response_container').prop('disabled', false);
            }
        } else {
            $('#response_container').hide('blind');
            $('#response_container').prop('disabled', true);
        }
    }

    //Code Related to Status Bar -start
    $(document).ready(function () {
        $('.selected').click(function () {
            $(this).next().css("display", "block");
        });

        $('.label').click(function () {
            $(this).parent().find('.check').css("visibility", "hidden");
            $(this).parent().prev().html($(this).find('#status').html());
            $(this).find('.check').css("visibility", "visible");
            $(this).parent().prev().css("background-color", $(this).css("background-color"));
            //var my_status = $(this).find('#status').html();
            var status_name = $(this).attr('data-status_name');
            var status_sid = $(this).attr('data-status_sid');
            //console.log(status_name + ' ' + status_sid);
            //var my_id = <?= $id ?>;
            var my_id = $(this).parent().find('#id').html();
            var my_url = "<?= base_url() ?>/applicant_profile/updateEmployerStatus";
            var my_request;

            my_request = $.ajax({
                url :  my_url,
                type : "POST",
                data : {
                    "sid" : my_id,
                    "status" : status_name,
                    "status_sid": status_sid
                }
            });

            my_request.done(function (response) {
                if (response == 'success') {
                    alertify.success("Candidate status updated successfully.");
                } else {
                    alertify.error("Could not update Candidate Status.");
                }
            });
        });

        $('.label').hover(function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 15,
            }, "fast");
        }, function () {
            $(this).find('#status').animate({
                'padding-top': 0,
                'padding-right': 0,
                'padding-bottom': 0,
                'padding-left': 5,
            }, "fast");
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        var $selected = $('.selected');
        $('.selected').each(function(){
            class_name = $(this).attr('class').split(' ');
            if(class_name.length > 0) {
                $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
            }
        });

        $('.cross').click(function () {
            $(this).parent().parent().css("display", "none");
        });

        $('.label').click(function () {
            $(this).parent().css("display", "none");
        });
    });

    function func_show_permanent_rejection_info(){
        alertify.alert('Permanently Rejected', 'This Applicant has been permanently rejected!');
    }

    $(document).ready(function () {
        $('#popupmodal').on('shown.bs.modal', function () {
            $('#response_container').hide('blind');
            $('#response_container').prop('disabled', true);
        });
    });

    function func_validate_rejection_response_form(){
        $('#form_applicant_rejection_response').validate({
            'ignore': '[disabled]'
        });

        if ($('#form_applicant_rejection_response').valid()) {
            var form_data = func_convert_form_to_json_object('form_applicant_rejection_response');
            var my_request;

            my_request = $.ajax({
                'url': '<?php echo base_url('applicant_approval_management/ajax_responder'); ?>',
                'type': 'POST',
                'responseType': 'json',
                'data': form_data
            });

            my_request.done(function(response) {
                if(response == 'success'){
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something Went Wrong!');
                }
            });
        }
    }

    function myFunctionAjax() {
        $.ajax({
            url: "<?= base_url() ?>hire_applicant/hire_applicant",
            type: "POST",
            data: {
                'id': "<?php echo $applicant_info['sid']; ?>",
                'email': "<?php echo $applicant_info['email']; ?>",
                'cid': "<?php echo $applicant_info['employer_sid']; ?>",
                'action': "hire_now"
            },
            dataType: "json",
            success: function (response) {
                if (response[0] == 'error') {
                    alertify.error(response[1]);
                } else {
                    alertify.success(response[1]);
                    window.location.assign(response[2]);
                }
            },
            error: function (request, status, error) {
                //console.log(request.responseText);
            }
        });
    }

    function func_get_hire_applicant_form(company_sid, applicant_sid, job_sid, email) {
        var my_request;

        my_request = $.ajax({
            url: '<?php echo base_url('application_tracking_system/ajax_responder'); ?>',
            type: 'POST',
            responseType: 'json',
            data: {
                'perform_action': 'get_hire_applicant_form',
                'company_sid': company_sid,
                'applicant_sid': applicant_sid,
                'job_sid': job_sid,
                'email': email
            }
        });

        my_request.done(function(response) {
            if(response != ''){
                response = JSON.parse(response); // comment it at LOCALHOST
            }
            
            $('#popupmodal #popupmodallabel').html('Hire Applicant');
            $('#popupmodal #popupmodalbody').html(response.view);
            $('#popupmodal').modal('toggle');
        });
    }

    function func_validate_hire_form_and_submit() {
        var form_data = func_convert_form_to_json_object('form_hire_applicant_send_documents');
        var my_request;

        my_request = $.ajax({
            url: "<?php echo base_url('hire_applicant/hire_applicant'); ?>",
            type: "POST",
            data: form_data,
            dataType: "json",
        });

        my_request.done(function(response){
            if (response[0] == 'error') {
                $('#popupmodal').modal('toggle');
                alertify.error(response[1]);
            } else {
                $('#popupmodal').modal('toggle');
                alertify.success(response[1]);
                window.location.assign(response[2]);
            }
        });
    }
    
    function send_questionnaire_confirmation() {
        alertify.defaults.glossary.title = 'Send Questionnaire Confirmation';
        alertify.confirm("Are you sure you want to send Screening Questionnaire?",
            function () {
                document.getElementById('questionnaire_confirm_click').click();
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    $('.startdate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();

    $('.add_edit_submit').click(function () {
        if($('input[name="video_source"]:checked').val() != 'no_video'){
            var flag = 0;
            if($('input[name="video_source"]:checked').val() == 'youtube'){
                
                if($('#yt_vm_video_url').val() != '') { 

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
            } else if($('input[name="video_source"]:checked').val() == 'vimeo'){
                
                if($('#yt_vm_video_url').val() != '') {              
                    var flag = 0;
                    var myurl = "<?= base_url() ?>learning_center/validate_vimeo";
                    $.ajax({
                        type: "POST",
                        url: myurl,
                        data: {url: $('#yt_vm_video_url').val()},
                        async : false,
                        success: function (data) {
                            if (data == false) {
                                alertify.error('Not a Valid Vimeo URL');
                                flag = 0;
                                return false;
                            } else {
                                flag = 1;
                            }
                        },
                        error: function (data) {
                        }
                    });
                } else {
                    flag = 0;
                    alertify.error('Please add valid vimeo video link.');
                    return false;
                }
            } else if ($('input[name="video_source"]:checked').val() == 'uploaded') {
                var old_uploaded_video = $('#pre_upload_video_url').val();
                if(old_uploaded_video != ''){
                    flag = 1;
                } else {
                    var file = upload_video_checker('upload_video');
                    if (file == false){
                        flag = 0;
                        return false;    
                    } else {
                        flag = 1;
                    }
                }
            }

            if(flag == 1){
                $('#my_loader').show(); 
                $('#applicant_profile_form').submit();
            } else {
                return false;
            }
        } else {
            $('#applicant_profile_form').submit();;
        }
    });

    $('.video_source').on('click', function(){
        var selected = $(this).val();
        if(selected == 'youtube'){
            $('#label_youtube').show();
            $('#label_vimeo').hide();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if (selected == 'vimeo') {
            $('#label_youtube').hide();
            $('#label_vimeo').show();
            $('#youtube_vimeo_input').show();
            $('#upload_input').hide();
        } else if(selected == 'uploaded') {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').show();
        }  else  {
            $('#youtube_vimeo_input').hide();
            $('#upload_input').hide();
        }
    });

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
                } else{
                    var file_size = Number(($("#" + val)[0].files[0].size/1024/1024).toFixed(2));
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


    // Set targets
    var _pn = $("#<?=$field_phone;?>"),
    _spn = $("#<?=$field_sphone;?>"),
    _opn = $("#<?=$field_ophone;?>");

    // Reset phone number on load
    // Added on: 05-07-2019
    var val = fpn(_pn.val());
    if(typeof(val) === 'object'){
        _pn.val(val.number);
        setCaretPosition(_pn, val.cur);
    } else _pn.val(val);
    // Reset phone number on load
    _pn.keyup(function(){
        var val = fpn($(this).val());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    });


    var val2 = fpn(_spn.val());
    if(typeof(val2) === 'object'){
        _spn.val(val2.number);
        setCaretPosition(_spn, val2.cur);
    } else _spn.val(val2);
    // Reset phone number on load
    _spn.keyup(function(){
        var val = fpn($(this).val());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    });

    // var val3 = fpn(_opn.val());
    // if(typeof(val3) === 'object'){
    //     _opn.val(val3.number);
    //     setCaretPosition(_opn, val3.cur);
    // } else _opn.val(val3);
    // // Reset phone number on load
    // _opn.keyup(function(){
    //     var val = fpn($(this).val());
    //     if(typeof(val) === 'object'){
    //         $(this).val(val.number);
    //         setCaretPosition(this, val.cur);
    //     } else $(this).val(val);
    // });

    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        // 
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else{
            var af = '', an = '', cur = 1;
            if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
            if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
            if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
            if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
            if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
            if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
            if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
            if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
            if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
            if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

            if(is_return) return match === null ? false : true;

            return { number: default_number.replace(af, an), cur: cur };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if(elem != null) {
            if(elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            }
            else {
                if(elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }


     $('#applicant_profile_form').submit(function(e){
         // TODO
        var is_error = false;
        // Check for phone number
        if(_pn.val() != '' && _pn.val().trim() != '(___) ___-____' && !fpn(_pn.val(), '', true)){
            alertify.alert('Invalid mobile number provided.', function(){ return; });
            e.preventDefault();
            is_error = true;
            return;
        }
        // Check for secondary number
        if(_spn.val() != '' && _spn.val().trim() != '(___) ___-____' && !fpn(_spn.val(), '', true)){
            alertify.alert('Invalid secondary mobile number provided.', function(){ return; });
            e.preventDefault();
            is_error = true;
            return;
        }
        // Check for other number
        // if(_opn.val() != '' && _opn.val().trim() != '(___) ___-____' && !fpn(_opn.val(), '', true)){
        //     alertify.alert('Invalid other phone number provided.', function(){ return; });
        //     e.preventDefault();
        //     is_error = true;
        //     return;
        // }

        if (is_error === false) {
            // Remove and set phone extension
            $('#js-phonenumber').remove();
            $('#js-secondary-phonenumber').remove();
            $('#js-other-phonenumber').remove();
            // Check the fields
            if(_spn.val().trim() == '(___) ___-____') _spn.val('');
            else $(this).append('<input type="hidden" id="js-secondary-phonenumber" name="txt_secondary_phonenumber" value="+1'+(_spn.val().replace(/\D/g, ''))+'" />');

            // if(_opn.val().trim() == '(___) ___-____') _opn.val('');
            // else $(this).append('<input type="hidden" id="js-other-phonenumber" name="txt_other_phonenumber" value="+1'+(_opn.val().replace(/\D/g, ''))+'" />');
            
            if($('input[name="video_source"]:checked').val() != 'no_video'){
                $('#my_loader').show();
            }
            $(this).append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+(_pn.val().replace(/\D/g, ''))+'" />');
        }



        
     });

</script>
