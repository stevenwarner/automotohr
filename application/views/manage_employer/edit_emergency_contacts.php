<?php

//$load_view = 'old';
//
//if ($this->session->userdata('logged_in')) {
//    if (!isset($session)) {
//        $session = $this->session->userdata('logged_in');
//    }
//    $access_level = $session['employer_detail']['access_level'];
//
//    if ($access_level == 'Employee') {
//        $load_view = 'new';
//    }
//}

?>

<?php if (!$load_view) { ?>
<?php 
    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $emergency_contacts['PhoneNumber'];
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        // $is_regex = 1;
        // $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        // $input_group_end   = '</div>';
        // $primary_phone_number = phonenumber_format($emergency_contacts['PhoneNumber'], true);
        // $primary_phone_number_cc = phonenumber_format($emergency_contacts['PhoneNumber']);
    }else{
        if($primary_phone_number === '+1') $primary_phone_number = ''; 
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'Not Specified'; 
    }
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                    <div class="dashboard-conetnt-wrp">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="page-header-area margin-top">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>

                                <?php echo $title; ?></span>
                        </div>
                        <div class="create-job-wrap">
                            <div class="job-title-text">                
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                            </div>
                            <div class="universal-form-style-v2">
                                <ul>
                                    <form id="add_emergency_contacts" action="" method="POST" enctype="multipart/form-data">
                                        <li class="form-col-50-left">
                                            <label>First Name:<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" name="first_name" id="first_name" value="<?php echo set_value('first_name', $emergency_contacts['first_name']); ?>">
                                            <?php echo form_error('first_name'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Last Name:<span class="staric">*</span></label>
                                            <input type="text" class="invoice-fields" name="last_name" id="last_name" value="<?php echo set_value('last_name', $emergency_contacts['last_name']); ?>">
                                            <?php echo form_error('last_name'); ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>E-Mail:<?php if($contactOptionsStatus['emergency_contact_email_status']==1){ ?><span class="staric">*</span><?php }?></label>
                                            <input type="email" class="invoice-fields" name="email" id="email" value="<?php echo set_value('email', $emergency_contacts['email']); ?>">
                                            <?php echo form_error('email'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Phone Number:<?php if($contactOptionsStatus['emergency_contact_phone_number_status']==1){ ?><span class="staric">*</span><?php } ?></label>
                                            <?=$input_group_start;?>
                                                <input class="invoice-fields startdate js-phone" id="PhoneNumber" name="PhoneNumber" type="text" value="<?php echo set_value('PhoneNumber', $primary_phone_number); ?>">
                                            <?=$input_group_end;?>
                                            <?php echo form_error('PhoneNumber'); ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Country:</label>  
                                            <div class="hr-select-dropdown">								
                                                <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                    <option value="">Select Country</option>
                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                        <option value="<?php echo $active_country["sid"]; ?>"
                                                        <?php
                                                        if ($emergency_contacts['Location_Country'] == $active_country["sid"]) {
                                                            echo "selected";
                                                        }
                                                        ?>>
                                                                    <?php echo $active_country["country_name"]; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>								
                                        </li>
                                        <p style="display: none;" id="state_id"><?php echo set_value('Location_State', $emergency_contacts['Location_State']); ?></p>
                                        <li class="form-col-50-right">
                                            <label>State:</label>  
                                            <div class="hr-select-dropdown">								
                                                <select class="invoice-fields" name="Location_State" id="state">
                                                    <option value="">Select State</option>  
                                                    <option value="">Please Select your country</option> 
                                                </select>
                                            </div>								
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>City:</label>  
                                            <input type="city" class="invoice-fields" name="Location_City" id="Location_City" value="<?php echo set_value('Location_City', $emergency_contacts['Location_City']); ?>">
                                            <?php echo form_error('Location_City'); ?>								
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>ZipCode:</label>
                                            <input class="invoice-fields startdate" name="Location_ZipCode" type="text" value="<?php echo set_value('Location_ZipCode', $emergency_contacts['Location_ZipCode']); ?>">        
                                            <?php echo form_error('Location_ZipCode'); ?>
                                        </li>
                                        <li class="form-col-100">
                                            <label>Address:</label>
                                            <input type="text" class="invoice-fields" name="Location_Address" id="Location_Address" value="<?php echo set_value('Location_Address', $emergency_contacts['Location_Address']); ?>">
                                            <?php echo form_error('Location_Address'); ?>
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Relationship:<span class="staric">*</span></label>
                                            <input class="invoice-fields startdate" name="Relationship" type="text" value="<?php echo set_value('Relationship', $emergency_contacts['Relationship']); ?>">        
                                            <?php echo form_error('Relationship'); ?>								
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Set Priority:<span class="staric">*</span></label>  
                                            <div class="hr-select-dropdown">								
                                                <select class="invoice-fields" name="priority">
                                                    <option value="">Select Priority</option> 
                                                    <option value="1" <?php
                                                    if ($emergency_contacts['priority'] == '1') {
                                                        echo 'selected';
                                                    }
                                                    ?>>1</option>  
                                                    <option value="2" <?php
                                                    if ($emergency_contacts['priority'] == '2') {
                                                        echo 'selected';
                                                    }
                                                    ?>>2</option> 
                                                    <option value="3" <?php
                                                    if ($emergency_contacts['priority'] == '3') {
                                                        echo 'selected';
                                                    }
                                                    ?>>3</option> 
                                                </select>
                                            </div>								
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <input type="hidden" name="formsubmit" value="1">
                                            <input type="hidden" name="sid" value="<?php echo $emergency_contacts['sid']; ?>">
                                            <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                            <?php if ($this->uri->segment(2) != null && $this->uri->segment(3) != null) { ?>
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'" />
                                            <?php } else { ?>
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'" />
                                            <?php } ?>
                                        </li>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript">
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
                                                        $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
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
                                                function validate_form() {
                                                    $("#add_emergency_contacts").validate({
                                                        ignore: ":hidden:not(select)",
                                                        rules: {
                                                            first_name: {
                                                                required: true,
                                                                pattern: /^[a-zA-Z0-9\- ]+$/
                                                            },
                                                            last_name: {
                                                                required: true,
                                                                pattern: /^[a-zA-Z0-9\- ]+$/
                                                            },
                                                            email: {
                                                                <?php if($contactOptionsStatus['emergency_contact_email_status']==1){ ?> required: true, <?php } ?>
                                                                email: true
                                                            },
                                                            PhoneNumber: {
                                                                <?php if($contactOptionsStatus['emergency_contact_phone_number_status']==1){ ?> required: true, <?php } ?>
                                                                <?php if($is_regex === 1) { ?>
                                                                pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                                                                <?php } else { ?>
                                                                pattern: /^[0-9\- ]+$/
                                                                <?php } ?>
                                                            },
                                                            Location_City: {
                                                                pattern: /^[a-zA-Z0-9\- ]+$/
                                                            },
                                                            Location_ZipCode: {
                                                                pattern: /^[0-9\-]+$/
                                                            },
                                                            Location_Address: {
                                                                pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                                                            },
                                                            Relationship: {
                                                                required: true,
                                                                pattern: /^[a-zA-Z\-#,':;. ]+$/
                                                            },
                                                            priority: {
                                                                required: true
                                                            }
                                                        },
                                                        messages: {
                                                            first_name: {
                                                                required: 'First Name is required',
                                                                pattern: 'Letters, numbers and dashes only please'
                                                            },
                                                            last_name: {
                                                                required: 'Last Name is required',
                                                                pattern: 'Letters, numbers and dashes only please'
                                                            },
                                                            email: {
                                                                // required: 'E-Mail is required',
                                                                email: 'Valid E-Mail Please'
                                                            },
                                                            PhoneNumber: {
                                                                // required: 'Phone Number is required',
                                                                <?php if($is_regex === 1) { ?>
                                                                pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                                                                <?php } else { ?>
                                                                pattern: 'numbers and dashes only please'
                                                                <?php } ?>
                                                            },
                                                            Location_City: {
                                                                pattern: 'Letters, numbers and dashes only please',
                                                            },
                                                            Location_ZipCode: {
                                                                pattern: 'Numeric values and dashes only please'
                                                            },
                                                            Location_Address: {
                                                                pattern: 'Valid Address Please'
                                                            },
                                                            Relationship: {
                                                                required: 'Relationship is required',
                                                                pattern: 'Please provide valid relation'
                                                            },
                                                            priority: {
                                                                required: 'Priority is required'
                                                            }
                                                        },
        errorPlacement: function(e, el){
            <?php if($is_regex === 1) { ?>
            if($(el)[0].id == 'PhoneNumber') $(el).parent().after(e);
            else $(el).after(e);
            <?php } else { ?>
            $(el).after(e);
            <?php } ?>
        },
                                                        submitHandler: function (form) {
            <?php if($is_regex === 1) { ?>
                                                            
            var is_error = false;
            // Check for phone number
            if($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)){
                alertify.alert('Invalid phone number.', function(){ return; });
                is_error = true;
                return;
            }

            if (is_error === false) {
                // Remove and set phone extension
                $('#js-phonenumber').remove();
                // Check the fields
                if($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
                else $("#add_emergency_contacts").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($('#PhoneNumber').val().replace(/\D/g, ''))+'" />');
    
                form.submit();
            }
            <?php } else { ?>
                form.submit();
            <?php } ?>
                                                        }
                                                    });
                                                }
</script>

 <?php if($is_regex === 1) { ?>

<script>
    $.each($('.js-phone'), function() {
        var v = fpn($(this).val().trim());
        if(typeof(v) === 'object'){
            $(this).val( v.number );
            setCaretPosition(this, v.cur);
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

<?php } ?>

<?php } else { ?>
    <?php $this->load->view('onboarding/edit_emergency_contacts'); ?>
<?php } ?>

