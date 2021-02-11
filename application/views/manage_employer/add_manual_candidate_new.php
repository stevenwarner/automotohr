<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <span class="page-heading down-arrow">Add Manual Candidate</span>
                        </div>
                        <div class="manual-candidate-text">
                            <h2 class="manual-candidate-heading">What is "Add Manual Candidate"?</h2>
                            <p>This area enables you to add job candidates to your company application tracking module. </p>
                            <p>Simply add the candidates basic information and click Submit. </p>
                            <p>The new Candidate profile will now appear in your applicant tracking system. </p>
                            <p>&nbsp;</p>
                        </div> 
                        <div class="universal-form-style-v2">
                            <ul>
                                <?php echo form_open_multipart('', array('id' => 'manual_candidate')); ?>
                                    <li class="form-col-50-left">
                                        <?php echo form_label('First Name <span class="hr-required">*</span>','first_name'); ?>
                                        <?php echo form_input('first_name',set_value('first_name'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('first_name'); ?>
                                    </li>
                                    <li class="form-col-50-right">
                                        <?php echo form_label('Last Name <span class="hr-required">*</span>','first_name'); ?>
                                        <?php echo form_input('last_name',set_value('last_name'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('last_name'); ?>
                                    </li>                               
                                    <li class="form-col-50-left">
                                        <?php echo form_label('E-Mail<span class="hr-required">*</span>','email'); ?>									
                                        <?php echo form_input('email',set_value('email'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('email'); ?>	
                                    </li>
                                    <li class="form-col-50-right">
                                        <?php echo form_label('Phone Number','phone_number'); ?>									
                                        <?php echo form_input('phone_number',set_value('phone_number'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('phone_number'); ?>
                                    </li>
                                    <li class="form-col-50-left">
                                        <?php echo form_label('Address','address'); ?>
                                        <?php echo form_input('address',set_value('address'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('address'); ?>
                                    </li>                                  
                                    <li class="form-col-50-right">
                                        <?php echo form_label('City','city'); ?>
                                        <?php echo form_input('city',set_value('city'),'class="invoice-fields"'); ?>
                                        <?php echo form_error('city'); ?>
                                    </li>
                                    <li class="form-col-50-left">
                                        <label>Country:</label>                             
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" id="country" name="country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                <option value="">Select Country</option>
                                                <?php foreach ($active_countries as $active_country) { ?>
                                                    <option value="<?php echo $active_country["sid"]; ?>">
                                                    <?php echo $active_country["country_name"]; ?>
                                                    </option>
                                                <?php } ?>
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
                                    <li class="form-col-50-left">
                                        <label>Select Job:</label>                             
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields" name="job_sid">
                                                <?php foreach ($all_jobs as $job) { ?>
                                                    <?php if ($jobs_approval_module_status == '1') { ?>
                                                        <?php if ($job['approval_status'] == 'approved') { ?>
                                                            <option value="<?php echo $job["sid"]; ?>"><?php echo $job["Title"]; ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                            <option value="<?php echo $job["sid"]; ?>"><?php echo $job["Title"]; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>                              
                                    </li>
                                    <li class="form-col-50-right">  
                                        <label>Desired Job Title:</label>                                   
                                        <div>
                                            <input type="text" class="invoice-fields" name="desired_job_title">
                                        </div>                              
                                    </li>

                                    <li class="form-col-50-left">
                                        <label>Attach Resume:</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_resume">No file selected</span>
                                            <input type="file" name="resume" id="resume"  onchange="check_file('resume')" >
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </li>

                                    <li class="form-col-50-right">
                                        <label>Attach Cover Letter:</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_cover_letter">No file selected</span>
                                            <input type="file" id="cover_letter" name="cover_letter" onchange="check_file('cover_letter')">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <a href="<?php echo base_url('application_tracking_system/active/all/all/all/all'); ?>" class="submit-btn btn-cancel">Cancel</a>
                                        <input type="submit" value="Submit" onclick="validate_form()" class="submit-btn">
                                    </li>
                                <?php echo form_close();?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>                       		
        </div>        
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script>
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
                                pattern: /^[0-9\-]+$/
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
                email:      {
                                required: 'Please provide Valid email'
                            },
                phone_number: {
                    required: 'Phone Number is required',
                    pattern: 'Numbers and dashes only please'
                },
            },
            submitHandler: function (form) {
                // Validate Job
                if(
                    $('input[name="desired_job_title"]').val().trim() == '' &&
                    (
                        $('select[name="job_sid"]').val() == null ||
                        $('select[name="job_sid"]').val() == '' ||
                        $('select[name="job_sid"]').val() == 0
                    )
                ){
                    alertify.alert('WARNING!', 'Please, either select a job or add a desired job title.', () => {});
                } else form.submit();
            }
        });
    }
    // get the states 
    $( document ).ready(function() {
        var myid = $('#state_id').html();
        setTimeout(function(){ 
            $( "#country" ).change();
        }, 1000);
        if(myid){
            setTimeout(function(){ 
                $('#state').val(myid);
            }, 1200);
        }
    });
    
    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
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
    
    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();

            if (val == 'resume' || val == 'cover_letter') {
                if (ext != "pdf" && ext != "docx" && ext != "doc") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc) allowed!</p>');
                }
            }
            else if(val == 'pictures'){
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }
</script>