<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"></i> Safety Check List</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <button onclick="myFunctionPrint()" class="btn btn-info btn-block mb-2"><i class="fa fa-print"></i> Print Safety Sheet</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <form method="post" action="" id="inc-form" enctype="multipart/form-data" accept-charset="utf-8">
                    <input type="hidden" name="submittion_type" id="submittion_type" value="digital">
                    <input type="hidden" name="safety_name" value="<?php echo $title; ?>">
                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 abcde">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if (sizeof($questions) > 0) { ?>
                            <div id="digital_form">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Your Full Name:<span class="required">*</span></label>
                                        <?php echo form_input('full-name', set_value('full-name', $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']), 'class="form-control" id="full-name"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($questions as $question) {
                                echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';

                                if ($question['question_type'] == 'textarea') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']); ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'text') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : "";
                                    echo form_input(strip_tags($question['label']), set_value(strip_tags($question['label'])), 'class="form-control" ' . $required); ?>
<?php                           } elseif ($question['question_type'] == 'time') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="12:00AM" class="form-control start_time"  aria-invalid="false" required="<?php echo $required; ?>">
<?php                           } elseif ($question['question_type'] == 'date') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="" class="form-control start_date"  aria-invalid="false" required="<?php echo $required; ?>" autocomplete="off">
<?php                           } elseif ($question['question_type'] == 'signature') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']) ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('signature_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'radio') { ?>
                                    <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" name="<?php echo strip_tags($question['label']) ?>" value="yes" style="position: relative;" <?php echo $question['is_required'] ? "required" : "" ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" name="<?php echo strip_tags($question['label']) ?>" value="no" style="position: relative;" <?php echo $question['is_required'] ? "required" : "" ?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
<?php                           } elseif ($question['question_type'] == 'single select') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select name="<?php echo strip_tags($question['label']) ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                            <option value="">-- Please Select --</option>
<?php                                       $options = explode(',', $question['options']);

                                            foreach ($options as $option) { ?>
                                                <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
<?php                                       } ?>
                                        </select>
                                    </div>
<?php                           } elseif ($question['question_type'] == 'multi select') { ?>
                                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
<?php                                   $options = explode(',', $question['options']); ?>
<?php                                   foreach ($options as $option) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo $option; ?>
                                                    <input type="checkbox" name="<?php echo strip_tags($question['label']) ?>[]" value="<?php echo $option; ?>" style="position: relative;">
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
                                    <div class="form-col-100 autoheight text-right">
                                        <input type="submit" value="Save Digital Sheet" name="submit" class="btn btn-info">
                                        <input type="button" value="Upload Printed Sheet" class="btn btn-info" onClick="myFunctionUpload()" />
                                    </div>    
                                </div>
                            </div>
                            </div>
                            <div class="row" id="analog_form">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Upload Safety Sheet:</label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_docs">No file selected</span>
                                            <input name="safety_checklist_docs" id="docs" onchange="check_file('docs')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div id="file-upload-div" class="file-upload-box"></div>
                                        <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                    </div>
                                    <div class="custom_loader">
                                        <div id="loader" class="loader" style="display: none">
                                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                            <span>Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <div class="btn-wrp full-width text-right">
                                            <input type="button" value="Back To Digital Sheet" class="btn btn-info" onClick="back_to_check_list()" />
                                            <input type="submit" value="Upload Safety Checklist" name="submit" class="btn btn-info" id="submit_safety_checklist">
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body" id="PRINT_VIEW">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 abcde">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if (sizeof($questions) > 0) { ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Your Full Name:<span class="required">*</span></label>
                                        <input style="width: 70%" type="text" name="name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($questions as $question) {
                                echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';

                                if ($question['question_type'] == 'textarea') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']); ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'text') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : "";
                                    echo form_input('text_' . $question['id'], set_value('text_' . $question['id']), 'class="form-control" ' . $required); ?>
<?php                           } elseif ($question['question_type'] == 'time') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="12:00AM" class="form-control start_time"  aria-invalid="false" required="<?php echo $required; ?>">
<?php                           } elseif ($question['question_type'] == 'date') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="" class="form-control start_date"  aria-invalid="false" required="<?php echo $required; ?>" autocomplete="off">
<?php                           } elseif ($question['question_type'] == 'signature') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']) ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('signature_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'radio') { ?>
                                    <br><label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                <input type="radio" name="radio_<?php echo $question['id']; ?>" value="yes" style="position: relative;">Yes
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="radio_<?php echo $question['id']; ?>" value="no" style="position: relative;">No
                                            </label>
                                        </div>
                                    </div>
<?php                           } elseif ($question['question_type'] == 'single select') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
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
                                                    <input type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
<?php                                   } ?>
                                    </div>
<?php                               }
                                echo '</div> </div> </div>';
                            } ?>
<?php                   } else {
                            echo "<span class='no-data'>No Questions Scheduled For This Type</span>";
                        } ?>
                    </div>
            </div>
      </div>
    </div>
  </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
   
    function myFunctionPrint() {
        var prtContent = document.getElementById("PRINT_VIEW");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

    function myFunctionUpload() {
        $('#digital_form').hide();
        $('#analog_form').show();
        $('#submittion_type').val('analog');
    }

    function back_to_check_list() {
        $('#digital_form').show();
        $('#analog_form').hide();
        $('#submittion_type').val('digital');
    }

    $(window).load(function(){
        $('#analog_form').hide();
    });

 

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {

            var selected_file = fileName;
            var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
           
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + original_selected_file + '</span> </div> <div class="pull-right"><input class="submit-btn btn btn-info" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No safety checklist selected');
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

    $('#submit_safety_checklist').click(function () {
        var fileName = $("#docs").val();
        
        if (fileName.length > 0) {
            $("#digital_form").remove();
            form.submit();
            return true;
        } else {
            alertify.error('Please Upload Safety Checklist');
            return false;
        }
    });

    $(document).ready(function () {

        $('.start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15
        });

        $('.start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    }); 
</script>