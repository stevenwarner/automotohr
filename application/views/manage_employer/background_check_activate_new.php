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
                        <span class="page-heading down-arrow">Background Check Activation</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="alert alert-info alert-dismissable">
                                        <h4><i class="icon fa fa-check"></i> Note!</h4>
                                        <span class="desc_p">This form allows Accurate Background to verify your company information. Please completely fill in this form and acknowledge the End User Agreement. </span>
                                        <span class="desc_p">You will only be asked one time for each company account to complete the Accurate Background company verification form.</span>

                                        <span class="desc_p">Download document from <a style="text-decoration: underline" href="<?php echo AWS_S3_BUCKET_URL; ?>End_User_Agreement_-_US_and_NonUS_uoKhpZNACA.docx">here</a>. </span>
                                        <span class="desc_p">Fill up all of Highlighted fields and upload the document again .</span>
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div> <!-- /.row -->
                    <form method="POST" enctype="multipart/form-data"  id="background_check_form" class="background_check_form">
                        <div class="universal-form-style-v2">
                            <ul>
                                <?php if ($flag == 1) { ?>
                                    <li class="form-col-100 autoheight">
                                        <label>Upload Document:</label>
                                        <div class="upload-file invoice-fields">
                                            <span class="selected-file" id="name_document">No file selected</span>
                                            <input type="hidden" name="action" value="upload">
                                            <input type="file" name="document" id="document" onchange="check_file('document')">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <?php echo form_error('document'); ?>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <input type="submit" value="Submit" onclick="validate_form()" class="submit-btn">
                                        <a class="submit-btn btn-cancel" href="<?php echo $cancel_location; ?>">Cancel</a>
                                    </li>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="box box-default">
                                                <div class="box-body">
                                                    <div class="alert alert-info-green alert-dismissable">
                                                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                                        <span class="desc_p">Your accurate background activation request is already in process.</span>
                                                        <span class="desc_p">We will notify you once your request is processed.</span>
                                                    </div>
                                                </div><!-- /.box-body -->
                                            </div><!-- /.box -->
                                        </div><!-- /.col -->
                                    </div> <!-- /.row -->
                                <?php } ?>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>          
        </div>
    </div>
</div>
<div id="user_agreement" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">End User Agreement</h4>
            </div>
            <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>End-User-Agreement---US-and-NonUS-1PZ0A.doc&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
        </div>
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
                                        function check_file(val) {
                                            var fileName = $("#" + val).val();
                                            if (fileName.length > 0) {
                                                $('#name_' + val).html(fileName.substring(0, 45));
                                                var ext = fileName.split('.').pop();
                                                if (val == 'document') {
                                                    if (ext != "docx" && ext != "doc" && ext != "pdf") {
                                                        $("#" + val).val(null);
                                                        alertify.error("Please select a valid Image format.");
                                                        $('#name_' + val).html('<p class="red">Only (.docx .doc .pdf) allowed!</p>');
                                                        return false;
                                                    } else
                                                        return true;
                                                }
                                            } else {
                                                $('#name_' + val).html('No file selected');
                                            }
                                        }


                                        function validate_form() {
                                            $("#background_check_form").validate({
                                                ignore: ":hidden:not(select)",
                                                rules: {
                                                    document: {
                                                        required: true,
                                                    }
                                                },
                                                messages: {
                                                    document: {
                                                        required: 'Document is Required'
                                                    }
                                                },
                                                submitHandler: function (form) {
                                                    form.submit();
                                                }
                                            });
                                        }


</script>