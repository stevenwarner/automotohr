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
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                        <div class="dashboard-conetnt-wrp">
                            <?php if($fb_available == 1){?>
                            
                                <div class="col-xs-12">
                                    <?php if(!empty($fb_config) && $fb_config['fb_app_id'] != '' && $fb_config['fb_app_secret'] != '' && $fb_config['fb_page_url'] != ''){ ?>
                                        <div class="well well-sm">
                                            <a class="delete-all-btn active-btn" target="_blank" href="https://www.facebook.com/dialog/pagetab?app_id=<?php echo $fb_config['fb_app_id']; ?>&next=<?php echo STORE_FULL_URL_SSL; ?>/facebook_configuration">Click Here To Authorize Your Facebook App</a>
                                            &nbsp;
                                            <a class="delete-all-btn active-btn" target="_blank" href="<?php echo base_url('facebook_configuration/instructions/');?>">Facebook API Instructions</a>

                                            <div class="clear"></div>
                                            <h4>Please Use Following Link as "Secure Page Tab URL" </h4>
                                            <?php
                                                $url = base_url() . 'jobs_list/facebook/' . $fb_config['fb_unique_identifier'];

                                            ?>

                                            <input type="text" class="invoice-fields" readonly="readonly" value="<?php echo $url; ?>" />

                                            <div class="clear"></div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="well well-sm">
                                            <a class="delete-all-btn active-btn" target="_blank" href="<?php echo base_url('facebook_configuration/instructions/');?>">Facebook Api Instructions</a>
                                            <div class="clear"></div>
                                        </div>
                                    <?php } ?>
                                    <div class="universal-form-style-v2">
                                        <ul>
                                            <form action="<?php echo base_url('facebook_configuration'); ?>" id="form_save_facebook_configuration" method="post">

                                                <input type="hidden" id="perform_action" name="perform_action" value="save_facebook_app_config" />
                                                <input type="hidden" id="sid" name="sid" value="<?php echo (!empty($fb_config)? $fb_config['sid']: '');?>" />
                                                <input type="hidden" id="fb_unique_identifier" name="fb_unique_identifier" value="<?php echo $fb_unique_identifier;?>" />


                                                <li class="form-col-100">
                                                    <label for="fb_page_url">Facebook Page Url<span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" id="fb_page_url" name="fb_page_url" value="<?php echo (!empty($fb_config)? $fb_config['fb_page_url']: '');?>" />
                                                </li>
                                                <li class="form-col-100">
                                                    <label for="fb_app_id">App Id<span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" id="fb_app_id" name="fb_app_id" value="<?php echo (!empty($fb_config)? $fb_config['fb_app_id']: '');?>" />
                                                </li>
                                                <li class="form-col-100">
                                                    <label for="fb_app_secret">App Secret<span class="staric">*</span></label>
                                                    <input type="text" class="invoice-fields" id="fb_app_secret" name="fb_app_secret" value="<?php echo (!empty($fb_config)? $fb_config['fb_app_secret']: '');?>" />
                                                </li>
                                            </form>
                                            <li class="form-col-100">
                                                <input type="button" class="delete-all-btn active-btn" onclick="fSaveFaceookConfigrurationForm();" value="Save" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            
                            <?php } else { ?>
                                <div class="well well-sm">
                                    <h2 class="text-center">Please Purchase PageTab Facebook App Integration.</h2>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function fValidateFacebookConfigurationForm(){
        $('#form_save_facebook_configuration').validate({
            rules: {
                fb_page_url:{
                    required: true
                },
                fb_app_id: {
                    required: true
                },
                fb_app_secret:{
                    required: true
                }


            }
        })
    }

    function fSaveFaceookConfigrurationForm(){
        fValidateFacebookConfigurationForm();
        if($('#form_save_facebook_configuration').valid()){
            $('#form_save_facebook_configuration').submit();
        }
    }
</script>