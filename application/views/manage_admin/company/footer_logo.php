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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>
                                    <div class="edit-template-from-main">
                                        <div class="add-new-company">
                                            <div class="heading-title page-title">
                                                <h2 class="page-title">Company Name: <?php echo ucwords($company_name); ?></h2>
                                            </div>
                                            <form action="<?php echo current_url();?>" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8" id="form_footer_logo">
                                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                <ul>
                                                    <li class="form-col-100">
                                                        <label for="page_content">Footer Logo Status</label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="footer_status" value="1" type="radio" checked="checked"  />
                                                            Enabled
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="footer_status" value="0" type="radio" <?php echo $footer_logo_data['footer_powered_by_logo'] == 0 ? 'checked="checked"' : ''; ?> />
                                                            Disabled
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <label>Footer Type</label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="footer_type" value="default" type="radio" checked="checked" />
                                                            AutomotoHR Logo&nbsp;&nbsp;
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="footer_type" value="text" type="radio" <?php echo $footer_logo_data['footer_logo_type'] == 'text' ? 'checked="checked"' : ''; ?> />
                                                            Text Based Logo&nbsp;&nbsp;
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="footer_type" value="logo" type="radio" <?php echo $footer_logo_data['footer_logo_type'] == 'logo' ? 'checked="checked"' : ''; ?> />
                                                            Other Company Logo
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100" id="div_text">
                                                        <label for="logo_text">Logo Text</label>
                                                        <div class="hr-fields-wrap">                                                 
                                                            <input name="logo_text" id="logo_text" value="" class="invoice-fields"  type="text">
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100" id="div_logo">
                                                        <label for="upload_logo">Upload Logo</label>
                                                        <div class="hr-fields-wrap">
                                                            <div class="upload-file invoice-fields">
                                                                <span class="selected-file" id="name_logo_upload">No logo selected</span>
                                                                <input type="file" name="logo_upload" id="logo_upload" onchange="check_file('logo_upload')">
                                                                <a href="javascript:;">Choose Logo</a>
                                                            </div>
                                                            <div class="hr-fields-wrap hr-fields-wrap-img">
                                                                <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png') ?>" id="footer_logo">

                                                            </div>
                                                        </div>
                                                    </li>
                                                    <input type="hidden" id="db_logo">
                                                    <li class="form-col-100">
                                                        <label for="footer_copyright_company_status">Copyright Company Name</label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="copyright_company_status" value="0" type="radio" checked="checked"  />
                                                            Default
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio admin-access-level radio_right_margin">
                                                            <input name="copyright_company_status" value="1" type="radio" <?php echo $footer_logo_data['copyright_company_status'] == 1 ? 'checked="checked"' : ''; ?> />
                                                            Other
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </li>
                                                    <li class="form-col-100" id="div_company_name">
                                                        <label for="company_name">Company Name</label>
                                                        <div class="hr-fields-wrap">                                                 
                                                            <input name="company_name" id="company_name" value="" class="invoice-fields"  type="text">
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100">
                                                        <button onclick="func_save_footer();" type="button" class="search-btn" >Update</button>
                                                    </li>
                                                </ul>
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
    </div>
</div>
<script type="text/javascript">
        
    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {

            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'logo_upload') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $( document ).ready(function() {
        var type = '<?php echo $footer_logo_data['footer_logo_type']; ?>';
        if (type == 'default') {
            $('#div_text').hide();
            $('#div_logo').hide();
        } else if (type == 'text') {
            var text = '<?php echo $footer_logo_data['footer_logo_text']; ?>';
            $('#logo_text').val(text);
            $('#div_text').show();
            $('#div_logo').hide();
        } else if (type == 'logo') {
            var img_source = '<?php echo AWS_S3_BUCKET_URL . $footer_logo_data['footer_logo_image']; ?>';
            $('#footer_logo').attr('src',img_source);
            $('#db_logo').val(img_source);
            $('#div_text').hide();
            $('#div_logo').show();
        }

        var copyright_status = '<?php echo $footer_logo_data['copyright_company_status']; ?>';
        if (copyright_status == 0) {
            $('#div_company_name').hide();
        } else if (copyright_status == 1) {
            var company_name = '<?php echo $footer_logo_data['copyright_company_name']; ?>';
            $('#div_company_name').show();
            $('#company_name').val(company_name);
        }
    });

    $("input[name='footer_type']").click(function(){
        
        var value = $("input[name='footer_type']:checked").val();
        if (value == 'default') {
            $('#div_text').hide();
            $('#div_logo').hide();
        } else if (value == 'text') {
            var text = '<?php echo isset($footer_logo_data['footer_logo_text']) && !empty($footer_logo_data['footer_logo_text']) ? $footer_logo_data['footer_logo_text'] : '' ; ?>';
            $('#logo_text').val(text);
            $('#div_text').show();
            $('#div_logo').hide();
        } else if (value == 'logo') {
            var img_source = '<?php echo isset($footer_logo_data['footer_logo_image']) && !empty($footer_logo_data['footer_logo_image']) ? AWS_S3_BUCKET_URL . $footer_logo_data['footer_logo_image'] : '' ; ?>';
            $('#footer_logo').attr('src',img_source);
            $('#db_logo').val(img_source);
            $('#div_text').hide();
            $('#div_logo').show();
        }
    });

    $("input[name='copyright_company_status']").click(function(){
        
        var value = $("input[name='copyright_company_status']:checked").val();
        if (value == 0) {
            $('#div_company_name').hide();
        } else if (value == 1) {
            var company_name = '<?php echo isset($footer_logo_data['copyright_company_name']) && !empty($footer_logo_data['copyright_company_name']) ? $footer_logo_data['copyright_company_name'] : '' ; ?>';
            $('#div_company_name').show();
            $('#company_name').val(company_name);
        } 
    });

    //  This function validate footer logo form.
    function func_save_footer() {
        
        if ($('#form_footer_logo').valid()) {
            
            
            var validation_flag = true;
            if($('input[name="footer_type"]:checked').val() == 'text'){
                
                var logo_text = $('#logo_text').val();

                if (logo_text == '') {
                    validation_flag = false;
                    alertify.error("Please Enter Footer logo Text");
                } else {
                    validation_flag = true;
                }
            } else if ($('input[name="footer_type"]:checked').val() == 'logo') {

               validation_flag = check_upload_company_logo('logo_upload');

            } 

            if ($('input[name="copyright_company_status"]:checked').val() == 1) {
                var company_name = $('#company_name').val();

                if (company_name == '') {
                    validation_flag = false;
                    alertify.error("Please Enter Company Name");
                } else {
                    validation_flag = true;
                }
            }
                    
            if(validation_flag == true ){
                $('#form_footer_logo').submit();
            }
            
        }
    }

    //  This function validate upload company footer logo image 
    //  is present or not when footer type is "Upload Logo".
    function check_upload_company_logo(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {

           return true;

        } else {

            var db_logo = $('#db_logo').val();

            if (db_logo == '') {
                alertify.error("Please Upload Footer Logo");
                $('#name_' + val).html('<p class="red">Please Upload Footer Logo</p>');
                return false;
            } else {
                return true;
            }

        }    
    }

</script>