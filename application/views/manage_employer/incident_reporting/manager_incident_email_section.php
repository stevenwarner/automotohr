<div class="table-responsive table-outer">
    <div class="panel panel-blue">
        <div class="panel-heading incident-panal-heading">
            <b>Related Emails</b>
            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all_emails/1').'/'.$id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-print"></i> Print</a>
            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/all_emails/2').'/'.$id; ?>" class="pull-right print-incident modify-comment-btn"><i class="fa fa-download"></i> Download</a>
        </div>
        <div class="panel-body">
            <div class="email">
                <?php 
                    foreach ($incident_all_emails as $incident_all_email) {
                        $manager_type           = $incident_all_email['manager_type'];
                        $incident_emails        = $incident_all_email['incident_emails'];
                        $incident_manual_emails = $incident_all_email['incident_manual_emails'];
                        if ((!empty($incident_emails) || !empty($incident_manual_emails)) && $manager_type == 'current') { 
                ?>
                            <div class="accordion-colored-header header-bg-gray">
                                <div class="panel-group">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" href="#current_manager">
                                                    <span class="glyphicon glyphicon-plus js-parent-gly"></span>
                                                    <?php
                                                        echo $incident_all_email['manager_name'];
                                                        $result_one = is_manager_have_new_email($current_user, $id);
                                                    ?>
                                                    <?php if ($result_one > 0) { ?>
                                                        <img src="<?php echo base_url('assets/images/new_msg.gif'); ?>" id="current_user_notification">
                                                    <?php } ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="current_manager" class="panel-collapse collapse js-parent-coll">
                                            <div class="panel-body">
                                                <!-- System Email -->
                                                <?php if (!empty($incident_emails)) { ?>
                                                    <?php foreach ($incident_emails as $key => $incident_email) { ?>
                                                        <?php
                                                            $mystring = $incident_email['name'];
                                                            $first = strtok($mystring, '(');

                                                            $colspan_id = str_replace(' ', '_', $first).'_current_manager';
                                                        ?>
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordion">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                                               href="#<?php echo $colspan_id; ?>">
                                                                                <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                                                <span>
                                                                                    <?php 
                                                                                        $email_count = count($incident_email['emails']);
                                                                                        if ($email_count > 1) {
                                                                                            echo '( '. $email_count . ' Messages )';
                                                                                        } else {
                                                                                            echo '( '. $email_count . ' Message )';
                                                                                        }
                                                                                    ?>
                                                                                    &nbsp;
                                                                                </span>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/1').'/'.$incident_email['incident_id'].'/'.$incident_email['user_one'].'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/2').'/'.$incident_email['incident_id'].'/'.$incident_email['user_one'].'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                <?php
                                                                                    echo $incident_email['name'];
                                                                                    $email_sender_sid = $incident_email['sender_sid'];
                                                                                    $result_two = is_user_have_unread_message($current_user, $email_sender_sid, $id);
                                                                                ?>

                                                                                <?php if ($result_two > 0) { ?>
                                                                                    <img src="<?php echo base_url('assets/images/new_msg.gif'); ?>" id="email_notification_<?php echo $email_sender_sid; ?>">
                                                                                <?php } ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-child-coll">
                                                                        <div class="panel-body">
                                                                            <?php if (!empty($incident_email['emails'])) { ?>
                                                                                <?php foreach ($incident_email['emails'] as $key => $email) { ?>
                                                                                    <div class="accordion-colored-header header-bg-gray">
                                                                                        <div class="panel-group" id="onboarding-configuration-accordions">
                                                                                            <div class="panel panel-default parent_div">
                                                                                                <div class="panel-heading">
                                                                                                    <h4 class="panel-title">
                                                                                                        <?php
                                                                                                            $email_type = '';
                                                                                                            $read_function = '';
                                                                                                            if ($email['receiver_sid'] == $current_user) {
                                                                                                                $email_type = 'Received';
                                                                                                                if ($email['is_read'] == 0) {
                                                                                                                    $read_function = 'onclick="mark_read('.$email['sid'].')"';
                                                                                                                }

                                                                                                            } else {
                                                                                                                $email_type = 'Sent';
                                                                                                            }
                                                                                                        ?>
                                                                                                        <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                                                           href="#<?php echo $colspan_id.'_'.$key; ?>" >
                                                                                                            <span class="glyphicon glyphicon-plus js-child-gly" <?php echo $read_function; ?>></span>

                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/1').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/2').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>

                                                                                                            <?php echo $email['subject'].' ( '.my_date_format($email['send_date']).' ) ( '.$email_type.' )'; ?>
                                                                                                            <?php if ($email['is_read'] == 0 && $email_type == 'Received') { ?>
                                                                                                                <img src="<?php echo base_url() ?>assets/images/new_msg.gif" id="email_read_<?php echo $email['sid']; ?>">
                                                                                                            <?php } ?>    
                                                                                                        </a>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div id="<?php echo $colspan_id.'_'.$key; ?>" class="panel-collapse collapse js-child-coll">
                                                                                                    <div class="panel-body">
                                                                                                        <table class="table table-bordered table-hover table-stripped">
                                                                                                            <tbody>
                                                                                                                <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <b>Action</b>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            <?php if ($email['receiver_sid'] == $current_user) { ?>
                                                                                                                                <?php
                                                                                                                                    $sender_sid = $email['sender_sid'];
                                                                                                                                    $sender_email = "";
                                                                                                                                    $sender_subject = $email["subject"];
                                                                                                                                    if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                                                        $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                                                        $sender_email = get_witness_email_by_id($witness_id);
                                                                                                                                    } else {
                                                                                                                                        $sender_info = db_get_employee_profile($sender_sid);
                                                                                                                                        $sender_email = $sender_info[0]['email'];
                                                                                                                                    }
                                                                                                                                ?>
                                                                                                                                <a href="javascript:;" class="btn-blockpull-right print-incident modify-comment-btn" data-title="<?php echo 'reply'; ?>" data-type="system" data-sid="<?php echo $sender_sid; ?>" data-email="<?php echo $sender_email; ?>" data-subject="<?php echo $sender_subject; ?>" onclick="send_email(this);">
                                                                                                                                    <i class="fa fa-reply"></i> 
                                                                                                                                    Reply Email
                                                                                                                                </a>
                                                                                                                            <?php } else { ?>
                                                                                                                                <?php
                                                                                                                                    $receiver_sid = $email['receiver_sid'];;
                                                                                                                                    $receiver_email = "";
                                                                                                                                    $receiver_subject = $email["subject"];
                                                                                                                                    if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                                                        $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                                                        $receiver_email = get_witness_email_by_id($witness_id);
                                                                                                                                    } else {
                                                                                                                                        $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                                        $receiver_email = $receiver_info[0]['email'];
                                                                                                                                    }
                                                                                                                                ?>
                                                                                                                                <a href="javascript:;" class="btn-blockpull-right print-incident modify-comment-btn" data-title="<?php echo 'resend'; ?>" data-type="system" data-sid="<?php echo $receiver_sid; ?>" data-email="<?php echo $receiver_email; ?>" data-subject="<?php echo $receiver_subject; ?>" onclick="send_email(this);"><i class="fa fa-retweet"></i> Resend Email</a>
                                                                                                                            <?php } ?>    
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                <?php } ?>    
                                                                                                                <tr>
                                                                                                                    <td><b>Message To</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $receiver_sid = $email['receiver_sid'];
                                                                                                                            if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                                                $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                                echo $receiver_name;
                                                                                                                            } else {
                                                                                                                                if ($assigned_incidents[0]['report_type'] == 'anonymous' && $receiver_sid != $current_user) {
                                                                                                                                    echo 'Anonymous';
                                                                                                                                } else {
                                                                                                                                    $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                                    echo $receiver_name;
                                                                                                                                }
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message From</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $sender_sid = $email['sender_sid'];
                                                                                                                            if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                                                $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                                echo $receiver_name;
                                                                                                                            } else {
                                                                                                                                if ($assigned_incidents[0]['report_type'] == 'anonymous' && $sender_sid != $current_user) {
                                                                                                                                    echo 'Anonymous';
                                                                                                                                } else {
                                                                                                                                    $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                                    echo $receiver_name;
                                                                                                                                }
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['subject']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message</b></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['message_body']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <?php 
                                                                                                                    $email_sid = $email['sid']; 
                                                                                                                    $attachments =get_email_attachment($id, $email_sid) ;
                                                                                                                ?>
                                                                                                                <?php if (!empty($attachments)) { ?>
                                                                                                                    <tr>
                                                                                                                        <td><b>Attachments</b></td>
                                                                                                                        <td>
                                                                                                                            <div class="row">
                                                                                                                                <?php 
                                                                                                                                    foreach ($attachments as $key => $attach_item) {
                                                                                                                                        $attach_item_type = $attach_item['attachment_type'];
                                                                                                                                        $item_sid = $attach_item['sid'];
                                                                                                                                        $item_title = $attach_item['item_title'];
                                                                                                                                        $item_source = $attach_item['item_type'];
                                                                                                                                        $item_path = $attach_item['item_path'];
                                                                                                                                        $item_url = '';

                                                                                                                                        if ($attach_item_type == 'Media') {
                                                                                                                                            $item_btn_text  = 'Watch Video';
                                                                                                                                            $download_url   = base_url('incident_reporting_system/download_incident_media/'.$item_path);

                                                                                                                                            if ($item_source == 'youtube') {
                                                                                                                                                $item_url = $item_path;
                                                                                                                                            } else if ($item_source == 'vimeo') {
                                                                                                                                                $item_url = $item_path;
                                                                                                                                            } else if ($item_source == "upload_video") {
                                                                                                                                                $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                                            } else if ($item_source == "upload_audio") {
                                                                                                                                                $item_btn_text = 'Listen Audio';
                                                                                                                                                $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                                            }

                                                                                                                                        } else {
                                                                                                                                            
                                                                                                                                            $document_path  = explode(".",$item_path); 
                                                                                                                                            $document_name  = $document_path[0];
                                                                                                                                            $print_url = '';
                                                                                                                                            $download_url   = base_url('incident_reporting_system/download_incident_document/'.$item_path);

                                                                                                                                            if ($item_source == 'pdf') {
                                                                                                                                                $document_category = 'PDF Document';
                                                                                                                                                $item_source      = 'document';
                                                                                                                                                $item_url = 'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$item_path.'&embedded=true';
                                                                                                                                                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$document_name.'.pdf';
                                                                                                                                            } else if (in_array($item_source, ['doc', 'docx'])) {
                                                                                                                                                $document_category = 'Word Document';
                                                                                                                                                $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);

                                                                                                                                                if ($item_source == 'doc') {
                                                                                                                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edoc&wdAccPdf=0';
                                                                                                                                                } else if ($item_source == 'docx') {
                                                                                                                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edocx&wdAccPdf=0';
                                                                                                                                                }

                                                                                                                                                $item_source      = 'document';
                                                                                                                                            } else if (in_array(strtolower($item_source), ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
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
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>  
                                                  
                                                <!-- Manual Email -->
                                                <?php if (!empty($incident_manual_emails)) { ?>
                                                    <?php foreach ($incident_manual_emails as $key => $incident_email) { ?>
                                                        <?php
                                                            $mystring = $incident_email['user_one_email'];
                                                            $first = strtok($mystring, '@');

                                                            $colspan_id = str_replace(' ', '_', $first).'_current_manager';
                                                        ?>
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordion">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                                               href="#<?php echo $colspan_id; ?>">
                                                                                <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                                                <span>
                                                                                    <?php 
                                                                                        $email_count = count($incident_email['emails']);
                                                                                        if ($email_count > 1) {
                                                                                            echo '( '. $email_count . ' Messages )';
                                                                                        } else {
                                                                                            echo '( '. $email_count . ' Message )';
                                                                                        }
                                                                                    ?>
                                                                                    &nbsp;
                                                                                </span>
                                                                                <?php 
                                                                                    if ($incident_email['user_one'] == 0) {
                                                                                        $user_one_sid = $incident_email['user_one_email'];
                                                                                    } else {
                                                                                        $user_one_sid = $incident_email['user_one'];
                                                                                    }
                                                                                ?>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/1').'/'.$incident_email['incident_id'].'/'.$user_one_sid.'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/2').'/'.$incident_email['incident_id'].'/'.$user_one_sid.'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                <?php
                                                                                    $sender_user_emial = $incident_email['user_one_email'];
                                                                                    echo $sender_user_emial;
                                                                                ?>
                                                                                <?php
                                                        
                                                                                    
                                                                                    $result_two = is_user_have_unread_message($current_user, $sender_user_emial, $id);
                                                                                    $split_email = explode('@',$sender_user_emial);
                                                                                    $sender_user_name = $split_email[0];
                                                                                ?>

                                                                                <?php if ($result_two > 0) { ?>
                                                                                    <img src="<?php echo base_url('assets/images/new_msg.gif'); ?>" id="email_notification_<?php echo $sender_user_name; ?>">
                                                                                <?php } ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-main-coll">
                                                                        <div class="panel-body">
                                                                            <?php if (!empty($incident_email['emails'])) { ?>
                                                                                <?php foreach ($incident_email['emails'] as $key => $email) { ?>
                                                                                    <div class="accordion-colored-header header-bg-gray">
                                                                                        <div class="panel-group" id="onboarding-configuration-accordions">
                                                                                            <div class="panel panel-default parent_div">
                                                                                                <div class="panel-heading">
                                                                                                    <h4 class="panel-title">
                                                                                                        <?php
                                                                                                            $email_type = '';
                                                                                                            $read_function = '';
                                                                                                            $email_sid = $email["sid"];
                                                                                                            if ($email['receiver_sid'] != 0) {
                                                                                                                $email_type = 'Received';
                                                                                                                if ($email['is_read'] == 0) {
                                                                                                                    $read_function = 'onclick="mark_read('.$email['sid'].')"';
                                                                                                                }
                                                                                                            } else {
                                                                                                                $email_type = 'Sent';
                                                                                                            }
                                                                                                        ?>
                                                                                                        <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                                                           href="#<?php echo $colspan_id.'_'.$key; ?>" ><span class="glyphicon glyphicon-plus js-child-gly" <?php echo $read_function; ?>></span>
                                                                                                           <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/1').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/2').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                                            <?php echo $email['subject'].' ( '.my_date_format($email['send_date']).' ) ( '.$email_type.' )'; ?>
                                                                                                            <?php if ($email['is_read'] == 0 && $email_type == 'Received') { ?>
                                                                                                                <img src="<?php echo base_url() ?>assets/images/new_msg.gif" id="email_read_<?php echo $email['sid']; ?>">
                                                                                                            <?php } ?>
                                                                                                        </a>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div id="<?php echo $colspan_id.'_'.$key; ?>" class="panel-collapse collapse js-child-coll">
                                                                                                    <div class="panel-body">
                                                                                                        <table class="table table-bordered table-hover table-stripped">
                                                                                                            <tbody>
                                                                                                                <?php if ($assigned_incidents[0]['status'] != 'Closed') { ?>
                                                                                                                    <tr>
                                                                                                                        <td>
                                                                                                                            <b>Action</b>
                                                                                                                        </td>
                                                                                                                        <td>
                                                                                                                            <?php if ($email['receiver_sid'] != 0) { ?>
                                                                                                                                <a href="javascript:;" class="btn-blockpull-right print-incident modify-comment-btn" data-title="<?php echo 'reply'; ?>" data-type="manual" data-sid="<?php echo $email['manual_email']; ?>" data-subject="<?php echo $email["subject"]; ?>" onclick="send_email(this);">
                                                                                                                                    <i class="fa fa-reply"></i> 
                                                                                                                                    Reply Email
                                                                                                                                </a>
                                                                                                                            <?php } else { ?>
                                                                                                                                <a href="javascript:;" class="btn-blockpull-right print-incident modify-comment-btn" data-title="<?php echo 'resend'; ?>" data-type="manual" data-sid="<?php echo $email['manual_email']; ?>" data-subject="<?php echo $email["subject"]; ?>" onclick="send_email(this);"><i class="fa fa-retweet"></i> Resend Email</a>
                                                                                                                            <?php } ?>    
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                <?php } ?>
                                                                                                                <tr>
                                                                                                                    <td><b>Message To</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $receiver_sid = $email['receiver_sid'];
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
                                                                                                                                echo $email['manual_email'];
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message From</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $sender_sid = $email['sender_sid'];
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
                                                                                                                                echo $email['manual_email'];
                                                                                                                            }
                                                                                                                        ?>

                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['subject']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message</b></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['message_body']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <?php 
                                                                                                                    $email_sid = $email['sid']; 
                                                                                                                    $attachments =get_email_attachment($id, $email_sid) ;
                                                                                                                ?>
                                                                                                                <?php if (!empty($attachments)) { ?>
                                                                                                                    <tr>
                                                                                                                        <td><b>Attachments</b></td>
                                                                                                                        <td>
                                                                                                                            <div class="row">
                                                                                                                                <?php 
                                                                                                                                    foreach ($attachments as $key => $attach_item) {
                                                                                                                          
                                                                                                                                        $attach_item_type = $attach_item['attachment_type'];
                                                                                                                                        $item_sid = $attach_item['sid'];
                                                                                                                                        $item_title = $attach_item['item_title'];
                                                                                                                                        $item_source = $attach_item['item_type'];
                                                                                                                                        $item_path = $attach_item['item_path'];
                                                                                                                                        $item_url = '';

                                                                                                                                        if ($attach_item_type == 'Media') {
                                                                                                                                            $item_btn_text  = 'Watch Video';
                                                                                                                                            $download_url   = base_url('incident_reporting_system/download_incident_media/'.$item_path);

                                                                                                                                            if ($item_source == 'youtube') {
                                                                                                                                                $item_url = $item_path;
                                                                                                                                            } else if ($item_source == 'vimeo') {
                                                                                                                                                $item_url = $item_path;
                                                                                                                                            } else if ($item_source == "upload_video") {
                                                                                                                                                $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                                            } else if ($item_source == "upload_audio") {
                                                                                                                                                $item_btn_text = 'Listen Audio';
                                                                                                                                                $item_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item_path;
                                                                                                                                            }

                                                                                                                                        } else {
                                                                                                                                            
                                                                                                                                            $document_path  = explode(".",$item_path); 
                                                                                                                                            $document_name  = $document_path[0];
                                                                                                                                            $print_url = '';
                                                                                                                                            $download_url   = base_url('incident_reporting_system/download_incident_document/'.$item_path);

                                                                                                                                            if ($item_source == 'pdf') {
                                                                                                                                                $document_category = 'PDF Document';
                                                                                                                                                $item_source      = 'document';
                                                                                                                                                $item_url = 'https://docs.google.com/gview?url='.AWS_S3_BUCKET_URL.$item_path.'&embedded=true';
                                                                                                                                                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$document_name.'.pdf';
                                                                                                                                            } else if (in_array($item_source, ['doc', 'docx'])) {
                                                                                                                                                $document_category = 'Word Document';
                                                                                                                                                $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);

                                                                                                                                                if ($item_source == 'doc') {
                                                                                                                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edoc&wdAccPdf=0';
                                                                                                                                                } else if ($item_source == 'docx') {
                                                                                                                                                    $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edocx&wdAccPdf=0';
                                                                                                                                                }

                                                                                                                                                $item_source      = 'document';
                                                                                                                                            } else if (in_array(strtolower($item_source), ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
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
                            </div>                
                <?php 
                        } 
                    }    
                ?>

                <?php 
                    foreach ($incident_all_emails as $p_key => $incident_all_email) {
                        $manager_sid            = $incident_all_email['manager_sid'];
                        $manager_name           = $incident_all_email['manager_name'];
                        $manager_type           = $incident_all_email['manager_type'];
                        $incident_emails        = $incident_all_email['incident_emails'];
                        $incident_manual_emails = $incident_all_email['incident_manual_emails'];
                        if ((!empty($incident_emails) || !empty($incident_manual_emails)) && $manager_type != 'current') { 
                            $parent_key = 'parent_key_'.$p_key;
                ?>
                            <div class="accordion-colored-header header-bg-gray">
                                <div class="panel-group">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" href="#<?php echo $parent_key; ?>">
                                                    <span class="glyphicon glyphicon-plus js-parent-gly"></span>
                                                    <?php
                                                        echo $incident_all_email['manager_name'];
                                                    ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="<?php echo $parent_key; ?>" class="panel-collapse collapse js-parent-coll">
                                            <div class="panel-body">
                                                <!-- System Email -->
                                                <?php if (!empty($incident_emails)) { ?>
                                                    <?php foreach ($incident_emails as $key => $incident_email) { ?>
                                                        <?php
                                                            $mystring = $incident_email['name'];
                                                            $first = strtok($mystring, '(');

                                                            $colspan_id = str_replace(' ', '_', $first).$p_key;
                                                        ?>
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordion">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                                               href="#<?php echo $colspan_id; ?>">
                                                                                <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                                                <span>
                                                                                    <?php 
                                                                                        $email_count = count($incident_email['emails']);
                                                                                        if ($email_count > 1) {
                                                                                            echo '( '. $email_count . ' Messages )';
                                                                                        } else {
                                                                                            echo '( '. $email_count . ' Message )';
                                                                                        }
                                                                                    ?>
                                                                                    &nbsp;
                                                                                </span>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/1').'/'.$incident_email['incident_id'].'/'.$incident_email['user_one'].'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/2').'/'.$incident_email['incident_id'].'/'.$incident_email['user_one'].'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                <?php
                                                                                    echo $incident_email['name'];
                                                                                ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-child-coll">
                                                                        <div class="panel-body">
                                                                            <?php if (!empty($incident_email['emails'])) { ?>
                                                                                <?php foreach ($incident_email['emails'] as $key => $email) { ?>
                                                                                    <div class="accordion-colored-header header-bg-gray">
                                                                                        <div class="panel-group" id="onboarding-configuration-accordions">
                                                                                            <div class="panel panel-default parent_div">
                                                                                                <div class="panel-heading">
                                                                                                    <h4 class="panel-title">
                                                                                                        <?php
                                                                                                            $email_type = '';

                                                                                                            if ($email['receiver_sid'] == $manager_sid) {
                                                                                                                $email_type = 'Received';
                                                                                                            } else {
                                                                                                                $email_type = 'Sent';
                                                                                                            }
                                                                                                        ?>
                                                                                                        <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                                                           href="#<?php echo $colspan_id.'_'.$key; ?>" >
                                                                                                            <span class="glyphicon glyphicon-plus js-child-gly"></span>

                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/1').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/2').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>

                                                                                                            <?php echo $email['subject'].' ( '.my_date_format($email['send_date']).' ) ( '.$email_type.' )'; ?>   
                                                                                                        </a>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div id="<?php echo $colspan_id.'_'.$key; ?>" class="panel-collapse collapse js-child-coll">
                                                                                                    <div class="panel-body">
                                                                                                        <table class="table table-bordered table-hover table-stripped">
                                                                                                            <tbody>   
                                                                                                                <tr>
                                                                                                                    <td><b>Message To</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $receiver_sid = $email['receiver_sid'];
                                                                                                                            if (str_replace('_wid', '', $receiver_sid) != $receiver_sid) {
                                                                                                                                $witness_id = str_replace('_wid', '', $receiver_sid);
                                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                                echo $receiver_name;
                                                                                                                            } else {
                                                                                                                                if ($assigned_incidents[0]['report_type'] == 'anonymous' && $receiver_sid != $current_user) {
                                                                                                                                    echo 'Anonymous';
                                                                                                                                } else {
                                                                                                                                    $receiver_info = db_get_employee_profile($receiver_sid);
                                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                                    echo $receiver_name;
                                                                                                                                }
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message From</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $sender_sid = $email['sender_sid'];
                                                                                                                            if (str_replace('_wid', '', $sender_sid) != $sender_sid) {
                                                                                                                                $witness_id = str_replace('_wid', '', $sender_sid);
                                                                                                                                $receiver_name = get_witness_name_by_id($witness_id);
                                                                                                                                echo $receiver_name;
                                                                                                                            } else {
                                                                                                                                if ($assigned_incidents[0]['report_type'] == 'anonymous' && $sender_sid != $current_user) {
                                                                                                                                    echo 'Anonymous';
                                                                                                                                } else {
                                                                                                                                    $receiver_info = db_get_employee_profile($sender_sid);
                                                                                                                                    $receiver_name = $receiver_info[0]['first_name'].' '.$receiver_info[0]['last_name'];
                                                                                                                                    echo $receiver_name;
                                                                                                                                }
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['subject']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message</b></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['message_body']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
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
                                                  
                                                <!-- Manual Email -->
                                                <?php if (!empty($incident_manual_emails)) { ?>
                                                    <?php foreach ($incident_manual_emails as $key => $incident_email) { ?>
                                                        <?php
                                                            $mystring = $incident_email['user_one_email'];
                                                            $first = strtok($mystring, '@');

                                                            $colspan_id = str_replace(' ', '_', $first);
                                                        ?>
                                                        <div class="accordion-colored-header header-bg-gray">
                                                            <div class="panel-group" id="onboarding-configuration-accordion">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                                               href="#<?php echo $colspan_id; ?>">
                                                                                <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                                                <span>
                                                                                    <?php 
                                                                                        $email_count = count($incident_email['emails']);
                                                                                        if ($email_count > 1) {
                                                                                            echo '( '. $email_count . ' Messages )';
                                                                                        } else {
                                                                                            echo '( '. $email_count . ' Message )';
                                                                                        }
                                                                                    ?>
                                                                                    &nbsp;
                                                                                </span>
                                                                                <?php 
                                                                                    if ($incident_email['user_one'] == 0) {
                                                                                        $user_one_sid = $incident_email['user_one_email'];
                                                                                    } else {
                                                                                        $user_one_sid = $incident_email['user_one'];
                                                                                    }
                                                                                ?>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/1').'/'.$incident_email['incident_id'].'/'.$user_one_sid.'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/emails/2').'/'.$incident_email['incident_id'].'/'.$user_one_sid.'/'.$incident_email['user_two']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                <?php
                                                                                    echo $incident_email['user_one_email'];
                                                                                ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-main-coll">
                                                                        <div class="panel-body">
                                                                            <?php if (!empty($incident_email['emails'])) { ?>
                                                                                <?php foreach ($incident_email['emails'] as $key => $email) { ?>
                                                                                    <div class="accordion-colored-header header-bg-gray">
                                                                                        <div class="panel-group" id="onboarding-configuration-accordions">
                                                                                            <div class="panel panel-default parent_div">
                                                                                                <div class="panel-heading">
                                                                                                    <h4 class="panel-title">
                                                                                                        <?php
                                                                                                            $email_type = '';
                                                                                                            $email_sid = $email["sid"];
                                                                                                            if ($email['receiver_sid'] != 0) {
                                                                                                                $email_type = 'Received';
                                                                                                            } else {
                                                                                                                $email_type = 'Sent';
                                                                                                            }
                                                                                                        ?>
                                                                                                        <a data-toggle="collapse" data-parent="#onboarding-configuration-accordions"
                                                                                                           href="#<?php echo $colspan_id.'_'.$key; ?>" ><span class="glyphicon glyphicon-plus js-child-gly"></span>
                                                                                                           <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/1').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-print"></i> Print</a>
                                                                                                            <a target="_blank" href="<?php echo base_url('incident_reporting_system/print_and_download/manager/0/single_email/2').'/'.$email['incident_reporting_id'].'/'.$email['sid']; ?>" class="pull-right print-incident"><i class="fa fa-download"></i> Download</a>
                                                                                                            <?php echo $email['subject'].' ( '.my_date_format($email['send_date']).' ) ( '.$email_type.' )'; ?>
                                                                                                        </a>
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div id="<?php echo $colspan_id.'_'.$key; ?>" class="panel-collapse collapse js-child-coll">
                                                                                                    <div class="panel-body">
                                                                                                        <table class="table table-bordered table-hover table-stripped">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td><b>Message To</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $receiver_sid = $email['receiver_sid'];
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
                                                                                                                                echo $email['manual_email'];
                                                                                                                            }
                                                                                                                        ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message From</b></td>
                                                                                                                    <td>
                                                                                                                        <?php
                                                                                                                            $sender_sid = $email['sender_sid'];
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
                                                                                                                                echo $email['manual_email'];
                                                                                                                            }
                                                                                                                        ?>

                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['subject']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td><b>Message</b></td>
                                                                                                                    <td>
                                                                                                                        <?php echo $email['message_body']; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
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
                            </div>                
                <?php 
                        } 
                    }    
                ?>                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-parent-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-parent-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-parent-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });
</script>