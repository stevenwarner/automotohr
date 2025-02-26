<?php $current_user = $session['employer_detail']['sid']; ?>
<div class="table-responsive table-outer">
    <div class="panel panel-blue">
        <div class="panel-heading incident-panal-heading">
            <b>My Emails</b>
            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all_emails/1').'/'.$id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-print"></i> Print</a>
            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all_emails/2').'/'.$id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download</a>
        </div>
        <div class="panel-body">
            <div class="email">
                <?php if ($report['emails']) { ?>
                    <?php foreach ($report['emails'] as $email) { ?>
                        <div class="accordion-colored-header header-bg-gray">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#email_block_<?php echo $email['userId'] ?>">
                                                <span class="glyphicon glyphicon-eye-open js-parent-gly"></span>
                                                <span>
                                                    <?php 
                                                        $email_count = count($email['emails']);
                                                        if ($email_count > 1) {
                                                            echo '( '. $email_count . ' Messages )';
                                                        } else {
                                                            echo '( '. $email_count . ' Message )';
                                                        }
                                                    ?>
                                                    &nbsp;
                                                </span>
                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/1').'/'.$email['incidentId'].'/'.$email['userId'].'/'.$email['employeeId']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/2').'/'.$email['incidentId'].'/'.$email['userId'].'/'.$email['employeeId']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                <?php
                                                    echo $email['userName'];

                                                    $result_one = is_manager_have_new_email($current_user, $id);
                                                ?>
                                                <?php if ($result_one > 0) { ?>
                                                    <img src="<?php echo base_url('assets/images/new_msg.gif'); ?>" id="current_user_notification">
                                                <?php } ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="email_block_<?php echo $email['userId']; ?>" class="panel-collapse collapse js-parent-coll">
                                        <div class="panel-body">
                                            <!-- System Email -->
                                            <?php if (!empty($email['emails'])) { ?>
                                                <?php foreach ($email['emails'] as $key => $incident_email) { ?>
                                                    <div class="accordion-colored-header">
                                                        <div class="panel-group" id="accordion">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class="panel-title">
                                                                        <?php
                                                                        
                                                                            $email_type = 'Sent';
                                                                            $read_function = '';
                                                                            
                                                                            if ($incident_email['is_read'] == 0) {
                                                                                $read_function = 'onclick="mark_read('.$incident_email['sid'].')"';
                                                                            }
                                                                        ?>
                                                                        <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#email_<?php echo $key.'_'.$email['userId']; ?>">
                                                                            <span class="glyphicon glyphicon-eye-open  js-main-gly" <?php echo $read_function; ?>></span>
                                                                            <?php echo $incident_email['subject'].' ( '.my_date_format($incident_email['send_date']).' ) ( '.$email_type.' )'; ?>
                                                                            <?php if ($incident_email['is_read'] == 0 && $email_type == 'Received') { ?>
                                                                                <img src="<?php echo base_url() ?>assets/images/new_msg.gif" id="email_read_<?php echo $incident_email['sid']; ?>">
                                                                            <?php } ?>
                                                                        </a>
                                                                        
                                                                    </h4>
                                                                </div>
                                                                <div id="email_<?php echo $key.'_'.$email['userId']; ?>" class="panel-collapse collapse js-main-coll">
                                                                    <div class="panel-body">
                                                                        <div class="form-wrp">
                                                                            <table class="table table-bordered table-hover table-stripped">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <b>Action</b>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if ($incident_email['sender_sid'] == $current_user || $incident_email['sender_sid'] == 0) {  ?>
                                                                                                
                                                                                                <?php if ($receiver_user !=  $incident_email['manual_email']) { ?>
                                                                                                    <?php
                                                                                                        $receiver_sid = $incident_email['receiver_sid'];
                                                                                                        $receiver_email = $incident_email['manual_email'];
                                                                                                        $receiver_subject = $incident_email["subject"];
                                                                                                    ?>
                                                                                                    <a href="javascript:;" class="pull-right print-incident modify-comment-btn" data-title="<?php echo 'resend'; ?>" data-type="manual" data-sid="<?php echo $receiver_sid; ?>" data-email="<?php echo $receiver_email; ?>" data-subject="<?php echo $receiver_subject; ?>" onclick="send_email(this);">
                                                                                                        <i class="fa fa-retweet"></i> 
                                                                                                        Resend Email
                                                                                                    </a>
                                                                                                <?php } else { ?>

                                                                                                    <?php if ($incident_email['receiver_sid'] != 0) {  ?>
                                                                                                        <a href="javascript:;" class="pull-right print-incident modify-comment-btn" data-title="<?php echo 'reply'; ?>" data-type="system" data-sid="<?php echo $incident_email['receiver_sid']; ?>" data-subject="<?php echo $incident_email["subject"]; ?>" onclick="send_email(this);">
                                                                                                            <i class="fa fa-reply"></i> 
                                                                                                            Reply Email
                                                                                                        </a>
                                                                                                    <?php } else { ?>
                                                                                                        <a href="javascript:;" class="pull-right print-incident modify-comment-btn" data-title="<?php echo 'resend'; ?>" data-type="system" data-sid="<?php echo $incident_email['receiver_sid']; ?>" data-subject="<?php echo $incident_email["subject"]; ?>" onclick="send_email(this);">
                                                                                                            <i class="fa fa-retweet"></i>
                                                                                                            Resend Email
                                                                                                        </a>
                                                                                                    <?php } ?> 
                                                                                                <?php } ?>    
                                                                                            <?php } else { ?>
                                                                                                <?php
                                                                                                    $sender_sid = $incident_email['sender_sid'];
                                                                                                    $sender_email = "";
                                                                                                    $sender_subject = $incident_email["subject"];
                                                                                                    if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                        $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                        $sender_email = get_witness_email_by_id($witness_id);
                                                                                                    } else {
                                                                                                        $sender_info = db_get_employee_profile($sender_sid);
                                                                                                        $sender_email = $sender_info[0]['email'];
                                                                                                    }
                                                                                                ?>
                                                                                                <a href="javascript:;" class="pull-right print-incident modify-comment-btn" data-title="<?php echo 'reply'; ?>" data-type="system" data-sid="<?php echo $sender_sid; ?>" data-email="<?php echo $sender_email; ?>" data-subject="<?php echo $sender_subject; ?>" onclick="send_email(this);">
                                                                                                    <i class="fa fa-reply"></i> 
                                                                                                    Reply Email
                                                                                                </a>
                                                                                            <?php } ?>    
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><b>Message To</b></td>
                                                                                        <td>
                                                                                            <?php
                                                                                                $receiver_sid = $incident_email['receiver_sid'];
                                                                                                if (!$receiver_sid == 0) {
                                                                                                    if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                        $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                        $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                        echo $receiver_name;
                                                                                                    } else {
                                                                                                        $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                        $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                        echo $receiver_name;
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $incident_email['manual_email'];
                                                                                                }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><b>Message From</b></td>
                                                                                        <td>
                                                                                            <?php
                                                                                                $sender_sid = $incident_email['sender_sid'];
                                                                                                if (!$sender_sid == 0) {
                                                                                                    if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                        $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                        $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                        echo $receiver_name;
                                                                                                    } else {
                                                                                                        $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                        $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                        echo $receiver_name;
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $incident_email['manual_email'];
                                                                                                }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><b>Subject</b></td>
                                                                                        <td>
                                                                                            <?php echo $incident_email['subject']; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td><b>Message</b></td>
                                                                                        <td>
                                                                                            <?php echo $incident_email['message_body']; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php 
                                                                                        $email_sid = $incident_email['sid']; 
                                                                                        $attachments = getComplianceSafetyReportEmailAttachment($email_sid);
                                                                                    ?>
                                                                                    <?php if (!empty($attachments)) { ?>
                                                                                        <tr>
                                                                                            <td><b>Attachments</b></td>
                                                                                            <td>
                                                                                                <div class="row">
                                                                                                    <?php 
                                                                                                        foreach ($attachments as $key => $attach_item) {
                                                                                                            //
                                                                                                            $attach_item_type = 'Media';
                                                                                                            //
                                                                                                            if ($attach_item['file_type'] == 'document' || $attach_item['file_type'] == 'image') {
                                                                                                                $attach_item_type = 'Document';
                                                                                                            }
                                                                                                            //
                                                                                                            $item_sid = $attach_item['sid'];
                                                                                                            $item_title = $attach_item['title'];
                                                                                                            $item_source = strtolower($attach_item['file_type']);
                                                                                                            $item_path = $attach_item['s3_file_value'];
                                                                                                            $item_url = '';

                                                                                                            if ($attach_item_type == 'Media') {
                                                                                                                $item_btn_text  = 'Watch Video';
                                                                                                                $download_url   = base_url('incident_reporting_system/download_incident_media/'.$item_path);

                                                                                                                if ($item_source == 'link') {
                                                                                                                    $item_url = $item_path;
                                                                                                                } else if ($item_source == "video") {
                                                                                                                    $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                } else if ($item_source == "audio") {
                                                                                                                    $item_btn_text = 'Listen Audio';
                                                                                                                    $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                }

                                                                                                            } else {
                                                                                                                $extension = pathinfo($item_path, PATHINFO_EXTENSION);
                                                                                                                $item_extension = strtolower($extension);
                                                                                                                $document_path  = explode(".",$item_path); 
                                                                                                                $document_name  = $document_path[0];
                                                                                                                $print_url = '';
                                                                                                                $download_url   = base_url('incident_reporting_system/download_incident_document/'.$item_path);

                                                                                                                if ($item_extension == 'pdf') {
                                                                                                                    $document_category = 'PDF Document';
                                                                                                                    $item_source      = 'document';
                                                                                                                    $item_url = 'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$item_path.'&embedded=true';
                                                                                                                    $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$document_name.'.pdf';
                                                                                                                } else if (in_array($item_extension, ['doc', 'docx'])) {
                                                                                                                    $document_category = 'Word Document';
                                                                                                                    $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);

                                                                                                                    if ($item_source == 'doc') {
                                                                                                                        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edoc&wdAccPdf=0';
                                                                                                                    } else if ($item_source == 'docx') {
                                                                                                                        $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edocx&wdAccPdf=0';
                                                                                                                    }

                                                                                                                    $item_source      = 'document';
                                                                                                                } else if (in_array($item_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                                                                                    $document_category = 'Image';
                                                                                                                    $item_url = AWS_S3_BUCKET_URL . $item_path;
                                                                                                                    $item_source    = "image";
                                                                                                                    $print_url = base_url('incident_reporting_system/print_image/'.$item_sid);
                                                                                                                }
                                                                                                            }

                                                                                                    ?>
                                                                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                                                                <div class="widget-box">
                                                                                                                    <div class="attachment-box full-width">
                                                                                                                        <h2>
                                                                                                                            <?php echo $attach_item_type; ?>
                                                                                                                        </h2>
                                                                                                                        <div></div>
                                                                                                                        <div class="attach-title">
                                                                                                                            <span>Title : <sub><?php echo $item_title; ?></sub></span>
                                                                                                                        </div>
                                                                                                                        <div class="status-panel">
                                                                                                                            <div class="row">
                                                                                                                                <?php if ($attach_item_type == 'Document') { ?>
                                                                                                                                    <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                                                                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_attach_item(this);" item-category="<?php echo $attach_item_type; ?>" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_source; ?>" item-url="<?php echo $item_url; ?>"><i class="fa fa-eye"></i></a>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                                                                                                        <a target="_blank" href="<?php echo $print_url; ?>" class="btn btn-block btn-info"><i class="fa fa-print"></i></a>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                                                                                                                        <a target="_blank" href="<?php echo $download_url; ?>" class="btn btn-block btn-info"><i class="fa fa-download"></i></a>
                                                                                                                                    </div>
                                                                                                                                <?php } else { ?>
                                                                                                                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                                                                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_attach_item(this);" item-category="<?php echo $attach_item_type; ?>" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_source; ?>" item-url="<?php echo $item_url; ?>"><i class="fa fa-eye"></i></a>
                                                                                                                                    </div>
                                                                                                                                    <?php if ($item_source == 'upload_video' || $item_source == 'upload_audio') { ?>
                                                                                                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                                                                                                            <a target="_blank" href="<?php echo $download_url; ?>" class="btn btn-block btn-info"><i class="fa fa-download"></i></a>
                                                                                                                                        </div>
                                                                                                                                    <?php } ?>
                                                                                                                                <?php } ?>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>   
                                                                                                            </div>
                                                                                                    <?php } ?>        
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>  
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>                
                    <?php } ?>       
                <?php } ?>             
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-parent-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-parent-gly").removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-parent-gly").removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
        });

        $('.js-main-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
        });

        
    });
</script>