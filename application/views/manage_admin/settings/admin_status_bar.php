<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">   
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                    <div class="dashboard-content">
                        <div class="form-wrp">
                            <form id="edit_status" name="edit_status" action="" method="POST">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 setting-box">
                                        <h2><strong><?php echo $page_title; ?></strong></h2>
                                    </div>
                                    <?php foreach ($application_status as $status) {
                                        if($status['status_type']=='default') {
                                            ?>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <!-- <input class="form-control" type="hidden"
                                                            name="sid_<?php echo $status['sid']; ?>"
                                                            id="sid_<?php echo $status['css_class']; ?>"
                                                            value="<?php echo $status['sid']; ?>"> -->
                                                    <label>Status Name: <span
                                                            class='staric'>*</span></label>
                                                    <input class="form-control" type="text"
                                                            name="name_<?php echo $status['css_class']; ?>"
                                                            id="<?php echo $status['css_class']; ?>"
                                                            value="<?php echo $status['name']; ?>"
                                                            required>
                                                    <?php echo form_error($status['css_class']); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <div class="form-group">
                                                    <label>Sorting Order : <span class="staric">*</span></label>
                                                    <input type="number"
                                                           value="<?php echo $status['status_order']; ?>"
                                                           name="order_<?php echo $status['css_class']; ?>"
                                                           id="order_<?php echo $status['css_class']; ?>"
                                                           min="1" class="form-control"
                                                           >
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <div class="form-group">
                                                    <label>Status : <span class="staric">*</span></label>
                                                    <select name="status_<?php echo $status['css_class']; ?>"class="form-control"> 
                                                        <option value="1" <?php if ($status['active']=="1") echo "selected='selected'"; ?>>Active</option> 
                                                        <option value="0" <?php if ($status['active']=="0") echo "selected='selected'"; ?>>Inactive</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
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
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <input type="submit" value="Update" onclick="return validate_form();" class="btn btn-success btn-sm">
                                        <a class="btn btn-info btn-sm submit-btn" href="<?php echo base_url('manage_admin'); ?>">Cancel</a>
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />
<script>
    $(document).ready(function () {
        $('.colorcustompicker').colorpicker();
    });    

    // function validate_form() {
    //     $("#edit_status").validate({
    //         ignore: [],
    //         rules: {
    //         <?php foreach ($application_status as $status) { ?>
    //             <?php echo 'sid_'.$status['css_class']; ?>: {
    //                     required: true,
    //                     number: true,
    //                     //max: 12,
    //                     min: 1
    //                 },
    //         <?php } ?>    
    //         <?php foreach ($application_status as $status) { ?>
    //             <?php echo $status['css_class']; ?>: {
    //                     required: true,
    //                     pattern: /^[a-zA-Z0-9\s]+$/i
    //                 },
    //         <?php } ?>
    //         <?php foreach ($application_status as $status) { ?>
    //             <?php echo 'order_'.$status['sid']; ?>: {
    //                     required: true,
    //                     number: true,
    //                     //max: 12,
    //                     min: 1
    //                 },
    //         <?php } ?>
    //         },
    //         messages: {
    //         <?php foreach ($application_status as $status) { ?>
    //             <?php echo $status['css_class']; ?>: {
    //                 required: 'Status name is required',
    //                 pattern: 'Status must be Alpha Numeric only.'
    //             },
    //         <?php } ?>
    //         <?php foreach ($application_status as $status) { ?>
    //             <?php echo 'order_'.$status['sid']; ?>: {
    //                 required: 'Order is required',
    //                 number: 'Please enter a valid number',
    //                 //max: 'Please enter a number between 1 and 12',
    //                 min: 'Please enter order minimum 1'
    //             },
    //         <?php } ?>
    //         },
    //         submitHandler: function (form) {
    //             form.submit();
    //         }
    //     });
    // }

</script>