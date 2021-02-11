<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">Background Check Activation</span>
                        <span class="desc_p">This form allows Accurate Background to verify your company information. Please completely fill in this form and acknowledge the End User Agreement. </span>
                        <span class="desc_p">You will only be asked one time for each company account to complete the Accurate Background company verification form.</span>
                    </div>
                    <form method="POST" id="background_check_form" class="background_check_form">
                        <div class="universal-form-style-v2">
                            <ul>
                                <li class="form-col-50-left">
                                    <label>company name<span class="staric">*</span></label>
                                    <input name="companyName" type="text" <?php if (!empty($companyDetail['CompanyName'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('companyName', $companyDetail['CompanyName']); ?>" class="invoice-fields">
                                    <?php echo form_error('companyName'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Type Of Business<span class="staric">*</span></label>
                                    <input type="text" name="typeOfBusiness" value="<?php echo set_value('typeOfBusiness'); ?>" class="invoice-fields">
                                    <?php echo form_error('typeOfBusiness'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Fed. Tax ID #<span class="staric">*</span></label>
                                    <input name="taxId" type="text"  value="<?php echo set_value('taxId'); ?>" class="invoice-fields">
                                    <?php echo form_error('taxId'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Years In Business<span class="staric">*</span></label>
                                    <input name="yearInBusiness" type="text" value="<?php echo set_value('yearInBusiness'); ?>" class="invoice-fields">
                                    <?php echo form_error('yearInBusiness'); ?>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <label>Street Address<span class="staric">*</span></label>
                                    <div class="comment-area">
                                        <textarea name="address" <?php if (!empty($companyDetail['Location_Address'])) { ?>readonly="" <?php } ?>  class="form-col-100 invoice-fields"><?php echo set_value('address', $companyDetail['Location_Address']); ?></textarea>
                                    </div>
                                    <?php echo form_error('address'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>City<span class="staric">*</span></label>
                                    <input type="text" name="city" <?php if (!empty($companyDetail['Location_City'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('city', $companyDetail['Location_City']); ?>"  class="invoice-fields">
                                    <?php echo form_error('city'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>State<span class="staric">*</span></label>
                                    <input type="text" name="state"  <?php if (!empty($companyDetail['Location_State'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('state', $companyDetail['Location_State']); ?>" class="invoice-fields">
                                    <?php echo form_error('state'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Zip<span class="staric">*</span></label>
                                    <input type="text" name="zip" <?php if (!empty($companyDetail['Location_ZipCode'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('zip', $companyDetail['Location_ZipCode']); ?>"  class="invoice-fields">
                                    <?php echo form_error('zip'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Telephone<span class="staric">*</span></label>
                                    <input type="text" name="telephone" <?php if (!empty($companyDetail['PhoneNumber'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('telephone', $companyDetail['PhoneNumber']); ?>"  class="invoice-fields">
                                    <?php echo form_error('telephone'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Fax<span class="staric">*</span></label>
                                    <input type="text" name="fax" value="<?php echo set_value('fax'); ?>"  class="invoice-fields">
                                    <?php echo form_error('fax'); ?>
                                </li>                                                        
                                <li class="form-col-50-right">
                                    <label>Contact Person<span class="staric">*</span></label>
                                    <input type="text" name="contactName" <?php if (!empty($companyDetail['ContactName'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('contactName', $companyDetail['ContactName']); ?>"  class="invoice-fields">
                                    <?php echo form_error('contactName'); ?>
                                </li>
                                <div class="form-col-100">
                                    <li class="form-col-100 autoheight">
                                        <label>Company Type<span class="staric">*</span></label>
                                        <input name="companyType" value="" type="radio" style="visibility: hidden;">
                                    </li>             
                                    <li class="form-col-50-left autoheight">
                                        <div class="checkbox-field">
                                            <figure>
                                                <input name="companyType" value="Corporation" type="radio" <?php echo set_radio('companyType', 'Corporation'); ?> id="corporation-type">
                                            </figure>
                                            <div class="text">
                                                <label for="corporation-type">Corporation</label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-50-right autoheight">
                                        <div class="checkbox-field">
                                            <figure>
                                                <input name="companyType" value="Partnership" type="radio" <?php echo set_radio('companyType', 'Partnership'); ?> id="partnership-type">
                                            </figure>
                                            <div class="text">
                                                <label for="partnership-type">Partnership</label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-50-left autoheight">
                                        <div class="checkbox-field">
                                            <figure>
                                                <input name="companyType" value="Sole Proprietorship" type="radio" <?php echo set_radio('companyType', 'Sole Proprietorship'); ?> id="sole-proprietership-type">
                                            </figure>
                                            <div class="text">
                                                <label for="sole-proprietership-type">Sole Proprietorship</label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="form-col-50-right autoheight send-email">
                                        <div class="form-col-30 other-business-type">
                                            <div class="checkbox-field">
                                                <figure>
                                                    <input  name="companyType" type="radio" value="Other" <?php echo set_radio('companyType', 'Other'); ?>  id="other-type">
                                                </figure>
                                                <div class="text">
                                                    <label for="other-type">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-col-70">
                                            <input type="text" value="<?php echo set_value('otherCompanyType'); ?>" name="otherCompanyType" class="invoice-fields">
                                        </div>                                    
                                    </li> 
                                    <?php echo form_error('companyType'); ?>
                                </div>
                                <li class="form-col-50-right">
                                    <label>Title<span class="staric">*</span></label>
                                    <input name="title" type="text" value="<?php echo set_value('title'); ?>" class="invoice-fields">
                                    <?php echo form_error('title'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Contact Telephone<span class="staric">*</span></label>
                                    <input name="contactTelephone" type="text" value="<?php echo set_value('contactTelephone'); ?>" class="invoice-fields">
                                    <?php echo form_error('contactTelephone'); ?>
                                </li>
                                <li class="form-col-50-right">
                                    <label>Contact Email Address<span class="staric">*</span></label>
                                    <input type="text" name="email" <?php if (!empty($companyDetail['email'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('email', $companyDetail['email']); ?>"  class="invoice-fields">
                                    <?php echo form_error('email'); ?>
                                </li>
                                <li class="form-col-50-left">
                                    <label>Company Website URL<span class="staric">*</span></label>
                                    <input name="website" type="text" <?php if (!empty($companyDetail['WebSite'])) { ?>readonly="" <?php } ?> value="<?php echo set_value('website', $companyDetail['WebSite']); ?>"  class="invoice-fields">
                                    <?php echo form_error('website'); ?>
                                </li>
                                <li class="form-col-100">
                                    <div class="checkbox-field term-condition">
                                        <figure>
                                            <input type="checkbox"  name="check_box" value="1" id="squared">
                                        </figure>
                                        <div class="text">
                                            <label for="squared" class="hint-label">
                                                I have Read and Understand the
                                                <a  href="javascript:;" data-toggle="modal" data-target="#user_agreement">
                                                    End User Agreement.
                                                </a> 
                                                <span class="staric">*</span>
                                            </label>    
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <input type="submit" value="Submit" onclick="validate_form()" class="submit-btn">
                                    <input type="button" value="Cancel" class="submit-btn btn-cancel">
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>          
        </div>
    </div>
</div>
<div id="user_agreement" class="modal fade file-uploaded-modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">End User Agreement</h4>
            </div>
            <iframe class="uploaded-file-preview" src="https://docs.google.com/gview?url=<?php echo AWS_S3_BUCKET_URL; ?>End-User-Agreement---US-and-NonUS-1PZ0A.doc&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
        </div>
    </div>
</div>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script  language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
                                        function validate_form() {
                                            $("#background_check_form").validate({
                                                ignore: ":hidden:not(select)",
                                                rules: {
                                                    companyName: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    typeOfBusiness: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    taxId: {
                                                        required: true,
                                                        pattern: /^[0-9\- ]+$/
                                                    },
                                                    yearInBusiness: {
                                                        required: true,
                                                        pattern: /^[0-9\- ]+$/
                                                    },
                                                    address: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\-#,':;. ]+$/
                                                    },
                                                    city: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/

                                                    },
                                                    state: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    zip: {
                                                        required: true,
                                                        pattern: /^[0-9\- ]+$/
                                                    },
                                                    telephone: {
                                                        required: true,
                                                        pattern: /^[0-9\-]+$/
                                                    },
                                                    fax: {
                                                        required: true,
                                                        pattern: /^[0-9\-]+$/
                                                    },
                                                    contactName: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    companyType: {
                                                        required: true,
                                                    },
                                                    title: {
                                                        required: true,
                                                        pattern: /^[a-zA-Z0-9\- ]+$/
                                                    },
                                                    contactTelephone: {
                                                        required: true,
                                                        pattern: /^[0-9\-]+$/
                                                    },
                                                    email: {
                                                        required: true,
                                                        email: true
                                                    },
                                                    website: {
                                                        required: true,
                                                    },
                                                    check_box: {
                                                        required: true,
                                                    }
                                                },
                                                messages: {
                                                    companyName: {
                                                        required: 'Company Name is required',
                                                        pattern: 'Letters, numbers, and dashes only please'
                                                    },
                                                    typeOfBusiness: {
                                                        required: 'Type Of Business is required',
                                                        pattern: 'Letters, numbers, and dashes only please'
                                                    },
                                                    taxId: {
                                                        required: 'Tax Id is required',
                                                        pattern: 'Numbers, and dashes only please'
                                                    },
                                                    yearInBusiness: {
                                                        required: 'Year In Business is required',
                                                        pattern: 'Numberic values only please'
                                                    },
                                                    city: {
                                                        required: 'City Name is required',
                                                        pattern: 'Please Provide valid City'
                                                    },
                                                    zip: {
                                                        required: 'Zip Code is required',
                                                        pattern: 'Please provide valid Zip Code'
                                                    },
                                                    address: {
                                                        required: 'Address is required',
                                                        pattern: 'Please provide valid Address'
                                                    },
                                                    telephone: {
                                                        required: 'Phone Number is required',
                                                        pattern: 'Please provide valid Phone Number'
                                                    },
                                                    fax: {
                                                        required: 'Fax Number is required',
                                                        pattern: 'Please provide valid Fax Number'
                                                    },
                                                    contactName: {
                                                        required: 'Contact Name is required',
                                                        pattern: 'Letters, numbers, and dashes only please'
                                                    },
                                                    companyType: {
                                                        required: 'Company Type is required',
                                                    },
                                                    title: {
                                                        required: 'Title is required',
                                                        pattern: 'Please provide valid Title'
                                                    },
                                                    contactTelephone: {
                                                        required: 'Contact Phone Number is required',
                                                        pattern: 'Please provide valid Phone Number'
                                                    },
                                                    email: {
                                                        required: 'Please provide Valid email'
                                                    },
                                                    website: {
                                                        required: 'Website Link is Required'
                                                    },
                                                    state: {
                                                        required: 'State Name is Required'
                                                    },
                                                    check_box: {
                                                        required: 'You must agree to our Terms & Conditions',
                                                    }

                                                },
                                                submitHandler: function (form) {
                                                    form.submit();
                                                }
                                            });
                                        }</script>