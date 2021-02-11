<!-- Application Tracking Section Start -->
<title>Employer Contacts - Job Portal</title>
<div class="job-main-content">
    <div class="job-container">
        <header class="hr-page-header">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <h2>
                <span><img src="<?= base_url() ?>assets/images/candidates4.png" alt="image"></span>
                    Edit Candidates
            </h2>
            <div class="back-btn">
                <a class="siteBtn redBtn" style="margin-bottom: 10px;" id="" href="<?= base_url('application_tracking_system/active/all/all/all/all') ?>">&laquo; BACK</a>
            </div>
        </header>
        <div class="job-feature-main">
            <div class="portalmid">
                <div class="job_top_section full_width_block">
                    <div class="jobsearch_box">
                        <!--<a href="javascript:;" onclick="add_new()" class="siteBtn jbBtn" style="margin-right:5px;">+ Add Contact</a>-->
                    </div>
                </div>           
                <div id="show_hide">                    
                    <div class="apply-job-from">
                        <ul>
                            <form class="popup-form" enctype="multipart/form-data" action="" method="post" name="register-form" id="register-form">
                                <li>
                                    <label>first name <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" value="<?php echo set_value('first_name', $candidate['first_name']); ?>" name="first_name" id="first_name" required="required" placeholder="Enter First Name">
                                    <?php echo form_error('first_name'); ?>
                                </li>
                                <li>
                                    <label>last name <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" value="<?php echo set_value('last_name', $candidate['last_name']); ?>" name="last_name" id="last_name" required="required" placeholder="Enter Last Name">
                                    <?php echo form_error('last_name'); ?>
                                </li>
                                <li>
                                    <label>Select Job </label>
                                    <select class="form-fields"  name="job_sid" id="job_sid">
                                        <option value="0">Select Job </option>
                                        <?php foreach ($all_jobs as $job) { ?>
                                            <option value="<?= $job["sid"] ?>" <?php if ($job["sid"] == $candidate['job_sid']) { ?>selected="selected" <?php } ?>><?= $job["Title"] ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('job_sid'); ?>
                                </li>
                                <li>
                                    <label>email </label>
                                    <input class="form-fields" type="email" value="<?php echo set_value('email', $candidate['email']); ?>" name="email" id="email" placeholder="Enter Email">
                                    <?php echo form_error('email'); ?>
                                </li>
                                <li>
                                    <label>Phone </label>
                                    <input class="form-fields" type="text" value="<?php echo set_value('phone_number', $candidate['phone_number']); ?>" name="phone_number" id="phone_number" placeholder="Phone Number">
                                    <?php echo form_error('phone_number'); ?>
                                </li>
                                <li>
                                    <label>street address</label>
                                    <input class="form-fields" type="text" value="<?php echo set_value('address', $candidate['address']); ?>" name="address" id="address" placeholder="Enter Address">
                                </li>
                                <li>
                                    <label>city </label>
                                    <input class="form-fields" type="text" value="<?php echo set_value('city', $candidate['city']); ?>" name="city" id="city" placeholder="Enter City">
                                </li>
                                <li>

                                    <?php $country_id = 227; ?>
                                    <label>state </label>
                                    <select class="form-fields" name="state" id="state">
                                        <?php if (empty($country_id)) { ?> <option value="">Select State </option> <?php
                                        } else {
                                            foreach ($active_states[$country_id] as $active_state) {
                                                ?>
                                                <option value="<?= $active_state["id"] ?>" <?php if ($active_state["id"] == $candidate['state']) { ?>selected="selected" <?php } ?>><?= $active_state["state_name"] ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <label>country </label>
                                    <select class="form-fields" name="country" id="country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                        <?php
                                        foreach ($active_countries as $active_country) {
                                            ?>
                                            <option value="<?= $active_country["id"]; ?>"
                                            <?php if ($candidate['country'] == $active_country["id"]) { ?>
                                                        selected
                                                        <?php
                                                    }
                                                    ?>
                                                    >
                                                        <?= $active_country["country_name"]; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <label>attach resume <span class="staric"></span></label>
                                    <div class="form-fields choose-file" name="resume" required="required">
                                        <div class="file-name" id="name_resume">Please Select</div>
                                        <input class="choose-file-filed" type="file" name="resume" id="resume"  onchange="check_file('resume')" >
                                        <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                    </div>
                                    <?php if ($candidate['resume'] != NULL) { ?>
                                        <p><b>Old file Name:</b><?= $candidate['resume'] ?></p>
                                    <?php } ?>
                                </li>
                                <li>
                                    <label>attach cover </label>
                                    <div class="form-fields choose-file">
                                        <div class="file-name" id="name_cover_letter">Please Select</div>
                                        <input class="choose-file-filed" type="file" id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')" >
                                        <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                    </div>
                                    <?php if ($candidate['cover_letter'] != NULL) { ?>
                                        <p><b>Old file Name:</b><?= $candidate['cover_letter'] ?></p>
                                    <?php } ?>

                                </li>
                                <li>
                                    <input type="hidden" name="action" id="action" value="update_contact">
                                    <input type="hidden" name="old_resume" id="action" value="<?= $candidate['resume'] ?>">
                                    <input type="hidden" name="old_letter" id="action" value="<?= $candidate['cover_letter'] ?>">
                                    <input class="siteBtn notes-btn" type="submit"  value="Update">
                                    <input class="siteBtn notes-btn btncancel"  type="button" onclick="location.href = '<?= base_url() ?>application_tracking_system/active/all/all/all/all';"  value="Cancel">
                                </li>
                            </form>
                        </ul>
                    </div>
                    <!-- Apply Job Form End -->
                    <div class="clear"></div>                            
                </div>
            </div>
        </div>
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script type="text/javascript">

                                                    $(document).ready(function () {
                                                        $('.selected').click(function () {
                                                            $(this).next().css("display", "block");
                                                        });

                                                        $('.label').click(function () {
                                                            $(this).parent().find('.check').css("visibility", "hidden");
                                                            $(this).parent().prev().html($(this).find('#status').html());
                                                            $(this).find('.check').css("visibility", "visible");
                                                            $(this).parent().prev().css("background-color", $(this).css("background-color"));
                                                            status = $(this).find('#status').html();
                                                            id = $(this).parent().find('#id').html();

                                                            $.ajax({
                                                                url: "{$base_url}/user/employer-portal/manage/manual_candidate.php?id=" + id + "&status=" + status + "&action=ajax_update_status",
                                                                success: function (data) {
                                                                    $('.lable-wrapper').hide();
                                                                    console.log(data);
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

                                                        $.each($(".selected"), function () {
                                                            class_name = $(this).attr('class').split(' ');
                                                            $(this).next().find('.' + class_name[1]).find('.check').css("visibility", "visible");
                                                        });
                                                    });
                                                    setTimeout(function () {
                                                        $(".success").slideUp();
                                                    }, 5000);

                                                    function callFunction(action, id) {
                                                        alertify.confirm("Please Confirm Delete", "Are you sure you want to delete your contact?",
                                                                function () {
                                                                    $.ajax({
                                                                        url: "{$base_url}/user/employer-portal/manage/manual_candidate.php?id=" + id + "&action=" + action,
                                                                        success: function (data) {
                                                                            $('#row' + id).hide();
                                                                            alertify.notify('Success: <br><br>Contact is removed from your list', 'success');
                                                                        }
                                                                    });
                                                                },
                                                                function () {
                                                                    alertify.error('Cancelled');
                                                                });
                                                    }
                                                    function add_new() {
                                                        $('#myModalLabel').html('Insert New Contact');
                                                        $('#show_hide').show();
                                                        $('#hide_questions').hide();
                                                        $('#first_name').val('');
                                                        $('#last_name').val('');
                                                        $('#job_sid').val('');
                                                        $('#email').val('');
                                                        $('#phone_number').val('');
                                                        $('#address').val('');
                                                        $('#city').val('');
                                                        $('#state').val('');
                                                        $('#sid').val('');
                                                        $('#country').val('227');
                                                        $('#action').val('insert_contact');
                                                    }

                                                    function edit_contact(id) {
                                                        $('#myModalLabel').html('Update Contact');
                                                        var first_name = $('#first_name' + id).val();
                                                        var last_name = $('#last_name' + id).val();
                                                        var job_sid = $('#job_sid' + id).val();
                                                        var email = $('#email' + id).val();
                                                        var phone_number = $('#phone_number' + id).val();
                                                        var address = $('#address' + id).val();
                                                        var city = $('#city' + id).val();
                                                        var state = $('#state' + id).val();
                                                        var country = $('#country' + id).val();

                                                        $('#first_name').val(first_name);
                                                        $('#last_name').val(last_name);
                                                        $('#job_sid').val(job_sid);
                                                        $('#email').val(email);
                                                        $('#phone_number').val(phone_number);
                                                        $('#address').val(address);
                                                        $('#city').val(city);
                                                        $('#state').val(state);
                                                        $('#country').val(country);
                                                        $('#sid').val(id);
                                                        $('#action').val('update_contact');

                                                        $('#show_hide').show();
                                                        $('#hide_questions').hide();
                                                    }

                                                    function view_contact(id) {
                                                        var first_name = $('#first_name' + id).val();
                                                        var last_name = $('#last_name' + id).val();
                                                        var job_sid = $('#job_sid' + id).val();
                                                        var email = $('#email' + id).val();
                                                        var phone_number = $('#phone_number' + id).val();
                                                        var address = $('#address' + id).val();
                                                        var city = $('#city' + id).val();
                                                        var state = $('#state' + id).val();
                                                        var country = $('#country' + id).val();

                                                        //$('#show_hide').hide();        
                                                        //$('#hide_questions').hide(); 
                                                        //$('#view_contact').show();
                                                        console.log('view contact');
                                                    }

                                                    function cancel_add() {
                                                        $('#hide_questions').show();
                                                        $('#show_hide').hide();
                                                    }

                                                    function check_file(val) {
                                                        var fileName = $("#" + val).val();
                                                        if (fileName.length > 0) {
                                                            $('#name_' + val).html(fileName.substring(12, fileName.length));
                                                        } else {
                                                            $('#name_' + val).html('Please Select');
                                                        }
                                                    }
                                                    function validate_form() {
                                                        $("#register-form").validate({
                                                            ignore: ":hidden:not(select)",
                                                            messages: {first_name: "Please provide first name",
                                                                last_name: "Please provide last name",
                                                                email: "Please provide valid email address",
                                                                phone_number: "Please provide valid number",
                                                                city: "City required!",
                                                                resume: "Resume required!",
                                                                state: "State required!",
                                                                country: "Country required!",
                                                                job_sid: "Please select a job"
                                                            },
                                                            submitHandler: function (form) {
                                                                form.submit();
                                                            }
                                                        });
                                                    }
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


                                                    function check_file(val) {
                                                        var fileName = $("#" + val).val();
                                                        if (fileName.length > 0) {
                                                            $('#name_' + val).html(fileName.substring(12, fileName.length));
                                                        } else {
                                                            $('#name_' + val).html('Please Select');
                                                        }
                                                    }

</script>