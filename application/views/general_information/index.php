<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$save_post_url = '';
$field_country = '';
$field_state = 'contact_state';
$field_city = '';
$field_zipcode = '';
$field_address = '';
$back_btn   = '';
$company_sid = $company_id;
$users_type = 'employee';
$users_sid = $employee['sid'];
$driv_license = isset($drivers_license_details['license_details']) ? $drivers_license_details['license_details'] : array();
$occu_license = isset($occupational_license_details['license_details']) ? $occupational_license_details['license_details'] : array();
$driv_license['license_file'] = isset($drivers_license_details['license_file']) ? $drivers_license_details['license_file'] : '';
$occu_license['license_file'] = isset($occupational_license_details['license_file']) ? $occupational_license_details['license_file'] : '';
$save_post_url = current_url();
//Field Names
$field_sid = 'employee_sid';
?>
<style>
    .start_animation {
        animation-name: icon_alert;
        animation-duration: 0.8s;
        animation-iteration-count: infinite;
    }

    .jsGeneralAssignDocument {
        margin-left: 10px;
    }

    @keyframes icon_alert {
        75% {
            color: #dc3545;
        }
    }
</style>
<div class="main jsmaincontent">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">General Information</h1>
                </div>
                <p class="text-blue">You can add/edit your Drivers License Details, Occupational License Details, Dependent Details and Emergency Contacts </p>
                <p class="text-blue"><b>Please review the Tap Pages</b></p>

                <div class="accordion-colored-header">
                    <div class="panel-group" id="accordion">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#driver_license"><span class="glyphicon glyphicon-plus"></span>driver license <strong data-id="drivers_license" class="jsGeneralAssignDocument"></strong></a>
                                </h4>
                            </div>
                            <div id="driver_license" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="jsNoteArea"></div>
                                    <div class="form-wrp">
                                        <form method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_drivers_license_information" />
                                            <input type="hidden" id="<?php echo $field_sid; ?>" name="<?php echo $field_sid; ?>" value="<?php echo $users_sid; ?>" />

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_type' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Type', $field_name); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>">
                                                                <option value="" selected="">Please Select</option>
                                                                <?php if (!empty($license_types)) { ?>
                                                                    <?php foreach ($license_types as $key => $license_type) { ?>
                                                                        <?php $default_selected = $key == $temp ? true : false; ?>
                                                                        <option <?php echo set_select($field_name, $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $license_type ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_authority' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Authority', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_class' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Class', $field_name); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>">
                                                                <option value="" selected="">Please Select</option>

                                                                <?php if (!empty($license_classes)) { ?>
                                                                    <?php foreach ($license_classes as $key => $license_class) { ?>
                                                                        <?php $default_selected = $key == $temp ? true : false; ?>
                                                                        <option <?php echo set_select($field_name, $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $license_class ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_number' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Number', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_issue_date' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? date('m/d/Y', strtotime(str_replace('-', '/', $driv_license[$field_name]))) : ''; ?>
                                                        <?php echo form_label('Issue Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="dr_' . $field_name . '" readonly=""'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'dob' ?>
                                                        <?php echo form_label('Date Of Birth', $field_name); ?>
                                                        <input type="text" name="dob" id="dob" readonly class="form-control datepicker" value="<?php echo isset($dob) ? date('m/d/Y', strtotime(str_replace('-', '/', $dob))) : ''; ?>">
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_expiration_date' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? date('m/d/Y', strtotime(str_replace('-', '/', $driv_license[$field_name]))) : ''; ?>
                                                        <?php echo form_label('Expiration Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="dr_' . $field_name . '" readonly=""'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 identi">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_indefinite' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php $default_selected = 'on' == $temp ? true : false; ?>
                                                        <label class="control control--checkbox">
                                                            Indefinite
                                                            <input <?php echo $default_selected == true ? 'checked="checked"' : ''; ?> name="<?php echo $field_name; ?>" id="Indefinite" type="checkbox">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_notes' ?>
                                                        <?php echo form_label('License Notes', $field_name); ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_textarea($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '" rows="3"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_file' ?>
                                                        <?php echo form_label('Upload Scanned ', $field_name); ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>

                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_dr_license_file">Update License Picture</span>
                                                            <input name="license_file" id="dr_license_file" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                                            <?= getFilePathForIframe($temp, true, [
                                                                'style="width: 50%"'
                                                            ]); ?>
                                                            <div class="img-thumbnail">
                                                                <?php if (empty($temp)) { ?>
                                                                    <span class="text-center text-success" style="font-size: 200px; display: inline-block; color: #3598dc;"><i class="fa fa-picture-o"></i></span>
                                                                <?php } else { ?>
                                                                    <!-- <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" /> -->
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <input class="btn btn-info" value="Update Driver License Info" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#occupational_license"><span class="glyphicon glyphicon-plus"></span>occupational license<strong data-id="occupational_license" class="jsGeneralAssignDocument"></strong></a>
                                </h4>
                            </div>

                            <div id="occupational_license" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="jsNoteArea"></div>
                                    <div class="form-wrp">
                                        <form id="form_occ_license" method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_occupational_license_information" />
                                            <input type="hidden" id="<?php echo $field_sid; ?>" name="<?php echo $field_sid; ?>" value="<?php echo $users_sid; ?>" />

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_type' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Type', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_authority' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Authority', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_number' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_label('License Number', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_file' ?>
                                                        <?php echo form_label('Upload Scanned ', $field_name); ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>

                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_license_file">Update License Picture</span>
                                                            <input name="license_file" id="license_file" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_issue_date' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? date('m/d/Y', strtotime(str_replace('-', '/', $occu_license[$field_name]))) : ''; ?>
                                                        <?php echo form_label('Issue Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="oc_' . $field_name . '" readonly="" '); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'dob' ?>
                                                        <?php echo form_label('Date Of Birth', $field_name); ?>
                                                        <input type="text" name="dob" id="ocdob" readonly class="form-control datepicker" value="<?php echo isset($dob) ? date('m/d/Y', strtotime(str_replace('-', '/', $dob))) : ''; ?>">
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_expiration_date' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? date('m/d/Y', strtotime(str_replace('-', '/', $occu_license[$field_name]))) : ''; ?>
                                                        <?php echo form_label('Expiration Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="oc_' . $field_name . '" readonly="" '); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 identi">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_indefinite' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php $default_selected = 'on' == $temp ? true : false; ?>
                                                        <label class="control control--checkbox">
                                                            Indefinite
                                                            <input <?php echo $default_selected == true ? 'checked="checked"' : ''; ?> name="<?php echo $field_name; ?>" id="Indefinite" type="checkbox">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_notes' ?>
                                                        <?php echo form_label('License Notes', $field_name); ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_textarea($field_name, set_value($field_name, $temp), 'class="form-control" id="' . $field_name . '" rows="3"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                                    <?php $field_name = 'license_file' ?>
                                                    <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                    <?= getFilePathForIframe($temp, true, [
                                                        'style="width: 50%"'
                                                    ]); ?>
                                                    <div class="img-thumbnail">
                                                        <?php if (empty($temp)) { ?>
                                                            <span class="text-center text-success" style="font-size: 200px; display: inline-block; color: #3598dc;"><i class="fa fa-picture-o"></i></span>
                                                        <?php } else { ?>

                                                        <?php } ?>
                                                    </div>

                                                    <br />
                                                    <br />
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <input class="btn btn-info" value="Update Occupational License Info" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#dependents"><span class="glyphicon glyphicon-plus"></span>Dependent Details<strong data-id="dependents" class="jsGeneralAssignDocument"></strong></a>
                                </h4>
                            </div>

                            <div id="dependents" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="jsNoteArea"></div>

                                    <?php if (!empty($dependents_arr)) { ?>
                                        <div class="table-responsive table-outer">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td class="col-lg-2">Name</td>
                                                        <td class="col-lg-2">Phone No.</td>
                                                        <td class="col-lg-4">Address</td>
                                                        <td class="col-lg-2">Relationship</td>
                                                        <td class="text-center col-lg-2" colspan="2">Actions</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($dependents_arr)) { ?>
                                                        <?php foreach ($dependents_arr as $dependent) { ?>
                                                            <tr>
                                                                <td><?php echo $dependent['first_name'] . ' ' . $dependent['last_name']; ?></td>
                                                                <td><?php echo $dependent['phone']; ?></td>
                                                                <td><?php echo $dependent['address']; ?></td>
                                                                <td><?php echo $dependent['relationship']; ?></td>
                                                                <td class="text-center">
                                                                    <a href="<?php echo base_url('general_info/edit_dependant_information/' . $dependent['sid']) ?>" class="btn btn-info btn-block">Edit</a>
                                                                </td>
                                                                <td class="text-center">
                                                                    <form autocomplete="off" id="form_delete_dependent_<?php echo $dependent['sid']; ?>" method="post" action="<?php echo current_url(); ?>" enctype="multipart/form-data">
                                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_dependent" />
                                                                        <input type="hidden" id="dependent_sid" name="dependent_sid" value="<?php echo $dependent['sid']; ?>" />
                                                                        <button type="button" class="btn btn-danger btn-block" onclick="func_delete_dependent(<?php echo $dependent['sid']; ?>);">Delete</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No dependent information found!</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group" style="margin-top: 20px;">
                                                <label class="control control--radio">
                                                    <input type="radio" class="havedependents" name="havedependents" value="1" checked="true">
                                                    <?php echo $dependents_yes_text; ?> &nbsp; <div class="control__indicator"></div> </label><br>
                                                <label class="control control--radio">
                                                    <input type="radio" class="havedependents" name="havedependents" value="0">
                                                    <?php echo $dependents_no_text ?> &nbsp; <div class="control__indicator"></div>
                                                </label>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-wrp" id="havedependantFormRow">
                                        <form id="dependantForm" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <input type="hidden" id="perform_action" name="perform_action" value="add_dependent" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                            <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">

                                                    <div class="form-group">
                                                        <?php $field_name = 'first_name'; ?>
                                                        <?php echo form_label('First Name <span class="required">*</span>', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'last_name'; ?>
                                                        <?php echo form_label('Last Name <span class="required">*</span>', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'address'; ?>
                                                        <?php echo form_label('Address', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'address_line'; ?>
                                                        <?php echo form_label('Address 2', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'Location_Country'; ?>
                                                        <?php $country_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                                        <?php echo form_label('Country:', $field_id); ?>
                                                        <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, 'Location_State')">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($active_countries as $active_country) { ?>
                                                                <?php $default_selected = $country_id == $active_country['sid'] ? true : false; ?>
                                                                <option <?php echo set_select($field_id, $active_country['sid'], $default_selected); ?> value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'Location_State'; ?>
                                                        <?php $state_id = ((isset($applicant_information[$field_id]) && !empty($applicant_information[$field_id])) ? $applicant_information[$field_id] : ''); ?>
                                                        <?php echo form_label('State:', $field_id); ?>
                                                        <select class="form-control" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
                                                            <?php if (empty($state_id)) { ?>
                                                                <option value="">Select State</option> <?php
                                                                                                    } else {
                                                                                                        foreach ($active_states[$country_id] as $active_state) { ?>
                                                                    <?php $default_selected = $state_id == $active_state['sid'] ? true : false; ?>
                                                                    <option <?php echo set_select($field_id, $active_state['sid'], $default_selected); ?> value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'city'; ?>
                                                        <?php echo form_label('City', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'postal_code'; ?>
                                                        <?php echo form_label('Postal Code', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'phone'; ?>
                                                        <?php echo form_label('Phone <span class="required">*</span>', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'birth_date'; ?>
                                                        <?php echo form_label('Date of Birth', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control datepicker" id="dependent_dob" readonly'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'relationship'; ?>
                                                        <?php echo form_label('Relationship <span class="required">*</span>', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'ssn'; ?>
                                                        <?php echo form_label('Social Security Number', $field_name); ?>
                                                        <?php echo form_input($field_name, '', 'class="form-control" id="' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'gender'; ?>
                                                        <?php echo form_label('Gender', $field_name); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" class="form-control">
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php $field_name = 'family_member'; ?>
                                                    <label for="<?php echo $field_name; ?>" class="control control--checkbox">
                                                        Add Family Members
                                                        <input name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" type="checkbox">
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <input class="btn btn-info" value="Add Dependent" type="submit">
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>


                                    <div class="form-wrp" id="donthavedependantFormRow">
                                        <form id="donthavedependantForm" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <input type="hidden" id="perform_action" name="perform_action" value="add_dependent_dont_have" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                            <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <input class="btn btn-info" value="Save" type="submit">
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#contacts"><span class="glyphicon glyphicon-plus"></span>Emergency Contacts<strong data-id="emergency_contacts" class="jsGeneralAssignDocument"></strong></a>
                                </h4>
                            </div>

                            <div id="contacts" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="jsNoteArea"></div>
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td class="col-lg-2">Name</td>
                                                    <td class="col-lg-2">Relationship</td>
                                                    <td class="col-lg-2">Phone No.</td>
                                                    <td class="col-lg-3">Address</td>
                                                    <td class="text-center col-lg-1">Priority</td>
                                                    <td class="text-center col-lg-2" colspan="2">Actions</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($emergency_contacts)) { ?>
                                                    <?php foreach ($emergency_contacts as $contact) { ?>
                                                        <tr>
                                                            <td><?php echo $contact['first_name'] . ' ' . $contact['last_name']; ?></td>
                                                            <td><?php echo $contact['Relationship']; ?></td>
                                                            <td><?php echo $contact['PhoneNumber']; ?></td>
                                                            <td><?php echo $contact['Location_Address']; ?></td>
                                                            <td class="text-center"><?php echo $contact['priority']; ?></td>
                                                            <td>
                                                                <a href="<?php echo base_url('general_info/edit_emergency_contacts/' . $contact['sid']) ?>" class="btn btn-info btn-block">Edit</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <form autocomplete="off" id="form_delete_emergency_contact_<?php echo $contact['sid'] ?>" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                                                    <input type="hidden" id="perform_action" name="perform_action" value="delete_emergency_contact" />
                                                                    <input type="hidden" id="contact_sid" name="contact_sid" value="<?php echo $contact['sid']; ?>" />
                                                                    <button type="button" onclick="func_delete_emergency_contact(<?php echo $contact['sid'] ?>);" class="btn btn-danger btn-block">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td class="text-center" colspan="6">No Emergency Contacts found!</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-wrp">
                                        <form id="add_emergency_contacts" action="<?php echo current_url(); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <input type="hidden" id="perform_action" name="perform_action" value="add_emergency_contact" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                            <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'first_name'; ?>
                                                        <?php echo form_label('First Name <span class="required">*</span>', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'last_name'; ?>
                                                        <?php echo form_label('Last Name <span class="required">*</span>', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'email';
                                                        $emailRequired = '';
                                                        if ($contactOptionsStatus['emergency_contact_email_status'] == 1) {
                                                            $emailRequired = ' <span class="required">*</span>';
                                                        }
                                                        ?>
                                                        <?php echo form_label('Email:' . $emailRequired, $field_id); ?>
                                                        <input type="email" class="form-control" data-rule-email="true" <?php echo $contactOptionsStatus['emergency_contact_email_status'] == 1 ? 'data-rule-required="true"' : ''  ?> name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" />
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php
                                                        $field_id = 'PhoneNumber';
                                                        $phoneNumberRequired = '';
                                                        $phoneNumberRequiredRule = '';
                                                        if ($contactOptionsStatus['emergency_contact_phone_number_status'] == 1) {
                                                            $phoneNumberRequired = ' <span class="required">*</span>';
                                                            $phoneNumberRequiredRule = ' data-rule-required="true"';
                                                        }
                                                        ?>
                                                        <?php echo form_label('Primary Number:' . $phoneNumberRequired, $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "' . $phoneNumberRequiredRule); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'contact_country'; ?>
                                                        <?php echo form_label('Country:', $field_id); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" onchange="getStates(this.value, <?php echo $states; ?>, '<?php echo $field_state; ?>')">
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($active_countries as $active_country) { ?>
                                                                    <option value="<?= $active_country["sid"]; ?>"> <?= $active_country["country_name"]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'contact_state'; ?>
                                                        <?php echo form_label('State:', $field_id); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" name="<?php echo $field_id ?>" id="<?php echo $field_id ?>">
                                                                <?php if (empty($state_id)) { ?>
                                                                    <option value="">Select State</option> <?php
                                                                                                        } else {
                                                                                                            foreach ($active_states[$country_id] as $active_state) { ?>
                                                                        <option value="<?= $active_state["sid"] ?>"><?= $active_state["state_name"] ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'city'; ?>
                                                        <?php echo form_label('City:', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'Location_ZipCode'; ?>
                                                        <?php echo form_label('Zipcode:', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group">
                                                        <?php $field_id = 'Location_Address'; ?>
                                                        <?php echo form_label('Address:', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'Relationship'; ?>
                                                        <?php echo form_label('Relationship: <span class="required">*</span>', $field_id); ?>
                                                        <?php echo form_input($field_id, '', 'class="form-control" data-rule-required="true" id="' . $field_id . ' "'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'priority'; ?>
                                                        <?php echo form_label('Set Priority: <span class="required">*</span>', $field_id); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="form-control" name="<?php echo $field_id; ?>" data-rule-required="true" id="<?php echo $field_id; ?>">
                                                                <option value="">Select Priority</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-wrp full-width mrg-top-20 text-right">
                                                <button type="submit" class="btn btn-info">Add Emergency Contact</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#direct_deposit"><span class="glyphicon glyphicon-plus"></span>Direct Deposit Information<strong data-id="direct_deposit" class="jsGeneralAssignDocument"></strong></a>
                                </h4>
                            </div>

                            <div id="direct_deposit" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="jsNoteArea"></div>
                                    <?php $this->load->view('direct_deposit/form'); ?>
                                    <?php //$this->load->view('direct_deposit/new_form'); 
                                    ?>
                                    <!-- <div class="form-wrp">
                                        <form id="form_update_bank_details" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                            <input type="hidden" id="perform_action" name="perform_action" value="update_bank_details" />
                                            <input type="hidden" id="users_type" name="users_type" value="<?php echo $users_type; ?>" />
                                            <input type="hidden" id="users_sid" name="users_sid" value="<?php echo $users_sid; ?>" />
                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'account_title'; ?>
                                                        <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                                        <?php echo form_label('Account Title', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'routing_transaction_number'; ?>
                                                        <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                                        <?php echo form_label('Routing/Transaction Number', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'account_number'; ?>
                                                        <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                                        <?php echo form_label('Checking/Saving Account Number', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_id = 'financial_institution_name'; ?>
                                                        <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                                        <?php echo form_label('Financial Institution (Bank) Name', $field_id); ?>
                                                        <?php echo form_input($field_id, set_value($field_id, $temp), 'class="form-control" id="' . $field_id . '"'); ?>
                                                        <?php echo form_error($field_id); ?>
                                                    </div>
                                                </div>

                                                <?php if (!empty($bank_details['voided_cheque']) && $bank_details['voided_cheque'] != NULL) { ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="well well-sm">
                                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $bank_details['voided_cheque'] ?>" alt="">
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <label>Voided Check </label>
                                                        <div class="upload-file form-control">
                                                            <span class="selected-file" id="name_picture">No file selected</span>
                                                            <input name="picture" id="picture" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="row">
                                                        <?php $field_id = 'account_type'; ?>
                                                        <?php $temp = isset($bank_details[$field_id]) && !empty($bank_details[$field_id]) ? $bank_details[$field_id] : ''; ?>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>Account Type</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-2">
                                                            <?php $default_checked = $temp == 'checking' ? true : false; ?>
                                                            <div class="checkbox-radio-row">
                                                                <label class="control control--radio">
                                                                    Checking
                                                                    <input <?php echo set_radio($field_id, 'checking', $default_checked); ?> name="<?php echo $field_id; ?>" id="account_type_checking" type="radio" value="checking" />
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-6 col-sm-2">
                                                            <?php $default_checked = $temp == 'savings' ? true : false; ?>
                                                            <div class="checkbox-radio-row">
                                                                <label class="control control--radio">
                                                                    Savings
                                                                    <input <?php echo set_radio($field_id, 'savings', $default_checked); ?> name="<?php echo $field_id; ?>" id="account_type_savings" type="radio" value="savings" />
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" value="<?= isset($enable_learbing_center) ? $enable_learbing_center : '' ?>" name="enable_learbing_center_flag">
                                            <div class="btn-wrp full-width text-right">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <input class="btn btn-info" id="add_edit_submit" value="<?= empty($next_btn) ? 'Save' : 'Save And Proceed Next'; ?>" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#equipment"><span class="glyphicon glyphicon-plus"></span>Equipment Info</a>
                                </h4>
                            </div>

                            <div id="equipment" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row row-flex">
                                        <?php
                                        if (sizeof($equipments) > 0) {
                                            foreach ($equipments as $equipment) {
                                                if (empty($equipment['equipment_details']) || $equipment['equipment_details'] == NULL) {
                                                    if (!empty($equipment['equipment_type'])) {
                                        ?>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <article class="listing-article no-image">
                                                                <div class="text">
                                                                    <h3>
                                                                        <?php echo ucwords($equipment['equipment_type']); ?>
                                                                    </h3>

                                                                    <div class="post-options">
                                                                        <ul>
                                                                            <li><strong>Brand Name:</strong> <?php
                                                                                                                echo ucwords($equipment['brand_name']); ?>
                                                                            </li>
                                                                            <?php if ($equipment['issue_date'] != NULL && !empty($equipment['issue_date']) && $equipment['issue_date'] != '0000-00-00 00:00:00') { ?>
                                                                                <br>
                                                                                <li><strong>Date Assigned:</strong> <?php echo date_with_time($equipment['issue_date']); ?></li>
                                                                            <?php }
                                                                            if ($equipment['model'] != NULL && !empty($equipment['model'])) { ?>
                                                                                <br>
                                                                                <li><strong>Model:</strong> <?php echo $equipment['model']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['imei_no'] != NULL && !empty($equipment['imei_no'])) { ?>
                                                                                <br>
                                                                                <li><strong>IMEI:</strong> <?php echo $equipment['imei_no']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['product_id'] != NULL && !empty($equipment['product_id'])) { ?>
                                                                                <br>
                                                                                <li><strong>Product Id:</strong> <?php echo $equipment['product_id']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['specification'] != NULL && !empty($equipment['specification'])) { ?>
                                                                                <br>
                                                                                <li><strong>Specification:</strong> <?php echo $equipment['specification']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['vin_number'] != NULL && !empty($equipment['vin_number'])) { ?>
                                                                                <br>
                                                                                <li><strong>Engine Number:</strong> <?php echo $equipment['vin_number']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['transmission_type'] != NULL && !empty($equipment['transmission_type'])) { ?>
                                                                                <br>
                                                                                <li><strong>Transmission Type:</strong> <?php echo $equipment['transmission_type']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['fuel_type'] != NULL && !empty($equipment['fuel_type'])) { ?>
                                                                                <br>
                                                                                <li><strong>Fuel Type:</strong> <?php echo $equipment['fuel_type']; ?></li>
                                                                            <?php }
                                                                            if ($equipment['serial_number'] != NULL && !empty($equipment['serial_number'])) { ?>
                                                                                <br>
                                                                                <li><strong>Serial Number:</strong> <?php echo $equipment['serial_number']; ?></li>
                                                                            <?php } ?>
                                                                                 <br>
                                                                            <li><strong>Acknowledge:</strong> <?php echo $equipment['acknowledgement_flag'] ? 'Yes' : 'No'; ?></li>


                                                                        </ul>
                                                                    </div>
                                                                    <?php if ($equipment['notes'] != NULL && !empty($equipment['notes'])) { ?>
                                                                        <div class="full-width announcement-des" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                                            <strong>Notes:</strong>
                                                                            <?php echo !empty($equipment['notes']) ? $equipment['notes'] : 'N/A'; ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <div class="post-options">
                                                                        <a href="<?php echo base_url('general_info/view_equipment_information/' . $equipment['sid']) ?>" class="btn btn-info btn-block">View Detail</a>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        </div>
                                            <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            ?>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                No Equipment Assigned
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($flow) { ?>
                            <!-- Clair -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#clair"><span class="glyphicon glyphicon-plus"></span>Clair<strong data-id="clair" class="jsGeneralAssignDocument"></strong></a>
                                    </h4>
                                </div>

                                <div id="clair" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="jsNoteArea"></div>
                                        <iframe src="<?= $flow; ?>" style="width: 100%; height: 800px; border: 0;"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        <?php if (isDontHaveDependens($company_sid, $users_sid, $users_type) > 0) { ?>
            $('#donthavedependantFormRow').show();
            $('#havedependantFormRow').hide();
            $("input[name=havedependents][value='0']").prop("checked", true);
            $("input[name=havedependents][value='1']").prop("checked", false);

        <?php  } else { ?>
            $('#donthavedependantFormRow').hide();
            $('#havedependantFormRow').show();
            $("input[name=havedependents][value='1']").prop("checked", true);
            $("input[name=havedependents][value='0']").prop("checked", false);

        <?php } ?>


        $('#add_emergency_contacts').validate();

        $('#form_license_info').validate({
            rules: {
                license_file: {
                    accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }
            },
            messages: {
                license_file: {
                    accept: 'Please select an image or pdf file.'
                }
            }
        });

        $('#oc_license_issue_date').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function(selectedDate) {
                $('#oc_license_expiration_date').datepicker('option', 'minDate', selectedDate);
            }
        });

        $('#oc_license_expiration_date').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+100",
            onSelect: function(selectedDate) {
                $('#oc_license_issue_date').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('#ocdob').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selectedDate) {
                $('#ocdob').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('#dr_license_issue_date').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function(selectedDate) {
                $('#dr_license_expiration_date').datepicker('option', 'minDate', selectedDate);
            }
        });

        $('#dr_license_expiration_date').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+100",
            onSelect: function(selectedDate) {
                $('#dr_license_issue_date').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('#dob').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selectedDate) {
                $('#dob').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('#dependent_dob').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selectedDate) {
                $('#dependent_dob').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('input[type=file]').on('change', function() {
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    function func_delete_dependent(dependent_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you sure you want to delete this dependent?',
            function() {
                $('#form_delete_dependent_' + dependent_sid).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    function func_delete_emergency_contact(contact_id) {
        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to delete this contact?',
            function() {
                $('#form_delete_emergency_contact_' + contact_id).submit();
            },
            function() {
                alertify.error('Cancelled!');
            });
    }

    $(document).ready(function() {
        $('#dependantForm').validate({
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                phone: {
                    required: true
                },
                relationship: {
                    required: true
                }
            },
            messages: {
                first_name: {
                    message: 'First Name is required'
                },
                last_name: {
                    message: 'Last Name is required'
                },
                phone: {
                    message: 'Phone number is required'
                },
                relationship: {
                    message: 'Relationship is required'
                }
            }
        });

        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50"
        }).val();
    });

    function getStates(val, states, select_id) {
        var html = '';

        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';

            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }

            $('#' + select_id).html(html);
            $('#' + select_id).trigger('change');
        }
    }
</script>

<style type="text/css">
    .identi {
        margin-top: 30px;
    }
</style>

<script>
    <?php if ($keyIndex != null) { ?>
        $(document).ready(() => {
            let obj = {
                'drivers_license': 'driver_license',
                'occupational_license': 'occupational_license',
                'direct_deposit': 'direct_deposit',
                'emergency_contacts': 'contacts',
                'dependents': 'dependents'
            };
            //
            let k = obj["<?= $keyIndex; ?>"];
            $(`a[href="#${k}"]`).trigger('click');
        });
    <?php } ?>
    // 
    let generalDocuments = <?= json_encode($generalAssignments); ?>;
    //
    if (Object.keys(generalDocuments).length > 0) {
        $('.jsGeneralAssignDocument').map(function() {
            //
            let slug = $(this).data('id');
            //
            if (generalDocuments[slug] !== undefined) {
                $(this)
                    .prop('title', 'Assigned Document')
                    .html(`<i class="fa fa-clipboard start_animation"></a>`);
                //
                if (generalDocuments[slug].note != '' && generalDocuments[slug].note != null) {
                    //
                    let row = `
                    <div class="panel panel-info">
                        <div class="panel-heading">Note:</div>
                        <div class="panel-body" style="word-break: break-all;">
                            ${generalDocuments[slug].note}
                        </div>
                    </div>
                    `;
                    $(this).closest('.panel').find('.jsNoteArea').append(row);
                }
            }
        });
    }

    //
    $(".havedependents").click(function() {
        if ($(this).val() == '1') {
            $('#havedependantFormRow').show();
            $('#donthavedependantFormRow').hide();
        } else {
            $('#havedependantFormRow').hide();
            $('#donthavedependantFormRow').show();
        }
    });
</script>