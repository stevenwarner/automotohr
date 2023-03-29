<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php echo form_open_multipart('', array('id'=>'domainmanagement')); ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-8">
                                <div class="create-job-wrap">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="form-col-100">
                                        <div class="info-text">
                                        <?php if($company['domain_type']=='addondomain'){ ?>
                                                Your Company Career Page is currently configured to work with your personal domain name. 
                                                <p><a href="<?php echo STORE_PROTOCOL . $company["sub_domain"]; ?>" target="_blank" title="<?php echo $company["sub_domain"]; ?>"><?php echo $company["sub_domain"]; ?></a></p>
                                        <?php } else { ?>
                                                Your Company Career Page is currently configured to work with the <?php echo STORE_NAME; ?> sub-domain.
                                                <p><a href="<?php echo STORE_PROTOCOL . $company["sub_domain"]; ?>" target="_blank" title="<?php echo $company["sub_domain"]; ?>"><?php echo $company["sub_domain"]; ?></a></p>
                                        <?php } ?>
                                                You can choose how your current career page domain name is displayed either as a new sub-domain which is the default (YourCompanyName.<?php echo STORE_DOMAIN; ?>) 
                                                or by using your company website domain address (MyCompanyName.com.<br/>
                                                Please choose from the options below.                            
                                        </div>
                                    </div>
                                    <div class="universal-form-style-v2">
                                        <div class="form-col-100 box-view">
                                            <div class="sub-domain-header">
                                                <input class="select-domain" type="radio" name="domain_type" value="subdomain" id="subdomain">
                                                <h3>Sub Domain Configurations</h3>
                                            </div>
                                            <div class="info-text">
                                                <?php echo STORE_NAME; ?> offers you the ability to configure your Career page Subdomain.
                                                A subdomain is a domain that is part of a larger domain.
                                                For example: yourcompanyname.<?php echo STORE_DOMAIN; ?> and yourchoice.<?php echo STORE_DOMAIN; ?> are subdomains and are the default setting for your Career page. 
                                                With this setting you have a fully functioning company Career page that you can start using right away.                                                       
                                            </div>
                                            <p class="domain_message">Note: Special characters and period sign are not allowed in domain name.</p> 
                                            <span class="form-col-50-left">
                                                <input type="text" id="new_subdomain" class="invoice-fields" name="new_subdomain" value="">
                                            </span>
                                            <span class="form-col-50-right">
                                                <p class="help_domain_name"> .<?php echo STORE_DOMAIN; ?></p>
                                            </span>
                                        </div>
                                        <div class="form-col-100 box-view">
                                            <div class="sub-domain-header">
                                                <input class="select-domain" type="radio" name="domain_type" value="addondomain" id="addondomain">
                                                <h3>Your Domain Configurations</h3>
                                            </div>
                                            <div class="info-text">
                                                Your Domain Configuration on <?php echo STORE_NAME; ?> is built with the ability to completely Synchronize with your existing company website domain URL. 
                                                All of your Career page activities will be managed from <?php echo ucwords(STORE_DOMAIN); ?> and will be displayed on your company website Career page domain. 
                                                (It will generally look something like this MyCompanyName.com/Employment) 
                                                Please point your domain to following DNS.
                                                <p> Name Server1: <b><?php echo SERVER_DNS_PRIMARY; ?></b><br/>
                                                    Name Server2: <b><?php echo SERVER_DNS_SECONDARY; ?></b></p>
                            
                                                <p><b>Please Note: </b> <br>(a) You need to make sure that the domain you are trying to add over here is pointing to the above Name Servers listed at your hosting panel.
                                                    <br>(b) Your domain should have SSL certificate installed on it otherwise visitors will get security warning.
                                                </p>
                                            </div>
                                            <p class="domain_message">Note: Please provide valid domain name with out <b>'<?php echo STORE_PROTOCOL; ?>www.'</b></p>
                                            <span class="form-col-50-left">
                                                <input type="text" id="new_addondomain" class="invoice-fields" name="new_addondomain" value="">
                                            </span>
                                        </div>
                                            <div class="form-col-100">
                                                <div class="btn-wrp">
                                                    <input type="hidden" name="id" value="<?php echo $company['sid'];?>">
                                                    <input type="hidden" name="user_sid" value="<?php echo $company['user_sid'];?>">
                                                    <input type="hidden" name="old_domain" value="<?php echo $company['sub_domain'];?>">
                                                    <input type="submit" value="Save" onclick="return validate_form()" class="submit-btn">
                                                    <input type="button" value="Cancel" class="submit-btn btn-cancel" onClick="document.location.href = '<?= base_url('my_settings') ?>'" />
                                                </div>
                                            </div>
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
<script type="text/javascript">
    $('input[name="domain_type"]').click(function (e) {
        var domain_type = $(this).val();
        if (domain_type == 'subdomain') {
            $("#new_subdomain").prop('required',true);
            $("#new_addondomain").prop('required',false);
        } else {
            $("#new_addondomain").prop('required',true);
            $("#new_subdomain").prop('required',false);
        }
    });
    
    $('input[name="new_subdomain"]').click(function (e) {
            $("#new_subdomain").prop('required',true);
            $("#new_addondomain").prop('required',false);
            $("#subdomain").prop("checked", true)
            //$("#subdomain").attr('checked', 'checked');
    });
    
    $('input[name="new_addondomain"]').click(function (e) {
            $("#new_subdomain").prop('required',false);
            $("#new_addondomain").prop('required',true);
            $("#addondomain").prop("checked", true)
            //$("#addondomain").attr('checked', 'checked');
    });
    
    function validate_form() {
        $("#domainmanagement").validate({
            ignore: ":hidden:not(select)",
            rules: {
                new_subdomain:      {
                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                    },
                new_addondomain:    {
                                        pattern: /^[a-zA-Z0-9\- .]+$/
                                    },
                    },
            messages: {
                new_subdomain:      {
                                        required: 'Please provide new sub-domain',
                                        pattern: 'Letters, numbers, and dashes only please'
                                    },
                new_addondomain:    {
                                        required: 'Please provide new addondomain',
                                        pattern: 'Letters, numbers, and dashes only please'
                                    },
                    },
            submitHandler: function (form) {
                    form.submit();
                }
            });
        }
</script>