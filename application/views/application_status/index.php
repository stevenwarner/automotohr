<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="form-wrp">
                                    <form id="edit_status" name="edit_status" action="" method="POST">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 setting-box">
                                                <h2><strong>Default Status</strong></h2>
                                            </div>
                                            <div class="col-sm-12"></div>
                                            <?php foreach ($application_status as $status) {
                                                if($status['status_type']=='default') {
                                                    ?>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Applicant Status: <span
                                                                    class='staric'>*</span></label>
                                                            <input class="form-control" type="text"
                                                                   name="<?php echo $status['css_class']; ?>"
                                                                   id="<?php echo $status['css_class']; ?>"
                                                                   value="<?php echo $status['name']; ?>">
                                                            <?php echo form_error($status['css_class']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="form-group">
                                                            <label>Sorting Order : <span class="staric">*</span></label>
                                                            <input type="number"
                                                                   value="<?php echo $status['status_order']; ?>"
                                                                   name="order_<?php echo $status['css_class']; ?>"
                                                                   id="order_<?php echo $status['css_class']; ?>"
                                                                   min="1" class="form-control">
                                                            <?php echo form_error('order_' . $status['css_class']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="form-group">
                                                            <label class="hidden-xs"></label>
                                                            <span
                                                                class="form-control <?php echo $status['css_class']; ?>_important"></span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }?>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 setting-box">
                                                <h2><strong>Custom Status</strong></h2>
                                            </div>
                                            <div class="col-sm-12"></div>
                                            <?php foreach ($application_status as $status) {
                                                if($status['status_type']=='custom'){?>
                                                <div id="<?=$status['sid']?>">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Applicant Status: <span class='staric'>*</span></label>
                                                            <input  class="form-control"  type="text" name="<?php echo $status['css_class']; ?>" id="<?php echo $status['css_class']; ?>" value="<?php echo $status['name']; ?>" >
                                                            <?php echo form_error($status['css_class']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <div class="form-group">
                                                        <label>Sorting Order : <span class="staric">*</span></label>
                                                        <input type="number" value="<?php echo $status['status_order']; ?>" name="order_<?php echo $status['css_class']; ?>" id="order_<?php echo $status['css_class']; ?>" min="1" class="form-control">
                                                        <?php echo form_error('order_'.$status['css_class']); ?>
                                                        </div>
                                                    </div>
                                                    <div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'>
                                                        <label>Color: <span class='staric'>*</span></label>
                                                        <div class='input-group colorcustompicker'>
                                                            <input type='text' class='form-control' name='color_<?=$status['css_class']?>' data-rule-required='true' value='<?='#'.$status['bar_bgcolor']?>'>
                                                            <span class='input-group-addon'><i></i></span>
                                                        </div>
                                                    </div>
                                                    <div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'>
                                                        <div class='delete-row-new text-right'>
                                                            <a href='javascript:;' data-attr="<?=$status['sid']?>" class="remove dlt-custom-status">
                                                            <i class='fa fa-times'></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <!-- for custom div close-->
                                            <?php
                                                }
                                            }?>
                                        </div>


                            <?php       if($additional_status_bar == 'enabled') { ?>
                                            <div class="row">
                                                <div id="add_status_row"></div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <a href="javascript:;" onclick="add_status_block()" class="add"> + Add More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <input type="hidden" value="0" id="additional_custom_count" name="additional_custom_count">
                            <?php       } ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <input type="submit" value="Update" onclick="return validate_form();" class="submit-btn">
                                                    <a class="submit-btn btn-cancel" href="<?php echo base_url('my_settings'); ?>">Cancel</a>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />
<script>
    $(document).ready(function () {
        $('.colorcustompicker').colorpicker();

        $('.dlt-custom-status').on('click',function(){
            var status_id = $(this).attr('data-attr');
            alertify.confirm('Delete Confirm','Are you sure you want to delete?',function(){
                $.ajax({
                    url: '<?php echo base_url('application_status/delete_custom_status')?>',
                    type: 'POST',
                    data:{
                        id:status_id
                    },
                    success: function (data) {
                        if(data=='success'){
                            alertify.success('Status Deleted Successfully');
                            $('#'+status_id).html('');
                        }
                    },
                    error: function () {

                    }
                })
            },function(){

            });
        });
    });

    var i = 0;
    function add_status_block() {
        var id = "add_status_row" + i;
        $("<div id='" + id + "'><\/div>").appendTo("#add_status_row");
        $('#' + id).html($('#' + id).html() + "<div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Applicant Status: <span class='staric'>*</span></label><input class='form-control' type='text' data-rule-required='true' name='custom_status_name_[]' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Sorting Order : <span class='staric'>*</span></label><input type='number' value='13' name='custom_sort_order_[]' min='1' class='form-control'></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><label>Color: <span class='staric'>*</span></label><div class='input-group colorcustompicker'><input type='text' class='form-control' name='custom_color_[]' data-rule-required='true' value='#a13e07'><span class='input-group-addon'><i></i></span></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new text-right'><a href='javascript:;' onclick=\"deleteAnswerBlock('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div>");
//        $('#' + id).html($('#' + id).html() + "<div class='col-lg-6 col-md-6 col-xs-12 col-sm-6'><div class='form-group'><label>Applicant Status: <span class='staric'>*</span></label><input class='form-control' type='text' data-rule-required='true' name='custom_status_name_"+ i +"' value='' /></div></div><div class='col-lg-3 col-md-3 col-xs-12 col-sm-3'><div class='form-group'><label>Sorting Order : <span class='staric'>*</span></label><input type='number' value='13' name='custom_sort_order_" + i + "' min='1' class='form-control'></div></div><div class='col-lg-2 col-md-2 col-xs-12 col-sm-2'><label>Color: <span class='staric'>*</span></label><div class='input-group colorcustompicker'><input type='text' class='form-control' name='custom_color_" + i + "' data-rule-required='true' value='#a13e07'><span class='input-group-addon'><i></i></span></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new text-right'><a href='javascript:;' onclick=\"deleteAnswerBlock('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div>");
        i++;
        $('.colorcustompicker').colorpicker();
        $('#additional_custom_count').val(i);
        //$('#edit_status').validate();
    }

    function validate_form() {
        
        $("#edit_status").validate({
            ignore: [],
            rules: {
            <?php foreach ($application_status as $status) { ?>
                <?php echo $status['css_class']; ?>: {
                        required: true,
                        pattern: /^[a-zA-Z0-9\s]+$/i
                    },
            <?php } ?>
            <?php foreach ($application_status as $status) { ?>
                <?php echo 'order_'.$status['css_class']; ?>: {
                        required: true,
                        number: true,
//                        max: 12,
                        min: 1
                    },
            <?php } ?>
            },
            messages: {
            <?php foreach ($application_status as $status) { ?>
                <?php echo $status['css_class']; ?>: {
                    required: 'Status name is required',
                    pattern: 'Status must be Alpha Numeric only.'
                },
            <?php } ?>
            <?php foreach ($application_status as $status) { ?>
                <?php echo 'order_'.$status['css_class']; ?>: {
                    required: 'Order is required',
                    number: 'Please enter a valid number',
//                    max: 'Please enter a number between 1 and 12',
                    min: 'Please enter order minimum 1'
                },
            <?php } ?>
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    


    function deleteAnswerBlock(id) {
        $('#' + id).remove();
        i--;
        $('#additional_custom_count').val(i);
    }


</script>