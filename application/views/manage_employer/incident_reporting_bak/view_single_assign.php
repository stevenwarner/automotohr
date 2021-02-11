<?php _e($assigned_incidents, true, false, true); 
if(!isset($assigned_incidents[0]['status'])){ $$assigned_incidents[0]['status'] = 'open';}

?>
<div class="main">
    <div class="container">
        <div class="row">
<!--            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">-->
<!--                --><?php //$this->load->view('main/employer_column_left_view'); ?>
<!--            </div>-->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a></a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <!-- <a href="<?php //echo base_url('incident_reporting_system/view_general_guide')?>" class="btn btn-info"><i class="fa fa-book"></i> Incident Guide </a> -->
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>


                <?php if (sizeof($assigned_incidents)>0) {

                    if ($assigned_incidents[0]['report_type'] == 'confidential') { ?>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped"
                                       id="reference_network_table">
                                    <b>Reporter Name</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $assigned_incidents[0]['first_name'] . " " . $assigned_incidents[0]['last_name'] ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped"
                                       id="reference_network_table">
                                    <b>Reporter Telephone Number</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php echo isset($assigned_incidents[0]['PhoneNumber']) ? $assigned_incidents[0]['PhoneNumber'] : 'N/A' ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped"
                                       id="reference_network_table">
                                    <b>Reporter Email</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $assigned_incidents[0]['email'] ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped"
                                       id="reference_network_table">
                                    <b>Reporter Title</b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $assigned_incidents[0]['job_title'] ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php }
                    foreach ($assigned_incidents as $incident) {
                        ?>
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <table class="table table-bordered table-hover table-stripped"
                                       id="reference_network_table">
                                    <b><?php echo $incident['question']; ?></b>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php
                                            $ans = @unserialize($incident['answer']);
                                            if ($ans !== false) {
                                                echo implode(', ', $ans);
                                            } else {
                                                echo ucfirst($incident['answer']);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

            <?php if ($assigned_incidents[0]['report_type'] == 'confidential' || $assigned_incidents[0]['report_type'] == 'anonymous') { ?>
                <div class="table-responsive table-outer">
                    <div class="table-wrp data-table">
                        <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                            <b>Related Documents</b>
                            <tbody>
                            <?php if (sizeof($get_incident_report) > 0) {
                                foreach ($get_incident_report as $report) {
                                    ?>
                                    <tr>
                                        <td><a href="<?php echo AWS_S3_BUCKET_URL . $report['file_code'] ?>"
                                               download="docs"><?php echo $report['file_name']; ?></a></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td>No Documents Found</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <form id="form_new_note" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                    <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                <b>Upload Docs :</b>
                                <tbody>

                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_docs">No file selected</span>
                                                <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                <a href="javascript:;">Choose File</a>
                                            </div>
                                        </div>

                                        <input name="inc_id" type="hidden" value="<?= $id ?>">
                                        <input name="assign_check" type="hidden" value="check">

                                        <div id="file-upload-div"></div>

                                        <div class="custom_loader">
                                            <div id="loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;"
                                                   class="fa fa-cog fa-spin"></i>
                                                <span>Uploading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }?>
                    <?php if (sizeof($comments) > 0) { ?>
                        <div class="table-responsive table-outer">
                            <div class="panel panel-blue">
                                <div class="panel-heading">
                                    <strong>Related Response</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="respond">

                                        <?php if (sizeof($comments) > 0) {
                                            foreach ($comments as $comment) {
                                                $name = empty($comment['user2']) ? ucfirst($comment['user1']) : ucfirst($comment['user2']);
                                                $pp = empty($comment['user2']) ? $comment['pp1'] : $comment['pp2'];
                                                $url = empty($pp) ? base_url() . "assets/images/attachment-img.png" : AWS_S3_BUCKET_URL . $pp;


                                                ?>
                                                <article <?php echo empty($comment['user2']) ? '' : 'class="reply"' ?>>
                                                    <figure><img class="img-responsive" src="<?= $url ?>"></figure>
                                                    <div class="text">
                                                        <div class="message-header">
                                                            <div class="message-title">
                                                                <h2><?php echo ($assigned_incidents[0]['report_type'] == 'anonymous' && $comment['emp_id'] != NULL) ? 'Anonymous' : $name . " (" . $comment['response_type'] . ')'; ?></h2>
                                                            </div>
                                                            <ul class="message-option">
                                                                <li>
                                                                    <time><?php echo my_date_format($comment['date_time']); ?></time>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!--                                            <span class="span">This is CV</span>-->
                                                        <p><?php echo strip_tags($comment['comment']); ?></p>
                                                    </div>
                                                </article>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td>No Response Found</td>
                                            </tr>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($assigned_incidents[0]['status'] != 'Closed'){ ?>
                    <div class="hr-box">
                        <div class="panel panel-blue">
                            <div class="panel-heading">
                                <strong>Add New Note</strong>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="universal-form-style-v2">
                                            <input type="hidden" id="applicant_sid" name="applicant_sid" value="10">

                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label class="auto-height">Type: <span
                                                            class="staric">*</span></label>

                                                    <div class="hr-select-dropdown">
                                                        <select name="response_type" class="form-control"
                                                                required="" aria-required="true">
                                                            <option value="">-- Please Select --</option>
                                                            <option value="Personal"> Employer Notes</option>
                                                            <option value="Response"> Send Response</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label for="note_txt">Note</label>
                                                    <!--                                                <script type="text/javascript" src="-->
                                                    <?php //echo site_url('assets/ckeditor/ckeditor.js'); ?><!--"></script>-->
                                                            <textarea class="form-control response" name="response"
                                                                      rows="8"
                                                                      cols="60"
                                                                      required><?php echo set_value('response'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <button type="submit" class="btn btn-info" name="submit"
                                                            value="submit">Add Note
                                                    </button>
                                                </div>
                                            </div>
                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }else{?>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="panel panel-blue">
                                <div class="alert alert-success">
                                    <h2 class="text-center"><strong class="text-center">Incident Is Resolved</strong></h2>
                                </div>
                            </div>
                        </div>
                          <?php }
                       }
                    }   else { ?>
                    <div id="show_no_jobs" class="table-wrp">
                        <span class="applicant-not-found">No Incident Assigned!</span>
                    </div>
                <?php } ?>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="application/javascript">
//    CKEDITOR.replace('response');

    $("#form_new_note").validate({
    ignore: ":hidden:not(select)",
    rules: {
        response_type: {
            required: true
        }
    },
    messages: {
        response_type: {
            required: 'Response Type is required'
        }
    }
});

    $(document).ready(function(){
        $('#form_new_note').submit(function(){
            var response = $('.response').val().trim();
            if(response.length === 0){
                alertify.error('Please provide your response');
                return false;
            }
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#file-upload-div').append('<div class="form-group autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-info" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-info" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div> ');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload(){
        $('.upload-file').show();
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload(){
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('inc_id', <?= $id ?>);
        form_data.append('assign_check', 'check');
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
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if(data=="error"){
                    alert('Doc error');
                }
            },
            error: function(){
            }
        });
    }

</script>