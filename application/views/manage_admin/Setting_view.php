
<?php echo $this->session->flashdata('message'); ?>

<div class="row" style="margin-left: 1%; padding-right: 2%;padding-top: 2%;">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div class="heading-title page-title">
            <h1 class="page-title"><i class="fa fa-cog"></i>General Settings</h1>
        </div>
        <!-- Settings Start -->
        <div class="hr-setting-page">
            <?php echo form_open(''); ?> 
                <ul>
                    <li>
                        <label>Site Title</label>
                        <div class="hr-fields-wrap">
                            <?php echo  form_input(array('class'=>'hr-form-fileds','name'=>'site_title'),'')  ?>
                            <?php echo form_error('site_title'); ?>
                        </div>
                    </li>
                    <li>
                        <label>Admin Email</label>
                        <div class="hr-fields-wrap">
                            <?php echo  form_input(array('class'=>'hr-form-fileds','type'=>'email','name'=>'admin_email'),'') ?>
                            <?php echo form_error('admin_email'); ?>
                        </div>
                    </li>
                    <li>
                        <label>Send Payment To</label>
                        <div class="hr-fields-wrap">
                            <textarea style="padding:10px; height:200px;" class="hr-form-fileds" name="payment_send"></textarea>
                            <?php echo form_error('payment_send'); ?>
                        </div>
                    </li>
                    <li>
                        <label>Mail Send From</label>
                        <div class="hr-fields-wrap">
                            <?php echo  form_input(array('class'=>'hr-form-fileds','name'=>'mail_send_from'),'')  ?>
                            <?php echo form_error('mail_send_from'); ?>
                        </div>
                    </li>
                    <li>
                        <label>Mail Send E-mail</label>
                        <div class="hr-fields-wrap">
                           <?php echo  form_input(array('class'=>'hr-form-fileds','type'=>'email','name'=>'mail_send_email'),'')  ?>
                            <?php echo form_error('mail_send_email'); ?>
                        </div>
                    </li>
                    <li>
                        <label>Mail Signature</label>
                        <div class="hr-fields-wrap">
                            <?php echo  form_input(array('class'=>'hr-form-fileds','name'=>'mail_signature'),'')  ?>
                            <?php echo form_error('mail_signature'); ?>
                        </div>
                    </li>
                    <li>
                       <?php echo form_submit('setting_submit','submit',array('class'=>'site-btn'));?>
                    </li>
                </ul>
            <?php echo form_close(); ?> </form>
        </div>
        <!-- Settings End -->
    </div>
</div>