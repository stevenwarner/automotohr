<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo $back_url; ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Resumes</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <?php echo form_open_multipart('', array('id' => 'manual_candidate')); ?>
                                                <?php if(!empty($resume['resume_files'])) { ?>
                                                    <input type="hidden" value="http://www.automotosocial.com/display-resume/<?php echo $resume['sid']?>/?filename=<?php echo $resume['resume_files'][0]['saved_file_name']; ?>" name="resume" id="resume" />
                                                    <input type="hidden" value="<?php echo $resume['resume_files'][0]['saved_file_name']; ?>" name="resume_file_name" id="resume_file_name" />
                                                <?php } ?>

                                                <?php if(!empty($resume['ProfilePictureDetail'])) { ?>
                                                    <input type="hidden" value="http://automotosocial.com/files/pictures/<?php echo $resume['ProfilePictureDetail']['saved_file_name']; ?>" name="profile_picture_url" id="profile_picture_url" />
                                                    <input type="hidden" value="<?php echo $resume['ProfilePictureDetail']['saved_file_name']; ?>" name="profile_picture_name" id="profile_picture_name" />
                                                <?php } ?>

                                                <?php if(!empty($resume['Objective'])) { ?>
                                                    <input type="hidden" value="<?php echo htmlentities($resume['Objective']); ?>" name="objective_text" id="objective_text" />
                                                <?php } ?>

<!--                                                --><?php //if(!empty($resume['Keywords'])) { ?>
<!--                                                    <input type="hidden" value="--><?php //echo htmlentities($resume['Keywords']); ?><!--" name="keywords_text" id="keywords_text" />-->
<!--                                                --><?php //} ?>

                                                <?php if(!empty($resume['Skills'])) { ?>
                                                    <input type="hidden" value="<?php echo htmlentities($resume['Skills']); ?>" name="skills_text" id="skills_text" />
                                                <?php } ?>

                                                <li class="form-col-50-left">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['FirstName'] : ''); ?>
                                                    <?php echo form_label('First Name <span class="hr-required">*</span>','first_name'); ?>
                                                    <?php echo form_input('first_name',set_value('first_name', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('first_name'); ?>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['LastName'] : ''); ?>
                                                    <?php echo form_label('Last Name <span class="hr-required">*</span>','last_name'); ?>
                                                    <?php echo form_input('last_name',set_value('last_name', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('last_name'); ?>
                                                </li>
                                                <li class="form-col-50-left">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['email'] : ''); ?>
                                                    <?php echo form_label('E-Mail<span class="hr-required">*</span>','email'); ?>
                                                    <?php echo form_input('email',set_value('email', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('email'); ?>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['PhoneNumber'] : ''); ?>
                                                    <?php echo form_label('Phone Number','phone_number'); ?>
                                                    <?php echo form_input('phone_number',set_value('phone_number', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('phone_number'); ?>
                                                </li>
                                                <li class="form-col-50-left">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['Location_Address'] : ''); ?>
                                                    <?php echo form_label('Address','address'); ?>
                                                    <?php echo form_input('address',set_value('address', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('address'); ?>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['Location_City'] : ''); ?>
                                                    <?php echo form_label('City','city'); ?>
                                                    <?php echo form_input('city',set_value('city', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('city'); ?>
                                                </li>
                                                <li class="form-col-50-left">
                                                    <label>Country</label>
                                                    <?php $temp = (isset($resume['user_info']) ? $resume['user_info']['Location_Country'] : '');?>
                                                    <?php $selected_state = (isset($resume['user_info']) ? $resume['user_info']['Location_State'] : '');?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="country" name="country" onchange="getStates(this.value, <?php echo $states; ?>, <?php echo $selected_state?>); ">
                                                            <option <?php echo ( $temp == '' ? ' selected="selected" ' : ''); ?> value="">Please Select</option>
                                                            <option <?php echo ( $temp == '227' ? ' selected="selected" ' : ''); ?> value="227">United States</option>
                                                            <option <?php echo ( $temp == '38' ? ' selected="selected" ' : ''); ?> value="38">Canada</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-50-right">
                                                    <label>state:</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="state" name="state">
                                                            <option value="">Select State</option>
                                                            <option value="">Please Select your country</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-100">
                                                    <label>Select Job:</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="job_sid">
                                                            <?php foreach ($all_jobs as $job) { ?>
                                                                <option value="<?php echo $job["sid"]; ?>"><?php echo $job["Title"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-100">
                                                    <?php $temp = (isset($resume['YouTube_Video']) ? $resume['YouTube_Video'] : ''); ?>
                                                    <?php echo form_label('Youtube Video','youtube_video'); ?>
                                                    <?php echo form_input('youtube_video',set_value('youtube_video', $temp),'class="invoice-fields"'); ?>
                                                    <?php echo form_error('youtube_video'); ?>
                                                </li>
                                                <li class="form-col-100 autoheight">
                                                    <a href="<?php echo base_url('resume_database/view/' . $resume['sid']); ?>" class="submit-btn btn-cancel">Cancel</a>
                                                    <input type="submit" value="Save" onclick="validate_form()" class="submit-btn">
                                                </li>
                                                <?php echo form_close();?>
                                            </ul>

                                            <hr />
                                            <div class="row">
                                                <?php if($resume['YouTube_Video'] != '') { ?>
                                                    <div class="col-xs-6">
                                                        <div class="list-group-item autoheight">
                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo  get_youtube_video_id_from_url($resume['YouTube_Video']); ?>"></iframe>
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
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function () {
        $('#country').trigger('change');

        $.validator.addMethod("youtubeurl", function(value, element) {
            if (value != undefined || value != '') {
                var regExp = /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/watch\?v=([^&]+)/m;
                var match = value.match(regExp);
                //console.log(match);
                if (match && match[1].length == 11) {
                    //console.log(match);
                    return true;
                } else {
                    // Do anything for not being valid
                    //console.log('not match');
                    return false;
                }
            }
        }, "Please specify the correct Youtube video url");

    });





    function validate_form() {
        $("#manual_candidate").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- .]+$/
                },
                email:      {
                    required: true,
                    email: true
                },
                phone_number: {
                    pattern: /^[0-9\- \s\+]+$/
                },
//                youtube_video: {
//                    youtubeurl: true
//                },
                youtube_video: {youtubeurl: function (element) {
                      var value = $.trim($('input[name=youtube_video]').val()); 
                      if(value == ''){
                          return false;
                      } else {
                          return true;
                      }
                    }
                }
            },
            messages: {
                first_name: {
                    required: 'First Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                email:      {
                    required: 'Please provide Valid email'
                },
                phone_number: {
                    required: 'Phone Number is required',
                    pattern: 'Numbers and dashes only please'
                },
                youtube_video: {
                    youtubeurl: 'Must be a valid Youtube Video Url.'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    function getStates(val, states, selected) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                var selected_text = ' selected="selected" ';

                if(id == selected){
                    html += '<option ' + selected_text + ' value="' + id + '">' + name + '</option>';
                }else{
                    html += '<option value="' + id + '">' + name + '</option>';
                }
            }
            $('#state').html(html);
            $('#state').trigger('change');
        }
    }
</script>