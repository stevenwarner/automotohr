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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $data['sid']); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <?php if (!empty($data['ip_address'])) { ?>
                                                <p>IP - Browsers Details: <?php echo $data['ip_address']; ?></p>
                                        <?php } ?>
                                        <div class="edit-template-from-main">
                                            <?php echo form_open('', array('class' => 'form-horizontal js-form')); ?>
                                            <ul>
                                                <li>
                                                    <?php echo form_label('Company Name <span class="hr-required">*</span>', 'CompanyName'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('CompanyName', set_value('CompanyName', $data['CompanyName']), 'class="hr-form-fileds"');
                                                        echo form_error('CompanyName'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Contact Name <span class="hr-required">*</span>', 'ContactName'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('ContactName', set_value('ContactName', $data['ContactName']), 'class="hr-form-fileds"');
                                                        echo form_error('ContactName'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Expiry Date:</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-register-date">
                                                            <input type="text" name="expiry_date" value="<?php echo set_value('expiry_date', $data['expiry_date']); ?>" class="hr-form-fileds" readonly="readonly" />
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>No of Rooftops</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="number" name="number_of_rooftops" value="<?php echo set_value('number_of_rooftops', $data['number_of_rooftops']); ?>" class="hr-form-fileds" />
                                                    </div>
                                                </li>
                                                <li>
                                        <?php   echo form_label('Job Category Industry', 'job_category_industries_sid');
                                                echo form_error('job_category_industries_sid'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="job_category_industries_sid" name="job_category_industries_sid">
                                                                <option value="">Select Job Industry Category</option>
                                                                <?php   foreach ($industry_categories as $industry_category) { ?>
                                                                            <option value="<?php echo $industry_category["sid"]; ?>" <?php if ($data["job_category_industries_sid"] == $industry_category["sid"]) { ?> selected <?php } ?>>
                                        <?php                                   echo $industry_category['industry_name']; ?>
                                                                            </option>
                                        <?php                           } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                        <?php if(IS_TIMEZONE_ACTIVE) { ?>
                                        <?php if($parent_sid == 0){ ?>
                                                <li class="js-timezone-row">
                                        <?php   echo form_label('Timezone', 'company_timezone');
                                                echo form_error('company_timezone'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <?=timezone_dropdown(
                                                                $portal_detail['company_timezone'],
                                                                array(
                                                                    'class' => 'invoice-fields',
                                                                    "id" => 'company_timezone',
                                                                    "name" => 'company_timezone'
                                                                )
                                                            );?>
                                                           
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                        <?php } ?>
                                        <?php } ?>
                                        <?php   echo form_label('Country', 'Location_Country');
                                                echo form_error('Location_Country'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="country" name="Location_Country" onchange="getStates(this.value, <?php echo $states; ?>)">
                                                                <option value="">Select Country</option>
                                                                    <?php foreach ($active_countries as $active_country) { ?>
                                                                    <option value="<?php echo $active_country["sid"]; ?>"
                                        <?php                       if ($data["Location_Country"] == $active_country["sid"]) { ?>
                                                                            selected
                                        <?php                       } ?>>
                                        <?php                       echo $active_country["country_name"]; ?>
                                                                    </option>
                                        <?php                       } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                        <?php       echo form_label('State', 'Location_State');
                                                    echo form_error('Location_State'); ?>
                                                    <p style="display: none;" id="state_id"><?php echo $data["Location_State"]; ?></p>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="state"
                                                                    name="Location_State">
                                                                <option value="">Select State</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                        <?php       echo form_label('City', 'Location_City'); ?>
                                                    <div class="hr-fields-wrap">
                                                    <?php echo form_input('Location_City', set_value('Location_City', $data['Location_City']), 'class="hr-form-fileds"');
                                                    echo form_error('Location_City'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('ZipCode', 'Location_ZipCode'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('Location_ZipCode', set_value('Location_ZipCode', $data['Location_ZipCode']), 'class="hr-form-fileds"');
                                                        echo form_error('Location_ZipCode'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Address', 'Location_Address'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input('Location_Address', set_value('Location_Address', $data['Location_Address']), 'class="hr-form-fileds"');
                                                        echo form_error('Location_Address'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                        <?php echo form_label('Phone Number', 'PhoneNumber'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="input-group">
                                                          <div class="input-group-addon">
                                                              <span class="input-group-text">+1</span>
                                                          </div>
                                <?php               echo form_input('PhoneNumber', set_value('PhoneNumber', phonenumber_format($data['PhoneNumber'], true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"');?>
                                                          </div>
                                                    <?php echo form_error('PhoneNumber'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Company Description', 'CompanyDescription'); ?>
                                                    <div class="hr-fields-wrap">
                                                            <?php //echo form_input('CompanyDescription',set_value('CompanyDescription', $data['CompanyDescription']),'class="hr-form-fileds"');  ?>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60"><?php echo set_value('CompanyDescription', $data['CompanyDescription']); ?></textarea>
                                                        <?php echo form_error('CompanyDescription'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Job Listing Template Group', 'job_listing_template_group'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                        <?php echo form_dropdown('job_listing_template_group', $groupOptions, set_value('job_listing_template_group', $data['job_listing_template_group']), 'class="invoice-fields"'); ?>
                                                        </div>
                                <?php                   echo form_error('job_listing_template_group'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                        <?php echo form_label('Website', 'WebSite'); ?>
                                                    <div class="hr-fields-wrap">
                                <?php               echo form_input('WebSite', set_value('WebSite', $data['WebSite']), 'class="hr-form-fileds"');
                                                    echo form_error('WebSite'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                        <?php echo form_label('Accounts Payable Contact Person', 'accounts_contact_person'); ?>
                                                    <div class="hr-fields-wrap">
                                <?php               echo form_input('accounts_contact_person', set_value('accounts_contact_person', $data['accounts_contact_person']), 'class="hr-form-fileds"');
                                                    echo form_error('accounts_contact_person'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                <?php               echo form_label('Accounts Payable Contact Number', 'accounts_contact_number'); ?>
                                                    <div class="hr-fields-wrap">
                                <?php               echo form_input('accounts_contact_number', set_value('accounts_contact_number', $data['accounts_contact_number']), 'class="hr-form-fileds"');
                                                    echo form_error('accounts_contact_number'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Full Billing Address', 'full_billing_address'); ?>
                                                    <div class="hr-fields-wrap">
                                                                <?php echo form_textarea('full_billing_address', set_value('full_billing_address', $data['full_billing_address']), 'class="hr-form-fileds field-row-autoheight"');
                                                                echo form_error('full_billing_address');
                                                                ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label for="country">Marketing Agency</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                    <?php $temp = $data['marketing_agency_sid']; ?>
                                                            <select class="invoice-fields" id="marketing_agency_sid" name="marketing_agency_sid" >
                                                                <option value="">Please Select</option>
                                                    <?php if (!empty($marketing_agencies)) { ?>
                                                        <?php foreach ($marketing_agencies as $marketing_agency) { ?>
                                                                    <?php $default_selected = ( $temp == $marketing_agency['sid'] ? true : false ); ?>
                                                                        <option <?php echo set_select('marketing_agency_sid', $marketing_agency['sid'], $default_selected); ?> value="<?php echo $marketing_agency["sid"]; ?>">
                                                                <?php echo $marketing_agency["full_name"]; ?>
                                                                        </option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                        <?php echo form_label('Company Payment Type', 'payment_type'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                        <?php echo form_dropdown('payment_type', $payment_type, set_value('payment_type', $data['payment_type']), 'class="invoice-fields"'); ?>
                                                        </div>
                                                    <?php echo form_error('payment_type'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <?php echo form_label('Company Past Due', 'past_due'); ?>
                                                    <div class="hr-fields-wrap">
                                                        <div class="hr-select-dropdown">
                                                        <?php echo form_dropdown('past_due', $past_due, set_value('past_due', $data['past_due']), 'class="invoice-fields"'); ?>
                                                        </div>
                                                        <?php echo form_error('past_due'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Enable Job Approval Module</label>
                                                    <div class="hr-fields-wrap ">
                                                        <div class="hr-register-date">
                                                            <input type="checkbox" name="has_job_approval_rights" value="1" class="hr-form-fileds" style="width:10%;" <?php echo($data['has_job_approval_rights'] == 1 ? 'checked' : ''); ?> />
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Main Company</label>
                                                    <div class="hr-fields-wrap ">
                                                        <div class="hr-register-date">
                                                            <input type="checkbox" name="is_paid" value="1" class="hr-form-fileds" style="width:10%;" <?php echo($data['is_paid'] == 1 ? 'checked' : ''); ?> />
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $data['sid']; ?>">
<?php if ($data['active'] == 1) { ?>
                                                        <button type="button" class="search-btn search-btn-red" data-company-sid="<?php echo $data['sid']; ?>" data-status="0" onclick="fSetCompanyStatus(this);">De-Activate Company</button>
<?php } else { ?>
                                                        <button type="button" class="search-btn" data-company-sid="<?php echo $data['sid']; ?>" data-status="1" onclick="fSetCompanyStatus(this);">Activate Company</button>
<?php } ?>
                                                    <input type="submit" name="submit" value="Apply" class="search-btn">
                                                    <input type="submit" name="submit" value="Save" class="search-btn">
<?php //echo form_submit('submit', 'Save profile', 'class="btn btn-primary btn-lg btn-block"');    ?>
                                                </li>
                                            </ul>
<?php echo form_close(); ?>
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
    $(document).ready(function () { // get the states
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

    $('.facebook-api').click(function () {
        if ($('.facebook-api').is(":checked")) {
            $('.facebook_api_date').fadeIn();
            $('#facebookExpiryDate').prop('required', true);
        } else {
            $('.facebook_api_date').hide();
            $('#facebookExpiryDate').prop('required', false);
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

    function fSetCompanyStatus(source) {
        var myUrl = '<?php echo base_url("manage_admin/companies/ajax_responder")?>';
        var company_sid = $(source).attr('data-company-sid');
        var company_status = $(source).attr('data-status');
        var dataToSend = { perform_action : 'set_company_status', company_sid : company_sid, company_status : company_status };
        var myRequest;

        myRequest = $.ajax({
            url: myUrl,
            data: dataToSend,
            type: 'POST'
        });

        myRequest.done(function(response) {
            if(response == 'success'){
                myUrl = window.location.href;
                window.location = myUrl;
            }
        });
    }

    <?php if(IS_TIMEZONE_ACTIVE) { ?>
    $('#company_timezone').select2();
    <?php } ?>
</script>


<script>

    $('.js-form').submit(function(event) {
        // Check for phone number
        if($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)){
            alertify.alert('Error!', 'Invalid phone number.', function(){ return; });
            event.preventDefault();
            return;
        }

        // Remove and set phone extension
        $('#js-phonenumber').remove();
        // Check the fields
        if($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
        else $(this).append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1'+($('#PhoneNumber').val().replace(/\D/g, ''))+'" />');
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