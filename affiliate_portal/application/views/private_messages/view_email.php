<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><b>Private Messages: <?php echo $page; ?></b></h1>
                    <div class="btn-panel float-right">
                        <a href="<?php echo base_url().$message_type; ?>" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('inbox'); ?>"><i class="fa fa-envelope-o"></i> Inbox </a>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('outbox'); ?>"><i class="fa fa-inbox"></i> Outbox</a>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('compose-messages'); ?>"><i class="fa fa-pencil-square-o"></i> Compose new Message </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <table class="table table-bordered table-hover table-stripped">
                                    <tbody>
                                        <tr>
                                            <td><b>Date</b></td>
                                            <td>
                                                <?php echo
                                                reset_datetime(array(
                                                    'datetime' => $message_detail['message_date'],
                                                    // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                    // 'format' => 'h:iA', //
                                                    'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                    'from_timezone' => $session['affiliate_users']['timezone'], //
                                                    '_this' => $this
                                                ));?>
<!--                                                --><?php //echo $message_detail['message_date']; ?>
                                            </td>
                                        </tr>
                                        <?php if ($message_type == 'inbox') { ?>
                                            <?php 
                                                $to_user_data = get_affiliate_name_and_email($message_detail['to_id']);
                                                $from_user_data = get_affiliate_name_and_email($message_detail['from_id']);

                                                $to_user_name = $to_user_data['full_name'];
                                                $from_user_name = $from_user_data['full_name'];
                                                $from_user_email = $from_user_data['email']; 
                                            ?>
                                        <tr>
                                            <td><b>From Name</b></td>
                                            <td>
                                                <?php echo $from_user_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>From Email</b></td>
                                            <td>
                                                <?php echo $from_user_email; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>To</b></td>
                                            <td>
                                                <?php echo $to_user_name; ?>
                                            </td>
                                        </tr>
                                        <?php } else if ($message_type == 'outbox') { ?>
                                            <?php 
                                                $to_user_data = get_affiliate_name_and_email($message_detail['to_id']);
                                                $from_user_data = get_affiliate_name_and_email($message_detail['from_id']);

                                                $to_user_name = $to_user_data['full_name'];
                                                $to_user_email = $to_user_data['email'];
                                                $from_user_name = $from_user_data['full_name']; 
                                            ?>
                                        <tr>
                                            <td><b>From</b></td>
                                            <td>
                                                <?php echo $from_user_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>To Name</b></td>
                                            <td>
                                                <?php echo $to_user_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>To Email</b></td>
                                            <td>
                                                <?php echo $to_user_email; ?>
                                            </td>
                                        </tr>
                                        <?php }  ?>
                                        <tr>
                                            <td><b>Subject</b></td>
                                            <td>
                                                <?php echo $message_detail['subject']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Message</b></td>
                                            <td>
                                                <?php echo $message_detail['message']; ?>
                                            </td>
                                        </tr>

                                        <?php if(isset($message_attachments) && sizeof($message_attachments) > 0) {
                                            foreach ($message_attachments as $attachment) { ?>
                                                <tr>
                                                    <td><b>Attachment</b></td>
                                                    <td>
                                                        <?php $document_filename = $attachment['attachment']; ?>
                                                        <a class="btn btn-primary"
                                                           href="javascript:void(0);"
                                                           onclick="preview_attachment(this);"
                                                           data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document_filename; ?>"
                                                           data-file-name="<?php echo $document_filename; ?>"
                                                           data-document-sid="<?php echo $message_detail['sid']; ?>">Preview
                                                            Uploaded Attachment</a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        }?>
                                        <tr>
                                            <td colspan="2">
                                                <div class="btn-wrp full-width text-right">
                                                    <input class="btn btn-danger" type="button" id="<?php echo $message_detail['sid']; ?>" onclick="todo('delete', this.id);" value="DELETE">
                                                    <?php if ($message_type == 'inbox') { ?>
                                                        <a href="<?php echo base_url('compose-messages/').$message_detail['from_id']; ?>"><input type="button" class="btn btn-success" value="Reply"></a>
                                                    <?php } ?>
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

<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header footer-header-color">
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer footer-header-color">

            </div>
        </div>
    </div>
</div>



<script>
    function todo(action, id) {
        url = "<?= base_url() ?>messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
            function () {
                $.post(url, {action: action, sid: id})
                    .done(function (data) {
                        alertify.success('Selected message have been ' + action + 'd.');
                        window.location.href = '<?php echo base_url().$message_type;?>';
                    });

            },
            function () {
                alertify.error('Canceled');
            });
    }

    function preview_attachment(source) {
        var document_title       = "Attachment";
        var document_preview_url = $(source).attr('data-preview-url');
        var document_file_name   = $(source).attr('data-file-name');
        var document_sid         = $(source).attr('data-document-sid');
        var file_extension       = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content        = '';
        var footer_content       = '';
        var iframe_url           = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default : //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $.ajax({
            'url': '<?php echo base_url('messages/get_url'); ?>',
            'type': 'POST',
            'data': {
                'message_sid': document_sid
            },
            success: function (urls) {
                var obj = jQuery.parseJSON(urls);
                var print_url = obj.print_url;
                var download_url = obj.download_url;
                footer_content = '<a target="_blank" class="btn btn-default modal-footer-button" href="'+download_url+'">Download</a>';
                footer_print_btn = '<a target="_blank" class="btn btn-default modal-footer-button" href="'+print_url+'" >Print</a>';

                $('#document_modal_body').html(modal_content);
                $('#document_modal_footer').html(footer_content);
                $('#document_modal_footer').append(footer_print_btn);
                $('#document_modal_title').html(document_title);
                $('#document_modal').modal("toggle");
                $('#document_modal').on("shown.bs.modal", function () {
                
                    if (iframe_url != '') {
                        $('#preview_iframe').attr('src', iframe_url);
                    }
                });
            }
        }); 
    }
</script>