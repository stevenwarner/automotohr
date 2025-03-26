<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                 <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header full-width">
                    <h1 class="section-ttile"><?php echo $title; ?></h1>
                    <strong> Information:</strong> If you are unable to view the Email, kindly reload the page.
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="table-responsive table-outer">
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <b>Related Emails</b>
                        </div>
                        <div class="panel-body">
                            <?php if (!empty($emails)) { ?>
                                <?php foreach($emails as $key => $email) { ?>
                                    <div class="accordion-colored-header">
                                        <div class="panel-group" id="accordion">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <?php 
                                                            //
                                                            $email_type = '';
                                                            $sender_type = '';
                                                            $readEmail = '';
                                                            $buttonText = 'Reply Email';
                                                            $modalTitle = 'reply';
                                                            $receiver_email = '';
                                                            $receiver_id = '';
                                                            $sender_id = '';
                                                            //
                                                            if ($user_type == 'manual') {
                                                                $sender_type = 'system';
                                                                //
                                                                $sender_id = $email['manual_email'];
                                                                //
                                                                if ($email['sender_sid'] == 0) {
                                                                    //
                                                                    $email_type = 'Sent';
                                                                    $buttonText = 'Resend Email';
                                                                    $modalTitle = 'resend';
                                                                    //
                                                                    $receiver_info = db_get_employee_profile($email['receiver_sid']);
                                                                    $receiver_email = $receiver_info[0]['email'];
                                                                    $receiver_id = $email['receiver_sid'];
                                                                    
                                                                } else if ($email['receiver_sid'] == 0) {
                                                                    $email_type = 'Received';
                                                                    //
                                                                    if ($email['is_read'] == 0) {
                                                                        $readEmail = 'jsMarkAsRead';
                                                                    }
                                                                    //
                                                                    $receiver_info = db_get_employee_profile($email['sender_sid']);
                                                                    $receiver_email = $receiver_info[0]['email'];
                                                                    $receiver_id = $email['sender_sid'];
                                                                }
                                                            } else {
                                                                $sender_id = $current_user;
                                                                //
                                                                if ($email['sender_sid'] != 0 || $email['sender_sid'] == $current_user) {
                                                                    //
                                                                    $email_type = 'Sent';
                                                                    $buttonText = 'Resend Email';
                                                                    $modalTitle = 'resend';
                                                                    //
                                                                    
                                                                    //
                                                                    if ($email['receiver_sid'] == 0) {
                                                                        $receiver_email = $email['manual_email'];
                                                                        $receiver_id = $email['manual_email'];
                                                                        $sender_type = 'manual';
                                                                    } else {
                                                                        $receiver_info = db_get_employee_profile($email['receiver_sid']);
                                                                        $receiver_email = $receiver_info[0]['email'];
                                                                        $receiver_id = $email['receiver_sid'];
                                                                        $sender_type = 'system';
                                                                    }

                                                                } else if ($email['receiver_sid'] != 0 || $email['receiver_sid'] == $current_user) {
                                                                    //
                                                                    $email_type = 'Received';
                                                                    //
                                                                    if ($email['is_read'] == 0) {
                                                                        $readEmail = 'jsMarkAsRead';
                                                                    }
                                                                    //
                                                                    if ($email['sender_sid'] == 0) {
                                                                        $receiver_email = $email['manual_email'];
                                                                        $receiver_id = $email['manual_email'];
                                                                        $sender_type = 'manual';
                                                                    } else {
                                                                        $receiver_info = db_get_employee_profile($email['sender_sid']);
                                                                        $receiver_email = $receiver_info[0]['email'];
                                                                        $receiver_id = $email['sender_sid'];
                                                                        $sender_type = 'system';
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                        <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#email_<?php echo $key; ?>">
                                                            <span class="glyphicon glyphicon-plus js-main-gly <?php echo $readEmail; ?>" data-id="<?php echo $email['sid']; ?>"></span>
                                                            <?php echo $email['subject'].' ( '.my_date_format($email['send_date']).' ) ( '.$email_type.' )'; ?>
                                                            <?php if ($email['is_read'] == 0 && $email_type == 'Received') { ?>
                                                                <img src="<?php echo base_url() ?>assets/images/new_msg.gif" id="email_read_<?php echo $email['sid']; ?>">
                                                            <?php } ?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="email_<?php echo $key; ?>" class="panel-collapse collapse js-main-coll">
                                                    <div class="panel-body">
                                                        <div class="form-wrp">
                                                            <table class="table table-bordered table-hover table-stripped">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <b>Action</b>
                                                                        </td>
                                                                        <td>
                                                                            <a href="javascript:;" class="btn-blockpull-right print-incident modify-comment-btn jsSendEmail" data-title="<?php echo $modalTitle; ?>" data-type="<?php echo $sender_type; ?>" data-sender_id="<?php echo $sender_id; ?>" data-receiver_id="<?php echo $receiver_id; ?>" data-email="<?php echo $receiver_email; ?>" data-subject="<?php echo $email["subject"]; ?>"><i class="fa fa-retweet"></i> <?php echo $buttonText; ?></a>
                                                                        </td>
                                                                    </tr>
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
                                                                        <td><b>Subject</b></td>
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
                                                                        $attachments = getComplianceSafetyReportEmailAttachment($email_sid) ;
                                                                    ?>
                                                                    <?php if (!empty($attachments)) { ?>
                                                                        <tr>
                                                                            <td><b>Attachments</b></td>
                                                                            <td>
                                                                                <div class="row">
                                                                                    <?php 
                                                                                        foreach ($attachments as $key => $attach_item) {
                                                                                            //
                                                                                            if ($item['file_type'] == 'document' || $item['file_type'] == 'image') {
                                                                                                //
                                                                                                if ($item["file_type"] === "image") {
                                                                                                    $link = AWS_S3_BUCKET_URL . $item["s3_file_value"]; 
                                                                                                } elseif ($item["file_type"] === "document") {
                                                                                                    if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $item["s3_file_value"])) {
                                                                                                        $link = 'https://view.officeapps.live.com/op/embed.aspx?src='.AWS_S3_BUCKET_URL . $item["s3_file_value"];
                                                                                                    } else {
                                                                                                        $link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
                                                                                                    }
                                                                                                } 
                                                                                                //
                                                                                            } else {
                                                                                                //
                                                                                                if ($item['file_type'] == 'link') {
                                                                                                    $link = $item['file_value'];
                                                                                                } else {
                                                                                                    $link  = AWS_S3_BUCKET_URL . $item["s3_file_value"];
                                                                                                } 
                                                                                            }
                                                                                            //
                                                                                            $attach_item_type = ucwords($item["file_type"]);
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
                            <?php } else { ?>
                                <div id="print_offer_letter" class="hr-box text-center" style="background: #fff; padding: 20px;">
                                    <h1 class="section-ttile">No Email Found!</h1>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="hr-box">
                    <div class="panel panel-blue">
                        <div class="panel-heading">
                            <strong>Compose Message</strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="dashboard-conetnt-wrp">
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp data-table">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <table class="table table-bordered table-hover table-stripped">
                                                        <tbody> 
                                                            <tr>
                                                                <td><b>Message To</b> <span class="required">*</span></td>
                                                                <td>
                                                                    <input type="hidden" id="jsUserType" value="<?php echo $senderType == 'employee' ? 'system' : 'manual'; ?>">
                                                                    <?php if ($senderType == "employee") { ?>
                                                                        <input type="text" id="email_to" name="receivers[]" value="<?php echo $sender_email; ?>" class="form-control invoice-fields" readonly>
                                                                        <input type="hidden" id="receiverId" value="<?php echo $receiver_user; ?>">
                                                                    <?php } else { ?>
                                                                        <input type="text" name="manual_email" id="manual_address" value="<?php echo $sender_email; ?>" class="form-control invoice-fields">
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Subject</b> <span class="required">*</span></td>
                                                                <td>
                                                                    <input type="text" id="subject" name="subject" value="" class="form-control invoice-fields">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Attachment</b></td>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <a href="javascript:;" class="btn btn-info btn-block show_media_library">Add Library Attachment</a>
                                                                        </div>
                                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                            <a href="javascript:;" class="btn btn-info btn-block show_manual_attachment">Add Manual Attachment</a>
                                                                        </div>
                                                                    </div> 
                                                                    
                                                                    <div class="table-responsive table-outer full-width" style="margin-top: 20px; display: none;" id="email_attachment_list">
                                                                        <div class="table-wrp data-table">
                                                                            <table class="table table-bordered table-hover table-stripped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">Attachment Title</th>
                                                                                        <th class="text-center">Attachment Type</th>
                                                                                        <th class="text-center">Attachment Source</th>
                                                                                        <th class="text-center">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="attachment_listing_data">
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div> 
                                                                    <div style="display: none;" id="email_attachment_files">  
                                                                    </div>  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Message</b> <span class="required">*</span></td>
                                                                <td>
                                                                    <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="email_message"></textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <div class="btn-wrp full-width text-right">
                                                                        <button type="button" class="btn btn-info incident-panal-button" name="submit" value="submit" id="send_normal_email">Send Email</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Section Start -->
<div id="send_email_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close email_pop_up_back_to_compose_email" btn-from="main" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="send_email_pop_up_title"></h4>
            </div>
            <div class="modal-body">
                <div id="pop_up_email_compose_container">
                    <form id="form_send_email" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                        <input type="hidden" id="perform_action" name="perform_action" value="send_email" />
                        <input type="hidden" id="send_email_type" name="send_type" value="" />
                        <input type="hidden" id="send_email_user" name="" value="" />

                        <table class="table table-bordered table-hover table-stripped">
                            <tbody>
                                <tr>
                                    <td><b>Message To</b></td>
                                    <td>
                                        <input type="text" id="send_email_address" value="" class="form-control invoice-fields" readonly="">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Subject</b> <span class="required">*</span></td>
                                    <td>
                                        <input type="text" id="send_email_subject" name="subject" value="" class="form-control invoice-fields">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Attachment</b></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <a href="javascript:;" class="btn btn-info btn-block attachment_pop_up" attachment-type="library">Add Library Attachment</a>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <a href="javascript:;" class="btn btn-info btn-block attachment_pop_up" attachment-type="manual">Add Manual Attachment</a>
                                            </div>
                                        </div> 
                                        
                                        <div class="table-responsive table-outer full-width" style="margin-top: 20px; display: none;" id="pop_up_email_attachment_list">
                                            <div class="table-wrp data-table">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Attachment Title</th>
                                                            <th class="text-center">Attachment Type</th>
                                                            <th class="text-center">Attachment Source</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="pop_up_attachment_listing_data">
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> 
                                        <div style="display: none;" id="pop_up_email_attachment_files">  
                                        </div>  
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Message</b> <span class="required">*</span></td>
                                    <td>
                                        <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="send_email_message"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="btn-wrp full-width text-right">
                                            <button type="button" class="btn btn-black incident-panal-button" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-info incident-panal-button" id="send_pop_up_email" name="submit" value="submit">Send Email</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                
                <div id="pop_up_attachment_library_container" style="display: none;">
                    <div class="table-responsive table-outer" id="show_pop_up_library_item">
                        <div class="text-right" style="margin-top:15px;">
                            <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="library" style="margin-bottom: 20px;">Back To Compose Email</button>
                        </div>

                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($libraryItems)) { ?>
                                        <?php foreach ($libraryItems as $d_key => $item) { ?>
                                            <?php if ($item['file_type'] == 'document' || $item['file_type'] == 'image' ) { ?>
                                                <tr>
                                                    <?php
                                                    $extension = pathinfo($item['s3_file_value'], PATHINFO_EXTENSION);
                                                    $item_url       = '';
                                                    $item_category  = '';
                                                    $item_type      = $item['file_type'];
                                                    $item_extension = strtolower($extension);
                                                    $item_path      = $item['s3_file_value'];
                                                    $item_title     = $item['title'];

                                                    if ($item_extension == 'pdf') {
                                                        $item_category = 'Document';
                                                        $item_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $item_path . '&embedded=true';
                                                    } else if (in_array($item_extension, ['doc', 'docx'])) {
                                                        $item_category = 'Document';
                                                        $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);
                                                    } else if (in_array($item_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                        $item_category = 'Image';
                                                        $item_url = AWS_S3_BUCKET_URL . $item_path;
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $item_title; ?></td>
                                                    <td class="text-center">
                                                        <?php echo $item_category; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $item_url; ?>">View Document</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="select_lib_item" id="doc_key_d_<?php echo $item['sid']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $item['sid']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <?php
                                                    $media_url      = '';
                                                    $media_category = '';
                                                    $media_btn_text = 'Watch Video';
                                                    $media_title    = $item['title'];
                                                    $item_type     = strtolower($item['file_type']);

                                                    if ($item_type == 'link') {
                                                        $media_category = 'Link';
                                                        $media_url = $item['s3_file_value'];
                                                    } else if ($item_type == "video") {
                                                        $media_category = 'Video';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                    } else if ($item_type == "audio") {
                                                        $media_category = 'Audio';
                                                        $media_btn_text = 'Listen Audio';
                                                        $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                    }
                                                    ?>

                                                    <td class="text-center"><?php echo $media_title; ?></td>
                                                    <td class="text-center"><?php echo $media_category; ?></td>
                                                    <td class="text-center">
                                                        <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                            <input class="select_lib_item" id="med_key_m_<?php echo $item['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="m_<?php echo $item['sid']; ?>" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>    
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4">
                                                <h3 class="text-center">
                                                    No Library Item Found
                                                </h3>
                                            </td>
                                        </tr>
                                    <?php } ?>    
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right" style="margin-top:15px;">
                            <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="library">Back To Compose Email</button>
                        </div>
                    </div>
                    <div id="view_pop_up_library_item" style="display:none;"> 
                        <h3 id="pop_up_library_item_title"></h3>
                        <hr>                   
                        <div class="embed-responsive embed-responsive-16by9">
                            <div id="email-pop-up-youtube-container" style="display:none;">
                                <div id="email-pop-up-youtube-iframe-holder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="email-pop-up-vimeo-container" style="display:none;">
                                <div id="email-pop-up-vimeo-iframe-holder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="email-pop-up-video-container" style="display:none;">
                                <div id="email-pop-up-video-player-holder" class="embed-responsive-item">
                                </div>
                            </div>  
                            <div id="email-pop-up-audio-container" style="display:none;">
                                <div id="email-pop-up-audio-player-holder" class="embed-responsive-item">
                                </div>
                            </div>
                            <div id="email-pop-up-document-container" style="display:none;">
                                <div id="email-pop-up-document-iframe-holder" class="embed-responsive-item">
                                </div>
                            </div>
                        </div>                    
                        <div class="text-right" style="margin-top:15px;">
                            <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_library">Back To Library</button>
                        </div>
                    </div>
                </div>
                <div id="pop_up_manual_attachment_container" style="display: none;">
                    <div class="form-group edit_filter autoheight">
                        <label for="attachment_type">Select Attachment Type</label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo YOUTUBE_VIDEO; ?>
                            <input id="default_manual_pop_up" class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="youtube" checked="checked"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo VIMEO_VIDEO; ?>
                            <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="vimeo"/>
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo UPLOAD_VIDEO; ?>
                            <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_video" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            <?php echo UPLOAD_AUDIO; ?>
                            <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_audio" />
                            <div class="control__indicator"></div>
                        </label>
                        <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                            Document
                            <input class="pop_up_attach_item_source" type="radio" name="pop_up_attach_item_source" value="upload_document" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>

                    <div class="row">
                        <div class="field-row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                <div class="form-group autoheight">
                                    <label for="attachment_title">Attachment Title <span class="required">*</span></label>
                                    <input type="text" name="attachment_title" class="form-control" id="pop_up_attachment_item_title">
                                </div>
                            </div>
                        </div>
                    </div>            

                    <div class="row" id="only_video">
                        <div class="field-row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                <div class="form-group autoheight" id="pop_up_attachment_yt_vm_video_input_container">
                                    <label for="video_id">Video Url <span class="required">*</span></label>
                                    <input type="text" name="pop_up_attach_social_video" value="" class="form-control" id="pop_up_attach_social_video" data-rule-required="true">
                                </div>
                                <div class="form-group autoheight" id="pop_up_attachment_upload_video_input_container">
                                    <label>Attach Video <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_pop_up_attach_video"></span>
                                        <input type="file" name="pop_up_attach_video" id="pop_up_attach_video" onchange="pop_up_check_attach_video('pop_up_attach_video')" >
                                        <a href="javascript:;">Choose Video</a>
                                    </div>
                                </div>
                                <div class="form-group autoheight" id="pop_up_attachment_upload_audio_input_container">
                                    <label>Attach Audio <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_pop_up_attach_audio"></span>
                                        <input type="file" name="pop_up_attach_audio" id="pop_up_attach_audio" onchange="pop_up_check_attach_audio('pop_up_attach_audio')" >
                                        <a href="javascript:;">Choose Audio</a>
                                    </div>
                                </div>
                                <div class="form-group autoheight" id="pop_up_attachment_upload_document_input_container">
                                    <label>Attach Document <span class="required">*</span></label>
                                    <div class="upload-file form-control" style="margin-bottom:10px;">
                                        <span class="selected-file" id="name_pop_up_attach_document"></span>
                                        <input type="file" name="pop_up_attach_document" id="pop_up_attach_document" onchange="pop_up_check_attach_document('pop_up_attach_document')" >
                                        <a href="javascript:;">Choose Document</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="field-row">
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-info incident-panal-button email_pop_up_back_to_compose_email" btn-from="manual">Back To Compose Email</button>
                                <button type="button" class="btn btn-info incident-panal-button" id="pop_up_save_attach_item">Save Attachment</button>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Send Email Section End -->

<!-- Attachment Library Section Start -->
<div id="attachment_library_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content full-width">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close back_to_library" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="library_item_title">Attachment Library</h4>
            </div>
            <div class="modal-body full-width">
                <div class="table-responsive table-outer" id="show_library_item">
                    <div class="table-wrp data-table">
                        <table class="table table-bordered table-hover table-stripped">
                            <thead>
                                <tr>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($libraryItems)) { ?>
                                    <?php foreach ($libraryItems as $d_key => $item) { ?>
                                        <?php if ($item['file_type'] == 'document' || $item['file_type'] == 'image' ) { ?>
                                            <tr>
                                                <?php
                                                $extension = pathinfo($item['s3_file_value'], PATHINFO_EXTENSION);
                                                $item_url       = '';
                                                $item_category  = '';
                                                $item_type      = $item['file_type'];
                                                $item_extension = strtolower($extension);
                                                $item_path      = $item['s3_file_value'];
                                                $item_title     = $item['title'];

                                                if ($item_extension == 'pdf') {
                                                    $item_category = 'Document';
                                                    $item_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $item_path . '&embedded=true';
                                                } else if (in_array($item_extension, ['doc', 'docx'])) {
                                                    $item_category = 'Document';
                                                    $item_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $item_path);
                                                } else if (in_array($item_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
                                                    $item_category = 'Image';
                                                    $item_url = AWS_S3_BUCKET_URL . $item_path;
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $item_title; ?></td>
                                                <td class="text-center">
                                                    <?php echo $item_category; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $item_url; ?>">View Document</a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="doc_key_d_<?php echo $item['sid']; ?>" type="checkbox" item-category="Document" item-title="<?php echo $item_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="d_<?php echo $item['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <?php
                                                $media_url      = '';
                                                $media_category = '';
                                                $media_btn_text = 'Watch Video';
                                                $media_title    = $item['title'];
                                                $item_type     = strtolower($item['file_type']);

                                                if ($item_type == 'link') {
                                                    $media_category = 'Link';
                                                    $media_url = $item['s3_file_value'];
                                                } else if ($item_type == "video") {
                                                    $media_category = 'Video';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                } else if ($item_type == "audio") {
                                                    $media_category = 'Audio';
                                                    $media_btn_text = 'Listen Audio';
                                                    $media_url = base_url() . 'assets/uploaded_videos/incident_videos/' . $item['s3_file_value'];
                                                }
                                                ?>

                                                <td class="text-center"><?php echo $media_title; ?></td>
                                                <td class="text-center"><?php echo $media_category; ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:;" class="btn btn-block btn-info" onclick="view_library_item(this);" item-category="media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-url="<?php echo $media_url; ?>"><?php echo $media_btn_text; ?></a>
                                                </td>
                                                <td class="text-center">
                                                    <label class="control control--checkbox" style="margin-left:10px; margin-top:10px;">
                                                        <input class="select_lib_item" id="med_key_m_<?php echo $item['sid']; ?>" type="checkbox" item-category="Media" item-title="<?php echo $media_title; ?>" item-type="<?php echo $item_type; ?>" item-sid="m_<?php echo $item['sid']; ?>" />
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>    
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4">
                                            <h3 class="text-center">
                                                No Library Item Found
                                            </h3>
                                        </td>
                                    </tr>
                                <?php } ?>      
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="view_library_item" style="display:none;">                    
                    <div class="embed-responsive embed-responsive-16by9">
                        <div id="library-youtube-section" style="display:none;">
                            <div id="library-youtube-placeholder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="library-vimeo-section" style="display:none;">
                            <div id="library-vimeo-placeholder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="library-video-section" style="display:none;">
                            <div id="library-video-placeholder" class="embed-responsive-item">
                            </div>
                        </div>  
                        <div id="library-audio-section" style="display:none;">
                            <div id="library-audio-placeholder" class="embed-responsive-item">
                            </div>
                        </div>
                        <div id="library-document-section" style="display:none;">
                            <div id="library-document-placeholder" class="embed-responsive-item">
                            </div>
                        </div>
                    </div>                    
                    <div class="text-right" style="margin-top:15px;">
                        <button type="button" class="btn btn-info incident-panal-button back_to_library">Back To Library</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer full-width">
                <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Attachment Library Section End -->

<!-- Manual Attachment Section Start -->
<div id="manual_attachment_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Manual Attachment</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="update_video_sid" value="" />
                
                <div class="form-group edit_filter autoheight">
                    <label for="attachment_type">Select Attachment Type</label>
                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                        <?php echo YOUTUBE_VIDEO; ?>
                        <input id="default_manual_select" class="attach_item_source" type="radio" name="attach_item_source" value="youtube" checked="checked"/>
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                        <?php echo VIMEO_VIDEO; ?>
                        <input class="attach_item_source" type="radio" name="attach_item_source" value="vimeo"/>
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                        <?php echo UPLOAD_VIDEO; ?>
                        <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_video" />
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                        <?php echo UPLOAD_AUDIO; ?>
                        <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_audio" />
                        <div class="control__indicator"></div>
                    </label>
                    <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                        Document
                        <input class="attach_item_source" type="radio" name="attach_item_source" value="upload_document" />
                        <div class="control__indicator"></div>
                    </label>
                </div>

                <div class="row">
                    <div class="field-row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                            <div class="form-group autoheight">
                                <label for="attachment_title">Attachment Title <span class="hr-required">*</span></label>
                                <input type="text" name="attachment_title" class="form-control" id="attachment_item_title">
                            </div>
                        </div>
                    </div>
                </div>            

                <div class="row" id="only_video">
                    <div class="field-row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                            <div class="form-group autoheight" id="attachment_yt_vm_video_container">
                                <label for="video_id">Video Url <span class="hr-required">*</span></label>
                                <input type="text" name="attach_social_video" value="" class="form-control" id="attach_social_video" data-rule-required="true">
                            </div>
                            <div class="form-group autoheight" id="attachment_video_container">
                                <label>Attach Video <span class="hr-required">*</span></label>
                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                    <span class="selected-file" id="name_attach_video"></span>
                                    <input type="file" name="attach_video" id="attach_video" onchange="check_attach_video('attach_video')" >
                                    <a href="javascript:;">Choose Video</a>
                                </div>
                            </div>
                            <div class="form-group autoheight" id="attachment_audio_container">
                                <label>Attach Audio <span class="hr-required">*</span></label>
                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                    <span class="selected-file" id="name_attach_audio"></span>
                                    <input type="file" name="attach_audio" id="attach_audio" onchange="check_attach_audio('attach_audio')" >
                                    <a href="javascript:;">Choose Audio</a>
                                </div>
                            </div>
                            <div class="form-group autoheight" id="attachment_document_container">
                                <label>Attach Document <span class="hr-required">*</span></label>
                                <div class="upload-file form-control" style="margin-bottom:10px;">
                                    <span class="selected-file" id="name_attach_document"></span>
                                    <input type="file" name="attach_document" id="attach_document" onchange="check_attach_document('attach_document')" >
                                    <a href="javascript:;">Choose Document</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="field-row">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-info" id="save_attach_item">Save Attachment</button>
                        </div>
                    </div>
                </div>        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info incident-panal-button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Manual Attachment Section End -->

<!-- View Email Attachment Section Start -->
<div id="view_media_document_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content full-width">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close close-current-item" data-dismiss="modal" aria-label="Close" id="close_media_document_modal_up"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="view_item_title"></h4>
            </div>
            <div class="modal-body full-width">                  
                <div class="embed-responsive embed-responsive-16by9">
                    <div id="youtube-container" style="display:none;">
                        <div id="youtube-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                    <div id="vimeo-container" style="display:none;">
                        <div id="vimeo-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                    <div id="video-container" style="display:none;">
                        <div id="video-player-holder" class="embed-responsive-item">
                        </div>
                    </div>  
                    <div id="audio-container" style="display:none;">
                        <div id="audio-player-holder" class="embed-responsive-item">
                        </div>
                    </div>
                    <div id="document-container" style="display:none;">
                        <div id="document-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer full-width">
                <button type="button" class="btn btn-info incident-panal-button close-current-item" data-dismiss="modal" id="close_media_document_modal_down" file-type="">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Email Attachment Section End -->


<!-- Email Attachment Loader Start -->
<div id="attachment_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are uploading email attachment...
        </div>
    </div>
</div>
<!-- Email Attachment Loader End -->

<script>
    var employeeId = '<?php echo $current_user; ?>';
    var companyId = '<?php echo $companyId; ?>';
    var reportId = '<?php echo $reportId; ?>';
    var incidentId = '<?php echo $incidentId; ?>';
    var baseURL = '<?php echo base_url(); ?>';
    var uploadVideoSize = '<?php echo UPLOAD_VIDEO_SIZE; ?>';
    var errorUploadVideoSize = '<?php echo ERROR_UPLOAD_VIDEO_SIZE; ?>';
</script>