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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i>Add New Corporate Company</h1>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="<?php echo current_url(); ?>" method="POST" name="add_corporate_company_form" id="add_corporate_company_form">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">corporate company detail</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">  
                                                    <div class="field-row">
                                                        <label for="CompanyName">Corporate Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('CompanyName', set_value('CompanyName'), 'class="hr-form-fileds" data-rule-required="true"'); ?>
                                                        <?php echo form_error('CompanyName'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="ContactName">Contact Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('ContactName', set_value('ContactName'), 'class="hr-form-fileds" data-rule-required="true"'); ?>
                                                        <?php echo form_error('ContactName'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="country">Country</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                                <option value="">Select Country</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <option value="<?php echo $active_country["sid"]; ?>"
                                                                    <?php if ($data["Location_Country"] == $active_country["sid"]) { ?>
                                                                                selected
                                                                            <?php } ?>>
                                                                                <?php echo $active_country["country_name"]; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="state">State</label>
                                                        <p style="display: none;" id="state_id"><?php echo $data["Location_State"]; ?></p>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="state" name="Location_State"  >
                                                                <option value="">Select State</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="Location_ZipCode">City</label>
                                                        <?php echo form_input('Location_City', set_value('Location_City'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('Location_City'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="Location_ZipCode">ZipCode</label>
                                                        <?php echo form_input('Location_ZipCode', set_value('Location_ZipCode'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('Location_ZipCode'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Address</label>
                                                        <?php echo form_input('Location_Address', set_value('Location_Address'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('Location_Address'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Phone Number</label>
                                                        <?php echo form_input('PhoneNumber', set_value('PhoneNumber'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('PhoneNumber'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Corporate Description</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60" >
                                                            <?php echo set_value('CompanyDescription'); ?>
                                                        </textarea>
                                                        <?php echo form_error('CompanyDescription'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight form-group">
                                                        <label>Corporate Site URL <span class="hr-required">*</span></label>
                                                        <div class="input-group">
                                                            <?php echo form_input('new_subdomain', set_value('new_subdomain'), 'class="hr-form-fileds"'); ?>
                                                            <span class="input-group-addon"><b>.<?php echo STORE_DOMAIN; ?></b></span>
                                                        </div>
                                                        <span class="domain_message">Note: Special characters and period sign are not allowed in domain name.</span>

                                                    </div>
                                                    <?php echo form_error('new_subdomain'); ?>
                                                    
                                                </div>



                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label class="state">Automotive Group</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="automotive_group_sid" name="automotive_group_sid">
                                                                <option value="">Select Automotive Group</option>
                                                                <?php if(!empty($automotive_groups)) { ?>
                                                                    <?php foreach($automotive_groups as $group) { ?>
                                                                        <option value="<?php echo $group['sid'];?>"><?php echo $group['group_name'];?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <button type="button" onclick="func_validate_and_submit();" class="btn btn-success">Register</button>
                                            <a href="<?php echo base_url('manage_admin/corporate_management'); ?>" class="btn black-btn">Cancel</a>
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

<script>
    function func_validate_and_submit(){
        $('#add_corporate_company_form').validate();

        if($('#add_corporate_company_form').valid()){
            $('#add_corporate_company_form').submit();
        }
    }

    // get the states
    $(document).ready(function () {
        var myid = $('#state_id').html();
        setTimeout(function () {
            $("#country").change();
        }, 1000);
        if (myid) {
            setTimeout(function () {
                $('#state').val(myid);
            }, 1200);
        }
    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }
</script>