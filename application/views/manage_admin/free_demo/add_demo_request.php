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
                                        <h1 class="page-title"><i class="glyphicon glyphicon-film"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <form method="post" action="" class="form" id="demo-form">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'first_name'; ?>
                                                    <label for="full_name">First Name <span class="hr-required">*</span></label>
                                                    <?php echo form_input($control_id, isset($message) ? $message["first_name"] : '', 'id="' . $control_id . '" class="form-control"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'last_name'; ?>
                                                    <label for="full_name">Last Name <span class="hr-required">*</span></label>
                                                    <?php echo form_input($control_id, isset($message) ? $message["last_name"] : '', 'id="' . $control_id . '" class="form-control"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'job_role'; ?>
                                                    <?php echo form_label('Title', $control_id); ?>
                                                    <?php echo form_input($control_id, isset($message) ? $message["job_role"] : '', 'id="' . $control_id . '" class="form-control"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'email'; ?>
                                                    <?php echo form_label('Email', $control_id); ?>
                                                    <?php echo form_input($control_id, isset($message) ? $message["email"] : '', 'id="' . $control_id . '" class="form-control" name="email"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'phone_number'; ?>
                                                    <?php echo form_label('Phone Number', $control_id); ?>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <span class="input-group-text">+1</span>
                                                        </div>
                                                        <?php echo form_input($control_id, isset($message) ? phonenumber_format($message["phone_number"], true) : '', 'id="' . $control_id . '" class="form-control js-phone"'); ?>
                                                    </div>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'company_name'; ?>
                                                    <label for="full_name">Company Name <span class="hr-required">*</span></label>
                                                    <?php echo form_input($control_id, isset($message) ? $message["company_name"] : '', 'id="' . $control_id . '" class="form-control"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'company_size'; ?>
                                                    <?php echo form_label('Company Size', $control_id); ?>
                                                    <select class="form-control" name="company_size">
                                                        <option value="" selected="selected">Select Company Size</option>
                                                        <option value="1-5" <?php echo isset($message) && $message['company_size'] == '1-5' ? 'selected="selected"' : ''?>>1 - 5</option>
                                                        <option value="6-25" <?php echo isset($message) && $message['company_size'] == '6-25' ? 'selected="selected"' : ''?>>6 - 25</option>
                                                        <option value="26-50" <?php echo isset($message) && $message['company_size'] == '26-50' ? 'selected="selected"' : ''?>>26 - 50</option>
                                                        <option value="51-100" <?php echo isset($message) && $message['company_size'] == '51-100' ? 'selected="selected"' : ''?>>51 - 100</option>
                                                        <option value="101-250" <?php echo isset($message) && $message['company_size'] == '101-250' ? 'selected="selected"' : ''?>>101 - 250</option>
                                                        <option value="251-500" <?php echo isset($message) && $message['company_size'] == '251-500' ? 'selected="selected"' : ''?>>251 - 500</option>
                                                        <option value="501+" <?php echo isset($message) && $message['company_size'] == '501+' ? 'selected="selected"' : ''?>>501+</option>
                                                    </select>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'country'; ?>
                                                    <?php echo form_label('Country', $control_id); ?>
                                                    <select class="form-control" id="country" name="country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="" selected="selected">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?php echo $active_country["sid"]; ?>" <?php echo isset($message) && $message['country'] == $active_country["country_name"] ? 'selected="selected"' : ''?>>
                                                                <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <p style="display: none;" id="state_id"><?php echo isset($message) ? $message["state"] : ''; ?></p>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'state'; ?>
                                                    <?php echo form_label('State/Region', $control_id); ?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="form-control" id="state" name="state">
                                                            <option value="">Select State</option>
                                                            <option value="">Please select your Country</option>
                                                        </select>
                                                    </div>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>City</label>
                                                    <input type="text" name="city" value="<?php echo isset($message) && !empty($message['city']) ? $message['city'] : ''?>" class="form-control">
                                                    <?php echo form_error("city"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>Street</label>
                                                    <input type="text" name="street" value="<?php echo isset($message) && !empty($message['street']) ? $message['street'] : ''?>" class="form-control">
                                                    <?php echo form_error("street"); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label>Zip Code</label>
                                                    <input type="text" name="zip_code" value="<?php echo isset($message) && !empty($message['zip_code']) ? $message['zip_code'] : ''?>" class="form-control">
                                                    <?php echo form_error("zip_code"); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php $control_id = 'client_source'; ?>
                                                    <?php echo form_label('How did you hear about us?', $control_id); ?>
                                                    <?php echo form_input($control_id, isset($message) ? $message['client_source'] : '', 'id="' . $control_id . '" class="form-control"'); ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <?php echo form_label('Timezone'); ?>

                                                    <?=
                                                        timezone_dropdown(isset($message) ? $message['timezone'] : '', array(
                                                            'class' => 'form-control js-timezone',
                                                            'id' => 'timezone',
                                                            'name' => 'timezone',
                                                            'style' => 'padding: 0;'
                                                        ));
                                                    ?>
                                                    <?php echo form_error($control_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($this->uri->segment(3) != 'add_demo_request') {?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <?php $control_id = 'client_message'; ?>
                                                        <?php echo form_label('Your Message?', $control_id); ?>
                                                        <textarea id="client_message" name="client_message" class="form-control " rows="10" cols="5"><?= isset($message) ? $message['client_message'] : ''?></textarea>
                                                        <?php echo form_error($control_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group autoheight">
                                                         <div class="form-group autoheight">
                                                            <label>Subscribe to our weekly newsletter!</label>
                                                            <label class="control control--radio" style="margin-top: 20px;">
                                                                Yes
                                                                <input <?= isset($message) & $message['newsletter_subscribe'] == 1 ? 'checked="checked"' : ''?> class="video_source" type="radio" name="newsletter_subscribe" value="1">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio" style="margin-top: 20px;">
                                                                No
                                                                <input <?= isset($message) & $message['newsletter_subscribe'] == 0 ? 'checked="checked"' : ''?> class="video_source" type="radio" name="newsletter_subscribe" value="0">
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group"> 
                                                    <?php if ($this->uri->segment(2) == 'edit_demo_request') { ?>
                                                        <a class="black-btn" href="<?php echo site_url('manage_admin/enquiry_message_details').'/'.$message['sid']; ?>"> Cancel </a> 
                                                    <?php } else if ($this->uri->segment(2) == 'referred_clients') { ?>
                                                        <?php if ($this->uri->segment(3) == 'add_demo_request') { ?>
                                                            <a class="black-btn" href="<?php echo site_url('manage_admin/referred_clients'); ?>"> Cancel </a> 
                                                        <?php } else { ?>
                                                            <a class="black-btn" href="<?php echo site_url('manage_admin/referred_clients/enquiry_message_details').'/'.$message['sid']; ?>"> Cancel </a>
                                                        <?php } ?>    
                                                    <?php } else { ?>
                                                        <a class="black-btn" href="<?php echo site_url('manage_admin/free_demo'); ?>"> Cancel </a> 
                                                    <?php } ?>
                                                    <input type="submit" value="<?php echo $submit_button; ?>" onclick="validate_form()" class="site-btn text-right">
                                                    <?php if ($this->uri->segment(2) == 'edit_demo_request' || $this->uri->segment(3) == 'edit_demo_request') { ?>
                                                        <input type="button" value="Add an Additional Contact" onclick="add_contact()" class="site-btn">
                                                        <input type="button" value="Add Primary Person Additional Phone Number" onclick="add_Primary_number()" class="site-btn">
                                                        <input type="button" value="Add Primary Person Additional Email" onclick="add_Primary_email()" class="site-btn">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php if ($this->uri->segment(2) == 'edit_demo_request' || $this->uri->segment(3) == 'edit_demo_request') { ?>
                                <!-- Primary Person Phone Number -->
                                <?php if (!empty($pp_phone_numbers)) { ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Primary Person Additional Phone Number</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-9">Phone Number</th>
                                                            <th class="col-lg-3 text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($pp_phone_numbers as $key => $pp_phone_number) { ?>
                                                            <tr>
                                                                <td><?=phonenumber_format($pp_phone_number['phone_number']);?></td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-info btn-sm" href="javascript:;" id="edit_additional_contact" name="delete" onclick="edit_pp_contact(<?php echo $pp_phone_number['sid']; ?>);">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                    <a class="btn btn-danger btn-sm" href="javascript:;" id="delete_additional_contact" name="delete" onclick="delete_contact(<?php echo $pp_phone_number['sid']; ?>);">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>    
                                        </div>
                                    </div>
                                <?php } ?> 

                                <!-- Primary Person Email -->
                                <?php if (!empty($pp_emails)) { ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Primary Person Additional Email</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-lg-9">Email</th>
                                                            <th class="col-lg-3 text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($pp_emails as $key => $pp_email) { ?>
                                                            <tr>
                                                                <td><?php echo $pp_email['email']; ?></td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-info btn-sm" href="javascript:;" id="edit_additional_contact" name="delete" onclick="edit_pp_contact(<?php echo $pp_email['sid']; ?>);">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                    <a class="btn btn-danger btn-sm" href="javascript:;" id="delete_additional_contact" name="delete" onclick="delete_contact(<?php echo $pp_email['sid']; ?>);">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>    
                                        </div>
                                    </div>
                                <?php } ?> 

                                <!-- Additional Contact Person -->
                                <?php if (!empty($additional_contacts)) { ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Additional Contact Person</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                                <th class="col-lg-3">Name</th>
                                                                <th class="col-lg-3">Email</th>
                                                                <th class="col-lg-3">Phone Number</th>
                                                                <th class="col-lg-3 text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($additional_contacts as $key => $contact_info) { ?>
                                                            <tr>
                                                                <td><?php echo $contact_info['full_name']; ?></td>
                                                                <td><?php echo $contact_info['email']; ?></td>
                                                                <td><?=phonenumber_format($contact_info['phone_number']);?></td>
                                                                <td class="text-center">
                                                                    <?php if ($contact_info['primary_person'] == 0) { ?>
                                                                        <a class="btn btn-info btn-sm" href="javascript:;" id="edit_additional_contact" name="delete" onclick="edit_contact(<?php echo $contact_info['sid']; ?>);">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    <?php } else if ($contact_info['primary_person'] == 1) { ?>
                                                                        <a class="btn btn-info btn-sm" href="javascript:;" id="edit_additional_contact" name="delete" onclick="edit_pp_contact(<?php echo $contact_info['sid']; ?>);">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <a class="btn btn-danger btn-sm" href="javascript:;" id="delete_additional_contact" name="delete" onclick="delete_contact(<?php echo $contact_info['sid']; ?>);">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>    
                                        </div>
                                    </div>
                                <?php } ?>  

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- ADD Modal -->
<div id="additional_contact" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add an Additional Contact</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" class="form" id="additional_contact_form">
                    <input type="hidden" name="demo_sid" value="<?php echo $message['sid']; ?>">
                    <input type="hidden" name="contact_type" value="both">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="full_name">Full Name <span class="hr-required">*</span></label>
                                <input type="text" name="full_name" id="additional_contact_full_name" class="form-control">
                                <span class="hr-required" id="error_name_message"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text">+1</span>
                                    </div>
                                    <input type="text" name="phone_number" id="additional_contact_phone_number" class="form-control js-phone">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="button" value="Save Contact" onclick="validate_contact_form()" class="site-btn pull-right">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit_additional_contact_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit an Additional Contact</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" class="form" id="edit_contact_form">
                    <input type="hidden" name="sid" id="contact_sid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="full_name">Full Name <span class="hr-required">*</span></label>
                                <input type="text" name="full_name" id="edit_full_name" class="form-control">
                                <span class="hr-required" id="edit_error_name"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text">+1</span>
                                    </div>
                                    <input type="text" name="phone_number" id="edit_phone_number" class="form-control js-phone">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="button" value="Update Contact" onclick="validate_edit_contact_form()" class="site-btn pull-right">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Primary Person Contact Modal -->
<div id="pp_additional_contact_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="pp_modal_title"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" class="form" id="pp_contact_form">
                    <input type="hidden" name="demo_sid" value="<?php echo $message['sid']; ?>">
                    <input type="hidden" name="contact_type" id="pp_contact_type" value="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" value="<?php echo $message["first_name"].' '.$message["last_name"]; ?>" class="form-control" readonly>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="phone_section">
                            <div class="form-group">
                                <label for="phone_number">Phone Number <span class="hr-required">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text">+1</span>
                                    </div>
                                    <input type="text" name="phone_number" id="pp_phone_number" class="form-control js-phone">
                                </div>
                                <span class="hr-required" id="pp_error_phone_number"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="email_section">
                            <div class="form-group">
                                <label for="email">Email <span class="hr-required">*</span></label>
                                <input type="email" name="email" id="pp_email" class="form-control">
                                <span class="hr-required" id="pp_error_email"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="button" id="pp_save_btn" onclick="validate_pp_contact_form()" class="site-btn pull-right">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Primary Person Contact Modal -->
<div id="pp_edit_additional_contact_model" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="pp_edit_modal_title">Edit Primary Person Additional Contact</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="" class="form" id="pp_edit_contact_form">
                    <input type="hidden" name="pp_contact_sid" id="pp_contact_sid">
                    <input type="hidden" name="contact_type" id="pp_edit_contact_type" value="">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="edit_pp_full_name" class="form-control" readonly>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="edit_phone_section">
                            <div class="form-group">
                                <label for="phone_number">Phone Number <span class="hr-required">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <span class="input-group-text">+1</span>
                                    </div>
                                    <input type="text" name="phone_number" id="pp_edit_phone_number" class="form-control js-phone">
                                </div>
                                <span class="hr-required" id="pp_error_edit_phone_number"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="edit_email_section">
                            <div class="form-group">
                                <label for="email">Email <span class="hr-required">*</span></label>
                                <input type="email" name="email" id="pp_edit_email" class="form-control">
                                <span class="hr-required" id="pp_error_edit_email"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="button" id="pp_update_btn" onclick="validate_pp_edit_contact_form()" class="site-btn pull-right">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        var myid = $('#state_id').html();
        setTimeout(function () {
            $("#country").change();
        }, 1000);
        if (myid) {
            setTimeout(function () {
                $('#state').val(myid);
            }, 1200);
        }
        CKEDITOR.replace('client_message');
    });
    
    function validate_form() {
        $("#demo-form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- .]+$/
                },
                company_name: {
                    required: true,
                }
            },
            messages: {
                first_name: {
                    required: 'First Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                company_name: {
                    required: 'Company Name required'
                },
                email: 'Please provide valid email address',
                phone_number: 'Please provide valid number',
                job_role: 'Please provide your Job Role',
                client_source: {
                    required: 'Please tell how did you find about us.',
                    pattern: 'Letters, numbers, and dashes only please'
                }
            },
            submitHandler: function (form) {
                var _pb = $('#phone_number');
                //
                var is_error = false;
                // Check for mobile number
                if(_pb.val() != '' && _pb.val().trim() != '(___) ___-____' && !fpn(_pb.val(), '', true)){
                    alertify.alert('Error!', 'Invalid phone number.', function(){ return; });
                    is_error = true;
                    return;
                }

                if (is_error === false) {
                    // Remove and set phone extension
                    $('#js-phonenumber').remove();

                    // Check the fields
                    if(_pb.val().trim() == '(___) ___-____') _pb.val('');
                    else $("#demo-form").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+(_pb.val().replace(/\D/g, ''))+'" />');

                    form.submit();
                }
            }
        });
    }

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

    function add_contact() {
        $('#additional_contact').appendTo("body").modal('show');
        $('#additional_contact_phone_number').val('(___) ___-____');
    }

    var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);

    function validate_contact_form () {
        var name = $('#additional_contact_full_name').val();

        phone_regex.lastIndex = 0;
        var phone = $('#additional_contact_phone_number').val().trim();
        
    
        if (name == '') { 
            $('#error_name_message').text("Full Name is Required");
        }else if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
            alertify.alert('Error!', 'Contact number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
            e.preventDefault();
        } else {
            if(phone != '' && phone != '(___) ___-____') $('#additional_contact_form').append('<input type="hidden" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
            var myurl = "<?= base_url() ?>manage_admin/free_demo/add_additional_contact";
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
               
                data: $('#additional_contact_form').serialize(),
                url: myurl,

                success: function(data){
                    $('#additional_contact').appendTo("body").modal('hide');
                    alertify.success('Additional Contact add Successfully');
                    location.reload();
                },
                error: function(){

                }
            });
        } 
    }

    function edit_contact(sid) {
        var myurl = "<?= base_url() ?>manage_admin/free_demo/get_additional_contact/"+sid;
    
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                $('#edit_additional_contact_model').appendTo("body").modal('show');
                var obj = jQuery.parseJSON(data);
                var contact_sid = obj.sid;
                var full_name = obj.full_name;
                var email = obj.email;
                var phone_number = obj.phone_number;
                $("#contact_sid").val(contact_sid);
                $("#edit_full_name").val(full_name);
                $("#edit_email").val(email);
                //
                var tmp = fpn(phone_number.replace('+1', ''));
                if(typeof(tmp) === 'object') phone_number = tmp.number;
                else phone_number = tmp;

                $("#edit_phone_number").val();
            },
            error: function (data) {

            }   
        });
    }

    function validate_edit_contact_form () {
        var name = $('#edit_full_name').val();

        phone_regex.lastIndex = 0;
        var phone = $('#edit_phone_number').val().trim();
    
        if (name == '') { 
            $('#edit_error_name').text("Full Name is Required");
        }else if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
            alertify.alert('Error!', 'Contact number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
            e.preventDefault();
        } else {
            if(phone != '' && phone != '(___) ___-____') $('#edit_contact_form').append('<input type="hidden" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
            var myurl = "<?= base_url() ?>manage_admin/free_demo/edit_additional_contact";
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
               
                data: $('#edit_contact_form').serialize(),
                url: myurl,

                success: function(data){
                    $('#edit_additional_contact_model').appendTo("body").modal('hide');
                    alertify.success('Additional Contact Edit Successfully');
                    location.reload();
                },
                error: function(){

                }
            });
        } 
    }

    function delete_contact (sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to delete this additional contact?',
            function () {
                var myurl = "<?= base_url() ?>manage_admin/free_demo/delete_additional_contact/"+sid;
                $.ajax({
                    type: "GET",
                    url: myurl,
                    async : false,
                    success: function (data) {
                        alertify.success('Additional Contact Delete Successfully');
                        location.reload();
                    },
                    error: function (data) {

                    }   
                });
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function add_Primary_number() {
        $('#pp_modal_title').text("Add Primary Person Additional Phone Number");
        $('#pp_contact_type').val("phone");
        $('#phone_section').show();
        $('#email_section').hide();
        $('#pp_phone_number').val("(___) ___-____");
        $('#pp_error_phone_number').text("");
        $('#pp_save_btn').val('Save Phone Number');
        $('#pp_additional_contact_model').appendTo("body").modal('show');
    }

    function add_Primary_email() {
        $('#pp_modal_title').text("Add Primary Person Additional Email");
        $('#pp_contact_type').val("email");
        $('#phone_section').hide();
        $('#email_section').show();
        $('#pp_email').val("");
        $('#pp_error_email').text("");
        $('#pp_save_btn').val('Save Email');
        $('#pp_additional_contact_model').appendTo("body").modal('show');
    }

    function validate_pp_contact_form () {
        var contact_type = $('#pp_contact_type').val();

        $('#txt_phonenumber').remove();

        if (contact_type == 'phone') {
            var phone = $('#pp_phone_number').val();

            phone_regex.lastIndex = 0;
    
            if(phone == '' || phone == '(___) ___-____'){
                alertify.alert('Error!', 'Phone number is required.', function(){ return; });
                return;
            }

            if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
                alertify.alert('Error!', 'Phone number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
                return;
            }
            //
            if(phone != '' && phone != '(___) ___-____') $('#pp_contact_form').append('<input type="hidden" id="txt_phonenumber" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
            //
            submit_primary_person_contact();
        } else if (contact_type == 'email') {
            var email = $('#pp_email').val();
            if (email == '') { 
                $('#pp_error_email').text("Email is Required");
            } else {
                submit_primary_person_contact();
            }  
        }
    }

    function submit_primary_person_contact () {
        var myurl = "<?= base_url() ?>manage_admin/free_demo/add_additional_pp_contact";
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
           
            data: $('#pp_contact_form').serialize(),
            url: myurl,

            success: function(data){
                $('#pp_additional_contact_model').appendTo("body").modal('hide');
                alertify.success('Primary Person Additional Contact Add Successfully');
                location.reload();
            },
            error: function(){

            }
        });
    }

    function edit_pp_contact (sid) {
        var myurl = "<?= base_url() ?>manage_admin/free_demo/get_additional_contact/"+sid;
    
        $.ajax({
            type: "GET",
            url: myurl,
            async : false,
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                var contact_sid = obj.sid;
                var full_name = obj.full_name;
                var email = obj.email;
                var phone_number = obj.phone_number;
                var contact_type = obj.contact_type;
                if (contact_type == 'phone') {
                    $('#pp_edit_modal_title').text("Edit Primary Person Additional Phone Number");
                    $('#pp_edit_contact_type').val("phone");
                    $('#edit_phone_section').show();
                    $('#edit_email_section').hide();
                    $("#pp_contact_sid").val(contact_sid);
                    $("#edit_pp_full_name").val(full_name);
                    var tmp = fpn(phone_number.replace('+1', ''));
                    phone_number = typeof(tmp) === 'object' ? tmp.number : tmp;
                    $("#pp_edit_phone_number").val(phone_number);
                    $('#pp_error_email').text("");
                    $('#pp_update_btn').val('Update Phone Number');
                    $('#pp_edit_additional_contact_model').appendTo("body").modal('show');   
                } else if (contact_type == 'email') {
                    $('#pp_edit_modal_title').text("Edit Primary Person Additional Email");
                    $('#pp_edit_contact_type').val("email");
                    $('#edit_phone_section').hide();
                    $('#edit_email_section').show();
                    $("#pp_contact_sid").val(contact_sid);
                    $("#edit_pp_full_name").val(full_name);
                    $("#pp_edit_email").val(email);
                    $('#pp_error_email').text("");
                    $('#pp_update_btn').val('Update Email');
                    $('#pp_edit_additional_contact_model').appendTo("body").modal('show');
                }
            },
            error: function (data) {

            }   
        });
    }

    function validate_pp_edit_contact_form () {
        var contact_type = $('#pp_edit_contact_type').val();

        if (contact_type == 'phone') {
            var phone = $('#pp_edit_phone_number').val();

            $('#txt_phonenumber').remove();

            phone_regex.lastIndex = 0;
    
            if(phone == '' || phone == '(___) ___-____'){
                $('#pp_error_edit_phone_number').text('Phone number is required.');
                return;
            }

            if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
                $('#pp_error_edit_phone_number').text('Phone number is invalid. Please use following format (XXX) XXX-XXXX.');
                return;
            }
            //
            if(phone != '' && phone != '(___) ___-____') $('#pp_edit_phone_number').append('<input type="hidden" id="txt_phonenumber" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
            //
            update_primary_person_contact();
        } else if (contact_type == 'email') {
            var email = $('#pp_edit_email').val();
            if (email == '') { 
                $('#pp_error_edit_email').text("Email is Required");
            } else {
                update_primary_person_contact();
            }  
        }
    }

    function update_primary_person_contact () {
        var myurl = "<?= base_url() ?>manage_admin/free_demo/edit_pp_additional_contact";
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
           
            data: $('#pp_edit_contact_form').serialize(),
            url: myurl,

            success: function(data){
                $('#pp_additional_contact_model').appendTo("body").modal('hide');
                alertify.success('Primary Person Additional Contact Add Successfully');
                location.reload();
            },
            error: function(){

            }
        });
    }

    $('.js-timezone').select2();
