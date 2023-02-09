<?php if (!$load_view) { ?>
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo ucwords($ticket['subject']); ?></span>
                        </div>
                    </div>
                    <div class="panel-group ticket-group" id="accordion" role="tablist" aria-multiselectable="false">
                        <?php if ($messages_count > 0) { ?>
                            <?php foreach ($messages as $message) { ?>
                                <div class="panel panel-default ticket-panel">
                                    <div class="panel-heading" data-toggle="collapse" data-parent="" href="#collapse<?php echo $message['sid']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $message['sid']; ?>">
                                        <h3 class="panel-title"><?php echo $message['employee_name']; ?>
                                            <small class="pull-right"><?=reset_datetime(array( 'datetime' => $message['date'], '_this' => $this)); ?></small>
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
                                    <h3 class="panel-title">No messages found.</h3>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="panel panel-default" id="reply">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-lock"></i> This ticket was Answered. If the problem persists or was not remedied, please <a href="javascript:;" id="reopen"><u>Reopen the Ticket</u></a>.</h3>
                            </div>
                        </div>
                        <div class="panel panel-default" id="post_reply">
                            <div class="panel-heading">
                                <h3 class="panel-title text-uppercase">Post a Reply </h3>
                            </div>
                            <div class="panel-body">
                                <form id="new_message" action="<?php echo base_url('support_tickets/add_new_ticket_message') . '/' . $ticket['sid']; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Message : <span class="staric">*</span></label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <textarea class="ckeditor" name="message_body" id="message_body" cols="60" rows="10"><?php echo set_value('message_body'); ?></textarea>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="upload-file invoice-fields">
                                            <input type="file" name="document" id="document" onchange="check_file('document')">
                                            <p id="name_document">Attachments</p>
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="text-right">
                                        <input type="submit" value="Submit" class="delete-all-btn active-btn" id="new_message_submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($files)) { ?>
                        <div class="table-responsive">
                            <h3>Attached Ticket Files</h3>
                            <div class="hr-document-list">
                                <table class="hr-doc-list-table">
                                    <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php   foreach ($files as $file) { ?>
                                        <tr>
                                            <td><?php echo $file['saved_file_name']; ?></td>
                                            <td>
                                                <a href="javascript:;" data-toggle="modal" data-target="#document_<?php echo $file['sid'] ?>" class = "action-btn enable-bs-tooltip" title="View and Download">
                                                    <i class = "fa fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php   } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>
    <?php $this->load->view('tickets/view_ticket_ems'); ?>
<?php } ?>

<?php   if(!empty($files)) {  ?>
    <?php foreach ($files as $file) { ?>
        <div id="document_<?php echo $file['sid'] ?>" class="modal fade file-uploaded-modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo $file['saved_file_name']; ?> </h4>

                    </div>
                    <div class="modal-body">
                    <?php   if($file['saved_file_type'] == 'png' || $file['saved_file_type'] == 'jpg' || $file['saved_file_type'] == 'jpe' || $file['saved_file_type'] == 'jpeg' || $file['saved_file_type'] == 'gif')  { ?>
                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $file['uploaded_file_name']; ?>" />
                    <?php   } else { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL . urlencode($file['uploaded_file_name']); ?>&embedded=true" frameborder="0"></iframe>
                        </div>
                    <?php   } ?>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-info" href="<?php echo AWS_S3_BUCKET_URL . $file['uploaded_file_name']; ?>" download="download" >Download</a>
                    </div>
                </div>

            </div>
        </div>
    <?php } ?>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".panel-group.ticket-group .ticket-panel:first-child .panel-heading").removeClass("collapsed").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel:first-child .panel-heading + div").addClass("in").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel").last().find(".panel-heading").removeClass("collapsed").attr("aria-expanded", "true");
        $(".panel-group.ticket-group .ticket-panel").last().find(".panel-heading").siblings().addClass("in").attr("aria-expanded", "true");
        
        var status = '<?php echo isset($ticket['status']) ? $ticket['status'] : ''; ?>';

        if(status == 'Feedback Required' || status == 'Awaiting Response') {
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
</script>