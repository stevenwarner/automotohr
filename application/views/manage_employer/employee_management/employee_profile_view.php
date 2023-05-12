<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_employee'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="job-title-text">                
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                                <p>Current Email address: <b><?php echo $employer['email']; ?></b></p><br>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open_multipart('', array('id' => 'employeeprofile')); ?>
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-50-left">
                                                <?php echo form_label('First Name <span class="hr-required">*</span>', 'first_name'); ?>
                                                <?php echo form_input('first_name', set_value('first_name', $employer['first_name']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('first_name'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <?php echo form_label('Last Name <span class="hr-required">*</span>', 'first_name'); ?>
                                                <?php echo form_input('last_name', set_value('last_name', $employer['last_name']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('last_name'); ?>
                                            </li>
                                            <li class="form-col-100">
                                                <?php echo form_label('Address', 'Location_Address'); ?>
                                                <?php echo form_input('Location_Address', set_value('Location_Address', $employer['Location_Address']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_Address'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <?php echo form_label('City', 'Location_City'); ?>
                                                <?php echo form_input('Location_City', set_value('Location_City', $employer['Location_City']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_City'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <?php echo form_label('Zip Code', 'Location_ZipCode'); ?>
                                                <?php echo form_input('Location_ZipCode', set_value('Location_ZipCode', $employer['Location_ZipCode']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_ZipCode'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>country</label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?php echo $active_country["sid"]; ?>"
                                                            <?php if ($employer["Location_Country"] == $active_country["sid"]) { ?>
                                                                        selected
                                                                    <?php } ?>>
                                                                        <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li>
                                            <p style="display: none;" id="state_id"><?php echo $employer["Location_State"]; ?></p>
                                            <li class="form-col-50-right">	
                                                <label>state</label>									
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="state" name="Location_State">
                                                        <option value="">Select State</option> 
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-50-left">	
                                                <?php echo form_label('Primary Number', 'PhoneNumber'); ?>									
                                                <?php echo form_input('PhoneNumber', set_value('PhoneNumber', $employer['PhoneNumber']), 'class="invoice-fields"'); ?>
                                                <?php echo form_error('PhoneNumber'); ?>								
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Access Level <span class="hr-required">*</span></label>	
                                                <div class="hr-select-dropdown">	
                                                    <select class="invoice-fields" name="access_level">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($access_level as $al) { ?>
                                                            <option value="<?php echo $al; ?>"
                                                            <?php if ($al == $employer['access_level']) { ?>
                                                                        selected
                                                                    <?php } ?>>
                                                                        <?php echo $al; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('employee_management') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                <p class="image-preview-text">Profile Picture</p> 
                                <div class="profile-iamge">
                                    <?php if (!empty($employer['profile_picture'])) { ?>
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $employer['profile_picture']; ?>" class="image-logo"> 
                                    <?php } else { ?>
                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg" class="image-logo">
                                    <?php } ?>
                                </div>
                                <div class="upload-btn">
                                    <div class="btn-inner">
                                        <input type="file" name="profile_picture" id="profile_picture" onchange="check_file()"  class="choose-file-filed"/>
                                        <a class="select-photo submit-btn" href="">upload photo</a>
                                        <div class="upload-photo" id="name_profile_picture"></div>
                                        <input type="hidden" name="image_extension" id="image_extension" value="">
                                        <input type="hidden" name="old_profile_picture" value="<?php echo $employer['profile_picture']; ?>">
                                        <input type="hidden" name="id" value="<?php echo $employer['sid']; ?>">
                                    </div>                                                    
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function validate_form() {
        $("#employeeprofile").validate({
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
                access_level: {
                    required: true
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
                access_level: {
                    required: 'Access Level is required'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    // get the states 
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
    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    function check_file() {
        var fileName = $("#profile_picture").val();
        if (fileName.length > 0) {
            $('#name_profile_picture').html(fileName.substring(12, fileName.length));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            $("#image_extension").val(ext);
            //$('#name_profile_picture').html(ext);
            if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                $("#profile_picture").val(null);
                $('#name_profile_picture').html('Only (.png .jpeg .jpg) allowed! Please Select again.');
            }
        }
    }
</script>