<?php
$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$field_country = '';
$field_state = '';
$field_city = '';
$field_zipcode = '';
$field_address = '';
$back_btn   = '';

if (isset($applicant)) {

    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/my_profile/' . $unique_sid);
    $driv_license = isset($drivers_license_details['license_details']) ? $drivers_license_details['license_details'] : array();
    $occu_license = isset($occupational_license_details['license_details']) ? $occupational_license_details['license_details'] : array();
    $driv_license['license_file'] = isset($drivers_license_details['license_file']) ? $drivers_license_details['license_file'] : '';
    $occu_license['license_file'] = isset($occupational_license_details['license_file']) ? $occupational_license_details['license_file'] : '';
    $save_post_url = current_url();
    //Field Names
    $field_sid = 'applicant_sid';
    $back_btn   = 'Review Previous Step';
    $skip_btn   = '<a href="'.base_url('onboarding/e_signature/' . $unique_sid).'" class="btn btn-warning"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $proceed_btn   = '<a href="'.base_url('onboarding/e_signature/' . $unique_sid).'" class="btn btn-success"> Proceed To Next<i class="fa fa-angle-right"></i></a>';

} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $driv_license = $license_info;
    $occu_license = $license_info;
    $save_post_url = base_url('add_emergency_contacts');
    //Field Names
    $field_sid = 'employee_sid';
    $back_btn   = 'Dashboard';
    $skip_btn   = '';
    $proceed_btn   = '';
}

$display_form_driv = false;
$display_form_occu = false;

