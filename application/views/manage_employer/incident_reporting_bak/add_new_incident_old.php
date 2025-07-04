<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area margin-top">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Back</a>
                        <?php echo $title; ?>
                    </span>
                </div>
                <form method="post" action="" id="inc-form" enctype="multipart/form-data">
                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if(sizeof($questions)>0){?>
                            <?php if($report_type == 'confidential'){?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                    <label>Your Full Name:<span class="staric">*</span></label>
<!--                                        <input type="text" class="invoice-fields" name="full-name" id="title" value="--><?php //echo $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']?><!--" readonly required="required">-->
                                    <?php echo form_input('full-name', set_value('full-name', $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']), 'class="form-control" id="full-name" readonly'); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                            <?php foreach($questions as $question){
                                echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';
                                if($question['question_type'] == 'textarea'){
                                    ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?> : <span class="staric"><?php echo $question['is_required'] ? '*' : ''?></span></label>

<!--                                        <script type="text/javascript" src="--><?php //echo site_url('assets/ckeditor/ckeditor.js'); ?><!--"></script>-->
                                    <textarea class="form-control textarea" name="text_<?php echo $question['id'];?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : ""?>><?php echo set_value('text_' . $question['id']); ?></textarea>
<!--                                        CK editor code-->
<!--                                        <script type="text/javascript" src="--><?php //echo site_url('assets/ckeditor/ckeditor.js'); ?><!--"></script>-->
<!--                                        <textarea class="ckeditor textarea" name="textarea_--><?php //echo $question['id'];?><!--" rows="8" cols="60" --><?php //echo $question['is_required'] ? "required" : ""?><!-->
<!--                                            --><?php //echo set_value('textarea_' . $question['id']); ?>
<!--                                        </textarea>-->
                                <?php
                                }
                                elseif($question['question_type'] == 'text'){
                                    ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="staric"><?php echo $question['is_required'] ? '*' : ''?></span></label>
<!--                                        <input class="invoice-fields" name="text_--><?php //echo $question['id'];?><!--" type="text" --><?php //echo $question['is_required'] ? "required" : ""?><!-- placeholder="--><?php //echo $question['placeholder']?><!--"/>-->
                                    <?php $required = $question['is_required'] ? "required" : ""; echo form_input('text_'.$question['id'], set_value('text_'.$question['id']), 'class="form-control" '. $required ); ?>

                                <?php
                                }
                                elseif($question['question_type'] == 'radio'){?>
                                    <label><?php echo strip_tags($question['label']) ?> : <span class="staric"><?php echo $question['is_required'] ? '*' : ''?></span></label>
                                    <div class="autoheight send-email">
                                        <label class="control control--radio auto-height">
                                            Yes<input type="radio" name="radio_<?php echo $question['id']; ?>" value="yes" style="position: relative;" checked>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="autoheight send-email">
                                        <label class="control control--radio auto-height">
                                            No<input type="radio" name="radio_<?php echo $question['id']; ?>" value="no" style="position: relative;">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                <?php
                                }
                                elseif($question['question_type'] == 'single select'){?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="staric"><?php echo $question['is_required'] ? '*' : ''?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                            <option value="">-- Please Select --</option>
                                            <?php
                                                $options = explode(',',$question['options']);
                                                foreach ($options as $option) { ?>
                                                    <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                <?php
                                }
                                elseif($question['question_type'] == 'multi select'){?>
                                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required']?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo $question['label'] ?> : <span class="staric"><?php echo $question['is_required'] ? '*' : ''?></span></label>
                                    <div class="full-width">
                                        <?php $options = explode(',',$question['options']); ?>
                                        <?php foreach ($options as $option) { ?>
                                            <div class="full-width">
                                                <label class="control control--checkbox">
                                                    <?php echo $option; ?>
                                                    <input type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        <?php }?>
                                    </div>
                                <?php
                                    }
                                echo '</div> </div> </div>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Incident Supporting Docs :</label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_docs">No file selected</span>
                                            <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div id="file-upload-div" class="file-upload-box"></div>
                                        <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                    </div>
                                    <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Other </div>
	                                <div class="custom_loader">
	                                    <div id="loader" class="loader" style="display: none">
	                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
	                                        <span>Uploading...</span>
	                                    </div>
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
                                        <div class="btn-wrp full-width text-right">
                                            <input type="submit" value="Submit" name="submit" class="btn btn-success" id="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else{
                            echo "<span class='no-data'>No Questions Scheduled For This Type</span>";
                        }?>
                    </div>
                </form>
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
    $("#inc-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            title: {
                required: true
            },
            nature_of_report: {
                required: true
            },
            who_did_inappropriate: {
                required: true
            },
            to_whom: {
                required: true
            },
            when_and_where: {
                required: true
            },
            was_it_isolated: {
                required: true
            },
            why_you_believe_above: {
                required: true
            },
            your_reaction: {
                required: true
            },
            any_witness: {
                required: true
            },
            spoken_to_anyone: {
                required: true
            },
            reported_to_supervisor: {
                required: true
            },
            avoid_future_incidents: {
                required: true
            }
        },
        messages: {
            Title: {
                required: 'Job title is required'
            },
            nature_of_report: {
                required: 'Nature of your report is required'
            },
            who_did_inappropriate: {
                required: 'This field is required'
            },
            to_whom: {
                required: 'This field is required'
            },
            when_and_where: {
                required: 'These details are required'
            },
            was_it_isolated: {
                required: 'These details are required'
            },
            why_you_believe_above: {
                required: 'These details are required'
            },
            your_reaction: {
                required: 'Your reaction is required'
            },
            any_witness: {
                required: 'Please provide this information'
            },
            spoken_to_anyone: {
                required: 'These details are required'
            },
            reported_to_supervisor: {
                required: 'Please provide this information'
            },
            avoid_future_incidents: {
                required: 'Please provide some suggestions'
            }
        }
    });

    $("#inc-form").submit(function() {
        var flag = 0;
        
        $(".multi-checkbox").each(function(index, element ) {
            if($(this).attr('data-attr')!='0') {
                var key = "multi-list_"+$(this).attr('data-key');
                var name = "input:checkbox[name^='"+key+"']:checked";
                var checked = $(name).length;
                
                if(!checked){
                    alertify.error($(this).attr('data-value')+' is required');
                    flag = 1;
                    return false;
                }
            }
        });

        if(flag){
            return false;
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload(){
        $('.upload-file').show();
        if($('#uploaded-files').html() != ''){
            $('#uploaded-files').show();
        }
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload(){
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('id', <?php echo $id;?>);
        if($('#inc-id').val()!='0'){
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
            success: function(data){
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> '+file_data['name']+'</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
//                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if(data!="error"){
                    $('#inc-id').val(data);
                }
                else{
                    alert('Doc error');
                }
            },
            error: function(){
            }
        });
    }
</script>
<?php } else { ?>
    <?php $this->load->view('manage_employer/incident_reporting/add_new_incident'); ?>
<?php } ?>