</script>



<script>

    // var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);
    // $('form.js-form').submit(function(e) {
    //     phone_regex.lastIndex = 0;
    //     var phone = $('#PhoneNumber').val().trim();
    //     if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
    //         alertify.alert('Error!', 'Contact number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
    //         e.preventDefault();
    //     }
    //     if(phone != '' && phone != '(___) ___-____') $(this).append('<input type="hidden" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
    // });

    $.each($('.js-phone'), function() {
        var v = fpn($(this).val().trim());
        if(typeof(v) === 'object'){
            $(this).val( v.number );
            setCaretPosition($(this), v.cur);
        }else $(this).val( v );
    });


    $('.js-phone').keyup(function(e){
        var val = fpn($(this).val().trim());
        if(typeof(val) === 'object'){
            $(this).val( val.number );
            setCaretPosition(this, val.cur);
        }else $(this).val( val );
    })


    // Format Phone Number
    // @param phone_number
    // The phone number string that 
    // need to be reformatted
    // @param format
    // Match format 
    // @param is_return
    // Verify format or change format
    function fpn(phone_number, format, is_return) {
        // 
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else{
            var af = '', an = '', cur = 1;
            if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
            if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
            if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
            if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
            if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
            if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
            if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
            if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
            if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
            if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

            if(is_return) return match === null ? false : true;

            return { number: default_number.replace(af, an), cur: cur };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if(elem != null) {
            if(elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            }
            else {
                if(elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }
</script>

<style>
    /* Remove the radius from left fro phone field*/
    .input-group input{ border-top-left-radius: 0; border-bottom-left-radius: 0; }
</style>