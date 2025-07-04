<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .upload-file input[type="file"], .upload-file a {
            top: -8px;
            height: 39px;
    }
</style>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><b>Private Messages: <?php echo $page; ?></b></h1>
                    <div class="btn-panel float-right">
                        <a href="<?php echo base_url('/dashboard'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('inbox'); ?>"><i class="fa fa-envelope-o"></i> Inbox </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('outbox'); ?>"><i class="fa fa-inbox"></i> Outbox</a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('compose-messages'); ?>"><i class="fa fa-pencil-square-o"></i> Compose new Message </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <form action="" class="form-horizontal" id="compose_message_form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                        <b>Select a Receiver</b><span class="hr-required red"> * </span>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-14 col-xs-12 col-sm-12 mb-4">
                                        <div class="row">
<!--                                            <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12 col-sm-3">-->
<!--                                                <label class="control control--radio">To Admin-->
<!--                                                    <input type="radio" name="send_type" value="to_admin" checked class="active_email_type">-->
<!--                                                    <div class="control__indicator"></div>-->
<!--                                                </label>-->
<!--                                            </div>-->
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <?php $send_email = isset($to_email) && !empty($to_email) ? 'checked="checked"' : ''; ?>
                                                <label class="control control--radio">Email
                                                    <input type="radio" name="send_type" value="to_email" checked class="active_email_type" <?php echo $send_email; ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label class="control control--radio">Affiliates
                                                    <input type="radio" name="send_type" value="to_affiliate" class="active_email_type">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>    
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover table-stripped">
                                        <tbody>
<!--                                            <tr id="to_admin_div">-->
<!--                                                <td><b>Message To</b></td>-->
<!--                                                <td><input class="form-control invoice-fields"  type="text" value="Admin" name="to-admin" disabled></td>-->
<!--                                            </tr>-->
                                            <tr id="to_email_div">
                                                <td><b>Message To (E-Mail)</b></td>
                                                <td><p>Please enter comma separated values</p>
                                                    <input class="form-control invoice-fields" name="toemail" id="toemail"  type="text" >
                                                </td>
                                            </tr>
                                            <tr style="display: none" id="to_affiliates_div">
                                                <td><b>Select Affiliates</b></td>
                                                <td>
                                                    <?php if (sizeof($affiliates) > 0) { ?>
                                                        <select class="form-control chosen-select" name="affiliates[]" multiple="multiple">
                                                        <option value="">Please Select Affiliates</option>
                                                        <?php if(!empty($affiliates)) { ?>
                                                            <?php foreach($affiliates as $affiliate) { ?>
                                                                <option value="<?php echo $affiliate['sid']; ?>">
                                                                    <?php echo $affiliate['full_name'] . ' ' . $affiliate['email']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <p>No Affiliate Found.</p>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Subject</b><span class="hr-required red"> * </span></td>
                                                <td><?php   echo form_input('subject', set_value('subject'), 'class="form-control invoice-fields"');
                                                            echo form_error('subject'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Message</b><span class="hr-required red"> * </span></td>
                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                <td><textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="message"><?php echo set_value('message'); ?></textarea>
                                                    <?php echo form_error('message'); ?>
                                                </td>
                                            </tr>
                                            <tr id="dynamicattachment">
                                                <td><b><a href="javascript:;" onclick="addattachmentblock(); return false;" class="add"> + Attachments</a></b></td>
                                                <td>
                                                    <div class="upload-file invoice-fields">
                                                        <input name="message_attachment[]" id="message_attachment"  type="file" class="choose-file">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="btn-wrp full-width text-right">
                                                        <a class="btn btn-black margin-right" href="<?php base_url('compose_message'); ?>">Cancel</a>
                                                        <input type="submit" value="Send Message" class="btn btn-info" id="submit_button" onclick="return validate_form();">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function (){

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

//        $('.active_email_type').click();


        var pre_selected = '<?php echo isset($to_email) && !empty($to_email) ? 'to_email' : 'to_admin'; ?>';
//        if (pre_selected == 'to_admin') {
//            $('#to_admin_div').show();
//            $('#to_email_div').hide();
//            $('#to_affiliates_div').hide();
//        } else
        if (pre_selected == 'to_email') {
            $('#to_admin_div').hide();
            $('#to_email_div').show();
            $('#to_affiliates_div').hide();
        } else if (pre_selected == 'to_affiliate') {
            $('#to_admin_div').hide();
            $('#to_email_div').hide();
            $('#to_affiliates_div').show();
        } 
        
    });
    var i = 1;

    function addattachmentblock() {
        var container_id = "message_attachment_container" + i;
        var id = "message_attachment" + i;
        $('#dynamicattachment').after('<tr id="'+i+'"><td><b class="btn btn-danger text-center" onclick="deleteAnswerBlock(' + i + '); return false;">Delete</b></td><td><div class="upload-file invoice-fields"><input type="file" name="message_attachment[]" id="'+id+'" class="choose-file"><a href="javascript:;"></a></div></td></tr>');
        $('.choose-file').filestyle({
            text: 'Browse...',
            btnClass: 'btn-green',
            placeholder: "No file selected"
        });
        i++;
    }

    function deleteAnswerBlock(id) {
        console.log('Delete it: '+id);
        $('#' + id).remove();
    }

    $('.active_email_type').on('click', function () {
        var selected = $(this).val();

        if (selected == 'to_admin') {
            $('#to_admin_div').show();
            $('#to_email_div').hide();
            $('#to_affiliates_div').hide();
        } else if (selected == 'to_email') {
            $('#to_admin_div').hide();
            $('#to_email_div').show();
            $('#to_affiliates_div').hide();
        } else if (selected == 'to_affiliate') {
            $('#to_admin_div').hide();
            $('#to_email_div').hide();
            $('#to_affiliates_div').show();
        }    
    });
</script>