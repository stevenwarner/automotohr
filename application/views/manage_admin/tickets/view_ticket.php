<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">                                
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo ucwords($ticket['subject']); ?></h1>
                                        <a class="black-btn pull-right" href="<?php 
                                            if($ticket['status'] == 'Awaiting Response'){
                                                echo base_url('manage_admin/support_tickets/lists/awaiting');
                                            } else if ($ticket['status'] == 'Feedback Required') {
                                                echo base_url('manage_admin/support_tickets/lists/feedback');
                                            } else if ($ticket['status'] == 'Answered') {
                                                echo base_url('manage_admin/support_tickets/lists/answered');
                                            } else {
                                                echo base_url('manage_admin/support_tickets');
                                            }
                                        ?>">
                                            <i class="fa fa-long-arrow-left"></i> 
                                            Back to <?php echo $ticket['status']; ?> Tickets
                                        </a>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- panel-group starts -->
                                    <div class="panel-group ticket-group" id="accordion" role="tablist" aria-multiselectable="false">
                                        <?php if ($messages_count > 0) { ?>
                                            <?php foreach ($messages as $message) { ?>
                                                <div class="panel panel-default ticket-panel">
                                                    <div class="panel-heading" data-toggle="collapse" data-parent="" href="#collapse<?php echo $message['sid']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $message['sid']; ?>">
                                                        <h3 class="panel-title"><?php echo $message['employee_name']; ?>
                                                            <small class="pull-right"><?php echo date_with_time($message['date']); ?></small>
                                                        </h3>
                                                    </div>
                                                    <div id="collapse<?php echo $message['sid']; ?>" class="panel-collapse collapse" role="tabpanel">
                                                        <div class="panel-body"><?php echo $message['message_body']; ?></div> 
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title text-uppercase">No messages found.</h3>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="panel panel-default" id="reply">
                                            <div class="panel-heading">
                                                <?php if ($ticket['status'] == "Answered") { ?>
                                                    <h3 class="panel-title text-uppercase"><i class="fa fa-lock"></i> This ticket was Answered. If the problem persists or was not remedied, please <a href="javascript:;" id="reopen"><u>Reopen the Ticket</u></a>.</h3>
                                                <?php } else if ($ticket['status'] == "Closed") { ?>
                                                    <h3 class="panel-title text-uppercase"><i class="fa fa-lock"></i> This ticket was Closed. If the problem persists or was not remedied, please <a href="javascript:;" id="reopen"><u>Reopen the Ticket</u></a>.</h3>
                                                <?php } ?>    
                                            </div>
                                        </div>
                                        <div class="panel panel-default" id="post_reply">
                                            <div class="panel-heading">
                                                <h3 class="panel-title text-uppercase">post a reply
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <form id="new_message" action="<?php echo base_url('manage_admin/support_tickets/add_new_ticket_message') . '/' . $ticket['sid']; ?>" method="POST" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label>Message : <span class="staric">*</span></label>
                                                        <textarea class="ckeditor" name="message_body" id="message_body" cols="60" rows="10"><?php echo set_value('message_body'); ?></textarea>
                                                    </div>
                                                    <!-- file upload code start -->
                                                    <div class="upload-file invoice-fields">
                                                        <input type="file" name="document" id="document" onchange="check_file('document')">
                                                        <p id="name_document">Attachments</p>
                                                        <a href="javascript:;">Choose File</a>
                                                    </div>
                                                    <!-- file upload code end -->
                                                    <div class="form-group">
                                                        <label>Status : </label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="ticket_status" id="ticket_status">
                                                                <option value="Answered">Answered</option>  
                                                                <option value="Feedback Required">Feedback Required</option> 
                                                            </select>
                                                        </div>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="text-right">
                                                        <input type="submit" value="Submit" class="search-btn" id="new_message_submit">
                                                        <input type="button" value="Close Ticket" class="search-btn" onclick="close_this_ticket(<?php echo $ticket['sid']; ?>)">
                                                    </div> 
                                                </form>
                                            </div> 
                                        </div>
                                    </div>
                                    <!-- panel-group ends -->
                                    <!-- display files start -->
                                    <?php if (!empty($files)) { ?>                                        
                                        <h3>Attached Ticket Files</h3>
                                        <div class="hr-document-list">
                                            <div class="hr-box-header"></div>
                                            <div class="table-responsive table-outer">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>                                                
                                                            <th width="80%">File Name</th>  
                                                            <th width="20%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($files as $file) { ?>
                                                            <tr>
                                                                <td><?php echo $file['saved_file_name']; ?></td>
                                                                <td class="text-center">
                                                                    <a href="javascript:void(0);" onclick="fLaunchModal(this);" class=" btn btn-success" data-preview-url="<?php echo $file['uploaded_file_name']; ?>" data-download-url="<?php echo $file['uploaded_file_name']; ?>" data-document-title="<?php echo $file['saved_file_name']; ?>" ><i class = "fa fa-download"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="hr-box-header hr-box-footer"></div>
                                        </div>                                        
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".panel-group.ticket-group .ticket-panel:first-child .panel-heading").removeClass("collapsed").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel:first-child .panel-heading + div").addClass("in").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel").last().find(".panel-heading").removeClass("collapsed").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel").last().find(".panel-heading").siblings().addClass("in").attr("aria-expanded", "true");
        var status = '<?php echo isset($ticket['status']) ? $ticket['status'] : ''; ?>';

        if (status == 'Feedback Required' || status == 'Awaiting Response') {
            $('#reply').hide();
            $('#post_reply').show();
        } else { // if status = 'Answered' or otherwise
            $('#reply').show();
            $('#post_reply').hide();
        }
    });

    $('#reopen').click(function () {
        $('#reply').hide();
        $('#post_reply').show();
    });

    $('#new_message_submit').click(function () {
        $("#new_message").validate({
            ignore: [],
            rules: {
                message_body: {
                    required: true,
                },
            },
            messages: {
                message_body: {
                    required: 'Message body is required',
                }
            }
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 28));
            var ext = fileName.split('.').pop();
            var ext = ext.toLowerCase();
            if (val == 'document') {
                if (ext != "pdf" && ext != "docx" && ext != "doc" && ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "gif" && ext != "txt" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    $('#name_' + val).html('<p class="red">Only (.pdf .docx .doc .jpg .jpeg .png .jpe .gif .txt) allowed!</p>');
                }
            }
        } else {
            $('#name_' + val).html('Please Select');
        }
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var type = document_preview_url.split(".");
        var file_type = type[type.length - 1];
        var modal_content = '';
        var footer_content = '';
        var iframe_url = 'https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '&embedded=true';
        var is_document = false;

        if (document_preview_url != '') {
            if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                modal_content = '<img src="<?php echo AWS_S3_BUCKET_URL; ?>' + document_preview_url + '" style="width:100%; height:500px;" />';
            } else {
                is_document = true;
                modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            }
            
            footer_content = '<a class="btn btn-success" href="<?php echo AWS_S3_BUCKET_URL; ?>' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#file_preview_modal').modal("toggle");

        if (is_document) {
            document.getElementById('preview_iframe').contentWindow.location = iframe_url;
        }
    }

    function close_this_ticket(sid) {
        var myurl = "<?= base_url() ?>manage_admin/support_tickets/closed_ticket/"+sid;
    
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                window.location.replace('<?php echo base_url('manage_admin/support_tickets/lists/closed') ?>');
            },
            error: function (data) {

            }   
        });
    }
</script>