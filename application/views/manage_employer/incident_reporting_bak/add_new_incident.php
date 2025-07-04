<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$next_btn = '';
$center_btn = '';
$back_btn = 'Dashboard';

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/general_information/' . $unique_sid);
    $next_btn = '<a href="javascript:;" class="btn btn-success btn-block" id="go_next" onclick="func_save_e_signature();"> Save And Proceed Next <i class="fa fa-angle-right"></i></a>';
    $center_btn = '<a href="'.base_url('onboarding/documents/' . $unique_sid).'" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
    $save_post_url = current_url();
    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $save_post_url = current_url();
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];
} ?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a></a>
                    </div>
                    <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php //echo base_url('incident_reporting_system/view_general_guide')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Incident Guide </a>
                    </div> -->
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-group">
                    <h3 class="text-blue">You are about to report a "<?php echo ucwords($report_type);?>" Report</h3>
                </div>
                <form method="post" action="" id="inc-form" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if (sizeof($questions) > 0) { ?>
                            <?php if ($report_type == 'confidential') { ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Your Full Name:<span class="required">*</span></label>
                                            <?php echo form_input('full-name', set_value('full-name', $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']), 'class="form-control" id="full-name" readonly'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
<?php
                            foreach ($questions as $question) {
                                echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';

                                if ($question['question_type'] == 'textarea') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required required_<?php echo $question['related_to_question'];?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                    <textarea id="text_<?php echo $question['id']; ?>" class="form-control textarea related_<?php echo $question['related_to_question']; ?>" data-require="<?php echo $question['is_required'];?>" data-attr="<?php echo $question['related_to_question'];?>" name="text_<?php echo $question['id']; ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'text') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question'];?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : "";
                                    echo form_input('text_' . $question['id'], set_value('text_' . $question['id']), 'class="form-control related_'.$question['related_to_question']. '" id="'. $question['id'] .'" data-require="'.$question['is_required'].'" '. $required . ' data-attr="'.$question['related_to_question'].'"'); ?>
<?php                           } elseif ($question['question_type'] == 'time') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question'];?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input id="<?php echo $question['id']; ?>" type="text" name="time_<?php echo $question['id']; ?>" value="12:00AM" class="form-control start_time related_<?php echo $question['related_to_question']; ?>" data-require="<?php echo $question['is_required'];?>" data-attr="<?php echo $question['related_to_question'];?>" aria-invalid="false" <?php echo $required; ?>>
<?php                           } elseif ($question['question_type'] == 'date') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question'];?>"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input id="<?php echo $question['id']; ?>" type="text" name="date_<?php echo $question['id']; ?>" value="" data-require="<?php echo $question['is_required'];?>" data-attr="<?php echo $question['related_to_question'];?>" class="form-control start_date related_<?php echo $question['related_to_question']; ?>"  aria-invalid="false" <?php echo $required; ?> autocomplete="off">
<?php                           } elseif ($question['question_type'] == 'signature') { ?>
                                    <div class="form-group">
                                        <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required required_<?php echo $question['related_to_question'];?> "><?php echo $question['is_required'] ? '*' : '' ?></span></label>
    <?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    </div>
                                    
                                    <!-- the below loaded view add e-signature -->
                                    <?php $this->load->view('static-pages/e_signature_button'); ?>
                                    <input type="hidden" name="signature" value="" id="signature_bas64_image">

<?php                           } elseif ($question['question_type'] == 'radio') { ?>
                                    <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="yes" style="position: relative;" checked>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" id="<?php echo $question['id']; ?>" name="radio_<?php echo $question['id']; ?>" data-attr="<?php echo $question['is_required']; ?>" value="no" style="position: relative;">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
<?php                           } elseif ($question['question_type'] == 'single select') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select id="<?php echo $question['id']; ?>" name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                            <option value="">-- Please Select --</option>
<?php                                       $options = explode(',', $question['options']);

                                            foreach ($options as $option) { ?>
                                                <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
<?php                                       } ?>
                                        </select>
                                    </div>
<?php                               } elseif ($question['question_type'] == 'multi select') { ?>
                                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
<?php                                   $options = explode(',', $question['options']); ?>
<?php                                   foreach ($options as $option) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo $option; ?>
                                                    <input id="<?php echo $question['id']; ?>" type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
<?php                                   } ?>
                                    </div>
<?php                               }
                                echo '</div> </div> </div>';
                            } ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Incident Supporting Docs:</label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_docs">No file selected</span>
                                            <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div id="file-upload-div" class="file-upload-box"></div>
                                        <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                    </div>
                                    <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents one after another </div>
                                    <div class="custom_loader">
                                        <div id="loader" class="loader" style="display: none">
                                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                            <span>Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xl-12 col-sm-12">
                                    <label class="auto-height">The Incident will be received/responded by: <span class="required">*</span></label>
                                    <div class="row">
