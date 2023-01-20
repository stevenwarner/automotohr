<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) {?>
                <?php $this->load->view('main/manage_ems_left_view');?>
                <?php } else {?>
                <?php $this->load->view('manage_employer/settings_left_menu_administration');?>
                <?php }?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message');?>
                <div class="page-header-area">
                    <span class="page-heading down-arrow">
                        <?php if ($this->session->userdata('logged_in')['company_detail']['ems_status']) {?>
                        <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Employee Management System
                        </a>
                        <?php } else {?>
                        <a href="<?php echo base_url('my_settings'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Settings
                        </a>
                        <?php }?>
                        <?php $this->load->view('manage_employer/company_logo_name'); ?>

                        <?php echo $title; ?>
                    </span>
                </div>
                <div id="form_div">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                        <div class="form-wrp">
                            <form type="post" id="onboarding_help" action="" enctype="multipart/form-data"
                                autocomplete="off">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Title </label> <span class="staric">*</span>
                                            <input type="text" name="title" class="form-control"
                                                autocomplete="new-password"
                                                value="<?php echo !empty($companyBlockData) ? $companyBlockData['block_title'] : 'Have Questions or Need Help with Onboarding'; ?>">
                                            <span class="js-error"></span>

                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Phone Number</label> <span class="staric">*</span>
                                            <input type="text" name="phone_number" class="form-control"
                                                autocomplete="new-password"
                                                value="<?php echo !empty($companyBlockData) ? $companyBlockData['phone_number'] : ''; ?>">
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Email</label> <span class="staric">*</span>
                                            <input type="text" name="email_address" class="form-control"
                                                autocomplete="new-password"
                                                value="<?php echo !empty($companyBlockData) ? $companyBlockData['email_address'] : ''; ?>">
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="txtStatus" <?=sizeof($companyBlockData) && $companyBlockData['is_active'] == 1 ? 'checked="true"' : '';?> style="width: 30px; height: 30px; margin-left: -41px !important; margin-top: -5px;"/>
                                                    Is Active
                                                </label>
                                            </div>
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Description</label>
                                            <textarea class="form-control autoheight" name="description"
                                                rows="5"><?php echo !empty($companyBlockData) ? $companyBlockData['description'] : 'If you have any questions, please feel free to reach out to us.'; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        <input type="hidden" name="txtId"
                                            value="<?php echo isset($companyBlockData['sid']) ? $companyBlockData['sid'] : 0; ?>" />
                                        <button type="submit"
                                            class="btn btn-success js_submit_button"><?php echo sizeof($companyBlockData) ? "Update" : "Save"; ?></button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--End of form div-->
                <div id="my_loader" class="text-center my_loader" style="display: none;">
                    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                    <div class="loader-icon-box">
                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                        <div class="loader-text" style="display:block; margin-top: 35px;">
                            Please, wait while we are processing the data.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
$('#onboarding_help').submit(validate_form);

function validate_form(e) {
    e.preventDefault();
    $('.js-error').html('');
    var titleREF = $(this).find('input[name="title"]');
    var phone_numberREF = $(this).find('input[name="phone_number"]');
    var email_addressREF = $(this).find('input[name="email_address"]');
    var descriptionREF = $(this).find('textarea[name="description"]');
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var sidREF = $(this).find('input[name="txtId"]');
    var isError = false;

    if (titleREF.val().trim() == '') {
        titleREF.next().show().html("<b class='red_warning'>Block title is compulsory</b>");
        isError = true;
    }
    if (email_addressREF.val().trim().match(mailformat)) {
       
    }else{
          email_addressREF.next().show().html("<b class='red_warning'>Email address is invalid</b>");
         isError = true;
    }
    if (phone_numberREF.val().trim() == '') {
        phone_numberREF.next().show().html(
            "<b class='red_warning'>Phone number is compulsory</b>");
        isError = true;
    }

    if (email_addressREF.val().trim() == '') {
        email_addressREF.next().show().html(
            "<b class='red_warning'>Email address is required</b>");
        isError = true;
    }
    if (isError == true) {
        return;
    }

    $('#my_loader').show();
    var megaOBJ = {};
    megaOBJ.title = titleREF.val();
    megaOBJ.sid = sidREF.val();
    megaOBJ.phone_number = phone_numberREF.val();
    megaOBJ.email_address = email_addressREF.val();
    megaOBJ.description = descriptionREF.val();
    megaOBJ.status = $(this).find('input[name="txtStatus"]').prop('checked') ? 1 : 0;
    formSubmit(megaOBJ);
}

function formSubmit(megaOBJ) {
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("Onboarding_block/insert_data_into_db"); ?>',
        data: megaOBJ,
        success: function(response) {
            $('#my_loader').hide();
            response = JSON.parse(response);
            <?php if (!sizeof($companyBlockData)) { ?>
                if (megaOBJ.sid == 0) {
                    $('input[name="txtId"]').val(response.sid);
                    $('.js_submit_button').text('Update');
                } 
            <?php } ?>

            if (response.msg == 'Success') 
                alertify.alert('SUCCESS', response.success);
            else
                alertify.alert('ERROR!', response.error);
        }
    });
}

</script>