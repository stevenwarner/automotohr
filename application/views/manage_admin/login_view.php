<div class="wrapper">
    <div class="main">
        <div class="container-fluid">
            <div class="row">		
                <div class="inner-content">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="hr-login-section">								
                            <div class="hr-box-wrap">
                                <div class="hr-login-logo"><img src="<?php echo site_url('assets/manage_admin/images/new_logo.JPG'); ?>" alt="<?php echo STORE_NAME; ?>-logo"></div>
                                <div class="hr-login-box">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <?php echo form_open('',array('class'=>'form-horizontal')); ?>
                                        <ul>
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_input('identity','','class="form-fileds" placeholder="User Name"'); ?>
                                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                                    <?php if(form_error('identity')){ ?>
                                                        <div class="container" style="padding-top:40px;">
                                                          <div class="alert alert-info alert-dismissible" role="alert">
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <?php echo form_error('identity');?>
                                                          </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_password('password','','class="form-fileds" placeholder="Password"'); ?>
                                                    <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                                    <?php echo form_error('password'); ?>
                                                </div>
                                            </li>	                                            
                                            <!--<li>
                                                <div class="fields-wrapper">
                                                    <?php /*echo form_checkbox('remember','1',FALSE,'id=rememberme'); */?>
                                                    <label for="rememberme">
                                                        Keep me signed in
                                                    </label>
                                                </div>
                                            </li>-->
                                            <li>
                                                <div class="fields-wrapper">
                                                    <?php echo form_submit('submit', 'login', 'class="hr-login-btn"'); ?>
                                                </div>
                                            </li>
                                        </ul>
                                    <?php echo form_close();?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="login-copyright">
                                <a href="javascript:;">&copy; <?php echo STORE_NAME; ?></a>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>