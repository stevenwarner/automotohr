<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i>Add New Company</h1>
                                    </div>
                                    <div class="add-new-company">
                                        <form method="POST" enctype="multipart/form-data" id="form_add_new_employer">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">company detail</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="CompanyName">Company Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('CompanyName', set_value('CompanyName'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('CompanyName'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="ContactName">Contact Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('ContactName', set_value('ContactName'), 'class="hr-form-fileds"'); ?>
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
                                                                    <option value="<?php echo $active_country["sid"]; ?>" <?php if ($data["Location_Country"] == $active_country["sid"]) { ?> selected <?php } ?>>
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
                                                            <select class="invoice-fields" id="state" name="Location_State">
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
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <?php echo form_input('PhoneNumber', set_value('PhoneNumber'), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                                                        </div>
                                                        <?php echo form_error('PhoneNumber'); ?>
                                                    </div>
                                                </div>
                                                <!--
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>YouTube Video</label>
                                                        <?php //echo form_input('YouTubeVideo', set_value('YouTubeVideo'), 'class="hr-form-fileds"'); 
                                                        ?>
                                                        <?php //echo form_error('YouTubeVideo'); 
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label>Logo</label>
                                                        <input type="file" value="" name="">
                                                    </div>
                                                </div>
                                                -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Company Description</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60">
                                                            <?php echo set_value('CompanyDescription'); ?>
                                                        </textarea>
                                                        <?php echo form_error('CompanyDescription'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Company Website</label>
                                                        <?php echo form_input('WebSite', set_value('WebSite'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('WebSite'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>No of Rooftops</label>
                                                        <input type="number" name="number_of_rooftops" value="1" min="1" max="100" class="hr-form-fileds">
                                                        <?php echo form_error('number_of_rooftops'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="country">Marketing Agency</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="marketing_agency_sid" name="marketing_agency_sid">
                                                                <option value="">Please Select</option>
                                                                <?php if (!empty($marketing_agencies)) { ?>
                                                                    <?php foreach ($marketing_agencies as $marketing_agency) { ?>
                                                                        <option value="<?php echo $marketing_agency["sid"]; ?>">
                                                                            <?php echo $marketing_agency["full_name"]; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- job category sid -->
                                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Industry Category</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="job_category_industries_sid" name="job_category_industries_sid">
                                                                <option value="">Please Select</option>
                                                                <?php if (!empty($industry_categories)) { ?>
                                                                    <?php foreach ($industry_categories as $category) { ?>
                                                                        <option value="<?php echo $category["sid"]; ?>">
                                                                            <?php echo $category["industry_name"]; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- job category sid -->
                                                <div class="col-lg-12 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Accounts Payable Contact Person</label>
                                                        <?php echo form_input('accounts_contact_person', set_value('accounts_contact_person'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('accounts_contact_person'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-6 col-xs-6 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Accounts Payable Contact Number</label>
                                                        <?php echo form_input('accounts_contact_number', set_value('accounts_contact_number'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('accounts_contact_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Full Billing Address</label>
                                                        <?php echo form_textarea('full_billing_address', set_value('full_billing_address'), 'class="hr-form-fileds field-row-autoheight"'); ?>
                                                        <?php echo form_error('full_billing_address'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">Administrator detail</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>First Name<span class="hr-required">*</span></label>
                                                        <?php echo form_input('first_name', set_value('first_name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('first_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Last Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('last_name', set_value('last_name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('last_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Username<span class="hr-required">*</span></label>
                                                        <?php echo form_input('user_name', set_value('user_name'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('user_name'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Job Title<span class="hr-required"></span></label>
                                                        <?php echo form_input('job_title', set_value('job_title'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('job_title'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Email<span class="hr-required">*</span></label>
                                                        <?php echo form_input('email', set_value('email'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Alternative Email</label>
                                                        <?php echo form_input('alternative_email', set_value('alternate_email'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('alternative_email'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php echo form_label('Security Access Level <span class="hr-required">*</span>', 'security_access_level'); ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="security_access_level" id="security_access_level" class="invoice-fields">
                                                                <option value="">Please Select</option>
                                                                <?php if (!empty($security_access_levels)) { ?>
                                                                    <?php foreach ($security_access_levels as $security_access_level) { ?>
                                                                        <option value="<?php echo $security_access_level; ?>"><?php echo ucwords($security_access_level); ?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php echo form_error('security_access_level'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Direct Business Number<span class="hr-required"></span></label>
                                                        <?php echo form_input('direct_business_number', set_value('direct_business_number'), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('direct_business_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Cell Number<span class="hr-required"></span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <?php echo form_input('cell_number', set_value('cell_number'), 'class="hr-form-fileds js-phone" id="CellNumber"'); ?>
                                                        </div>
                                                        <?php echo form_error('cell_number'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Start Date</label>
                                                        <input class="invoice-fields datepicker" id="registration_date" name="registration_date" value="" readonly="readonly" />
                                                    </div>
                                                </div>
                                                <!--
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Your Picture</label>
                                                        <input type="file" name="" value="">
                                                    </div>
                                                </div>
                                                -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <!--<input type="submit" class="search-btn" value="Register" name="submit">-->
                                                    <input name="action" type="hidden" id="submit_action" value="">
                                                    <input type="button" name="action" value="Register" onclick="return fValidateForm('addonly')" class="site-btn">
                                                    <input type="button" name="action" value="Register & Send Email" onclick="return fValidateForm('sendemail')" class="site-btn">
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
        </div>
    </div>
</div>

<script>
    // get the states
    $(document).ready(function() {
        var myid = $('#state_id').html();

        setTimeout(function() {
            $("#country").change();
        }, 1000);

        if (myid) {
            setTimeout(function() {
                $('#state').val(myid);
            }, 1200);
        }

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
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

    function fValidateForm(actionType) {
        console.log('validat');
        $("#submit_action").val(actionType);
        $('#form_add_new_employer').validate({
            debug: true,
            rules: {
                CompanyName: {
                    required: true
                },
                ContactName: {
                    required: true
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                username: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                alternative_email: {
                    email: true
                },
                security_access_level: {
                    required: true
                }
            },
            messages: {
                CompanyName: {
                    required: 'Company Name is Required'
                },
                ContactName: {
                    required: 'Contact Name is Required'
                },
                first_name: {
                    required: 'First Name is Required'
                },
                last_name: {
                    required: 'Last Name is Required'
                },
                username: {
                    required: 'Username is Required'
                },
                email: {
                    required: 'Email is Required',
                    email: 'Please input a valid email address'
                },
                alternative_email: {
                    email: 'Please input a valid email address'
                },
                security_access_level: {
                    required: 'Please select an Access Level'
                }
            }
        });

        if ($('#form_add_new_employer').valid()) {

            // Check for phone number
            if ($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)) {
                alertify.alert('Error!', 'Invalid phone number.', function() {
                    return;
                });
                return;
            }

            // Check for cell number
            if ($('#CellNumber').val() != '' && $('#CellNumber').val().trim() != '(___) ___-____' && !fpn($('#CellNumber').val(), '', true)) {
                alertify.alert('Error!', 'Invalid cell number.', function() {
                    return;
                });
                return;
            }

            // Remove and set phone extension
            $('#js-phonenumber').remove();
            $('#js-cellnumber').remove();
            // Check the fields
            if ($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
            else $('#form_add_new_employer').append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + ($('#PhoneNumber').val().replace(/\D/g, '')) + '" />');
            if ($('#CellNumber').val().trim() == '(___) ___-____') $('#CellNumber').val('');
            else $('#form_add_new_employer').append('<input type="hidden" id="js-cellnumber" name="txt_cellnumber" value="+1' + ($('#CellNumber').val().replace(/\D/g, '')) + '" />');

            document.getElementById('form_add_new_employer').submit();
        }
    }
</script>



<script>
    $.each($('.js-phone'), function() {
        var v = fpn($(this).val().trim());
        if (typeof(v) === 'object') {
            $(this).val(v.number);
            setCaretPosition(this, v.cur);
        } else $(this).val(v);
    });


    $('.js-phone').keyup(function(e) {
        var val = fpn($(this).val().trim());
        if (typeof(val) === 'object') {
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
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
        if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
        //
        if (match) {
            var intlCode = '';
            if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
        } else {
            var af = '',
                an = '',
                cur = 1;
            if (cleaned.substring(0, 1) != '') {
                af += "(_";
                an += '(' + cleaned.substring(0, 1);
                cur++;
            }
            if (cleaned.substring(1, 2) != '') {
                af += "_";
                an += cleaned.substring(1, 2);
                cur++;
            }
            if (cleaned.substring(2, 3) != '') {
                af += "_) ";
                an += cleaned.substring(2, 3) + ') ';
                cur = cur + 3;
            }
            if (cleaned.substring(3, 4) != '') {
                af += "_";
                an += cleaned.substring(3, 4);
                cur++;
            }
            if (cleaned.substring(4, 5) != '') {
                af += "_";
                an += cleaned.substring(4, 5);
                cur++;
            }
            if (cleaned.substring(5, 6) != '') {
                af += "_-";
                an += cleaned.substring(5, 6) + '-';
                cur = cur + 2;
            }
            if (cleaned.substring(6, 7) != '') {
                af += "_";
                an += cleaned.substring(6, 7);
                cur++;
            }
            if (cleaned.substring(7, 8) != '') {
                af += "_";
                an += cleaned.substring(7, 8);
                cur++;
            }
            if (cleaned.substring(8, 9) != '') {
                af += "_";
                an += cleaned.substring(8, 9);
                cur++;
            }
            if (cleaned.substring(9, 10) != '') {
                af += "_";
                an += cleaned.substring(9, 10);
                cur++;
            }

            if (is_return) return match === null ? false : true;

            return {
                number: default_number.replace(af, an),
                cur: cur
            };
        }
    }

    // Change cursor position in input
    function setCaretPosition(elem, caretPos) {
        if (elem != null) {
            if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.move('character', caretPos);
                range.select();
            } else {
                if (elem.selectionStart) {
                    elem.focus();
                    elem.setSelectionRange(caretPos, caretPos);
                } else elem.focus();
            }
        }
    }
</script>

<style>
    /* Remove the radius from left fro phone field*/
    .input-group input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>