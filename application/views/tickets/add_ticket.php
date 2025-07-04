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
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="multistep-progress-form">
                                <form id="add_new_ticket" class="msform" action="" method="POST" enctype="multipart/form-data">
                                    <fieldset id="create_div">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-100">
                                                    <label>Select Ticket Category : </label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="ticket_category" id="ticket_category">
                                                            <option value="Technical Inquiry" <?php if (set_value('ticket_category') == "Technical Inquiry") { ?>selected<?php } ?>>Technical Inquiry</option>
                                                            <option value="Sales Support" <?php if (set_value('ticket_category') == "Sales Support") { ?>selected<?php } ?>>Sales Support</option>
                                                            <option value="Other" <?php if (set_value('ticket_category') == "Other") { ?>selected<?php } ?>>Other</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="form-col-100" id="custom_ticket_cat">
                                                    <label>Please Specify Category : <span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" name="custom_category" id="custom_category" value="<?php echo set_value('custom_category'); ?>">
                                                    <?php echo form_error('custom_category'); ?>
                                                </li>
                                                <li class="form-col-100">
                                                    <label>Subject : <span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" name="subject" id="subject" value="<?php echo set_value('subject'); ?>">
                                                    <?php echo form_error('subject'); ?>
                                                </li>
                                                <div class="description-editor">
                                                    <label>Message : <span class="staric">*</span></label>
                                                    <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                    <textarea class="ckeditor" name="message_body" id="message_body" cols="60" rows="10"><?php echo set_value('message_body'); ?></textarea>
                                                    <?php echo form_error('message_body'); ?>
                                                </div>
                                                <div class="form-group">
                                                    <div class="upload-file invoice-fields">
                                                        <input type="file" name="document" id="document" onchange="check_file('document')">
                                                        <p id="name_document"></p>
                                                        <a href="javascript:;">Choose File</a>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </ul>
                                        </div>
                                        <div class="text-right">
                                            <input type="submit" value="Submit" onclick="" class="submit-btn" id="add_ticket_submit">
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <?php $this->load->view('tickets/add_ticket_ems'); ?>
<?php } ?>


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $('#add_ticket_submit').click(function () {
        $("#add_new_ticket").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true,
                },
                message_body: {
                    required: true,
                },
                custom_category: {required: function (element) {
                        return $("#ticket_category").val() == 'Other';
                    }
                }
            },
            messages: {
                subject: {
                    required: 'Support Ticket subject is required',
                },
                message_body: {
                    required: 'Message body is required',
                }
            }
        });
    });

    $(document).ready(function () {
        display();
    });

    $('#ticket_category').on('change', function () {
        display();
    });

    function display() {
        var value = $("#ticket_category").val();
        if (value == 'Other') {
            $("#custom_ticket_cat").show();
        } else {
            $("#custom_ticket_cat").hide();
        }
    }

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