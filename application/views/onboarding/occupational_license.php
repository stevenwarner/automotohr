<div class="onboarding-body-wrp">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('onboarding/onboarding_left_menu'); ?>
            </div>
            <div class="col-lg-10 col-md-9 col-xs-12 col-sm-8">
                <div class="page-header">
                    <h1 class="title-heading">Occupational License</h1>
                </div>
                <div class="btn-wrp">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <a href="<?php echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>" class="btn btn-success btn-block"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                            <!--<a href="javascript:;" class="btn btn-danger btn-block">Skip</a>-->
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <a href="<?php echo base_url('onboarding/drivers_license/' . $unique_sid); ?>" class="btn btn-success btn-block">Next&nbsp;<i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="hr-box">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="perform_action" name="perform_action" value="update_license_information" />
                        <input type="hidden" id="applicant_sid" name="applicant_sid" value="<?php echo $applicant_sid; ?>" />
                        
                        <div class="universal-form-style-v2">
                            <ul class="row">
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_type' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('License Type', $field_name); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>">
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
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_authority' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('License Authority', $field_name); ?>
                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_class' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('License Class', $field_name); ?>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>">
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
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_number' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('License Number', $field_name); ?>
                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_issue_date' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('Issue Date', $field_name); ?>
                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields datepicker" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <?php $field_name = 'license_expiration_date' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_label('Expiration Date', $field_name); ?>
                                    <?php echo form_input($field_name, set_value($field_name, $temp), 'class="invoice-fields datepicker" id="' . $field_name . '"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </li>
                                <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                    <?php $field_name = 'license_indefinite' ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php $default_selected = 'on' == $temp ? true : false; ?>
                                    <label class="control control--checkbox">
                                        Indefinite
                                        <input <?php echo $default_selected == true ? 'checked="checked"': ''; ?> name="<?php echo $field_name; ?>" id="Indefinite" type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                                <li class="col-lg-12 col-md-12 col-xs-12 col-sm-12 autoheight">
                                    <?php $field_name = 'license_notes' ?>
                                    <?php echo form_label('License Notes', $field_name); ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>
                                    <?php echo form_textarea($field_name, set_value($field_name, $temp), 'class="invoice-fields autoheight" id="' . $field_name . '" rows="3"'); ?>
                                    <?php echo form_error($field_name); ?>
                                </li>
                                <li class="col-lg-6 col-md-6 col-xs-12 col-sm-6 autoheight">
                                    <?php $field_name = 'license_file' ?>
                                    <?php echo form_label('Upload Scanned ', $field_name); ?>
                                    <?php $temp = isset($license_details[$field_name]) && !empty($license_details[$field_name]) ? $license_details[$field_name] : ''; ?>

                                    <div class="upload-file invoice-fields">
                                        <span class="selected-file" id="name_license_file">No Document</span>
                                        <input name="license_file" id="license_file" accept="image/*" type="file">
                                        <a href="javascript:;">Choose File</a>
                                    </div>
                                    <em style="color:rgb(255, 155, 0);"><i class="fa fa-warning"></i>&nbsp;Maximum file size allowed: 10MB</em>
                                    <br />
                                    <div class="img-thumbnail text-center">
                                        <?php if(empty($temp)) { ?>
                                            <span class="text-center text-success" style="font-size: 200px; display: inline-block; color: #81b431;"><i class="fa fa-picture-o"></i></span>
                                        <?php } else { ?>
                                            <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $temp; ?>"
                                        <?php } ?>
                                    </div>
                                </li>
                            </ul>
                            <div class="btn-wrp">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10"></div>
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <input class="btn btn-success btn-block" value="Save" type="submit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="btn-wrp">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <a href="<?php echo base_url('onboarding/emergency_contacts/' . $unique_sid); ?>" class="btn btn-success btn-block"><i class="fa fa-arrow-left"></i>&nbsp;Previous</a>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                            <!--<a href="javascript:;" class="btn btn-danger btn-block">Skip</a>-->
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <a href="<?php echo base_url('onboarding/drivers_license/' . $unique_sid); ?>" class="btn btn-success btn-block">Next&nbsp;<i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>                              
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });
    function check_file_all(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html('<span>' + fileName + '</span>');
            //console.log(fileName);
        } else {
            $('#name_' + val).html('Please Select');
            //console.log('in else case');
        }
    }

</script>