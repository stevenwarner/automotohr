<?php 
    $field_phone = 'PhoneNumber';
    //
    $is_regex = 0;
    $input_group_start = $input_group_end = '';
    $primary_phone_number_cc = $primary_phone_number = $company[$field_phone];
    if(isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
        $is_regex = 1;
        $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
        $input_group_end   = '</div>';
        $primary_phone_number = phonenumber_format($company[$field_phone], true);
        $primary_phone_number_cc = phonenumber_format($company[$field_phone]);
    }else{
        if($primary_phone_number === '+1') $primary_phone_number = ''; 
        if($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'Not Specified'; 
    }
    // Replace '+1' with ''
    if(isset($company[$field_phone]) && $company[$field_phone] != '' && $is_regex === 1){
        $company[$field_phone] = str_replace('+1', '', $company[$field_phone]);
    }
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open_multipart('', array('id' => 'companyaddress')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="create-job-wrap">
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <li class="form-col-100">
                                                <?php echo form_label('Company Address','Location_Address'); ?>
                                                <?php echo form_input('Location_Address',set_value('Location_Address', $company['Location_Address']),'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_Address'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <?php echo form_label('City','Location_City'); ?>
                                                <?php echo form_input('Location_City',set_value('Location_City', $company['Location_City']),'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_City'); ?>
                                            </li>
                                            <li class="form-col-50-right">
                                                <?php echo form_label('Zip Code','Location_ZipCode'); ?>
                                                <?php echo form_input('Location_ZipCode',set_value('Location_ZipCode', $company['Location_ZipCode']),'class="invoice-fields"'); ?>
                                                <?php echo form_error('Location_ZipCode'); ?>
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>country</label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($active_countries as $active_country) { ?>
                                                            <option value="<?php echo $active_country["sid"]; ?>"
                                                            <?php if ($company["Location_Country"] == $active_country["sid"]) { ?>
                                                                selected
                                                            <?php } ?>>
                                                            <?php echo $active_country["country_name"]; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>								
                                            </li>
                                            <p style="display: none;" id="state_id"><?php echo $company["Location_State"]; ?></p>
                                            <li class="form-col-50-right">	
                                                <label>state</label>									
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" id="state" name="Location_State">
                                                        <option value="">Select State</option> 
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-100">	
                                                <?php echo form_label('Company Phone Number','PhoneNumber'); ?>
                                                 <?=$input_group_start;?>
                                                    <?php echo form_input('PhoneNumber', set_value('PhoneNumber', $primary_phone_number),'class="invoice-fields" '.( $is_regex === 1 ? 'placeholder="(___) ___-____"' : '' ).''); ?>
                                                 <?=$input_group_end;?>
                                                </div>         
                                                <?php echo form_error('PhoneNumber'); ?>								
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <input type="hidden" name="id" value="<?php echo $company['sid'];?>">
                                                <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url('assets')?>/js/additional-methods.min.js"></script>
<script>

    var phone_ref =  $("input[name=\"<?=$field_phone;?>\"]");
    function validate_form() {
        $("#companyaddress").validate({
            ignore: ":hidden:not(select)",
             rules: {
                Location_City: {
                                pattern: /^[a-zA-Z0-9\- ]+$/
                            },
                Location_ZipCode: {
                                pattern: /^[a-zA-Z0-9\- ]+$/
                            },
                Location_Address: {
                                pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                            },
                PhoneNumber: {
                    <?php if($is_regex === 1){ ?>
                                pattern: /(\(\d{3}\))\s(\d{3})-(\d{4})$/
                    <?php } else { ?>
                                pattern: /^[0-9\-]+$/
                    <?php } ?>
                            },
                },
            messages: {
                Location_City: {
                                required: 'Contact Name is required',
                                pattern: 'Please Provide valid City'
                              },
                Location_ZipCode: {
                                required: 'Zip Code is required',
                                pattern: 'Please provide valid Zip Code'
                              },
                Location_Address: {
                                required: 'Address is required',
                                pattern: 'Please provide valid Address'
                              },
                PhoneNumber: {
                                required: 'Phone Number is required',
                    <?php if($is_regex === 1){ ?>
                                pattern: 'Invalid format! e.g. (XXX) XXX-XXXX'
                    <?php } else { ?>
                                pattern: 'Provide a valid number'
                    <?php } ?>
                              },
            },
            errorPlacement: function(e, el){
                <?php if($is_regex === 1){ ?>
                if($(el)[0].name == 'PhoneNumber') $(el).parent().after(e);
                else $(el).after(e);
                <?php } else { ?>
                $(el).after(e);
                <?php } ?>
            },
            submitHandler: function (form) {
                <?php if($is_regex === 1){ ?>
                // Validate phone number
                if(phone_ref.val() != '' && fpn(phone_ref.val(), '', true) === false){
                    alertify.alert('Invalid phone format.', function(){ return; });
                }else{
                    // Remove and set phone extension
                    $('#js-phonenumber').remove();
                    $("#companyaddress").append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+( $("input[name=\"<?=$field_phone;?>\"]").val().replace(/\D/g, ''))+'" />');
                    form.submit();
                }
                <?php } else { ?>
                    form.submit();
                <?php } ?>
            }
        });
    }

    // get the states 
    $( document ).ready(function() {
        var myid = $('#state_id').html();
        setTimeout(function(){ 
            $( "#country" ).change();
        }, 1000);
        if(myid){
            setTimeout(function(){ 
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
    
    <?php if($is_regex === 1){ ?>

    // Added on: 05-07-2019
    var val = fpn($("input[name=\"<?=$field_phone;?>\"]").val());
    if(typeof(val) === 'object'){
        $("input[name=\"<?=$field_phone;?>\"]").val(val.number);
        setCaretPosition($("input[name=\"<?=$field_phone;?>\"]"), val.cur);
    } else $("input[name=\"<?=$field_phone;?>\"]").val(val);


    // Reset phone number on load
    $("input[name=\"<?=$field_phone;?>\"]").keyup(function(){
        var val = fpn($(this).val());
        if(typeof(val) === 'object'){
            $(this).val(val.number);
            setCaretPosition(this, val.cur);
        } else $(this).val(val);
    });

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

    <?php } ?>
    
</script>