<?php                                   foreach ($incident_managers as $im) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo $im['employee_name']; ?>
                                                    <!--<input type="checkbox" name="manager_to_review[]" value="<?php echo $im['employee_id']; ?>" style="position: relative;">-->
                                                    <input type="checkbox" name="review_manager[]" value="<?php echo $im['employee_id']; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
<?php                                   } ?>
                                    </div>
                                </div>
                                <input type="hidden" id="inc-id" name="inc-id" value="0"/>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <b><h4>BY CLICKING ON "SUBMIT" I CERTIFY THAT I HAVE BEEN TRUTHFUL IN EVERY RESPECT IN FILLING THIS REPORT</h4></b>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                    <div class="form-group">

                                        <div class="custom_loader">
                                            <div id="submit-loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                <span>Submitting...</span>
                                            </div>
                                        </div>
                                        <div class="btn-wrp full-width text-right">
                                            <input type="submit" value="Submit" name="submit" class="btn btn-info" id="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php                   } else {
                            echo "<span class='no-data'>No Questions Scheduled For This Type</span>";
                        } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('static-pages/e_signature_popup'); ?>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        <?php
                foreach ($questions as $question) {
                    if($question['question_type'] == 'textarea'){
                        echo 'CKEDITOR.replace("text_'.$question['id'].'");'."\r\n";
                    }
                }
            ?>
    });
    $("#inc-form").validate({
        ignore: ":hidden:not(select)",
        submitHandler: function (form) {
            var is_signature_exist = $('#signature_bas64_image').val();
            $('#submit').attr('disabled','disabled');
            <?php
                foreach ($questions as $question) {
                    if($question['question_type'] == 'textarea' && $question['is_required'] == 1){
                        echo 'var instances'.$question['id'].' = $.trim(CKEDITOR.instances.text_'.$question['id'].'.getData());'."\r\n";
                        echo 'if (instances'.$question['id'].'.length === 0) {'."\r\n";
                        echo 'alertify.alert("Error! Answer Missing", "Please Provide All (*)Required Fields");'."\r\n";
                        echo '$("#submit").removeAttr("disabled");'."\r\n";
                        echo 'return false;'."\r\n";
                        echo '}'."\r\n";
                    }
                }
            ?>
            if(is_signature_exist == ""){
                alertify.error('Please Add Your Signature!');
                $("#submit").removeAttr("disabled");
                return false;
            }

            var flag = 0;

            $(".multi-checkbox").each(function (index, element) {
                if ($(this).attr('data-attr') != '0') {
                    var key = "multi-list_" + $(this).attr('data-key');
                    var name = "input:checkbox[name^='" + key + "']:checked";
                    var checked = $(name).length;

                    if (!checked) {
                        alertify.error($(this).attr('data-value') + ' is required');
                        flag = 1;
                        $('#submit').removeAttr('disabled');
                        return false;
                    }
                }
            });

            if($('[name="review_manager[]"]:checked').length == 0){
                alertify.error('Please select manager');
                flag = 1;
                $('#submit').removeAttr('disabled');
                return false;
            }

            if (flag) {
                $('#submit').removeAttr('disabled');
                return false;
            }

            $('#submit-loader').show();
//            console.log('1');
//            return false;
            $("#inc-form")[0].submit();
        }
    });
    $('.start_date').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50"
    });

    $(document).on('change','input[type="radio"]',function(){
        var related = $(this).attr('id');
        var value = $("input[type='radio'][name='radio_"+related+"']:checked").val();
        if(value == 'no'){
//            $(this).rules('remove', 'required');
            $('.related_'+related).removeClass('error');
            $('.related_'+related).removeAttr('required');
            $('.required_'+related).hide();
        } else{
            $('.related_'+related).each(function(index,object){
                var require = $(object).attr('data-require');
                if(require == '1'){
//                  $(this).rules('add', 'required');
                    $(object).addClass('error');
                    $(object).prop('required',true);
                    $(object).prev().find('.required_'+related).show();
                } else{
                    $(object).removeClass('error');
                    $(object).removeAttr('required');
                    $(object).prev().find('.required_'+related).hide();
                }
            });
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-info" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-info" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();
        
        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }
        
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('id', <?php echo $id; ?>);
        
        if ($('#inc-id').val() != '0') {
            form_data.append('inc_id', $('#inc-id').val());
        }
        
        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('incident_reporting_system/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
//                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                
                if (data != "error") {
                    $('#inc-id').val(data);
                } else {
                    alert('Doc error');
                }
            },
            error: function () {
            }
        });
    }
    
</script>