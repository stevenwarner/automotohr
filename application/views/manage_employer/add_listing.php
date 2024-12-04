<div class="emp-main-content">
    <div class="employer-portal-container">
        <header class="hr-page-header">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <h2>Create Job</h2>
            <div class="back-btn">
                <a class="siteBtn redBtn" style="margin-bottom: 10px;" id="" href="<?= base_url('dashboard') ?>">&laquo; BACK</a>
            </div>
        </header>
        <div class="create-job-wrap">
            <div class="job-title-text">                
                <p>Enter information about your job.<br>Fields marked with an asterisk (<span>*</span>) are mandatory</p>
            </div>
            <div class="job-title-area">
                <span>title: <samp class="red"> * </samp></span>
                <form id="employers_add_job" action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="Title" id="Title" value="<?php echo set_value('ConatctName'); ?>">
                    <?php echo form_error('Title'); ?>
                    </div>
                    <div class="description-editor">
                        <h2>Job Description: <samp class="red"> * </samp></h2>
                        <textarea class="ckeditor"  name="JobDescription" id="JobDescription" cols="67" rows="6"><?php echo set_value('JobDescription'); ?></textarea>
                        <?php echo form_error('JobDescription'); ?>
                    </div>
                    <div class="description-editor">
                        <h2>Job requirements:</h2>
                        <textarea class="ckeditor"  name="JobRequirements" id="JobRequirements" cols="67" rows="6"><?php echo set_value('JobRequirements'); ?></textarea>
                    </div>
                    <div class="salary-form">
                        <ul>
                            <li>
                                <span>salary ($):</span>	
                                <div class="text">								
                                    <input type="text" name="salary" id="salary" value="<?php echo set_value('salary'); ?>" >
                                    <?php echo form_error('salary'); ?>
                                </div>
                            </li>
                            <li>
                                <span>salary ($):</span>	
                                <div class="text">								
                                    <input type="text" name="salary" id="salary" value="<?php echo set_value('salary'); ?>" >
                                    <?php echo form_error('salary'); ?>
                                </div>
                            </li>
                            <li>
                                <span>salary ($):</span>	
                                <div class="text">								
                                    <input type="text" name="salary" id="salary" value="<?php echo set_value('salary'); ?>" >
                                    <?php echo form_error('salary'); ?>
                                </div>
                            </li>
                            <li>
                                <span>Salary Type:</span>	
                                <div class="text">								
                                    <select class="searchList" name="SalaryType">
                                        <option value="" >Select Salary Type</option>			
                                        <option value="per_hour" <?php if (set_value('SalaryType') == "per_hour") { ?>selected<?php } ?>>per hour</option>
                                        <option value="per_week" <?php if (set_value('SalaryType') == "per_week") { ?>selected<?php } ?>>per week</option>
                                        <option value="per_month" <?php if (set_value('SalaryType') == "per_month") { ?>selected<?php } ?>>per month</option>
                                        <option value="per_year" <?php if (set_value('SalaryType') == "per_year") { ?>selected<?php } ?>>per year</option>
                                    </select>
                                </div>								
                            </li>
                            <li>
                                <span>country:</span>	
                                <div class="text">								
                                    <select class="searchList" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                        <?php
                                        foreach ($active_countries as $active_country) {
                                            ?>
                                            <option value="<?= $active_country["id"]; ?>"
                                            <?php if (set_value('Location_Country') == $active_country["id"]) { ?>
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
                                </div>								
                            </li>
                            <li>
                                <span>State:</span>	
                                <div class="text">								
                                    <select class="searchList" name="Location_State" id="state">
                                        <option value="">Select State</option>    
                                    </select>
                                </div>								
                            </li>
                            <li>
                                <span>City:</span>
                                <div class="text">									
                                    <input type="text" name="Location_City" id="city" value="<?php echo set_value('salary'); ?>" >
                                    <?php echo form_error('Location_City'); ?>
                                </div>							
                            </li>
                            <li>
                                <span>Zip Code:</span>
                                <div class="text">									
                                    <input type="text" name="Location_ZipCode"  id="zip_code" value="<?php echo set_value('salary'); ?>"  >
                                    <?php echo form_error('Location_ZipCode'); ?>
                                </div>
                            </li>
                            <li>
                                <span>Job Type:</span>
                                <div class="text">									
                                    <input type="radio" name="JobType" value="Full Time" checked/>Full Time <br>
                                    <input type="radio" name="JobType" value="Part Time"  />Part Time 
                                </div>									
                            </li>
                            <li>
                                <span>Image:</span>
                                <!--                                <div id="remove_image" class="logo-box">
                                                                    <div ><img src="" width="150" height="150">
                                                                        <div class="close-btn">
                                                                            <a href="javascript:;" onclick="image_remove()"><img src="../images/btn-close.png">
                                                                                <div class="tooltip">remove logo</div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                                <div class="text">
                                    <input type="file" name="pictures" id="logo">		
                                </div>							
                            </li>
                            <li>
                                <span>Youtube Video:</span>
                                <div class="text">
                                    <input type="text" name="YouTube_Video" id="youtubevideo" value="<?php echo set_value('salary'); ?>" placeholder="Youtube Video Link" >
                                    <div class="video-link" style='font-style: italic;'><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX </div> 
                                </div>
                                <br><p ></p><br>
                                <?php echo form_error('YouTube_Video'); ?>
                                <div style='font-style: italic; color: red;' id="video_link" ></div> 
                            </li>
                            <li>
                                <span>Job Category(s): <samp class="red"> * </samp></span>	
                                <div class="text">	
                                    <div class="Category_chosen">							
                                        <select data-placeholder="- Please Select -" multiple="multiple" onchange="multiselectbox()" name="JobCategory[]" id="Category"  class="chosen-select">
                                            <?php foreach ($data_list as $data) {
                                                ?>
                                                <option value="<?= $data['id']; ?>" ><?= $data['value']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div style="display: none;" id="choiceLimit">5</div>
                                    <span class="available"><samp id="choicelimitavailable">5</samp> available</span>
                                </div>    
                                <?php echo form_error('JobCategory'); ?>                                
                            </li>
                            <?php
                            if (!empty($screening_questions)) {
                                ?>
                                <li>
                                    <span>Screening Questionnaire:</span>	
                                    <div class="text">								
                                        <select class="searchList" name="questionnaire_sid">
                                            <option value="">Select Screening Questionnaire</option>
                                            <?php
                                            foreach ($screening_questions as $screening_question) {
                                                ?>
                                                <option value="<?= $screening_question['sid'] ?>">
                                                    <?= $screening_question['caption'] ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>								
                                </li>
                                <?php
                            }
                            ?>
                            <input type="hidden" value="add"  name="action">
                            <input type="hidden" value="1" name="active">
                            <li>
                                <input type="submit" value="Post">
                                <input type="button" value="Cancel" onClick="document.location.href = '<?= base_url('dashboard') ?>'" />
                            </li>
                        </ul>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace('JobDescription');
    CKEDITOR.replace('JobRequirements');
</script>
<script>

    function image_remove() {
        $('#remove_image').hide();
        document.getElementById("delete_image").value = '1';
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

    $('form').submit(function () {
        salary = $('#salary').val();
        city = $('#city').val();
        zip_code = $('#zip_code').val();
        title = $('#Title').val();


        if (title == '')
        {
            alertify.alert('Error! Job Title Missing', "Job Title cannot be Empty");
            return false;
        }
        var text_pass = $.trim(CKEDITOR.instances.JobDescription.getData());
        if (text_pass.length === 0) {
            alertify.alert('Error! Job Description Missing', "Job Description cannot be Empty");
            return false;

        }
        var items_length = $('#Category :selected').length;
        if (items_length == 0)
        {
            alertify.alert('Error! Job Category Missing', "Job Category cannot be Empty");
            return false;
        }

        if (isNaN(salary) && salary != '')
        {
            alertify.alert('Error! Salary Input', "Please enter Salary in number(s)");
            return false;
        }
        if (isNaN(zip_code) && zip_code != '')
        {
            alertify.alert('Error! Zip Code Input', "Please enter Zip_code in number(s)");
            return false;
        }
        if (!isAlphaOrParen(city) && city != '') {
            alertify.alert('Error! City Input', "Please enter City name in Alpabet(s)");
            return false;
        }

        return youtube_check();



    });

    function youtube_check() {

        var matches = $('#youtubevideo').val().match(/https:\/\/(?:www\.)?youtube.*watch\?v=([a-zA-Z0-9\-_]+)/);
        data = $('#youtubevideo').val();
        if (matches || data == '') {
            $("#video_link").html("");
            return true;
        } else {
            $("#video_link").html("Please enter a Valid Youtube Link");
            return false;
        }
    }

    function isAlphaOrParen(str) {
        return /^[-\sa-zA-Z]+$/.test(str);
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
</script>

<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $("body").ready(function () {
        var mylimit = parseInt($('#choiceLimit').html());
        multiselectbox();
        $(".chosen-select").chosen({max_selected_options: mylimit});
        $(".chosen-select").bind("liszt:maxselected", function () {
        });
        $(".chosen-select").chosen().change(function () {
        });

       
    });

</script>
