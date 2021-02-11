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
                                        <h1 class="page-title">
                                            <i class="fa fa-users"></i>
                                            <?php echo $page_title; ?>
                                        </h1>
                                        <a href="<?php echo base_url('manage_admin/corporate_management'); ?>" class="black-btn pull-right">
                                            <i class="fa fa-long-arrow-left"></i> 
                                            Back To Corporate Management
                                        </a>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="<?php echo current_url(); ?>" method="POST" name="edit_corporate_company_form" id="edit_corporate_company_form">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title">corporate company detail</h1>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">  
                                                    <div class="field-row">
                                                        <label for="CompanyName">Corporate Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('CompanyName', set_value('CompanyName', $company_detail['CompanyName']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('CompanyName'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="ContactName">Contact Name <span class="hr-required">*</span></label>
                                                        <?php echo form_input('ContactName', set_value('ContactName', $company_detail['ContactName']), 'class="hr-form-fileds"'); ?>
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
                                                                    <?php if ($company_detail['Location_Country'] == $active_country["sid"]) { ?>
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
                                                        <p style="display: none;" id="state_id"><?php echo $company_detail['Location_State']; ?></p>
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
                                                        <?php echo form_input('Location_City', set_value('Location_City', $company_detail['Location_City']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('Location_City'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="Location_ZipCode">ZipCode</label>
                                                        <?php echo form_input('Location_ZipCode', set_value('Location_ZipCode', $company_detail['Location_ZipCode']), 'class="hr-form-fileds"'); ?>
                                                        <?php echo form_error('Location_ZipCode'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Address</label>
                                                        <?php echo form_input('Location_Address', set_value('Location_Address', $company_detail['Location_Address']), 'class="hr-form-fileds"'); ?>
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
                                                            <?php echo form_input('PhoneNumber', set_value('PhoneNumber', phonenumber_format($company_detail['PhoneNumber'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                                                        </div>
                                                        <?php echo form_error('PhoneNumber'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Corporate Description</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60" >
                                                            <?php echo set_value('CompanyDescription', $company_detail['CompanyDescription']); ?>
                                                        </textarea>
                                                        <?php echo form_error('CompanyDescription'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                    <div class="field-row">
                                                        <label class="state">Automotive Group</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="automotive_group_sid" name="automotive_group_sid">
                                                                <option value="0">Select Automotive Group</option>
                                                                <?php if(!empty($automotive_groups)) { ?>
                                                                    <?php foreach($automotive_groups as $group) { ?>
                                                                        <?php $default_selected = $company_detail['automotive_group_sid'] == $group['sid'] ? true : false; ?>
                                                                        <option <?php echo set_select('automotive_group_sid', $group['sid'], $default_selected); ?> value="<?php echo $group['sid'];?>"><?php echo $group['group_name'];?></option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                        <hr />

                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <button type="button" onclick="func_validate_and_submit();" class="btn btn-success">Update</button>
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
        $('#edit_corporate_company_form').validate();

        if($('#edit_corporate_company_form').valid()){
            $('#edit_corporate_company_form').submit();
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



<script>

    var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);
    $('form#edit_corporate_company_form').submit(function(e) {
        phone_regex.lastIndex = 0;
        var phone = $('#PhoneNumber').val().trim();
        if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
            alertify.alert('Error!', 'Phone number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
            e.preventDefault();
        }
        if(phone != '' && phone != '(___) ___-____') $(this).append('<input type="hidden" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
    });

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
            setCaretPosition($(this), val.cur);
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