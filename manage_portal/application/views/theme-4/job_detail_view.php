<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>
        </div>
    </div>
</div>

<div class="main">
    <?php if ((!empty($jobs_detail_page_banner_data) && $jobs_detail_page_banner_data['banner_type'] == 'default') || empty($jobs_detail_page_banner_data)) { ?>
        <div class="row">
            <div class="col-sm-12 text-center">
                <a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a><br>
            </div>
            <div class="col-sm-12 text-center">
                <?php if ($indeedApplyButtonDetails): ?>
                    <a class="site-btn apply-now-large" style="background: none;min-width:100px;" href="javascript:void(0)">
                        <div
                            data-indeed-apply-widget-type="AWI"
                            data-indeed-apply-sourceJobPostingId="<?= $indeedApplyButtonDetails["indeed_posting_id"]; ?>"
                            data-indeed-apply-partnerApiToken="<?= getCreds("AHR")->INDEED_PARTNER_KEY; ?>"
                            data-indeed-apply-encryptedJobUrl="<?= $indeedApplyButtonDetails["attributes"]["encryptedJobUrl"]; ?>"
                            data-indeed-apply-encryptedExitUrl="<?= $indeedApplyButtonDetails["attributes"]["encryptedExitUrl"]; ?>"
                            data-indeed-apply-hl="en"
                            data-indeed-apply-co="US"
                            data-indeed-apply-newTab="true" style="background: none; text-align: center! important">
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php   } ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="job-detail">
                    <?php if (!empty($job_details['YouTube_Video'])) { ?>
                        <?php $this->load->view('common/video_player_partial'); ?>
                    <?php } ?>

                    <div class="job-description-text">
                        <header class="heading-title">
                            <span class="section-title color">Job Description:</span>
                        </header>
                        <?php echo $job_details['JobDescription']; ?>
                    </div>
                    <?php if (!empty($job_details['JobRequirements'])) { ?>
                        <div class="job-description-text job-requirement">
                            <header class="heading-title">
                                <span class="section-title color">Job Requirements:</span>
                            </header>
                            <?php echo $job_details['JobRequirements']; ?>
                        </div>
                    <?php }
                    if (empty($value['pictures']) && !empty($company_details['Logo'])) {
                        $image = AWS_S3_BUCKET_URL . $company_details['Logo'];
                    } elseif (!empty($value['pictures'])) {
                        $image = AWS_S3_BUCKET_URL . $value['pictures'];
                    } else {
                        $image = AWS_S3_BUCKET_URL . DEFAULT_JOB_IMAGE;
                    } ?>
                    <div class="social-media job-detail">
                        <?php if (isset($job_details['share_links'])) {
                            echo $job_details['share_links'];
                        } ?>
                    </div>
                    <div class="bottom-btn-row">
                        <ul>
                            <li><a href="javascript:;" class="site-btn bg-color apply-now-large" data-toggle="modal" data-target="#myModal">apply now</a></li>
                            <li><a href="<?php echo strtolower(str_replace(" ", "_", $more_career_oppurtunatity)); ?>" class="site-btn bg-color-v3">More Career Opportunities With This Company</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/theme-4/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets') ?>/theme-4/js/additional-methods.min.js"></script>
<script type="text/javascript">
    function show_popup(val) {
        //console.log('show popup: ' + val);
        job_title = $('#job_title' + val).html();
        questionnaire_sid = $('#questionnaire_sid' + val).html();
        questions = $('#questions' + val).html();
        $('#show_questionnaire').html(questions);
        $('#show_hide' + val).click();
        $('#myModalLabel').html("Apply for '" + job_title + "'");
        $("#register-form").attr('action', '/job_details/' + val);
        document.getElementById("job_sid").value = val;
        document.getElementById("questionnaire_sid").value = questionnaire_sid;
    }

    function getStates(val, states) {
        states = jQuery.parseJSON(states);
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

    function validate_form() {
        youtube_check()
        $("#register-form").validate({
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
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    pattern: /^[0-9\-]+$/
                },
                city: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                state: {
                    required: true,
                },
                country: {
                    required: true,
                },
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
                email: {
                    required: 'Please provide Valid email'
                },
                phone_number: {
                    required: 'Phone Number is required',
                    pattern: 'Numbers and dashes only please'
                },
                city: {
                    required: 'Contact Name is required',
                    pattern: 'Please Provide valid City'
                },
                state: {
                    required: 'State is required'
                },
                country: {
                    required: 'Country is required'
                },
                us_citizen: "Please Provide Your Residential Status.",
                visa_status: "Please Provide Your Visa Status.",
                group_status: "Please Provide Your Group Status.",
                veteran: "Please Provide Your Veteran Status.",
                disability: "Please Provide Your Disability Status.",
                gender: "Please Select Your Gender."
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 35));
            var ext = fileName.split('.').pop();
            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                }
            } else if (val == 'pictures') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .jpe .png) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function youtube_check() {
        var matches = $('#YouTube_Video').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
        data = $('#YouTube_Video').val();
        if (matches || data == '') {
            $("#video_link").html("");
            return true;
        } else {
            $("#video_link").html("<label for='YouTube_Video' generated='true' class='error'>Please provide youtube link</label>");
            return false;
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('.eeo_check').click(function() {
            if ($('#eeo_yes').is(':checked')) {
                //show form
                $('.questionnaire-form').slideDown('slow');
                $("input[name='us_citizen']").prop('required', true);
                $("input[name='group_status']").prop('required', true);
                $("input[name='veteran']").prop('required', true);
                $("input[name='disability']").prop('required', true);
                $("input[name='gender']").prop('required', true);
            } else {
                //hide form
                $('.questionnaire-form').slideUp('slow');
                $("input[name='us_citizen']").prop('required', false);
                $("input[name='group_status']").prop('required', false);
                $("input[name='veteran']").prop('required', false);
                $("input[name='disability']").prop('required', false);
                $("input[name='gender']").prop('required', false);
                $("#visa_status").prop('required', false);
            }
        });

        $('.citizen_check').click(function() {
            if ($('#citizen-yes').is(':checked')) {
                $("#visa_status").prop('required', false);
                $(".visa_status_div").css('display', 'none');
            } else {
                $("#visa_status").prop('required', true);
                $(".visa_status_div").css('display', 'block');
            }
        });
    });
</script>

<script src="https://apply.indeed.com/indeedapply/static/scripts/app/awi-bootstrap.js"></script>