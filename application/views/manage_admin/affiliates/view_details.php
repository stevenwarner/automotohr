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
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a id="back_btn" href="<?php echo base_url('manage_admin/affiliates')?>" class="black-btn pull-right"><i class="fa fa-arrow-left"> </i> Go Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="page-title"><?php echo $page_sub_title; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php   if(sizeof($affiliation)>0) { ?>
                                            <form method="post" action="" class="form js-form" enctype="multipart">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>First Name <i class="fa fa-asterisk text-danger"></i></label>
                                                        <input type="text" name="first_name" value="<?php echo ucfirst($affiliation['first_name']);?>" class="hr-form-fileds">
                                                        <?php echo form_error("first_name"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Last Name <i class="fa fa-asterisk text-danger"></i></label>
                                                        <input type="text" name="last_name" value="<?php echo ucfirst($affiliation['last_name']);?>" class="hr-form-fileds">
                                                        <?php echo form_error("last_name"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Email <i class="fa fa-asterisk text-danger"></i></label>
                                                        <input type="email" name="email" value="<?php echo $affiliation['email'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("email"); ?>
                                                    </div>
                                                </div>
                                                <?php  if($uri_segment == "affiliates") { ?>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Paypal Email</label>
                                                            <input type="text" name="paypal_email" value="<?php echo $affiliation['paypal_email'];?>" class="hr-form-fileds">
                                                            <?php echo form_error("paypal_email"); ?>
                                                        </div>
                                                    </div>
                                                <?php  } ?>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Company</label>
                                                        <input type="text" name="company" value="<?php echo $affiliation['company']; ?>" class="hr-form-fileds">
                                                        <?php echo form_error("company"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Street</label>
                                                        <input type="text" name="street" value="<?php echo $affiliation['street'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("street"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>City</label>
                                                        <input type="text" name="city" value="<?php echo $affiliation['city'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("city"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>State</label>
                                                        <input type="text" name="state" value="<?php echo $affiliation['state'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("state"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Zip Code</label>
                                                        <input type="text" name="zip_code" value="<?php echo $affiliation['zip_code'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("zip_code"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Country</label>
                                                        <input type="text" name="country" value="<?php echo $affiliation['country'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("country"); ?>
                                                    </div>
                                                </div>
                                                <?php  if($uri_segment == "affiliates") { ?>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Method Of Promotion</label>
                                                            <input type="text" name="method_of_promotion" value="<?php echo $affiliation['method_of_promotion'];?>" class="hr-form-fileds">
                                                            <?php echo form_error("method_of_promotion"); ?>
                                                        </div>
                                                    </div>
                                                <?php  } ?>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Website</label>
                                                        <input type="text" name="website" value="<?php echo $affiliation['website'];?>" class="hr-form-fileds">
                                                        <?php echo form_error("website"); ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Contact Number</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <span class="input-group-text">+1</span>
                                                            </div>
                                                            <input type="text" name="contact_number" value="<?=phonenumber_format($affiliation['contact_number'], true);?>" class="hr-form-fileds js-phone" id="PhoneNumber">
                                                        </div>
                                                        <?php echo form_error("contact_number"); ?>
                                                    </div>
                                                </div>
                                            
                                                <?php  if($uri_segment == "affiliates") { ?>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row field-row-autoheight">
                                                            <label>Email List</label>
                                                            <input type="text" name="email_list" value="<?php echo $affiliation['email_list']; ?>" class="hr-form-fileds">
                                                            <?php echo form_error("email_list"); ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="<?=$uri_segment == "affiliates" ? 'col-lg-12 col-md-12 col-xs-12 col-sm-12' : 'col-lg-6 col-md-6 col-xs-12 col-sm-6';?>">
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Timezone</label>
                                                        <?=timezone_dropdown(
                                                            $affiliation['timezone'],
                                                            array(
                                                                'name' => 'timezone',
                                                                'id' => 'timezone',
                                                                'class' => 'hr-form-fileds js-timezone',
                                                                'style' => 'padding: 0;'
                                                            )
                                                        );  ?>
                                                        <?php echo form_error("timezone"); ?>
                                                    </div>
                                                </div>
                                                <?php  if($uri_segment == "affiliates") { ?>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="field-row field-row-autoheight">
                                                            <label for="special_notes">Special Notes</label>
                                                            <textarea name="special_notes" cols="40" rows="10" class="hr-form-fileds field-row-autoheight valid" aria-invalid="false"><?php echo $affiliation['special_notes']; ?></textarea>
                                                            <?php echo form_error("special_notes"); ?>
                                                        </div>
                                                    </div>
                                                <?php  } ?>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <a class="black-btn" href="<?php echo site_url('manage_admin/'.$uri_segment.'/view_details/'.$affiliation['sid']);?>"> Cancel </a> 
                                                            <input type="submit" value="Update" class="site-btn text-right">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                <?php       } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $('#admin').chosen();
        $('#assigned-admin').submit(function () {
            if($('#admin').val()==null){
                alertify.error('Please Assign An Admin');
                return false;
            }
        });
        $('.accept').click(function(){
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Accept this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:1,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/affiliates/accept_reject')?>',
                        success: function(data){
                            if(data == 'exist'){
                                window.location.href = '<?php echo base_url('manage_admin/affiliates/view_details/')?>/' + id;
                            }else{
                                window.location.href = '<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/')?>/' + data;
                            }
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
        $('.reject').click(function(){
            var id = $(this).attr('id');
            alertify.confirm('Confirmation', "Are you sure you want to Reject this Application?",
                function () {
                    $.ajax({
                        type: 'POST',
                        data:{
                            status:2,
                            id: id
                        },
                        url: '<?= base_url('manage_admin/affiliates/accept_reject')?>',
                        success: function(data){
                            location.reload();
                        },
                        error: function(){

                        }
                    });
                },
                function () {
                    alertify.error('Canceled');
                });
        });
    });

    $('#timezone').select2();
</script>




<script>

    var phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/);
    $('form.js-form').submit(function(e) {
        phone_regex.lastIndex = 0;
        var phone = $('#PhoneNumber').val().trim();
        if(phone != '' && phone != '(___) ___-____' && !phone_regex.test(phone)){
            alertify.alert('Error!', 'Contact number is invalid. Please use following format (XXX) XXX-XXXX.', function(){ return; });
            e.preventDefault();
        }
        if(phone != '' && phone != '(___) ___-____') $(this).append('<input type="hidden" name="txt_phonenumber" value="+1'+(phone.replace(/\D/g, ''))+'" />');
    });

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