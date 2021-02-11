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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a id="back_btn" href="<?php echo base_url('manage_admin/affiliates/referred')?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title">Modify Referred Details</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(sizeof($referred)>0) { ?>
                                        <form method="post" action="" class="form" enctype="multipart">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>First Name <i class="fa fa-asterisk text-danger"></i></label>
                                                    <input type="text" name="first_name" value="<?php echo ucfirst($referred['first_name']);?>" class="hr-form-fileds">
                                                    <?php echo form_error("first_name"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Last Name <i class="fa fa-asterisk text-danger"></i></label>
                                                    <input type="text" name="last_name" value="<?php echo ucfirst($referred['last_name']);?>" class="hr-form-fileds">
                                                    <?php echo form_error("last_name"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Email <i class="fa fa-asterisk text-danger"></i></label>
                                                    <input type="email" name="email" value="<?php echo $referred['email'];?>" class="hr-form-fileds">
                                                    <?php echo form_error("email"); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Job Role</label>
                                                    <input type="text" class="hr-form-fileds" name="job_role"  value="<?php echo $referred['job_role']; ?>">
                                                    <?php echo form_error('job_role'); ?>
                                                </div>
                                            </div>    
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Company Name</label>
                                                    <input type="text" name="company_name" value="<?php echo $referred['company_name']; ?>" class="hr-form-fileds">
                                                    <?php echo form_error("company_name"); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="field-row field-row-autoheight">
                                                    <?php $control_id = 'company_size'; ?>
                                                    <?php echo form_label('Company Size', $control_id); ?>
                                                    <div class="select">
                                                        <select class="hr-form-fileds" name="company_size">
                                                            <option value="" selected="selected">Select Company Size</option>
                                                            <option value="1-5" <?php echo isset($referred) && $referred['company_size'] == '1-5' ? 'selected="selected"' : ''?>>1 - 5</option>
                                                            <option value="6-25" <?php echo isset($referred) && $referred['company_size'] == '6-25' ? 'selected="selected"' : ''?>>6 - 25</option>
                                                            <option value="26-50" <?php echo isset($referred) && $referred['company_size'] == '26-50' ? 'selected="selected"' : ''?>>26 - 50</option>
                                                            <option value="51-100" <?php echo isset($referred) && $referred['company_size'] == '51-100' ? 'selected="selected"' : ''?>>51 - 100</option>
                                                            <option value="101-250" <?php echo isset($referred) && $referred['company_size'] == '101-250' ? 'selected="selected"' : ''?>>101 - 250</option>
                                                            <option value="251-500" <?php echo isset($referred) && $referred['company_size'] == '251-500' ? 'selected="selected"' : ''?>>251 - 500</option>
                                                            <option value="501+" <?php echo isset($referred) && $referred['company_size'] == '501+' ? 'selected="selected"' : ''?>>501+</option>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="field-row field-row-autoheight">
                                                    <?php $control_id = 'country'; ?>
                                                    <?php echo form_label('Country', $control_id); ?>
                                                    <select class="hr-form-fileds" id="country" name="country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="" selected="selected">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?php echo $active_country["sid"]; ?>" <?php echo isset($referred) && $referred['country'] == $active_country["country_name"] ? 'selected="selected"' : ''?>>
                                                                <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <p style="display: none;" id="state_id"><?php echo isset($referred) ? $referred["state"] : ''; ?></p>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="field-row field-row-autoheight">
                                                    <?php $control_id = 'state'; ?>
                                                    <?php echo form_label('State/Region', $control_id); ?>
                                                    <div class=" hr-select-dropdown">
                                                        <select class="hr-form-fileds" id="state" name="state">
                                                            <option value="">Select State</option>
                                                            <option value="">Please select your Country</option>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>City</label>
                                                    <input type="text" name="city" value="<?php echo $referred['city'];?>" class="hr-form-fileds">
                                                    <?php echo form_error("city"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Street</label>
                                                    <input type="text" name="street" value="<?php echo $referred['street'];?>" class="hr-form-fileds">
                                                    <?php echo form_error("street"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Zip Code</label>
                                                    <input type="text" name="zip_code" value="<?php echo $referred['zip_code'];?>" class="hr-form-fileds">
                                                    <?php echo form_error("zip_code"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row field-row-autoheight">
                                                    <label>Contact Number</label>
                                                    <input type="text" name="phone_number" value="<?php echo $referred['phone_number'];?>" class="hr-form-fileds">
                                                    <?php echo form_error("contact_number"); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <a class="black-btn" href="<?php echo site_url('manage_admin/referred_clients/view_details/'.$referred["sid"]);?>"> Cancel </a> 
                                                        <input type="submit" value="Update" class="site-btn text-right">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php } ?>
                                </div>
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
<script>
    $(document).ready(function () {
        
    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please select your Country</option>');
        } else {
            html = '<option value="">Select State</option>';
            allstates = states[val];
            
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + name + '">' + name + '</option>';
            }
            
            $('#state').html(html);
        }
    }
</script>