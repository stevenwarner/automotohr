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
    $primary_phone_number_cc = $primary_phone_number = $dependant_info['phone'];
    // if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
    //     $is_regex = 1;
    //     $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
    //     $input_group_end   = '</div>';
    //     $primary_phone_number = phonenumber_format($dependant_info['phone'], true);
    //     $primary_phone_number_cc = phonenumber_format($dependant_info['phone']);
    // }else{
        if($primary_phone_number === '+1') $primary_phone_number = ''; 
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'Not Specified'; 
    // }
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="tagline-heading">
                                    <h4>Add a Spouse / Domestic Partner</h4>
                                </div>
                                <form id="dependantForm" action="" method="post">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-50-left">
                                                <label>First Name<span class="staric">*</span></label>
                                                <input name="first_name" value="<?php
                                                if (isset($dependant_info['first_name'])) {
                                                    echo $dependant_info['first_name'];
                                                }
                                                ?>" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Last Name<span class="staric">*</span></label>
                                                <input value="<?php
                                                if (isset($dependant_info['last_name'])) {
                                                    echo $dependant_info['last_name'];
                                                }
                                                ?>" name="last_name" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Address<span class="staric">*</span></label>
                                                <input value="<?php
                                                if (isset($dependant_info['address'])) {
                                                    echo $dependant_info['address'];
                                                }
                                                ?>" name="address" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Address Line 2</label>
                                                <input value="<?php
                                                if (isset($dependant_info['address_line'])) {
                                                    echo $dependant_info['address_line'];
                                                }
                                                ?>" name="address_line" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Country:</label>  
                                                <div class="hr-select-dropdown">								
                                                    <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?php echo $active_country["sid"]; ?>"
                                                            <?php
                                                            if ($dependant_info['Location_Country'] == $active_country["sid"]) {
                                                                echo "selected";
                                                            }
                                                            ?>>
                                                                        <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li>
                                            <p style="display: none"  id="state_id"><?php echo set_value('Location_State', $dependant_info['Location_State']); ?></p>
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
                                                <label>City</label>
                                                <input value="<?php
                                                if (isset($dependant_info['city'])) {
                                                    echo $dependant_info['city'];
                                                }
                                                ?>" name="city" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Postal Code</label>
                                                <input value="<?php
                                                if (isset($dependant_info['postal_code'])) {
                                                    echo $dependant_info['postal_code'];
                                                }
                                                ?>" name="postal_code" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Phone<span class="staric">*</span></label>
                                                <?=$input_group_start;?>
                                                <input value="<?=isset($dependant_info['phone']) ? $primary_phone_number : '';?>" name="phone" type="text" class="invoice-fields js-phone" id="PhoneNumber">
                                                <?=$input_group_end;?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Birth Date</label>
                                                <input value="<?php
                                                if (isset($dependant_info['birth_date'])) {
                                                    echo $dependant_info['birth_date'];
                                                }
                                                ?>" name="birth_date" type="text" readonly class="eventdate invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Relationship<span class="staric">*</span></label>
                                                <input value="<?php
                                                if (isset($dependant_info['relationship'])) {
                                                    echo $dependant_info['relationship'];
                                                }
                                                ?>" name="relationship" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>SSN#</label>
                                                <input value="<?php
                                                if (isset($dependant_info['ssn'])) {
                                                    echo $dependant_info['ssn'];
                                                }
                                                ?>" name="ssn" type="text" class="invoice-fields">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>Gender</label>
                                                <div class="hr-select-dropdown">
                                                    <select name="gender" class="invoice-fields">
                                                        <option <?php if (isset($dependant_info['gender']) && $dependant_info['gender'] == "male") { ?> selected <?php } ?>  value="male">Male</option>    
                                                        <option <?php if (isset($dependant_info['gender']) && $dependant_info['gender'] == "female") { ?> selected <?php } ?>  value="female">Female</option>   
                                                    </select>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight send-email">
                                                <input name="family_member" <?php if (isset($dependant_info['family_member']) && $dependant_info['family_member'] == 'on') { ?> checked <?php } ?>   type="checkbox" id="add_family_member">
                                                <label for="add_family_member">Add Family Members</label>
                                            </li>
                                            <div class="btn-panel">
                                                <input type="submit" class="submit-btn" onclick="validate_form()" value="Save">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?php echo base_url($cancel_url); ?>'" />
                                            </div>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>

            </div>
        </div>
    </div>
</div>
<script language = "JavaScript" type = "text/javascript" src = "<?= base_url('assets') ?>/js/jquery.validate.min.js" ></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
                                                    function validate_form() {
                                                        $("#dependantForm").validate({
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
                                                                address: {
                                                                    required: true,
                                                                    pattern: /^[a-zA-Z0-9\-, ]+$/
                                                                },
                                                                phone: {
                                                                    required: true,
                                                                    <?php if($is_regex === 1){ ?>
                                                                    pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/ // (555) 123-4567
                                                                    <?php } else { ?>
                                                                    pattern: /^[0-9\-+ ]+$/
                                                                    <?php } ?>
                                                                },
                                                                relationship: {
                                                                    required: true,
                                                                    pattern: /^[a-zA-Z\-#,':;. ]+$/
                                                                }
                                                            },
                                                            messages: {
                                                                first_name: {
                                                                    required: 'First name is required',
                                                                    pattern: 'Please provide valid first name'
                                                                },
                                                                last_name: {
                                                                    required: 'Last name is required',
                                                                    pattern: 'Please provide valid last name'
                                                                },
                                                                address: {
                                                                    required: 'Address is required',
                                                                    pattern: 'Please provide valid address'
                                                                },
                                                                phone: {
                                                                    required: 'Phone Number is required',
                                                                    <?php if($is_regex === 1){ ?>
                                                                    pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                                                                    <?php } else { ?>
                                                                    pattern: 'Numbers and dashes only please'
                                                                    <?php } ?>
                                                                },
                                                                relationship: {
                                                                    required: 'Relationship is required',
                                                                    pattern: 'Please provide valid relation'
                                                                }
                                                            },
        errorPlacement: function(e, el){
            <?php if($is_regex === 1){ ?>
            if($(el)[0].id == 'PhoneNumber') $(el).parent().after(e);
            else $(el).after(e);
            <?php } else { ?>
            $(el).after(e);
            <?php } ?>
        },
                                                            submitHandler: function (form) {
            <?php if($is_regex === 1){ ?>
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
                else $("#dependantForm").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($('#PhoneNumber').val().replace(/\D/g, ''))+'" />');
    
                form.submit();
            }
            <?php } else { ?>
                form.submit();
            <?php } ?>
                                                            }
                                                        });
                                                    }


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
</script>
<?php if($is_regex === 1){ ?>
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
    <?php $this->load->view('onboarding/edit_dependents'); ?>
<?php } ?>


