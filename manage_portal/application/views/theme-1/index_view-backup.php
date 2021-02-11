<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="container">
        <div class="row">
            <?php $this->load->view($theme_name . '/_parts/admin_flash_message'); ?>					
            <div class="col-md-12">
                <header class="heading-title">
                    <h2 class="page-title">Job Details<?php //echo $heading_title                        ?></h2>
                    <?php echo anchor("/join_our_talent_network", "Join Our Talent Network", array('class' => 'join-btn')); ?>       
                </header>
                <?php if (empty($job_listings)) { ?>
                    <div class="job-article-wrapper">
                        <article class="job-article">
                            <div class="icon-job">
                                <figure>
                                    <?php if (empty($company_details['Logo'])) { ?>
                                        <img src="<?php echo base_url('assets/theme-1/images/logo.png'); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo AWS_S3_BUCKET_URL . $company_details['Logo']; ?>">
                                    <?php } ?>
                                </figure>
                            </div>
                            <div class="job-title">
                                <span class="title-no-jobs">
                                    <?php echo 'No Active Jobs Available at the moment!'; ?> 
                                </span>									
                            </div>		
                        </article>
                    </div>
                <?php } else { ?>
                    <?php foreach ($job_listings as $key => $value) { ?>
                        <?php
                        if (empty($value['pictures'])) {
                            $image = base_url('assets/theme-1/images/logo.png');
                        } else {
                            $image = AWS_S3_BUCKET_URL . $value['pictures'];
                        }
                        ?>
                        <!---Social Media Sharing Meta Tags Start--->
                        <!--{*facebook meta*}-->
                        <meta property="og:title" content="<?= $value['Title'] ?>" />
                        <meta property="og:site_name" content="<?= $value['JobCategory'] ?>"/>
                        <meta property="og:type" content="website" />
                        <meta property="og:url" content="<?= base_url() ?>job_details/<?= $value['sid'] ?>"/>
                        <meta property="og:image" content="<?= $image ?>" />

                        <!--{*google meta*}-->
                        <meta itemprop="name" content="<?= $value['Title'] ?>">
                        <meta itemprop="description" content="<?= $value['JobCategory'] ?>">
                        <meta property="og:image" content="<?= $image ?>" />

                        <!--{*twitter meta*}-->
                        <meta name="twitter:card" content="summary" />
                        <meta name="twitter:site" content="<?= base_url() ?>job_details/<?= $value['sid'] ?>" />
                        <meta name="twitter:title" content="<?= $value['Title'] ?>" />
                        <meta name="twitter:description" content="<?= $value['JobCategory'] ?>" />
                        <meta property="og:image" content="<?= $image ?>" />

                        <!---Social Media Sharing Meta Tags End--->
                        <div class="job-article-wrapper">
                            <article class="job-article">
                                <div class="icon-job">
                                    <figure>
                                        <?php if (empty($value['pictures'])) { ?>
                                            <img src="<?php echo base_url('assets/theme-1/images/logo.png'); ?>">
                                        <?php } else { ?>
                                            <img src="<?php echo AWS_S3_BUCKET_URL . $value['pictures']; ?>">
                                        <?php } ?>
                                    </figure>
                                </div>
                                <div class="job-title">
                                    <h2 class="post-title">
                                        <?php echo anchor("/job_details/" . $value['sid'], $value['Title'], array('class' => 'color', 'id' => 'job_title' . $value['sid'])); ?> 
                                    </h2>
                                    <ul class="categories">
                                        <li><?php echo $value['JobCategory']; ?></li>
                                    </ul>
                                    <div class="job-location">
                                        <ul>
                                            <li>
                                                <?php echo img('assets/theme-1/images/icon-location.png'); ?>
                                                <div class="text"><?php if(!empty($value['Location_State'])){ echo $value['Location_State']; ?>, <?php } ?> <?php echo $value['Location_Country']; ?></div>
                                            </li>
                                        </ul>
                                    </div>									
                                </div>							
                                <div class="show-job">
                                    <a class="siteBtn apply-now-btn custom-apply-now" href="javascript:void(0)" onclick="show_popup(<?= $value['sid'] ?>)">apply now</a>
                                    <a style="display:none;" id="show_hide<?= $value['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                    <a class="siteBtn showDetail" href="javascript:void(0)">show details</a>
                                </div>
                                <div class="description-wrapper">
                                    <div class="job-description">
                                        <h2 class="post-title color">job description</h2>
                                        <?php echo $value['JobDescription']; ?>
                                    </div>
                                    <?php if (!empty($value['JobRequirements'])) { ?>
                                        <div class="job-description">
                                            <h2 class="post-title color">job requirement</h2>
                                            <?php echo $value['JobRequirements']; ?>
                                        </div>
                                    <?php } ?>
                                    <!-- screening questionnaire data *** START *** -->
                                    <p id="questionnaire_sid<?php echo $value['sid']; ?>" style="display:none;"><?php echo $value['questionnaire_sid']; ?></p> 
                                    <div style="display:none" id="questions<?php echo $value['sid']; ?>"> 
                                        <label>Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)</label>
                                        <?php if ($value['questionnaire_sid'] > 0) { ?>
                                            <div class="wrap-container">
                                                <div class="wrap-inner">
                                                    <h2 class="post-title color">Questionnaire</h2>
                                                    <input type='hidden' name="q_name" value="<?php echo $value['q_name']; ?>">
                                                    <input type='hidden' name="q_passing" value="<?php echo $value['q_passing']; ?>">
                                                    <input type='hidden' name="q_send_pass" value="<?php echo $value['q_send_pass']; ?>">
                                                    <input type='hidden' name="q_pass_text" value="<?php echo $value['q_pass_text']; ?>">
                                                    <input type='hidden' name="q_send_fail" value="<?php echo $value['q_send_fail']; ?>">
                                                    <input type='hidden' name="q_fail_text" value="<?php echo $value['q_fail_text']; ?>">
                                                    <input type='hidden' name="my_id" value="<?php echo $value['my_id']; ?>">
                                                    <?php
                                                    $my_id = $value['my_id'];
                                                    foreach ($value[$my_id] as $questions_list) {
                                                        ?>
                                                        <input type="hidden" name="all_questions_ids[]" value="<?php echo $questions_list['questions_sid']; ?>">
                                                        <input type="hidden" name="caption<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['caption']; ?>">
                                                        <input type="hidden" name="type<?php echo $questions_list['questions_sid']; ?>" value="<?php echo $questions_list['question_type']; ?>">
                                                        <p>
                                                            <label><?php echo $questions_list['caption']; ?>: <?php if ($questions_list['is_required'] == 1) { ?><samp class="red"> * </samp><?php } ?></label>
                                                            <?php if ($questions_list['question_type'] == 'string') { ?>
                                                                <input type="text" class="form-fields" name="string<?php echo $questions_list['questions_sid']; ?>" placeholder="<?php echo $questions_list['caption']; ?>" value="" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'boolean') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                    <input type="radio" name="boolean<?php echo $questions_list['question_type']; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>> <?php echo $answer_list['value']; ?>&nbsp; 
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'list') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                                <select name="list<?php echo $questions_list['questions_sid']; ?>" class="form-fields" <?php if ($questions_list['is_required'] == 1) { ?> required <?php } ?>>
                                                                    <option value="">-- Please Select --</option>
                                                                    <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                        <option value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>"> <?php echo $answer_list['value']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } ?>
                                                            <?php if ($questions_list['question_type'] == 'multilist') { ?>
                                                                <?php $answer_key = 'q_answer_' . $questions_list['questions_sid']; ?>
                                                            <div class="checkbox-wrap">
                                                                <?php $iterate = 0; ?>
                                                                <?php foreach ($value[$answer_key] as $answer_list) { ?>
                                                                    <div class="label-wrap">
                                                                        <div class="squared">
                                                                            <input type="checkbox" name="multilist<?php echo $questions_list['questions_sid']; ?>[]" id="squared<?php echo $iterate; ?>" value="<?php echo $answer_list['value']; ?> @#$ <?php echo $answer_list['score']; ?>">
                                                                            <label for="squared<?php echo $iterate; ?>"></label>
                                                                        </div>
                                                                        <span><?php echo $answer_list['value']; ?></span>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                            </div> 
                                        <?php } ?> 
                                    </div>
                                    <!-- screening questionnaire data ***  END *** -->
                                    <div class="btn-wrap">
                                        <?php echo anchor("/job_details/" . $value['sid'], "more details", array('class' => 'siteBtn btn-deatil-more')); ?> 
                                        <a class="siteBtn apply-now-btn custom-apply-now" href="javascript:void(0)" onclick="show_popup(<?= $value['sid'] ?>)">apply now</a>
                                        <a style="display:none;" id="show_hide<?= $value['sid'] ?>" data-toggle="modal" data-target="#myModal">&nbsp;</a>
                                        <?php //echo anchor("javascript:void(0)","hide details",array('class'=>'siteBtn hideDetail btn-hide'));     ?> 
                                        <a class="siteBtn hideDetail btn-hide" href="javascript:void(0)">hide details</a>
                                    </div>									
                                </div>
                            </article>
                            <div class="social-media">
                                <ul>
                                    <li><span class='st_facebook_large' st_title='<?= $value['Title'] ?>' st_image='<?= $image ?>' st_url='<?= base_url() ?>job_details/<?= $value['sid'] ?>' displayText='ShareThis'></span></li>
                                    <li><a href="https://twitter.com/intent/tweet?url=<?= base_url() ?>job_details/<?= $value['sid'] ?>" target="_blank"><?php echo img('assets/theme-1/images/social-3.png'); ?></a></li>
                                    <li><a href="https://plusone.google.com/_/+1/confirm?hl=en&url=<?= base_url() ?>job_details/<?= $value['sid'] ?>" target="_blank"><?php echo img('assets/theme-1/images/social-1.png'); ?></a></li>
                                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= base_url() ?>job_details/<?= $value['sid'] ?>&title=<?= $value['Title'] ?>&summary=<?= $value['JobCategory'] ?>&source=" target="_blank"><?php echo img('assets/theme-1/images/social-4.png'); ?></a></li>
                                </ul>
                            </div>
                        </div>	
                    <?php } ?>
                <?php } ?>
                <!-- <div class="Pagination">
                    <ul>
                        <li class="previous"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
                        <li class="bg-color"><a href="javascript:void(0)">1</a></li>
                        <li class="bg-color"><a href="javascript:void(0)">2</a></li>
                        <li class="bg-color"><a href="javascript:void(0)">3</a></li>
                        <li class="next"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
                    </ul>
                </div>-->
            </div>
        </div>
    </div>
    <?php //$this->load->view('theme-1/dialogbox_view');   ?>
    <style>
        .stLarge {
            background-image: url('assets/theme-1/images/social-2.png') !important;
            height: 41px !important;
            width: 114px !important;
        }
        .stButton .stLarge:hover {
            background-position: 0 center !important;
            opacity: 0.75;
        }
        .social-media ul li img:hover{
            opacity: 0.75;
        }
        .Pagination li:hover {
            background: rgba(0, 0, 0, 0) linear-gradient(to bottom, rgba(0, 167, 0, 1) 0%, rgba(0, 141, 1, 1) 100%) repeat scroll 0 0;
        }
        .Pagination .active {
            background: rgba(0, 0, 0, 0) linear-gradient(to bottom, rgba(0, 167, 0, 1) 0%, rgba(0, 141, 1, 1) 100%) repeat scroll 0 0;
            border-radius: 50%;
        }
    </style>

    <script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/theme-1/js/jquery.validate.min.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
    <script type="text/javascript">
                                    function show_popup(val) {
                                        console.log('show popup: ' + val);
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
                                                }, email: {
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
                                            },
                                            submitHandler: function (form) {
                                                form.submit();
                                            }
                                        });
                                    }
                                    function check_file(val) {
                                        console.log('I am checking: ' + val);
                                        var fileName = $("#" + val).val();
                                        if (fileName.length > 0) {
                                            $('#name_' + val).html(fileName.substring(12, fileName.length));
                                            var ext = fileName.split('.').pop();
                                            if (val == 'resume' || val == 'cover_letter') {
                                                if (ext != "pdf" && ext != "docx" && ext != "doc") {
                                                    $("#" + val).val(null);
                                                    $('#name_' + val).html('Only (.pdf .docx .doc) allowed!');
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog custom-popup" role="document">
            <div class="modal-content">
                <div class="modal-header border-none">
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title modal-heading" id="myModalLabel">apply for job</h4>
                </div>
                <div class="modal-body">
                    <div class="apply-job-from">
                        <ul>
                            <form class="popup-form" method="post" name="register-form" enctype="multipart/form-data" id="register-form">
                                <li>
                                    <label>first name <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" name="first_name" required="required" placeholder="Enter First Name" value="<?php
                                    if (isset($formpost['first_name'])) {
                                        echo $formpost['first_name'];
                                    }
                                    ?>">
                                           <?php echo form_error('first_name'); ?>
                                </li>
                                <li>
                                    <label>last name <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" name="last_name" required="required" placeholder="Enter Last Name" value="<?php
                                    if (isset($formpost['last_name'])) {
                                        echo $formpost['last_name'];
                                    }
                                    ?>">
                                           <?php echo form_error('last_name'); ?>
                                </li>
                                <li>
                                    <label>profile picture</label>
                                    <div class="form-fields choose-file">
                                        <div class="file-name" id="name_pictures">Please Select</div>
                                        <input class="choose-file-filed bg-color" type="file" name="pictures" id="pictures" onchange="check_file('pictures')">
                                        <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                    </div>
                                    <?php echo form_error('pictures'); ?>
                                </li>
                                <li>
                                    <label>share Youtube video</label>
                                    <input class="form-fields" type="text" name="YouTube_Video" id="YouTube_Video" placeholder="Youtube Video Link" onblur="return youtube_check()" value="<?php
                                    if (isset($formpost['YouTube_Video'])) {
                                        echo $formpost['YouTube_Video'];
                                    }
                                    ?>">
                                    <p id="video_link"></p>
                                    <?php echo form_error('YouTube_Video'); ?>
                                </li>
                                <li>
                                    <label>email <span class="staric">*</span></label>
                                    <input class="form-fields" type="email" name="email" required="required" placeholder="Enter Email" value="<?php
                                    if (isset($formpost['email'])) {
                                        echo $formpost['email'];
                                    }
                                    ?>">
                                           <?php echo form_error('email'); ?>
                                </li>
                                <li>
                                    <label>Phone <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" name="phone_number" required="required" placeholder="Phone Number" value="<?php
                                    if (isset($formpost['phone_number'])) {
                                        echo $formpost['phone_number'];
                                    }
                                    ?>">
                                           <?php echo form_error('phone_number'); ?>
                                </li>
                                <li>
                                    <label>street address</label>
                                    <input class="form-fields" type="text" name="address" placeholder="Enter Address" value="<?php
                                    if (isset($formpost['address'])) {
                                        echo $formpost['address'];
                                    }
                                    ?>">
                                           <?php echo form_error('address'); ?>
                                </li>
                                <li>
                                    <label>city <span class="staric">*</span></label>
                                    <input class="form-fields" type="text" name="city" required="required" placeholder="Enter City" value="<?php
                                    if (isset($formpost['city'])) {
                                        echo $formpost['city'];
                                    }
                                    ?>">
                                           <?php echo form_error('city'); ?>
                                </li>
                                <li>
                                    <?php
                                    if (isset($formpost['country'])) {
                                        $country_id = $formpost['country'];
                                    } else {
                                        $country_id = 227;
                                    }
                                    ?>
                                    <label>state <span class="staric">*</span></label>
                                    <select class="form-fields" name="state" id="state" required="required" >
                                        <?php
                                        if (empty($country_id)) {
                                            echo '<option value="">Select State </option>';
                                        } else {
                                            foreach ($active_states[$country_id] as $active_state) {
                                                echo '<option value="' . $active_state['sid'] . '">' . $active_state['state_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php echo form_error('state'); ?>
                                </li>
                                <li>
                                    <label>country <span class="staric">*</span></label>
                                    <select class="form-fields" name="country" onchange="getStates(this.value, '<?php echo $states; ?>')" required="required" >
                                        <?php foreach ($active_countries as $active_country) { ?>
                                            <option value="<?php echo $active_country['sid']; ?>" 
                                            <?php if ($active_country['sid'] == $country_id) { ?>
                                                        selected
                                                        <?php
                                                        $country_id = $active_country['sid'];
                                                    }
                                                    ?>>
                                                        <?php echo $active_country['country_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo form_error('country'); ?>
                                </li>
                                <li>
                                    <label>attach resume <span class="staric">*</span></label>
                                    <div class="form-fields choose-file" name="resume" required="required">
                                        <div class="file-name" id="name_resume">Please Select</div>
                                        <input class="choose-file-filed" type="file" name="resume" id="resume" required="required" onchange="check_file('resume')" >
                                        <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                    </div>
                                    <?php echo form_error('resume'); ?>
                                </li>
                                <li>
                                    <label>attach cover </label>
                                    <div class="form-fields choose-file">
                                        <div class="file-name" id="name_cover_letter">Please Select</div>
                                        <input class="choose-file-filed" type="file" id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')" >
                                        <a class="choose-btn bg-color" href="javascript:;">choose file</a>
                                    </div>
                                    <?php echo form_error('cover_letter'); ?>
                                </li>
                                <li class="questionare-section" id="show_questionnaire"></li>
                                <li>
                                    <input type="hidden" name='job_sid' id="job_sid" value=""> 
                                    <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="">
                                    <input type="hidden" name="action" value="job_applicant">
                                    <input class="siteBtn bg-color" type="submit" onclick="validate_form()" value="apply now">
                                </li>
                            </form>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>	      
            </div>
        </div>
    </div>