if(isset($license_type) && $license_type == 'drivers') {
    $display_form_driv = true;
} else if (isset($license_type) && $license_type == 'occupational') {
    $display_form_occu = true;
} else {
    $display_form_driv = true;
    $display_form_occu = true;
}

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h1 class="section-ttile">License Info</h1>
                </div>
                <div class="accordion-colored-header">
                    <div class="panel-group" id="accordion">
                        <?php if($display_form_occu == true) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#occupational_license"><span class="glyphicon glyphicon-minus"></span>occupational license</a>
                                </h4>
                            </div>
                            <div id="occupational_license" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="form-wrp">
                                        <form id="form_occ_license" method="POST" enctype="multipart/form-data">
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
                                                        <?php $field_name = 'license_issue_date' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_label('Issue Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="oc_' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_expiration_date' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php echo form_label('Expiration Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="oc_' . $field_name . '"'); ?>
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
                                                            <span class="selected-file" id="name_license_file">No Document</span>
                                                            <input name="license_file" id="license_file" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_indefinite' ?>
                                                        <?php $temp = isset($occu_license[$field_name]) && !empty($occu_license[$field_name]) ? $occu_license[$field_name] : ''; ?>
                                                        <?php $default_selected = 'on' == $temp ? true : false; ?>
                                                        <label class="control control--checkbox">
                                                            Indefinite
                                                            <input <?php echo $default_selected == true ? 'checked="checked"': ''; ?> name="<?php echo $field_name; ?>" id="Indefinite" type="checkbox">
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

                                                    <div class="img-thumbnail">
                                                        <?php if(empty($temp)) { ?>
                                                            <span class="text-center text-success" style="font-size: 200px; display: inline-block; color: #3598dc;"><i class="fa fa-picture-o"></i></span>
                                                        <?php } else { ?>
                                                            <?php $license_file = pathinfo($temp); ?>
                                                            <?php $license_extension = $license_file['extension']; ?>
                                                            <?php if(in_array($license_extension, ['pdf'])){ ?>
                                                                <iframe src="<?php echo 'https://docs.google.com/gview?url=' . urlencode(AWS_S3_BUCKET_URL . $temp) . '&embedded=true'; ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100% !important; height:500px;" frameborder="0"></iframe>
                                                            <?php } else if(in_array($license_extension, [ 'jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])){ ?>
                                                                <img  class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" />
                                                            <?php } else if(in_array($license_extension, ['doc', 'docx'])){ ?>
                                                                <iframe src="<?php echo 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $temp); ?>" id="preview_iframe" class="uploaded-file-preview"  style="width:100% !important; height:500px;" frameborder="0"></iframe>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>

                                                    <br />
                                                    <br />
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                                                        <input class="btn btn-info" value="Save" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if($display_form_driv == true) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#driver_license"><span class="glyphicon glyphicon-minus"></span>driver license</a>
                                </h4>
                            </div>
                            <div id="driver_license" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="form-wrp">
                                        <form method="POST" enctype="multipart/form-data">
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
                                                                <?php if(!empty($license_types)) { ?>
                                                                    <?php foreach($license_types as $key => $license_type) { ?>
                                                                        <?php $default_selected = $key == $temp ? true : false; ?>
                                                                        <option <?php echo set_select($field_name, $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $license_type?></option>
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
                                                                <?php if(!empty($license_classes)) { ?>
                                                                    <?php foreach($license_classes as $key => $license_class) { ?>
                                                                        <?php $default_selected = $key == $temp ? true : false; ?>
                                                                        <option <?php echo set_select($field_name, $key, $default_selected); ?> value="<?php echo $key; ?>"><?php echo $license_class?></option>
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
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('Issue Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="dr_' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="form-group">
                                                        <?php $field_name = 'license_expiration_date' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php echo form_label('Expiration Date', $field_name); ?>
                                                        <?php echo form_input($field_name, set_value($field_name, $temp), 'class="form-control datepicker" id="dr_' . $field_name . '"'); ?>
                                                        <?php echo form_error($field_name); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <?php $field_name = 'license_indefinite' ?>
                                                        <?php $temp = isset($driv_license[$field_name]) && !empty($driv_license[$field_name]) ? $driv_license[$field_name] : ''; ?>
                                                        <?php $default_selected = 'on' == $temp ? true : false; ?>
                                                        <label class="control control--checkbox">
                                                            Indefinite
                                                            <input <?php echo $default_selected == true ? 'checked="checked"': ''; ?> name="<?php echo $field_name; ?>" id="Indefinite" type="checkbox">
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
                                                            <span class="selected-file" id="name_dr_license_file">No Document</span>
                                                            <input name="license_file" id="dr_license_file" accept="image/*" type="file">
                                                            <a href="javascript:;">Choose File</a>
                                                        </div>
                                                        <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                                                            <div class="img-thumbnail">
                                                                <?php if(empty($temp)) { ?>
                                                                    <span class="text-center text-success" style="font-size: 200px; display: inline-block; color: #3598dc;"><i class="fa fa-picture-o"></i></span>
                                                                <?php } else { ?>
                                                                    <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>" />
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="btn-wrp full-width text-right">
                                                        <a class="btn btn-black margin-right" href="<?php echo $back_url; ?>">cancel</a>
                                                        <input class="btn btn-info" value="Save" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if($users_type != 'applicant') { ?>
                <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                    <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
                <!-- </div> -->
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_license_info').validate({
            rules: {
                license_file: {
                    accept: 'image/png,image/bmp,image/gif,image/jpeg,image/tiff,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                }
            },
            messages:{
                license_file: {
                    accept: 'Please select an image or pdf file.'
                }
            }
        });


        $('#oc_license_issue_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (selectedDate) {
                $('#oc_license_expiration_date').datepicker('option', 'minDate', selectedDate);
            }
        });

        $('#oc_license_expiration_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (selectedDate) {
                $('#oc_license_issue_date').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('#dr_license_issue_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (selectedDate) {
                $('#dr_license_expiration_date').datepicker('option', 'minDate', selectedDate);
            }
        });

        $('#dr_license_expiration_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (selectedDate) {
                $('#dr_license_issue_date').datepicker('option', 'maxDate', selectedDate);
            }
        });

        $('input[type=file]').on('change', function () {
            var selected_file = $(this).val();
            var selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);

            var id = $(this).attr('id');
            $('#name_' + id).html(selected_file);
        });

        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });




</script>
