<div id="messageBox">
    <div id="content">
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-xs-12 col-sm-12 login-page-bg">
                    <div class="login-section ">
                        <div class="registered-user">
                            <h2 class="form-heading">Generate Account Password</h2>
                            <?php if($this->session->flashdata('message')){ ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('message');?>
                                    </div>
                                </div>
                            <?php } ?>
                            <form action="" method="post" id="generate-pass" class="ng-pristine ng-valid">
                                <input type="hidden" name="return_url" value="{$return_url}" />
                                <input type="hidden" name="action" value="login" />
                                <ul>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds"  type="password" id="password" name="password" placeholder="Password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('password'); ?>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="form-fileds"  type="password" id="cpassword" name="cpassword" placeholder="Confirm Password">
                                            <span class="field-icon"><i class="fa fa-unlock"></i></span>
                                            <?php echo form_error('cpassword'); ?>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="fields-wrapper">
                                            <input class="login-btn" type="submit" value="Generate Password">
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
            </div>
            <?php $this->load->view('main/demobuttons'); ?>
        </div>
    </div>
</div>
<style>
    #generate-pass{
        float: left;
        width: 100%;
        padding: 0 112px;
    }
</style>