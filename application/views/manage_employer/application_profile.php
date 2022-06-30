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
                        <?php if ($applicant_info['archived'] == 0) { ?>
                            <?php if($applicants_approval_module_status == 0) { ?>
                                <div class="hire-applicant">
                                    <button class="hire-btn" onclick="myPopup()"><i
                                            class="fa fa-refresh fa-check-square"></i>Send Docs
                                    </button>
                                </div>
                            <?php } elseif($applicants_approval_module_status == 1 ) { ?>
                                <?php if($applicant_info['approval_status'] == null || $applicant_info['approval_status'] == 'NULL' || $applicant_info['approval_status'] == 'null') { ?>
                                    <div class="hire-applicant">
                                        <button class="hire-btn" onclick="fSetApplicantForApproval(<?php echo $applicant_info['sid'];?>);"><i class="fa fa-refresh fa-check-square"></i>Get Hiring Approval</button>
                                    </div>
                                <?php } elseif ($applicant_info['approval_status'] == 'pending') { ?>
                                    <div class="hire-applicant">
                                        <div class="hire-btn" onclick=""><i class="fa fa-hourglass-half"></i>Approval Pending</div>
                                    </div>
                                <?php } elseif($applicant_info['approval_status'] == 'approved') { ?>
                                    <div class="hire-applicant">
                                        <button class="hire-btn" onclick="myPopup()"><i
                                                class="fa fa-refresh fa-check-square"></i>Send Docs
                                        </button>
                                    </div>
                                <?php } elseif($applicant_info['approval_status'] == 'rejected') { ?>
                                    <div class="hire-applicant">
                                        <button class="hire-btn" onclick="fShowRejectionInformation();"><i class="fa fa-ban"></i><?php echo ucwords($applicant_info['approval_status']); ?> Click for Details</button>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <article>
                            <figure>
                                <img src="<?php echo AWS_S3_BUCKET_URL;
                                if (isset($applicant_info['pictures']) && $applicant_info['pictures'] != "") {
                                    echo $applicant_info['pictures'];
                                } else {
                                    ?>default_pic-ySWxT.jpg<?php } ?>" alt="Profile Picture">
                            </figure>
                            <div class="text">
                                <h2><?php echo $applicant_info["first_name"]; ?> <?= $applicant_info["last_name"] ?></h2>
                                <span>
                                    <?php if ($applicant_info['archived'] == 1) { ?>
                                        Archived&nbsp;
                                    <?php } ?>
                                    <?= $applicant_info["applicant_type"]; ?>
                                </span>

                                <div class="start-rating">
                                    <input readonly="readonly"
                                           id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                           type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                           data-size="xs">
                                </div>
                            </div>
                        </article>
                    </div>
                    <div id="HorizontalTab" class="HorizontalTab">
                        <ul class="resp-tabs-list hor_1">
                            <li><a href="javascript:;">Personal Info</a></li>
                            <li><a href="javascript:;">Questionnaire</a></li>
                            <li><a href="javascript:;">Notes</a></li>
                            <li><a href="javascript:;">Messages</a></li>
                            <li><a href="javascript:;">reviews</a></li>
                            <li><a href="javascript:;">Calendar</a></li>
                        </ul>
                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div
                                    class="universal-form-style-v2 info_view" <?php if ($edit_form) { ?> style="display: none;" <?php } ?>>
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
                                            <label>phone number:</label>

                                            <p><?php echo $applicant_info["phone_number"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>address:</label>

                                            <p><?php echo $applicant_info["address"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>city:</label>

                                            <p><?php echo $applicant_info["city"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>date applied:</label>

                                            <p><?php echo $applicant_info["date_applied"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>zipcode:</label>

                                            <p> <?php echo $applicant_info["zipcode"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>country:</label>

                                            <p> <?php echo $applicant_info["country_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>state:</label>

                                            <p> <?php echo $applicant_info["state_name"] ?></p>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Job Title:</label>

                                            <p> <?php echo $job_title ?></p>
                                        </li>

                                        <li class="form-col-50-right">
                                            <label>Secondary Email:</label>
                                            <?php if (isset($extra_info["secondary_email"])) { ?>
                                                <p> <?php echo $extra_info["secondary_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Secondary Phone Number:</label>
                                            <?php if (isset($extra_info["secondary_PhoneNumber"])) { ?>
                                                <p> <?php echo $extra_info["secondary_PhoneNumber"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Other Email:</label>
                                            <?php if (isset($extra_info["other_email"])) { ?>
                                                <p> <?php echo $extra_info["other_email"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Other Phone Number:</label>
                                            <?php if (isset($extra_info["other_PhoneNumber"])) { ?>
                                                <p> <?php echo $extra_info["other_PhoneNumber"]; ?></p>
                                            <?php } ?>
                                        </li>
                                        <?php if (isset($applicant_info["referred_by_name"]) && $applicant_info["referred_by_name"] != null && $applicant_info["referred_by_name"] != '') { ?>
                                            <li class="form-col-50-right">
                                                <label>Referred By:</label>

                                                <p> <?php echo $applicant_info["referred_by_name"]; ?></p>
                                            </li>
                                        <?php } ?>
                                        <?php if (isset($applicant_info["referred_by_email"]) && $applicant_info["referred_by_email"] != null && $applicant_info["referred_by_email"] != '') { ?>
                                            <li class="form-col-50-left">
                                                <label>Referrer Email:</label>

                                                <p> <?php echo $applicant_info["referred_by_email"]; ?></p>
                                            </li>
                                        <?php } ?>
                                        <li class="form-col-100">
                                            <label>Linkedin public Profile URL</label>
                                            <?php if (isset($applicant_info["linkedin_profile_url"])) { ?>
                                                <p><a href="<?php echo $applicant_info["linkedin_profile_url"]; ?>"
                                                      target="_blank"> <?php echo $applicant_info["linkedin_profile_url"]; ?></a>
                                                </p>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                    <?php if (isset($applicant_info["YouTube_Video"]) && $applicant_info["YouTube_Video"] != "") { ?>
                                        <div class="applicant-video">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe
                                                    src="https://www.youtube.com/embed/<?php echo $applicant_info["YouTube_Video"] ?>"
                                                    frameborder="0" webkitAllowFullScreen mozallowfullscreen
                                                    allowFullScreen></iframe>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!--Edit part-->
                                <div <?php if ($edit_form) { ?>style="display: block;"
                                     <?php } else { ?>style="display: none;" <?php } ?>
                                     class="universal-form-style-v2 info_edit">
                                    <ul>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="form-title-section">
                                                <h2>Personal Information</h2>

                                                <div class="form-btns">
                                                    <input type="submit" value="save">
                                                    <input type="submit" value="cancel" class="view_button">
                                                </div>
                                            </div>
                                            <li class="form-col-50-left">
                                                <label>first name:<samp class="red"> * </samp></label>
                                                <input
                                                    class="invoice-fields  <?php if (form_error('first_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('first_name', $applicant_info["first_name"]); ?>"
                                                    type="text" name="first_name">
                                                <?php echo form_error('first_name'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>last name:<samp class="red"> * </samp></label>
                                                <input
                                                    class="invoice-fields  <?php if (form_error('last_name') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('last_name', $applicant_info["last_name"]); ?>"
                                                    type="text" name="last_name">
                                                <?php echo form_error('last_name'); ?>

                                            </li>
                                            <li class="form-col-50-left">
                                                <label>email:<samp class="red"> * </samp></label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('email', $applicant_info["email"]); ?>"
                                                    type="email" name="email">
                                                <?php echo form_error('email'); ?>

                                            </li>
                                            <li class="form-col-50-right">
                                                <label>phone number:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('phone_number', $applicant_info["phone_number"]); ?>"
                                                       type="text" name="phone_number">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>address:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('address', $applicant_info["address"]); ?>"
                                                       type="text" name="address">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>city:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('city', $applicant_info["city"]); ?>"
                                                       type="text" name="city">
                                            </li>

                                            <li class="form-col-50-left">
                                                <label>zipcode:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('zipcode', $applicant_info["zipcode"]); ?>"
                                                       type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>country:</label>

                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="country" id="country"
                                                            onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?= $active_country["id"]; ?>"
                                                                <?php if ($applicant_info['country'] == $active_country["id"]) { ?>
                                                                    selected
                                                                <?php } ?> >
                                                                <?= $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <?php $country_id = 227; ?>
                                            <li class="form-col-50-left">
                                                <label>state:</label>

                                                <p style="display: none;"
                                                   id="state_id"><?php echo $applicant_info['state']; ?></p>

                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="state" id="state">
                                                        <?php if (empty($country_id)) { ?>
                                                            <option value="">Select State</option>
                                                            <?php
                                                        } else {
                                                            foreach ($active_states[$country_id] as $active_state) {
                                                                ?>
                                                                <option value="<?= $active_state["id"] ?>"
                                                                        <?php if ($active_state["id"] == $applicant_info['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Youtube video:</label>
                                                <input class="invoice-fields"
                                                       value="<?php if ($applicant_info["YouTube_Video"] != NULL) { ?>https://www.youtube.com/watch?v=<?php }
                                                       echo set_value('YouTube_Video', $applicant_info["YouTube_Video"]); ?>"
                                                       type="text" name="YouTube_Video">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Jobs List:</label>

                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="job_sid" id="job_sid">
                                                        <option value="0">Select Job</option>
                                                        <?php foreach ($all_jobs as $job) { ?>
                                                            <option value="<?= $job["sid"] ?>"
                                                                    <?php if ($job["sid"] == $applicant_info['job_sid']) { ?>selected="selected" <?php } ?>><?= $job["Title"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>

                                            <li class="form-col-50-right">
                                                <label>Profile picture:</label>

                                                <div class="upload-file invoice-fields">
                                                    <span class="selected-file"
                                                          id="name_pictures">No file selected</span>
                                                    <input type="file" name="pictures" id="pictures"
                                                           onchange="check_file_all('pictures')"
                                                           accept=".jpg,.jpeg,.jpe,.png">
                                                    <a href="javascript:;" style="background: #549809;">Choose File</a>
                                                </div>
                                                <!-- <input type="file" name="pictures">-->
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
                                                <input
                                                    class="invoice-fields <?php if (form_error('secondary_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('secondary_email', $extra_info["secondary_email"]); ?>"
                                                    type="email" name="secondary_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>secondary phone number:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('secondary_PhoneNumber', $extra_info["secondary_PhoneNumber"]); ?>"
                                                       type="text" name="secondary_PhoneNumber">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>other email:</label>
                                                <input
                                                    class="invoice-fields <?php if (form_error('other_email') !== "") { ?> error <?php } ?>"
                                                    value="<?php echo set_value('other_email', $extra_info["other_email"]); ?>"
                                                    type="email" name="other_email">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>other phone number:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('other_PhoneNumber', $extra_info["other_PhoneNumber"]); ?>"
                                                       type="text" name="other_PhoneNumber">
                                            </li>
                                            <li class="form-col-100">
                                                <label>Linkedin Public Profile URL:</label>
                                                <input class="invoice-fields"
                                                       value="<?php echo set_value('linkedin_profile_url', $applicant_info["linkedin_profile_url"]); ?>"
                                                       type="text" name="linkedin_profile_url">
                                            </li>
                                            <div class="form-title-section">
                                                <div class="form-btns">
                                                    <input type="submit" value="save">
                                                    <input type="submit" value="cancel" class="view_button">
                                                </div>
                                            </div>
                                        </form>
                                    </ul>
                                </div>
                                <!--Edit part Ends-->
                            </div>
                            <!-- #tab1 -->
                            <div id="tab2" class="tabs-content">
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('.collapse').on('shown.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
                                        }).on('hidden.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
                                        });
                                    });
                                </script>
                                <?php if ($applicant_info['test']) { ?>
                                    <div class="tab-header-sec">
                                        <h2 class="tab-title">Screening Questionnaire</h2>

                                        <div class="tab-btn-panel">
                                            <span>Score : <?php echo $applicant_info["score"] ?></span>
                                            <?php if ($applicant_info['passing_score'] <= $applicant_info['score']) { ?>
                                                <a href="javascript:;">Pass</a>
                                            <?php } else { ?>
                                                <a href="javascript:;">Fail</a>
                                            <?php } ?>
                                        </div>
                                        <p class="questionnaire-heading">Question’s / Answer’s</p>
                                    </div>
                                    <div class="panel-group-wrp">
                                        <div class="panel-group" id="accordion">
                                            <?php
                                            $counter = 0;
                                            foreach ($applicant_info['questionnaire'] as $key => $value) {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse"
                                                               data-parent="#accordion"
                                                               href="#collapseOne_<?php echo $counter ?>">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne_<?php echo $counter ?>"
                                                         class="panel-collapse collapse">
                                                        <?php
                                                        if (is_array($value)) {
                                                            foreach ($value as $answer) {
                                                                ?>
                                                                <div class="panel-body">
                                                                    <?php echo $answer; ?>
                                                                </div>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <div class="panel-body">
                                                                <?php echo $value; ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php
                                                $counter++;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="tab-header-sec">
                                        <h2 class="tab-title">Screening Questionnaire</h2>
                                        <div class="applicant-notes">
                                            <div class="notes-not-found">No questionnaire found!</div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- #tab2 -->
                            <div id="tab3" class="tabs-content">
                                <div class="universal-form-style-v2" id="show_hide">


                                    <form action="<?php echo base_url('applicant_profile/insert_notes') ?>"
                                          method="POST" id="note_form">
                                        <input type="hidden" name="action" value="add_note">
                                        <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                        <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                        <div class="form-title-section">
                                            <h2>Applicant Notes</h2>

                                            <div class="form-btns">
                                                <input type="submit" style="display: none;" class="note_div"
                                                       value="save">
                                                <input type="button" id="cancel_note" style="display: none;"
                                                       class="note_div" value="cancel">
                                                <input type="submit" class="no_note" id="add_notes" value="Add note">
                                            </div>
                                        </div>

                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <div class="hr-ck-editor note_div" style="display: none;">
                                                <textarea class="ckeditor" id="notes" name="notes" rows="8"
                                                          cols="60"></textarea>
                                            </div>
                                            <span class="notes-not-found  no_note"
                                                  <?php if (empty($applicant_notes)) { ?>style="display: block;" <?php } else { ?> style="display: none;"<?php } ?>>No Notes Found</span>
                                            <?php foreach ($applicant_notes as $note) { ?>
                                                <article class="notes-list" id="notes_<?= $note['sid'] ?>">
                                                    <h2>
                                                        <span id="<?= $note['sid'] ?>"><?= $note['notes'] ?></span>

                                                        <p class="postdate"><?php echo date('m-d-Y', strtotime($note['insert_date'])); ?></p>

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
                                    <form name="edit_note"
                                          action="<?php echo base_url('applicant_profile/insert_notes') ?>"
                                          method="POST">
                                        <div class="form-title-section">
                                            <h2>Applicant Notes</h2>

                                            <div class="form-btns">
                                                <input type="submit" name="note_submit" value="Update">
                                                <input onclick="cancel_notes()" type="button" name="cancel"
                                                       value="Cancel">
                                            </div>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <textarea class="ckeditor" name="my_edit_notes" id="my_edit_notes" cols="67"
                                                  rows="6"></textarea>
                                        <input type="hidden" name="action" value="edit_note">
                                        <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                        <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                        <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                        <input type="hidden" name="sid" id="sid" value="">

                                    </form>
                                </div>
                            </div>
                            <!-- #tab3 -->
                            <div id="tab4" class="tabs-content">
                                <form enctype="multipart/form-data"
                                      action="<?php echo base_url('applicant_profile/applicant_message') ?>"
                                      method="post">
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
                                                <input type="hidden" name="to_type" value="admin">
                                                <input type="hidden" name="applicant_name"
                                                       value="<?= $applicant_info["first_name"] ?> <?= $applicant_info["last_name"] ?>">
                                                <input type="hidden" name="job_id" value="<?= $id ?>">
                                                <input type="hidden" name="users_type" value="applicant">

                                                <input class="message-subject" required="required" name="subject"
                                                       type="text" placeholder="Enter Subject (required)"/>
                                                <textarea id="applicantMessage" required="required"
                                                          name="message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-btn">
                                        <input type="submit" value="Send Message">
                                    </div>                                    
                                </form>
                                <div class="respond">
                                    <?php
                                    if (count($applicant_message) > 0) {
                                        foreach ($applicant_message as $message) {
                                            ?>
                                            <article <?php if ($message['outbox'] == 1) { ?>class="reply"<?php } ?>
                                                     id="delete_message<?php echo $message['id']; ?>">
                                                <figure><img <?php if (empty($message['profile_picture'])) { ?>
                                                        src="<?= base_url() ?>assets/images/attachment-img.png"
                                                    <?php } else { ?>
                                                        src="<?php echo AWS_S3_BUCKET_URL . $message['profile_picture'] ?>" width="48"
                                                    <?php } ?>
                                                        >
                                                </figure>
                                                <div class="text">
                                                    <div class="message-header">
                                                        <div class="message-title">
                                                            <h2>
                                                                <?php
                                                                if (!empty($message['first_name'])) {
                                                                    echo ucfirst($message['first_name']);
                                                                    if (!empty($message['last_name'])) {
                                                                        echo " " . ucfirst($message['last_name']);
                                                                    }
                                                                } else {
                                                                    echo $message['username'];
                                                                }
                                                                ?>
                                                            </h2>
                                                        </div>
                                                        <ul class="message-option">
                                                            <li>
                                                                <time><?php echo my_date_format($message['date']); ?></time>
                                                            </li>
                                                            <?php if ($message['outbox'] == 1) { ?>
                                                                <!--  <li>
                                                                        <a class="action-btn" onclick="resend_message(<?php echo $message['id']; ?>)" href="javascript:;">
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
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- #tab4 -->
                            <div id="tab5" class="tabs-content">
                                <div class="universal-form-style-v2">
                                    <div class="form-title-section">
                                        <h2>Reviews and Ratings</h2>

                                        <div class="form-btns">
                                        </div>
                                    </div>
                                    <?php if ($applicant_ratings_count !== NULL) { ?>
                                        <div class="start-rating yellow-stars">
                                            <input readonly="readonly"
                                                   id="input-21b" <?php if (!empty($applicant_average_rating)) { ?> value="<?php echo $applicant_average_rating; ?>" <?php } ?>
                                                   type="number" name="rating" class="rating" min=0 max=5 step=0.2
                                                   data-size="xs">

                                            <p class="rating-count"><?php echo $applicant_average_rating; ?></p>

                                            <p><?php echo $applicant_ratings_count; ?> review(s)</p>
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Rating By All Employers</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <?php foreach ($applicant_all_ratings as $rating) { ?>
                                                <article class="comment-list box-view">
                                                    <h2><?php echo $rating['username']; ?></h2>

                                                    <div class="start-rating">
                                                        <input readonly="readonly" id="input-21b"
                                                               value="<?php echo $rating['rating']; ?>" type="number"
                                                               name="rating" class="rating" min=0 max=5 step=0.2
                                                               data-size="xs">
                                                    </div>
                                                    <p><?php echo $rating['comment']; ?></p>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="applicant-notes">
                                            <span class="notes-not-found ">No Review Found</span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- #tab5 -->
                            <div id="tab6" class="tabs-content">
                                <form action="<?php echo base_url('applicant_profile/event_schedule'); ?>"
                                      name="event_form" id="event_form" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                    <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                    <input type="hidden" name="users_type" value="applicant">
                                    <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                    <input type="hidden" name="action" value="add_event">
                                    <input type="hidden" name="redirect_to" value="applicant_profile">

                                        <div class="form-title-section">
                                            <h2>Calendar & Scheduling</h2>

                                            <div class="form-btns event_detail">
                                                <input type="button" id="add_event" value="Add Event">
                                            </div>
                                            <div class="form-btns event_create" style="display: none">
                                                <input type="submit" value="Save">
                                                <input onclick="cancel_event()" type="button" value="Cancel">
                                            </div>
                                        </div>
                                        <div class="event_create" style="display: none">
                                            <div class="form-col-100">
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <h1 class="hr-registered">Schedule Event</h1>
                                                    </div>
                                                    <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-striped table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>
                                                                    <label class="group-label">Event Title<span
                                                                            class="staric">*</span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="group-label">
                                                                        <i class="fa fa-calendar"></i>Date<span
                                                                            class="staric">*</span>
                                                                    </label>
                                                                </th>
                                                                <th class="text-center" colspan="3">
                                                                    <label class="group-label">
                                                                        <i class="fa fa-clock-o"></i>Time<span
                                                                            class="staric">*</span>
                                                                    </label>
                                                                </th>
                                                                <th class="text-center">
                                                                    <label class="group-label">
                                                                        <i class="fa fa-user"></i>Interviewer<span
                                                                            class="staric">*</span>
                                                                    </label>
                                                                </th>
                                                                <th>Category</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" placeholder="Event Title" name="title" id="title" class="invoice-fields">
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <input type="text" readonly="" placeholder="Event date"
                                                                               name="date" class="invoice-fields"
                                                                               required="required" id="eventdate">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <input name="eventstarttime" id="eventstarttime"
                                                                               value="12:00AM" readonly="readonly" type="text"
                                                                               class="stepExample1 eventstarttime"
                                                                               required="required">

                                                                    </div>
                                                                </td>
                                                                <td style="vertical-align: middle;">To</td>

                                                                <td>
                                                                    <div class="group-element">
                                                                        <input name="eventendtime" id="eventendtime"
                                                                               readonly="readonly" value="12:00PM" type="text"
                                                                               class="stepExample2 eventendtime"
                                                                               required="required">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element">
                                                                        <select class='contact_id' multiple name="interviewer[]"
                                                                                required="required">
                                                                            <option></option>
                                                                            <?php foreach ($company_accounts as $account) { ?>
                                                                                <option value="<?= $account['sid'] ?>">
                                                                                    <?= $account['username'] ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="group-element" style="width: 100px;">
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" id='category'
                                                                                    name='category' required="required">
                                                                                <option value="call">Call</option>
                                                                                <option value="email">Email</option>
                                                                                <option value="meeting">Meeting</option>
                                                                                <option selected="selected" value="interview">
                                                                                    Interview
                                                                                </option>
                                                                                <option value="personal">Personal</option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="address-area form-col-100">
                                                    <div class="cl-title">
                                                        <h2>Meeting Location</h2>
                                                    </div>
                                                    <input type="text" name="address"
                                                           placeholder="Enter valid address for Google Maps"
                                                           class="form-col-100 invoice-fields">
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="goto_meeting" class="goto_meeting" value="1"
                                                           name="goToMeetingCheck" type="checkbox">
                                                    <label for="goto_meeting">Meeting Call In Details</label>
                                                </div>
                                                <div class="show-hide-meeting meeting-div" style="display: none">
                                                    <div class="address-area form-col-50-left">
                                                        <input type="text" name="meetingCallNumber" id="meetingCallNumber"
                                                               placeholder="Meeting Call In Number"
                                                               class="form-col-100 invoice-fields">
                                                    </div>
                                                    <div class="address-area form-col-50-right">
                                                        <input type="text" name="meetingId" id="meetingId"
                                                               placeholder="Meeting ID Number"
                                                               class="form-col-100 invoice-fields">
                                                    </div>
                                                    <div class="address-area form-col-100">
                                                        <input type="text" name="meetingURL" id="meetingURL"
                                                               placeholder="Webinar/Meeting log in URL"
                                                               class="form-col-100 invoice-fields">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="interviewer_comment" class="interviewer_comment" value="1"
                                                           name="commentCheck" type="checkbox">
                                                    <label for="interviewer_comment">Comment For Interviewer</label>
                                                </div>
                                                <div class="show-hide-comments comment-div" style="display: none">
                                                    <div class="comment-box">
                                                        <div class="textarea">
                                                            <textarea id="interviewerComment" name="comment"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-col-100">
                                                <div class="applicant-comments-label">
                                                    <input id="candidate_msg" name="messageCheck" value="1" type="checkbox">
                                                    <label for="candidate_msg">Message To Candidate</label>
                                                </div>
                                                <div class="show-hide-comments message-div" style="display: none">
                                                    <div class="comment-box">
                                                        <div class="textarea">
                                                            <input class="message-subject" name="subject" type="text"
                                                                   placeholder="Enter Subject (required)"/>
                                                            <textarea id="applicantMessage" name="message"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="upload-file invoice-fields upload-sm">
                                                        <span class="selected-file" id="name_message_file_add">No file selected</span>
                                                        <input type="file" id="message_file_add" name="messageFile" onchange="check_file_all('message_file_add');" />
                                                        <a href="javascript:;">Add Attachment</a>
                                                    </div>
                                                </div>
                                                <div class="form-btns event_create" style="display: none">
                                                    <input type="submit" onclick="validate_form()" value="Save">
                                                    <input onclick="cancel_event()" type="button" value="Cancel">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                <div class="event_detail">
                                    <div class="row">
                                        <?php if (!empty($applicant_events)) { ?>
                                            <?php foreach ($applicant_events as $event) { ?>
                                                <div class="col-xs-12">
                                                    <div class="hr-box" id="remove_li<?= $event["sid"] ?>">
                                                        <div class="hr-box-header">
                                                            <span class="pull-left">
                                                                <strong>
                                                                Event : <?php echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); ?>
                                                                </strong>
                                                            </span>
                                                            <span class="pull-right">
                                                                <a href="javascript:;" class="btn btn-info btn-xs"
                                                                   data-toggle="modal"
                                                                   data-target="#editModal_<?= $event["sid"] ?>">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <a href="javascript:void(0);"
                                                                   class="btn btn-danger btn-xs"
                                                                   onclick="remove_event(<?= $event["sid"] ?>)">
                                                                    <i class="fa fa-remove"></i>
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="table-responsive hr-innerpadding">
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <!--<tr>
                                                                <td class="col-lg-3"><strong>Title</strong></td>
                                                                <td colspan="3"><?php /*echo ( $event['title'] != '' ? $event['title'] : 'No Title Specified'); */?></td>

                                                            </tr>-->
                                                            <tr>
                                                                <th>Event Category</th>
                                                                <th class="text-center">Event Date</th>
                                                                <th class="text-center">Start Time</th>
                                                                <th class="text-center">End Time</th>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo ucwords( $event['category']); ?></td>
                                                                <td class="text-center"><?php echo date("M jS, Y", strtotime($event['date'])) ?></td>
                                                                <td class="text-center"><?php echo $event['eventstarttime']; ?></td>
                                                                <td class="text-center"><?php echo $event['eventendtime']; ?></td>
                                                            </tr>
                                                            </tr>
                                                            <?php if($event['subject'] != '' && $event['message'] != '') { ?>
                                                                <tr>
                                                                    <th class="col-lg-3">Message to Candidate</th>
                                                                    <td colspan="3">
                                                                        <h5 style="margin-top: 0;"><strong><?php echo $event['subject']; ?></strong></h5>
                                                                        <p><?php echo $event['message']; ?></p>
                                                                <span class="pull-right">
                                                                    <?php if($event['messageFile'] != '') { ?>
                                                                        <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>" class="btn btn-success btn-sm">Message Attachment</a>
                                                                    <?php } else { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-success btn-sm disabled">Message Attachment</a>
                                                                    <?php } ?>
                                                                </span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <th class="col-lg-3">Participants</th>
                                                                <td colspan="3">
                                                                    <?php
                                                                    $event['interviewer'] = explode(',', $event['interviewer']);
                                                                    foreach ($company_accounts as $subaccount) {
                                                                        foreach ($event['interviewer'] as $interviewer) {
                                                                            if ($interviewer == $subaccount['sid']) {
                                                                                ?>
                                                                                <div
                                                                                    class="badge"><?php echo $subaccount['username']; ?></div>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php if($event['goToMeetingCheck'] == 1) { ?>
                                                                <tr>
                                                                    <th>Meeting Call Details</th>
                                                                    <td colspan="3">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <th class="col-lg-4 text-center">Number</th>
                                                                                <th class="col-lg-4 text-center">Meeting ID</th>
                                                                                <th class="col-lg-4 text-center">Meeting Url</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center"><?php echo $event['meetingCallNumber']?></td>
                                                                                <td class="text-center"><?php echo $event['meetingId']?></td>
                                                                                <td class="text-center"><?php echo $event['meetingURL']?></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <?php if($event['comment'] != '') { ?>
                                                                <tr>
                                                                    <td class="col-lg-3"><strong>Comment</strong></td>
                                                                    <td colspan="3">
                                                                        <?php echo $event['comment']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                        <?php } else { ?>
                                            <div class="col-lg-12">
                                                <div class="no-data form-col-100">No event scheduled!</div>
                                            </div>
                                        <?php } ?>
                                    </div> 
                                </div>
                            </div>
                            <!-- #tab6 -->
                        </div>
                    </div>

                    <div class="hr-box applied-jobs margin-top">
                        <div class="hr-box-header">
                            <strong>List Of Applied jobs</strong>
                        </div>
                        <div class="table-responsive hr-innerpadding">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-xs-2">Job Title</th>
                                        <th class="col-xs-2 text-center">Applicant Type</th>
                                        <th class="col-xs-2 text-center">Applicant Status</th>
                                        <th class="col-xs-2 text-center">Date Applied</th>
                                        <th class="col-xs-2 text-center">Review Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-xs-2">software engineer</td>
                                        <td class="col-xs-2 text-center">Manual</td>
                                        <td class="col-xs-2 text-center">
                                            <div class="label-wrapper-outer">
                                                <?php if($have_status == false) { ?>
                                                    <?php if ($applicant_info["status"] == 'Contacted') { ?>
                                                        <div class="selected contacted"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Candidate Responded') { ?>
                                                        <div class="selected responded"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Qualifying') { ?>
                                                        <div class="selected qualifying"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Submitted') { ?>
                                                        <div class="selected submitted"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Interviewing') { ?>
                                                        <div class="selected interviewing"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Offered Job') { ?>
                                                        <div class="selected offered"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Not In Consideration') { ?>
                                                        <div class="selected notin"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Client Declined') { ?>
                                                        <div class="selected decline"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Placed/Hired' || $applicant_info["status"] == 'Ready to Hire') { ?>
                                                        <div class="selected placed">Ready to Hire</div>
                                                    <?php } elseif ($applicant_info["status"] == 'Not Contacted Yet') { ?>
                                                        <div class="selected not_contacted"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Future Opportunity') { ?>
                                                        <div class="selected future_opportunity"><?= $applicant_info["status"] ?></div>
                                                    <?php } elseif ($applicant_info["status"] == 'Left Message') {?>
                                                        <div class="selected left_message"><?= $applicant_info["status"] ?></div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="selected <?php echo (isset($applicant_info['status_css_class'])) ? $applicant_info['status_css_class'] : ''; ?>">
                                                        <?php echo (isset($applicant_info['status_name'])) ? $applicant_info['status_name'] : ''; ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="lable-wrapper">
                                                    <div id="id" style="display:none;"><?= $applicant_info['sid'] ?></div>
                                                    <div style="height:20px;"><i class="fa fa-times cross"></i></div>
                                                    <?php if($have_status == false) { ?>
                                                    <div class="label applicant not_contacted">
                                                        <div id="status">Not Contacted Yet</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant left_message">
                                                        <div id="status">Left Message</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant contacted">
                                                        <div id="status">Contacted</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant responded">
                                                        <div id="status">Candidate Responded</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant interviewing">
                                                        <div id="status">Interviewing</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant submitted">
                                                        <div id="status">Submitted</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant qualifying">
                                                        <div id="status">Qualifying</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant placed">
                                                        <div id="status">Ready to Hire</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant offered">
                                                        <div id="status">Offered Job</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant decline">
                                                        <div id="status">Client Declined</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant notin">
                                                        <div id="status">Not In Consideration</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <div class="label applicant future_opportunity">
                                                        <div id="status">Future Opportunity</div>
                                                        <i class="fa fa-check-square check"></i>
                                                    </div>
                                                    <?php } else { ?>
                                                        <?php foreach ($company_statuses as $status) { ?>
                                                            <div class="label applicant <?php echo $status['css_class']; ?>">
                                                                <div id="status"><?php echo $status['name']; ?></div>
                                                                <i class="fa fa-check-square check"></i>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-xs-2 text-center">12-15-2016</td>
                                        <td class="col-xs-2 text-center">1.6 with 1 Review(s)</td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-2">software engineer</td>
                                        <td class="col-xs-2 text-center">Telent Network</td>
                                        <td class="col-xs-2 text-center">Applicant Status</td>
                                        <td class="col-xs-2 text-center">12-15-2016</td>
                                        <td class="col-xs-2 text-center">1.6 with 1 Review(s)</td>
                                    </tr> 
                                    <tr>
                                        <td class="col-xs-2">software engineer</td>
                                        <td class="col-xs-2 text-center">Applicat</td>
                                        <td class="col-xs-2 text-center">Applicant Status</td>
                                        <td class="col-xs-2 text-center">12-15-2016</td>
                                        <td class="col-xs-2 text-center">1.6 with 1 Review(s)</td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <?php $this->load->view('manage_employer/profile_right_menu_applicant'); ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
<?php if (!empty($applicant_events)) { ?>

    <?php foreach ($applicant_events as $event) { ?>
        <!-- Modal -->
        <div id="editModal_<?= $event["sid"] ?>" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header modal-header-bg">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Event Management</h4>
                    </div>
                    <form class="date_form"
                          action="<?php echo base_url('applicant_profile/event_schedule'); ?>" method="POST"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="universal-form-style-v2 modal-form">
                                <ul class="row">
                                    <input type="hidden" name="applicant_job_sid" value="<?= $id ?>">
                                    <input type="hidden" name="employers_sid" value="<?= $user_sid ?>">
                                    <input type="hidden" name="applicant_email" value="<?= $email ?>">
                                    <input type="hidden" name="users_type" value="applicant">
                                    <input type="hidden" name="action" value="edit_event">
                                    <input type="hidden" name="sid" value="<?= $event["sid"] ?>">
                                    <input type="hidden" name="redirect_to" value="applicant_profile">
                                    <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <label>Title:</label>
                                        <input class="invoice-fields" id="eventtitle<?= $event["sid"] ?>" type="text" name="title" placeholder='Enter title here' value="<?= $event["title"] ?>">
                                    </li>
                                    <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <label>Category:</label>

                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" id='category' name='category'>
                                                <option
                                                    value="call" <?php if ($event["category"] == 'call') { ?> selected <?php } ?> >
                                                    Call
                                                </option>
                                                <option
                                                    value="email" <?php if ($event["category"] == 'email') { ?> selected <?php } ?> >
                                                    Email
                                                </option>
                                                <option
                                                    value="meeting" <?php if ($event["category"] == 'meeting') { ?> selected <?php } ?> >
                                                    Meeting
                                                </option>
                                                <option
                                                    value="interview" <?php if ($event["category"] == 'interview') { ?> selected <?php } ?> >
                                                    Interview
                                                </option>
                                                <option
                                                    value="personal" <?php if ($event["category"] == 'personal') { ?> selected <?php } ?> >
                                                    Personal
                                                </option>
                                                <option
                                                    value="other" <?php if ($event["category"] == 'other') { ?> selected <?php } ?> >
                                                    Other
                                                </option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">  
                                        <label>Event Date:</label>
                                        <input class="eventdate invoice-fields" name="date" type="text"
                                               value="<?php echo date('m-d-Y', strtotime($event["date"])); ?>"
                                               id="datepicker101<?= $event['sid'] ?>"
                                               readonly="readonly" required="">
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">  
                                        <label>Start Time: </label>
                                        <input id="eventstarttime_<?= $event['sid'] ?>"
                                                   name="eventstarttime" type="text"
                                                   value="<?= $event["eventstarttime"] ?>"
                                                   class="stepExample1 eventstarttime invoice-fields">
                                        
                                    </li>
                                    <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                        <label>End Time: </label>
                                        <input id="eventendtime_<?= $event['sid'] ?>"
                                                   name="eventendtime" type="text"
                                                   value="<?= $event["eventendtime"] ?>"
                                                   class="stepExample2 eventendtime invoice-fields">
                                        
                                    </li>
                                    <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <label>Interviewers</label>
                                        <select class='contact_id' multiple name="interviewer[]"
                                                required="required">
                                            <?php
                                            $event['interviewer'] = explode(',', $event['interviewer']);
                                            foreach ($company_accounts as $account) {
                                                ?>
                                                <option value="<?= $account['sid'] ?>"
                                                    <?php
                                                    foreach ($event['interviewer'] as $interviewer) {
                                                        if ($interviewer == $account['sid']) {
                                                            ?>
                                                            selected="selected"
                                                            <?php
                                                        }
                                                    }
                                                    ?>>
                                                    <?= $account['username'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </li>
                                    <?php //if ($event['commentCheck'] == 1) { ?>
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                            <label>Comment for Interviewer:</label>
                                            <div class="fields-wrapper">
                                                <input value="1" name="commentCheck" type="hidden">
                                                <textarea class="invoice-fields comment-field" id="interviewerComment" name="comment"><?php echo $event['comment']; ?></textarea>
                                            </div>
                                        </li>
                                    <?php //} ?>
                                    <?php //if ($event['messageCheck'] == 1) { ?>
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                            <label>Message to Candidate:</label>
                                            <input value="1" name="messageCheck" type="hidden">
                                            <div class="comment-box">
                                                <div class="textarea">
                                                    <input class="message-subject" name="subject"
                                                           type="text"
                                                           value="<?php echo $event['subject']; ?>"
                                                           placeholder="Enter Subject (required)"/>
                                                    <textarea
                                                        name="message"><?php echo $event['message']; ?></textarea>
                                                    
                                                </div>
                                            </div>
                                        </li>
                                    <?php //} ?>                                             
                                    <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <label>Message Attachment: </label>
                                        <div class="upload-file invoice-fields">
                                             <span class="selected-file" id="name_messageFile">No file selected</span>
                                            <input type="file" id="messageFile" name="messageFile"  onchange="check_file_all('messageFile');" />
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div class="current-attachments bg-success col-lg-12">
                                            <label class="pull-left">Current Attachment: </label>
                                            <div class="pull-right">
                                                <?php if($event['messageFile'] != '') { ?>
                                                    <a href="<?php echo AWS_S3_BUCKET_URL . $event['messageFile']; ?>"><i class="fa fa-paperclip"></i> <span><?php echo $event['messageFile']; ?></span></a>
                                                <?php } else { ?>
                                                    <a href="javascript:;"><i class="fa fa-paperclip"></i> <span>No file attached</span></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </li>  
                                    <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <label>Address: </label>
                                        <input class="invoice-fields" name="address" type="text" value="<?php echo $event["address"] ?>">
                                    </li>
                                    <?php //if ($event['goToMeetingCheck'] == 1) { ?>
                                        <input value="1" name="goToMeetingCheck" type="hidden">
                                        <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label>Meeting Call In #: </label>
                                                <input class="invoice-fields" name="meetingCallNumber" type="text"
                                                       value="<?php echo $event["meetingCallNumber"] ?>">
                                        </li>
                                        <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <label>Meeting ID #: </label>
                                                <input class="invoice-fields" name="meetingId" type="text"
                                                       value="<?php echo $event["meetingId"] ?>">
                                        </li>
                                        <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label>Webinar/Meeting log in URL: </label>
                                                <input class="invoice-fields" name="meetingURL" type="text"
                                                       value="<?php echo $event["meetingURL"] ?>">
                                        </li>
                                    <?php //} ?>
                                    <!--<li>
                                        <label>Description:</label>
                                        <div class="fields-wrapper">
                                            <textarea name="description" id='description_1' class="eventtextarea"><? //= $event["description"] ?></textarea>
                                        </div>
                                        </p>
                                    </li>-->
                                </ul>
                            </div> 
                        </div>
                        <div class="modal-footer">
                        <input class="btn btn-success" type='submit' value="Save" id="save_event_edit_<?= $event['sid'] ?>">
                            <a href="javascript:;" class="btn btn-default" data-dismiss="modal">Close</a>                                
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?> 

<?php } ?>
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
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php if($applicant_info['approval_status_type'] == 'rejected_unconditionally') { ?>
                            
                              <div class="text-center">
                                  <strong>Rejected!</strong>&nbsp; This applicant has been rejected.
                              </div>
                            
                        <?php }elseif($applicant_info['approval_status_type'] == 'rejected_conditionally') { ?>
                            
                            <div class="text-center">
                                <p>This applicant has been rejected untill following conditions are met.</p>
                                  <p><?php echo $applicant_info['approval_status_reason']?></p>
                              </div>

                            <hr />

                            <label class="control control--checkbox" for="conditions_met_chekbox">
                                <input onclick="fShowRejectionResponseForm(this);" data-rule-required="true" type="checkbox" value="conditions_met" id="conditions_met_chekbox" name="conditions_met_chekbox"  />&nbsp;
                                Applicant has met all required conditions
                                <div class="control__indicator"></div>
                            </label>
                            <hr />

                            <form id="form_rejection_response" method="post" enctype="multipart/form-data" action="<?php echo base_url('applicant_approval_management/ajax_responder'); ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="reset_applicant_for_approval" />
                                <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_info['sid']; ?>" />
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $applicant_info['employer_sid']; ?>" />
                                <input type="hidden" id="approval_status" name="approval_status" value="pending" />

                                
                                <div class="form-group">
                                    <label>Your Response</label>
                                    <textarea data-rule-required="true" data-msg-required="Response is required" class="form-control" id="employer_response" name="employer_response" <?php echo (trim($applicant_info['approval_status_reason_response']) != null ||  trim($applicant_info['approval_status_reason_response']) != '' ? 'disabled=disabled' : '' ); ?>><?php echo ($applicant_info['approval_status_reason_response'] != null || $applicant_info['approval_status_reason_response'] != '' ? $applicant_info['approval_status_reason_response'] : '' ); ?></textarea>
                                </div>
                                <?php if(trim($applicant_info['approval_status_reason_response']) == null ||  trim($applicant_info['approval_status_reason_response']) == '') { ?>
                                <div class="form-group">
                                    <button type="button" class="submit-btn" onclick="fValidateRejectionResponseForm();">Resend Approval Request</button>
                                </div>
                                <?php } ?>
                            </form>
                        <?php } ?>

                    </div>
                </div>

            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/chosen.jquery.js"></script>
<script language="JavaScript" type="text/javascript"
        src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">



    $(document).ready(function () {
        $('#form_rejection_response').hide();
        
        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function() {}
        });


        var mylimit = parseInt($('#choiceLimit').html());
        multiselectbox();
        $(".chosen-select").chosen({max_selected_options: mylimit});
        $(".chosen-select").bind("liszt:maxselected", function () {
        });
        $(".chosen-select").chosen().change(function () {
        });

        $('.applied-jobs .selected').click(function () {
            $(this).closest("tr").css({'height': '420px'});
        });
        $('.applied-jobs .cross, .applied-jobs .label').click(function () {
            $(this).closest("tr").css({'height': 'auto'});
        });
    });

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

    function fShowRejectionResponseForm(source) {
        if ($(source).prop('checked')) {
            console.log('checked');
            $('#form_rejection_response').show();
        } else {
            console.log('unchecked');
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
    function remove_event(val) {
        var sid = val;
        alertify.defaults.glossary.title = 'Delete Event';
        alertify.confirm("Are you sure you want to delete the event?",
            function () {
                $.ajax({
                    url: "<?= base_url('applicant_profile/deleteEvent') ?>?action=remove_event&sid=" + sid,
                    success: function (data) {
                        console.log(data);
                    }
                });

                $('#remove_li' + val).hide();
                alertify.success('Event deleted successfully.');
            },
            function () {
                //alertify.error('');
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
                        console.log(data);
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
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif") {
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
            $('#name_' + val).html('<span>' + fileName + '</span>');
            //console.log(fileName);
        } else {
            $('#name_' + val).html('Please Select');
            //console.log('in else case');
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

        $('.eventdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
        $('#eventdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
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
                var id = allstates[i].id;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
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
                            alertify.success(response[1]);
                            //console.log(response[2]);
                            window.location.assign(response[2]);
                        }
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }
                });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    function myPopup() {
//                                                        $('#cc_send').addClass("disabled-btn");
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
        else {
            // do nothing
        }
    });


    function check_status(source) {
        if ($(source).is(':checked')) {
            $('#yes-btn').removeAttr('disabled');
            $('#yes-btn').removeClass('disabled-btn');

            console.log('checked');
        } else {
            $('#yes-btn').attr('disabled', 'disabled');
            $('#yes-btn').addClass('disabled-btn');

            console.log('not checked');
        }


    }


    $(document).ready(function () {
        $('#yes-btn').attr('disabled', 'disabled');
        $('#yes-btn').addClass('disabled-btn');
    });


    function fSetApplicantForApproval(applicant_id) {
        alertify.confirm(
            'Are You Sure!',
            'Are You Sure you Want to Get this Applicant Approved For Hiring?',
            function () {
                console.log(applicant_id);
                var url = '<?php echo base_url('applicant_profile/ajax_responder'); ?>';
                var dataToSend = {applicant_id: applicant_id, perform_action: 'set_applicant_for_approval'};

                var myRequest;
                myRequest = $.ajax({
                    url: url,
                    data: dataToSend,
                    type: 'POST'
                });

                myRequest.done(function (response) {
                    if (response == 'success') {
                        url = '<?php echo base_url('applicant_profile')?>' + '/' + applicant_id;

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
        $('#rejection_info_modal').modal('toggle');
    }
</script>

