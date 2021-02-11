<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modal Box Start -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-popup" role="document">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading" id="myModalLabel">applied for job</h4>
            </div>
            <div class="modal-body">
                <!-- Apply Job Form Start -->
                <div class="apply-job-from">
                    <ul>
                            <?php echo form_open_multipart('theme1/theme1/apply_job', 'post'); ?>
                            <li>
                                <?php echo form_label('first name'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter Your Email', 'name' => 'first_name'), set_value('first_name')); ?>
                                <?php echo form_error('first_name'); ?>
                            </li>
                            <li>
                                <?php echo form_label('last name'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter Description', 'name' => 'last_name'), set_value('last_name')); ?>
                                <?php echo form_error('last_name'); ?>  
                            </li>
                            <li>
                                <?php echo form_label('profile picture<span class="staric">*</span>'); ?>   
                                <div class="form-fields choose-file">
                                    <div class="file-name">Select a File</div>
                                    <?php echo form_input(array('class' => 'choose-file-filed bg-color', 'name' => 'profile_picture', 'type' => 'file'), set_value('profile_picture')); ?>
                                    <a class="choose-btn bg-color" href="javascript:void(0)">choose file</a>
                                </div>
                                <?php echo form_error('profile_picture'); ?> 
                            </li>
                            <li>
                                <?php echo form_label('share youtube video'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Youtube Video Link', 'name' => 'ytvideo_link'), set_value('ytvideo_link')); ?>
                                <?php echo form_error('ytvideo_link'); ?>  
                            </li>
                            <li>
                                <?php echo form_label('email<span class="staric">*</span>'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter Email', 'name' => 'email_address', 'type' => 'email'), set_value('email_address')); ?>
                                <?php echo form_error('email_address'); ?> 
                            </li>
                            <li>
                                <?php echo form_label('Phone'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Phone Number', 'name' => 'phone_number'), set_value('phone_number')); ?>
                                <?php echo form_error('phone_number'); ?> 
                            </li>
                            <li>
                                <?php echo form_label('street address'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter Address', 'name' => 'stree_address'), set_value('stree_address')); ?>
                                <?php echo form_error('stree_address'); ?> 
                            </li>
                            <li>
                                <?php echo form_label('city'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter City', 'name' => 'city'), set_value('city')); ?>
                                <?php echo form_error('city'); ?> 
                            </li>
                            <li>
                                <?php echo form_label('state'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter State', 'name' => 'state'), set_value('state')); ?>
                                <?php echo form_error('state'); ?>
                            </li>
                            <li>
                                <?php echo form_label('country'); ?>
                                <?php echo form_input(array('class' => 'form-fields', 'placeholder' => 'Enter Your Country', 'name' => 'country'), set_value('country')); ?>
                                <?php echo form_error('country'); ?>
                            </li>
                            <li>
                                <?php echo form_label('attach resume<span class="staric">*</span>'); ?>
                                <div class="form-fields choose-file">
                                    <div class="file-name">Select a File</div>
                                    <?php echo form_input(array('class' => 'choose-file-filed', 'name' => 'resume', 'type' => 'file'), set_value('resume')); ?>
                                    <a class="choose-btn bg-color" href="javascript:void(0)">choose file</a>
                                </div>
                                <?php echo form_error('resume'); ?>
                            </li>
                            <li>
                                <?php echo form_label('attach cover <span class="staric">*</span>'); ?>
                                <div class="form-fields choose-file">
                                    <div class="file-name" > Select a File</div>
                                    <?php echo form_input(array('class' => 'choose-file-filed', 'name' => 'cover', 'type' => 'file'), set_value('cover')); ?>
                                    <a class="choose-btn bg-color" href="javascript:void(0)">choose file</a>
                                </div>
                                 <?php echo form_error('cover'); ?>
                            </li>
                            <li class="questionare-section">
                                <?php echo form_label('Attach Resume (.pdf .docx .doc .jpg .jpe .jpeg .png .gif) Attach Cover (.pdf .docx .doc .jpg .jpe .jpeg .png .gif)'); ?>
                                <div class="wrap-container">
                                    <div class="wrap-inner">
                                        <h2 class="post-title color">Questionare</h2>
                                        <p>
                                            <?php echo form_label('Question: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod?'); ?>
                                            <?php echo form_input(array('class' => 'form-fields', 'name' => 'question'), set_value('question')); ?>
                                            <?php echo form_error('question'); ?>
                                        </p>
                                        <p>
                                            <?php echo form_label('Question: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod?'); ?>
                                        <div class="radio-btn-wrap">
                                            <div class="slide-btn">	
                                                <?php echo form_checkbox(array('name' => 'toggle', 'id' => 'slide-btn'), 'none', FALSE); ?> 
                                                <label class="bg-color" for="slide-btn"></label>
                                            </div>
                                        </div>
                                        </p>
                                        <p>
                                            <?php echo form_label('Question: Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod?'); ?>
                                        <div class="checkbox-wrap">
                                            <div class="label-wrap">
                                                <div class="squared">
                                                    <?php echo form_checkbox(array('name' => 'check1', 'id' => 'squared1'), '1', FALSE); ?>
                                                    <label for="squared1"></label>
                                                </div>
                                                <span>Lorem ipsum dolor sit amet</span>
                                            </div>
                                            <div class="label-wrap">
                                                <div class="squared">
                                                    <?php echo form_checkbox(array('name' => 'check2', 'id' => 'squared2'), '1', FALSE); ?>
                                                    <label for="squared2"></label>
                                                </div>
                                                <span>Lorem ipsum dolor sit amet</span>
                                            </div>
                                            <div class="label-wrap">
                                                <div class="squared">
                                                    <?php echo form_checkbox(array('name' => 'check1', 'id' => 'squared3'), '1', FALSE); ?>
                                                    <label for="squared3"></label>
                                                </div>
                                                <span>Lorem ipsum dolor sit amet</span>
                                            </div>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <?php echo form_submit(array('class' => 'siteBtn bg-color', 'type' => 'submit'), 'apply now'); ?>
                            </li>
                            <?php form_close(); ?>
                    </ul>
                </div>
                <!-- Apply Job Form End -->
                <div class="clear"></div>
            </div>	      
        </div>
    </div>
</div>
<!-- Modal Box End -->