<div class="main-content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
              <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message');?>
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Employee Management System </a> <?php echo $title; ?>
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
                                            <input type="text" name="helpboxtitle" class="form-control" value="<?php echo !empty($contact_info[0]['box_title']) ? $contact_info[0]['box_title'] : getCompanyNameBySid($company_sid).' HR Department' ; ?>">
                                            <span class="js-error"></span>

                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Phone Number</label> <span class="staric">*</span>
                                            <input type="text" name="helpboxphonenumber" class="form-control"
                                                value="<?php echo !empty($contact_info[0]['box_support_phone_number']) ? $contact_info[0]['box_support_phone_number'] : ''; ?>">
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Email</label> <span class="staric">*</span>
                                            <input type="text" name="helpboxemail" class="form-control"
                                                value="<?php echo !empty($contact_info[0]['box_support_email']) ? $contact_info[0]['box_support_email'] : ''; ?>">
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                        <label>Status</label> <span class="staric">*</span> 
                                            <select name="helpboxstatus" class="form-control" id="helpboxstatus">
                                                        <option value="0" >Inactive</option>
                                                        <option value="1" >Active</option>
                                                        </select>
                                            <span class="js-error"></span>
                                        </div>
                                    </div>
                                 
                                    <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                        <input type="hidden" name="txtId"
                                            value="<?php echo isset($companyBlockData['sid']) ? $companyBlockData['sid'] : 0; ?>" />
                                        <button type="submit"
                                            class="btn btn-success js_submit_button"><?php echo !empty($companyBlockData) ? "Update" : "Save"; ?></button>
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

$('#helpboxstatus').val('<?php echo $contact_info[0]['box_status'];?>');
$('#onboarding_help').submit(validate_form);

function validate_form(e) {
    e.preventDefault();
    $('.js-error').html('');
    var helpboxtitle = $(this).find('input[name="helpboxtitle"]');
    var helpboxphonenumber = $(this).find('input[name="helpboxphonenumber"]');
    var helpboxemail = $(this).find('input[name="helpboxemail"]');
    var helpboxstatus = $('#helpboxstatus').val();
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;


    isError = false;
    if (helpboxtitle.val().trim() == '') {
        helpboxtitle.next().show().html("<b class='red_warning'>Title is compulsory</b>");
        isError = true;
    }
  
    if (helpboxphonenumber.val().trim() == '') {
        helpboxphonenumber.next().show().html(
            "<b class='red_warning'>Phone number is compulsory</b>");
        isError = true;
    }

    if (helpboxemail.val().trim() == '') {
        helpboxemail.next().show().html(
            "<b class='red_warning'>Email address is required</b>");
        isError = true;
    }

    if (helpboxemail.val().trim().match(mailformat)) {
       
    }else{
        helpboxemail.next().show().html("<b class='red_warning'>Email address is invalid</b>");
         isError = true;
    }

    if (isError == true) {
        return;
    }

    $('#my_loader').show();
    var megaOBJ = {};
    megaOBJ.helpboxtitle = helpboxtitle.val();
    megaOBJ.helpboxphonenumber = helpboxphonenumber.val();
    megaOBJ.helpboxemail = helpboxemail.val();
    megaOBJ.helpboxstatus = helpboxstatus;
    formSubmit(megaOBJ);
}

function formSubmit(megaOBJ) {
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("onboarding_block/manage_company_help_box_update"); ?>',
        data: megaOBJ,
        success: function(response) {
            $('#my_loader').hide();
            response = JSON.parse(response);
                      if (response.msg == 'Success') 
                alertify.alert('SUCCESS', response.success);
            else
                alertify.alert('ERROR!', response.error);
        }
    });
}

</script>