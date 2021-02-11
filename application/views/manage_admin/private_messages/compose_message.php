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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php $this->load->view('templates/_parts/admin_message_panel_header'); ?>
                                    <div class="hr-add-new-promotions">
                                        <?php echo form_open_multipart('', array('class' => 'form-horizontal', 'id' => 'compose_message_form')); ?>
                                         <input type="hidden" name="users_type" value="employee">
                                        <ul>
                                            <?php if ($page == 'compose') { ?>
                                                <li>
                                                    <label> Select a Receiver </label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="invoice-field-wrap">
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <label class="control control--radio">Employee
                                                                    <input type="radio" name="receiver" value="to_employees" id="to_employees" checked>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                <label class="control control--radio">Email
                                                                    <input type="radio" name="receiver" value="to_email" id="to_email">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Message To: 
                                                        <span class="hr-required">*</span>
                                                    </label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="invoice-field-wrap">

                                                            <div class="hr-select-dropdown" id="employerSelector">
                                                                <select class="invoice-fields" name="to_id" id="to_id">
                                                                    <option value="">Select Employer Name</option>
                                                                    <?php foreach ($employers as $employer) { ?>
                                                                        <option <?php if ($employer['sid'] == set_value('to_id')) { ?> selected <?php } ?> value="<?= $employer['sid'] ?>"><?= $employer['username'] ?> (<?= $employer['email'] ?>)</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div id="custom_email" style="display:none">
                                                                <span class="text-danger">Please Note: Email template tags will not work with this email type</span>

                                                                <?php
                                                                echo form_input('custom_email', set_value('custom_email'), 'class="hr-form-fileds"');
                                                                echo form_error('custom_email');
                                                                ?>
                                                            </div>

                                                            <?= form_error('to_id') ?>
                                                        </div>
                                                    </div>
                                                </li>

                                            <?php } elseif ($page == 'reply') { ?>
                                                <li>
                                                    <label>Message To:</label>
                                                    <div class="hr-fields-wrap">
                                                        <a> <?= $employer_info['username'] ?> /<?= $employer_info['email'] ?></a>
                                                        <input type="hidden" name="to_id" value="<?= $employer_info['sid'] ?>">
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <label>From Email:
                                                    <span class="hr-required">*</span>
                                                </label>
                                                <div class="hr-fields-wrap">
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="from_email" id="from_email">
                                                                <option value="">Please Select From Email</option>
                                                                <option value="steven@<?= STORE_DOMAIN?>">steven@<?= STORE_DOMAIN?></option>
                                                                <option value="dev@<?= STORE_DOMAIN?>">dev@<?= STORE_DOMAIN?></option>
                                                                <option value="info@<?= STORE_DOMAIN?>">info@<?= STORE_DOMAIN?></option>
                                                                <option value="events@<?= STORE_DOMAIN?>">events@<?= STORE_DOMAIN?></option>
                                                                <option value="accounts@<?= STORE_DOMAIN?>">accounts@<?= STORE_DOMAIN?></option>
                                                                <option value="support@<?= STORE_DOMAIN?>">support@<?= STORE_DOMAIN?></option>
                                                                <option value="notifications@<?= STORE_DOMAIN?>">notifications@<?= STORE_DOMAIN?></option>
                                                            </select>
                                                        </div>

                                                        <?= form_error('from_email') ?>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <label>
                                                    Templates
                                                </label>
                                                <div class="hr-fields-wrap">
                                                    <select class="form-control js-email-template">
                                                        <option value="null">[Select a template]</option>
                                                        <?php if(sizeof($admin_templates)) { ?>
                                                        <?php   foreach ($admin_templates as $k0 => $v0) { ?>
                                                        <option value="<?=$v0['id'];?>"><?=$v0['templateName'];?></option>
                                                        <?php   } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>
                                                    Subject
                                                    <span class="hr-required">*</span>
                                                </label>				
                                                <div class="hr-fields-wrap">
                                                    <?php
                                                    echo form_input('subject', set_value('subject'), 'class="hr-form-fileds" id="subject"');
                                                    echo form_error('subject');
                                                    ?>                                                    
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <label>Message</label>
                                                        <span class="hr-required">*</span>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <div class="hr-fields-wrap" style="width: 91%;">
                                                            <textarea style="padding:10px; height:200px;" class="hr-form-fileds ckeditor" id="message" name="message"><?php echo set_value('message'); ?></textarea>
                                                            <?php echo form_error('message'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="offer-letter-help-widget pull-right" style="top: 0;">
                                                            <div class="tags-area pull-left">
                                                                <br />
                                                                <strong>Email Tags :</strong>
                                                                <ul class="tags">
                                                                    <li>{{first_name}}</li>
                                                                    <li>{{last_name}}</li>
                                                                    <li>{{phone}}</li>
                                                                    <li>{{email}}</li>
                                                                    <li>{{company_name}}</li>
                                                                    <li>{{company_address}}</li>
                                                                    <li>{{company_phone}}</li>
                                                                    <li>{{career_site_url}}</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <input type="submit" value="Send Message" class="site-btn" id="submit_button" onclick="return validate_form();">
                                            </li>
                                        </ul>
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


<script type="text/javascript">

//    $('#employerSelector').select2();

    $('input[name="receiver"]').change(function (e) {
        var div_to_show = $(this).val();
        display(div_to_show);
    });

    $(document).ready(function () {
        var div_to_show = $('input[name="receiver"]').val();
        display(div_to_show);
    });
    function display(div_to_show) {
        $('input[name="subject"]').prop('disabled', false);

        if (div_to_show == 'to_email') {
            $('#employerSelector').hide();
            $('#custom_email').show();
            $("#custom_email").prop('required', true);
        } else if (div_to_show == 'to_employees') {
            $("#custom_email").prop('required', false);
            $('#custom_email').hide();
            $('#employerSelector').show();
        }
    }

    function validate_form() { // validate form for empty selects
        var div_to_show = $('input[name=receiver]:checked', '#compose_message_form').val()

        if (div_to_show == 'to_employees') {
            if ($('#to_id :selected').val() == '') {
                alertify.alert('No employee selected', "Please select some employees");
                return false;
            }
        }
    }

    $('#submit_button').click(function () {
        $("#compose_message_form").validate({
            ignore: [],
            rules: {
                subject: {
                    required: true,
                },
                message: {
                    required: true,
                },
                toemail: {
                    required: function (element) {
                        return $('input[name=receiver]:checked', '#compose_message_form').val() == 'to_email';
                    }
                },
                from_email: {
                    required: true,
                }
            },
            messages: {
                subject: {
                    required: 'Subject is required',
                },
                message_body: {
                    required: 'Message body is required',
                },
                toemail: {
                    required: "Email address is required.",
                }
            },
            submitHandler: function (form) {
                $('#candidate-loader').show();
                $('#submit_button').addClass('disabled-btn');
                $('#submit_button').prop('disabled', true);
                form.submit();
            }
        })
    });


    $(function(){
        var email_templates = <?=@json_encode($admin_templates);?>;

        $('.js-email-template').change(function(event) {
            var obj = indexFinder('id', $(this).val());
            if(!obj) return false;
            //
            $('input[name="subject"]').val(obj['subject']);
            // $('textarea[name="message"]').val(obj['body']);
            CKEDITOR.instances['message'].setData(obj['body']);
        });

        function indexFinder(searchIndex, searchValue){
            var i = 0,
            arrLength = email_templates.length;
            for(i; i < arrLength; i++){
                if(email_templates[i][searchIndex] == searchValue) return email_templates[i];
            }
            return false;
        }
    })
</script>

<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }
</style>
