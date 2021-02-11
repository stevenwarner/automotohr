<div class="dash-inner-block">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="heading-title page-title">
                <h1 class="page-title"><i class="fa fa-shopping-cart"></i>Edit a product</h1>
            </div>
            <!-- Add New Product Start -->
            <div class="hr-add-new-product">
               <?php echo form_open(''); ?>
                    <ul>
                        <li>
                            <label>
                                Name
                                <span class="hr-required">*</span>
                            </label>				
                            <div class="hr-fields-wrap">
                                <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'product_name'),$edit_data->product_name); ?>
                                <?php echo form_error('product_name'); ?>
                            </div>
                        </li>
                        <li>
                            <label>Short Description</label>
                            <div class="hr-fields-wrap">
                                
                                <textarea style="width:100%;height:100px; padding:10px;" class="hr-form-fileds" name="short_description"><?php echo $edit_data->product_short_desc;?></textarea>
                            </div>
                        </li>
                        <li>
                            <label>Employer Portal</label>
                            <div class="hr-fields-wrap">
                              <?php  if($edit_data->product_employer_portal==0){$chk=FALSE;}else{$chk=TRUE;} ?>
                                <?php echo form_checkbox('employer_portal',1,$chk); ?>
                                <small>Please check the checkbox if this product is for employer's portal</small>
                            </div>
                        </li>
                        <li>
                            <label>Detailed Description</label>
                            <div class="hr-fields-wrap">
                                <?php if(isset($_POST['detail_description'])){$detail_description=$_POST['detail_description'];}?>
                                <textarea style="width:100%;height:250px; padding:10px;" class="hr-form-fileds" name="detail_description"><?php echo $edit_data->product_detail_desc;?></textarea>
                            </div>
                        </li>
                        <li>
                            <label>User Group</label>
                            <div class="hr-fields-wrap">
                                <div>                                                                        
                                    <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'user_group'),$edit_data->product_user_group); ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <label>Active</label>
                            <div class="hr-fields-wrap">
                                <?php  if($edit_data->product_active==0){$chk=FALSE;}else{$chk=TRUE;} ?>
                               <?php echo form_checkbox('active','1',$chk); ?>
                            </div>
                        </li>
                        <li>
                            <label>Availability</label>
                            <div class="hr-fields-wrap registration-date">
                                <div class="hr-register-date">
                                    <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'availablity_from','type'=>'date'),$edit_data->product_available_from); ?>
                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>To
                                </div>
                                <div class="hr-register-date">
                                    <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'availablity_to','type'=>'date'),$edit_data->product_available_to); ?>
                                    <button class="ui-datepicker-trigger" type="button"><i class="fa fa-calendar"></i></button>
                                </div>

                            </div>
                        </li>
                        <li>
                            <label>Trial Product</label>
                            <div class="hr-fields-wrap">
                                <?php  if($edit_data->product_active==0){$chk=FALSE;}else{$chk=TRUE;} ?>
                                <?php echo form_checkbox('trial_product','1',$chk); ?>
                                <small>Trial Product will not be available for a user after the 1st purchase</small>
                            </div>
                        </li>
                        <li>
                            <label>Welcome Email</label>
                            <div class="hr-fields-wrap">
                                <div class="hr-select-dropdown">
                                    
                                    <select class="invoice-fields" name="welcome_email">
                                        <option value="<?php echo $edit_data->product_email_template_id;?>"><?php echo $edit_data->product_email_template_id;  ?></option>
                                        <?php
                                    foreach($template_name as $template)
                                    {
                                        echo '<option value="'.$template->template_name.'">'.$template->template_name.'</option>'; 
                                    }
                                    ?>
                                    </select>
                                </div>
                                <small>You can create more email templates for different products from Admin Panel &gt; Email Templates &gt; Product Emails</small>
                            </div>
                        </li>	
                        <li>
                            <div class="hr-fields-wrap">
                                
                                <?php  
                                
                                if($edit_data->product_payment=="One-time payment"){$chk=TRUE; $chk1=FALSE; }
                                else{$chk=FALSE;$chk1=TRUE;} ?>
                                
                              <?php   echo form_checkbox(array('type'=>'radio','name'=>'payment',), 'One-time payment',$chk); ?><span>&nbsp;One-time payment</span><br>
                              <?php   echo form_checkbox(array('type'=>'radio','name'=>'payment',), 'Recurring Subscription',$chk1); ?><span>&nbsp;Recurring Subscription</span> 
                            </div>
                        </li>
                        <li>
                            <label>price</label>
                            <div class="hr-fields-wrap">
                                <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'price'),$edit_data->product_price); ?>
                            </div>
                        </li>
                        <li>
                            <label>Period<span class="hr-required">*</span></label>
                            <div class="hr-fields-wrap">
                                <?php echo form_input(array('class'=>'hr-form-fileds','name'=>'period','style'=>'width:100px; float:left'),$edit_data->product_period); ?>
                                <div class="hr-select-dropdown" style="width:200px; float:left; margin:0 0 0 10px;">
                                    <select class="invoice-fields" name="period_status">
                                        <option value="<?php  echo $edit_data->product_period_status;?>"><?php  echo $edit_data->product_period_status;?></option>
                                        <option value="Unlimited">Unlimited</option>
                                        <option value="Week">Week(s)</option>
                                        <option value="Month">Month(s)</option>
                                        <option value="Year">Year(s)</option>
                                    </select>
                                </div>
                                <?php echo form_error('period'); ?>
                            </div>     
                        </li>		
                        <li>
                            <?php echo form_submit(array('type'=>'submit','class'=>'site-btn',),'save'); ?>
                        </li>
                    </ul>
                </form>
            </div>
            <!-- Add New Product End -->
        </div>
    </div>
</div>
