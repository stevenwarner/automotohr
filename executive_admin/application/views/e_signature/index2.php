<?php 
    $company_name = ucwords($session['company_detail']['CompanyName']);
  ?>

<?php
if($this->uri->segment(2) != 'e_signature') $sideBar = '';
 else $sideBar = onboardingHelpWidget($company_info['sid']);

$company_sid = 0;
$users_type = '';
$users_sid = 0;
$back_url = '';
$dependants_arr = array();
$delete_post_url = '';
$save_post_url = '';
$next_btn = '';
$center_btn = '';
$back_btn = 'Dashboard';

$company_name = ucwords($session['company_detail']['CompanyName']);
if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/getting_started/' . $unique_sid);
    if ($consent == 1) {
        $next_btn = '<a href="'.base_url('onboarding/hr_documents/' . $unique_sid).'" class="btn btn-success btn-block">  Proceed To Next <i class="fa fa-angle-right"></i></a>';
    } else {
        $next_btn = '<a href="javascript:;" class="btn btn-success btn-block" onclick="func_save_e_signature();"> Save And Proceed Next <i class="fa fa-angle-right"></i></a>';
    }
    $center_btn = '<a href="'.base_url('onboarding/hr_documents/' . $unique_sid).'" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
    $back_btn = 'Review Previous Step';
    $save_post_url = current_url();
    $first_name = $applicant['first_name'];
    $last_name = $applicant['last_name'];
    $email = $applicant['email'];
} else if (isset($employee)) {
    $company_sid = $employee['parent_sid'];
    $users_type = 'employee';
    $users_sid = $employee['sid'];
    $back_url = $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system');
    $save_post_url = current_url();
    $first_name = $employee['first_name'];
    $last_name = $employee['last_name'];
    $email = $employee['email'];
} ?>

<link rel="stylesheet" href="<?php echo base_url('assets/crop/darkroom.css'); ?>">

<!-- E-signature loader -->
<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate your E-Signature...
        </div>
    </div>
</div>

<div class="main">
    <div class="container">
        
        <div class="row">
            <?php if($this->uri->segment(1) != 'e_signature'){ ?>
            <div class="col-sm-12">
                <p style="color: #cc0000;"><b><i>We suggest that you only use Google Chrome to access your account
                            and use its Features. Internet Explorer is not supported and may cause certain feature
                            glitches and security issues.</i></b></p>
                            <?=$sideBar;?>
            </div>
            <?php } ?>
                <?php // $this->load->view('templates/_parts/admin_flash_message'); ?>

                <?php if (isset($applicant)) { ?>
                    <div class="btn-panel">
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <a href="<?php echo $back_url; ?>" class="btn blue-button btn-block"><i class="fa fa-angle-left"></i>  <?= $back_btn;?></a>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <?php echo $center_btn;?>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <?php echo $next_btn;?>
                            </div>
                        </div>
                    </div>
                <?php } elseif (isset($employee)) { ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn blue-button btn-block mb-2"><i class="fa fa-arrow-left"> </i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                <?php } ?>   

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">    
                        <div class="page-header">
                            <h1 class="section-ttile">E-Signatures 
                            <?php if (isset($consent) && $consent == 1) { ?>
                                <button onclick="func_regenerate_e_signature();" type="button" class="btn blue-button break-word-text">
                                    Regenerate Signature
                                </button>
                            <?php } ?></h1>
                        </div>
                        <div class="form-wrp">
                            <?php if (isset($consent) && $consent == 1) { ?>
                                <p class="text-blue">You have successfully created your authentic legally binding Electronic Signature.</p>
                            <?php } else { ?>
                                   <p class="text-blue">You can easily create your authentic Electronic Signatures either by Drawing or Typing or Uploading. </p>         
                            <?php } ?> 
                            
                            <p class="text-blue"><b>This E-Signature will be used with all documents that require your Electronic signature.</b></p>
                            <?php if($this->uri->segment(1) != 'onboarding') { ?>
                            <p class="red-paragragh"><b><i>We suggest that you only use Google Chrome to access your account and use its Features. Internet Explorer is not supported and may cause certain feature glitches and security issues.</i></b></p>
                            <?php } ?>
                            
                            <form id="form_e_signature" enctype="multipart/form-data" method="post" action="<?php echo $save_post_url; ?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="save_e_signature" />
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type; ?>" />
                                <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />
                                <input type="hidden" id="ip_address" name="ip_address" value="<?php echo getUserIP(); ?>" />
                                <input type="hidden" id="user_agent" name="user_agent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                                <input type="hidden" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
                                <input type="hidden" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />
                                <input type="hidden" id="email_address" name="email_address" value="<?php echo $email; ?>" />
                                <input type="hidden" id="signature_timestamp" name="signature_timestamp" value="<?php echo date('d/m/Y H:i:s'); ?>" />

                                <!-- Section to populate current user info -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                <tr>
                                                    <th class="col-xs-4">Name</th>
                                                    <td class="col-xs-8"><?php echo ucwords($first_name . ' ' . $last_name); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="col-xs-4">Email</th>
                                                    <td class="col-xs-8"><?php echo $email; ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <?php $active_signature = isset($e_signature_data['active_signature']) ? $e_signature_data['active_signature'] : 'typed'; ?>
                                <!-- Section to select Signature Type -->
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 mb-2">
                                        <label class="control control--radio">
                                            Type
                                            <input <?php echo set_radio('active_signature', 'typed', $active_signature == 'typed'); ?> class="active_signature" type="radio" id="active_signature_typed" name="active_signature" value="typed" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 mb-2 e_sign_mobile_hide">
                                        <label class="control control--radio">
                                            Draw
                                            <input <?php echo set_radio('active_signature', 'drawn', $active_signature == 'drawn'); ?> class="active_signature" type="radio" id="active_signature_drawn" name="active_signature" value="drawn" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                    <!-- <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4 mb-2">
                                        <label class="control control--radio">
                                            Upload
                                            <input <?php //echo set_radio('active_signature', 'upload', $active_signature == 'upload'); ?> class="active_signature" type="radio" id="active_signature_upload" name="active_signature" value="upload" <?php //echo $consent == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div> -->
                                </div>
                                

                                <?php if ($consent != 1) { ?>
                                    <!-- Section to select Signature Font family -->
                                    <div class="row" id="select_font_family" style="margin-bottom: 50px;">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading"><strong>Please select any font to type your Signature</strong></div>
                                                <div class="panel-body signature-variations">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="row">
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_5">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="5" checked="checked">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_2">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="2">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>    
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_3">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="3">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div> 
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_4">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="4">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_6">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="6">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="form-group autoheight">
                                                                        <label class="control control--radio e_signature_font_family_11">
                                                                            John Doe
                                                                            <input type="radio" class="active_font_family" name="select_font_family" value="11">
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>  

                                <!-- Section to type the signature and show signature to user  -->
                                <div class="row">
                                    <div id="type_signature" style="min-height: 88px;" class="field-row">
                                        <?php $signature = isset($e_signature_data['signature']) ? $e_signature_data['signature'] : '';?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group autoheight">
                                                <label class="auto-height"><strong>Type Signature</strong></label> 
                                                <p class="domain_message">Hint: Please type your First and Last Name (<small>Max characters limit is 30</small>)</p>
                                                <input data-rule-required="true" type="text" class="form-control" name="signature" id="signature"  maxlength="30" value="<?php echo set_value('signature', $signature); ?>"  placeholder="John Doe" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <?php echo form_error('signature'); ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <?php if(isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image'])) { ?>
                                            <label class="auto-height"><strong>Signature</strong></label> 
                                        <?php } ?>
                                        <p class="e_signature_type_fixed_p e_signature_font_family_5" id="tergit"><?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? '<img style="max-height: 48px;" src="'.$e_signature_data['signature_bas64_image'].'"/>' : ''; ?></p>
                                    </div>
                                </div>        

                                <!-- Section to type the initial_signature and show initial_signature to user  -->
                                <div class="row">
                                    <div id="init_type_signature" style="min-height: 88px;" class="field-row">
                                        <?php $init_signature = isset($e_signature_data['init_signature']) ? $e_signature_data['init_signature'] : '';?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-wrp">
                                            <div class="form-group autoheight"> 
                                                <label class="auto-height"><strong>Type Initial </strong></label>
                                                <p class="domain_message">Hint: Please enter your initial (<small>Max characters limit is 4</small>)</p>
                                                <input data-rule-required="true" type="text" class="form-control" name="init_signature" id="init_signature"  maxlength="4" value="<?php echo set_value('init_signature', $init_signature); ?>"  placeholder="John Doe" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <?php echo form_error('init_signature'); ?>
                                    </div>    
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <?php if(isset($e_signature_data['init_signature_bas64_image']) && !empty($e_signature_data['init_signature_bas64_image'])) { ?>
                                            <label class="auto-height"><strong>Initial</strong></label> 
                                        <?php } ?>
                                        <p class="e_signature_type_fixed_p e_signature_font_family_5" id="init_tergit"><?php echo isset($e_signature_data['init_signature_bas64_image']) && !empty($e_signature_data['init_signature_bas64_image']) ? '<img style="max-height: 48px;" src="'.$e_signature_data['init_signature_bas64_image'].'"/>' : ''; ?></p>
                                    </div>
                                </div>    

                                <!-- Hidden fields for signature and signature_initial in base64 in all 3 cases  -->
                                <input type="hidden" id="drawn_signature" name="drawn_signature" value="<?php echo (isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image'])) ? $e_signature_data['signature_bas64_image'] : ''; ?>" />
                                <input type="hidden" id="drawn_init_signature" name="drawn_init_signature" value="<?php echo (isset($e_signature_data['init_signature_bas64_image']) && !empty($e_signature_data['init_signature_bas64_image'])) ? $e_signature_data['init_signature_bas64_image'] : ''; ?>" />

                                <!-- Section to draw signature and initial signature in canvas  -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div id="draw_signature_canvas" style="min-height: 250px;">
                                            <div class="field-row autoheight canvas-wrapper">
                                                <canvas class="signature-canvas" id="can" width="500" height="200"></canvas>
                                                <p>Please draw your signature</p>
                                                <button type="button" class="btn btn-danger btn-sm del-signature" onclick="clearArea();"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div id="draw_init_signature_canvas" style="min-height: 250px;">
                                            <div class="field-row autoheight canvas-wrapper">
                                                <canvas class="signature-canvas" id="init_can" width="500" height="200"></canvas>
                                                <p>Please draw your initial</p>
                                                <button type="button" class="btn btn-danger btn-sm del-signature" onclick="clearAreaInit();"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info section how to crop and save upload signature/initial image   -->
                                <div class="row" id="Info_button_crop_and_rotate" style="margin-bottom: 50px;">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Upload Image Guide</div>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-xs-3">Redo-Undo Buttons <img src="<?php echo base_url('assets/images/corp/redo-undo-btn.png'); ?>"></th>
                                                                <th class="col-xs-3">Rotate Buttons <img src="<?php echo base_url('assets/images/corp/rotate-btn.png'); ?>"></th>
                                                                <th class="col-xs-3">Crop Buttons <img src="<?php echo base_url('assets/images/corp/crop-btn.png'); ?>"></th>
                                                                <th class="col-xs-3">Save Button <img src="<?php echo base_url('assets/images/corp/save-btn.png'); ?>"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <ul class="fa-ul">
                                                                        <li>
                                                                            <i class="fa-li fa fa-reply fa-1x"></i>
                                                                            Undo Image Modification
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa-li fa fa-share fa-1x"></i>
                                                                            Redo Image Modification
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <ul class="fa-ul">
                                                                        <li>
                                                                            <i class="fa-li fa fa-undo fa-1x"></i>
                                                                            Rotate Image To Left
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa-li fa fa-repeat fa-1x"></i>
                                                                            Rotate Image To Right
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <ul class="fa-ul">
                                                                        <li>
                                                                            <i class="fa-li fa fa-crop fa-1x text-info"></i>
                                                                            Enable Image Crop Functionality
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa-li fa fa-check fa-1x text-success"></i>
                                                                            Save Image Corp Section
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa-li fa fa-times fa-1x text-danger"></i>
                                                                            Un-Save Image Corp Section
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <ul class="fa-ul">
                                                                        <li>
                                                                            <i class="fa-li fa fa-floppy-o fa-1x"></i>
                                                                            Save Modified Image
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    
                                <!-- Section to upload Signature image  -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight" id="upload_signature">
                                            <label>Upload Your Signature:</label>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_signature_upload">No Signature selected</span>
                                                <input name="signature_upload" id="signature_upload" onchange="check_upload_signature('signature_upload', this)" type="file">
                                                <a href="javascript:;">Choose Sign</a>
                                            </div>
                                        </div>
                                        <div class="form-group autoheight" id="upload_signature_img">
                                            
                                        </div>
                                        <div class="custom_loader">
                                            <div id="loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                <span>Uploading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>    

                                <!-- Section to upload Initial Signature image  -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight" id="upload_init">
                                            <label>Upload Your Initial:</label>
                                            <div class="upload-file form-control">
                                                <span class="selected-file" id="name_init_upload">No Initial selected</span>
                                                <input name="init_upload" id="init_upload" onchange="check_upload_init('init_upload', this)" type="file">
                                                <a href="javascript:;">Choose Init</a>
                                            </div>
                                        </div>
                                        <div class="form-group autoheight" id="upload_init_img">
                                            
                                        </div>
                                        <div class="custom_loader">
                                            <div id="loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                <span>Uploading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section for IP address and Signature Date  -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-col-100">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                    <label class="" style="font-size:14px;">IP Address</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                    <?php $ip_address = isset($e_signature_data['ip_address']) ? $e_signature_data['ip_address'] : getUserIP(); ?>
                                                    <?php echo $ip_address; ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                    <label class="" style="font-size:14px;">Date/Time</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                            <span>
                                                <?php $date_time = isset($e_signature_data['signature_timestamp']) ? date('m-d-Y H:i:s',strtotime($e_signature_data['signature_timestamp'])) : date('m-d-Y H:i:s'); ?>
                                                <?php echo $date_time; ?>
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <!-- Section for consent note  -->
                                <div class="row">
                                    <div class="col-xs-12 text-justify">
                                        <p>
                                            <?php echo str_replace("{{company_name}}",$company_name,SIGNATURE_CONSENT_HEADING); ?>
                                        </p>
                                        <p>
                                            <?php echo SIGNATURE_CONSENT_TITLE; ?>     
                                        </p>
                                        <p>
                                            <?php echo str_replace("{{company_name}}",$company_name,SIGNATURE_CONSENT_DESCRIPTION); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Section for user consent acceptence button  -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                                        <label class="control control--checkbox">
                                            <?php echo SIGNATURE_CONSENT_CHECKBOX; ?>
                                            <input <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>/>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>

                                <hr />

                                <!-- Section for submit form button  -->
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <button onclick="func_save_e_signature();" type="button" class="btn blue-button break-word-text" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>><?php echo SIGNATURE_CONSENT_BUTTON; ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if($sideBar != ''){ ?>
</div>
<?php } ?>
</div>

<script src="<?php echo base_url('assets/js/html2canvas.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/crop/fabric.js'); ?>"></script>
<script src="<?php echo base_url('assets/crop/darkroom.js'); ?>"></script>

<script>

    //Draw Signature - Start
    var mousePressed = false;
    var init_mousePressed = false;
    var lastX, lastY, lastinX, lastinY;
    var ctx, init_ctx;

    var stored_image = '<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : ''; ?>';
    var init_stored_image = '<?php echo isset($e_signature_data['init_signature_bas64_image']) && !empty($e_signature_data['init_signature_bas64_image']) ? $e_signature_data['init_signature_bas64_image'] : ''; ?>';

    var canvas_id  = 'can';
    var init_canvas_id  = 'init_can';

    //  This function create signature with cursor movement.
    function InitThis() {
        ctx = document.getElementById(canvas_id).getContext("2d");

        if(stored_image != '' && stored_image != undefined && stored_image != null){
            // console.log('Condition true');
            var image = new Image();
            image.onload = function(){
                ctx.drawImage(image, 0, 0);
            }
            image.src = stored_image;
        }

        $('#' + canvas_id).mousedown(function (e) {
            mousePressed = true;
            Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        document.getElementById(canvas_id).addEventListener('touchstart', function (e) {
            mousePressed = true;
            Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        $('#' + canvas_id).mousemove(function (e) {
            if (mousePressed) {
                Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        document.getElementById(canvas_id).addEventListener('touchmove', function (e) {
            if (mousePressed) {
                Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        $('#' + canvas_id).mouseup(function (e) {
            mousePressed = false;
            get_signature();
        });

        document.getElementById(canvas_id).addEventListener('touchend', function (e) {
            mousePressed = false;
            get_signature();
        });

        $('#' + canvas_id).mouseleave(function (e) {
            mousePressed = false;
            get_signature();
        });
    }

    //  This function create signature initial with cursor movement. 
    function InitThisForInitSignature () {
        init_ctx = document.getElementById(init_canvas_id).getContext("2d");

        if(init_stored_image != '' && init_stored_image != undefined && init_stored_image != null){
            var image = new Image();
            image.onload = function(){
                init_ctx.drawImage(image, 0, 0);
            }
            image.src = init_stored_image;
        }

        $('#' + init_canvas_id).mousedown(function (e) {
            init_mousePressed = true;
            Init_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        document.getElementById(init_canvas_id).addEventListener('touchstart', function (e) {
            init_mousePressed = true;
            Init_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });

        $('#' + init_canvas_id).mousemove(function (e) {
            if (init_mousePressed) {
                Init_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        document.getElementById(init_canvas_id).addEventListener('touchmove', function (e) {
            if (init_mousePressed) {
                Init_Draw(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });

        $('#' + init_canvas_id).mouseup(function (e) {
            init_mousePressed = false;
            get_signature();
        });

        document.getElementById(init_canvas_id).addEventListener('touchend', function (e) {
            init_mousePressed = false;
            get_signature();
        });

        $('#' + init_canvas_id).mouseleave(function (e) {
            init_mousePressed = false;
            get_signature();
        });

        $('#' + init_canvas_id).mouseover(function (e) {
            preventMouseover = true;
        });
    }

    //  This function draw or plot signature into canvas
    function Draw(x, y, isDown) {
        if (isDown) {
            ctx.beginPath();
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineJoin = "round";
            ctx.hovered = false;

            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);

            ctx.closePath();
            ctx.stroke();
        }
        lastX = x; lastY = y;
    }

    //  This function draw or plot signature initial into canvas.
    function Init_Draw(x, y, isDown) {
        if (isDown) {
            init_ctx.beginPath();
            init_ctx.strokeStyle = '#000';
            init_ctx.lineWidth = 2;
            init_ctx.lineJoin = "round";

            init_ctx.moveTo(lastinX, lastinY);
            init_ctx.lineTo(x, y);

            init_ctx.closePath();
            init_ctx.stroke();
        }
        lastinX = x; lastinY = y;
    }

    //  This function clear signature from signature canvas.
    function clearArea() {
        // Use the identity matrix while clearing the canvas
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    }

    //  This function clear initial signature from initial canvas.
    function clearAreaInit() {
        // Use the identity matrix while clearing the canvas
        init_ctx.setTransform(1, 0, 0, 1, 0, 0);
        init_ctx.clearRect(0, 0, init_ctx.canvas.width, init_ctx.canvas.height);
    }

    //  This function draw e_signature or e_signature initial
    //  into there canvas.
    function get_signature(){
        canvas = document.getElementById(canvas_id);
        var dataURL = canvas.toDataURL();

        $('#drawn_signature').val(dataURL);

        init_canvas = document.getElementById(init_canvas_id);
        var init_dataURL = init_canvas.toDataURL();

        $('#drawn_init_signature').val(init_dataURL);
    }

    //  On page load initialize the canvas if signature type
    //  is drawn.
    window.onload = InitThis();
    window.onload = InitThisForInitSignature();

    //Draw Signature - End

    //  This function populate signature p tag when user start
    //  typing its signature.
    var inputBox = document.getElementById('signature');
    inputBox.onkeyup = function(){
        document.getElementById('tergit').innerHTML = inputBox.value;
    }

    //  This function convert p tag into canves and and then convert 
    //  it into base64 image formate for signature.
    $("#signature").on("change paste ", function() {
        html2canvas(document.querySelector("#tergit")).then(canvas => {
            $("#drawn_signature").val(canvas.toDataURL());
        }); 
    });

    //  This function populate initial signature p tag when user start
    //  typing its signature initial. 
    var init_inputBox = document.getElementById('init_signature');
    init_inputBox.onkeyup = function(){
        document.getElementById('init_tergit').innerHTML = init_inputBox.value; 
    }

    //  This function convert p tag into canves and and then convert 
    //  it into base64 image formate for signature initial.
    $("#init_signature").on("change paste ", function() {
        html2canvas(document.querySelector("#init_tergit")).then(canvas => {
            $("#drawn_init_signature").val(canvas.toDataURL());
        }); 
    });

    $(document).ready(function () {

        $('#draw_signature').hide();

        //  This onclick function allow user to select one signature
        //  type from these, (type, draw, upload) three signature type.
        $('.active_signature').on('click', function () {
            var selected;
            var sub_e_sign = '<?php echo $consent; ?>';
            if (sub_e_sign == 0){
                selected = $(this).val();
            } else {
                var sign_type = '<?php echo $active_signature; ?>';
                
                if (sign_type != 'typed') {
                    $('p').removeClass("e_signature_type_fixed_p");
                    $('p').removeClass("e_signature_type_fixed_p");
                }
                
            }

            if (selected == 'drawn') {
                $('#type_signature').hide();
                $('#init_type_signature').hide();
                $('#Info_button_crop_and_rotate').hide();
                $('#draw_signature_canvas').show();
                $('#draw_init_signature_canvas').show();
                $('#upload_signature').hide();
                $('#upload_init').hide();
                $('#tergit').hide();
                $('#init_tergit').hide();
                $('#select_font_family').hide();
                $('#upload_signature_img').empty();
                $('#upload_init_img').empty();
            } else if (selected == 'typed') {
                $('#type_signature').show();
                $('#init_type_signature').show();
                $('#tergit').show();
                $('#init_tergit').show();
                $('#select_font_family').show();
                $('#draw_signature_canvas').hide();
                $('#draw_init_signature_canvas').hide();
                $('#Info_button_crop_and_rotate').hide();
                $('#upload_signature').hide();
                $('#upload_init').hide();
                $('#upload_signature_img').empty();
                $('#upload_init_img').empty();
            } else if (selected == 'upload') {
                $('#type_signature').hide();
                $('#init_type_signature').hide();
                $('#draw_signature_canvas').hide();
                $('#draw_init_signature_canvas').hide();
                $('#upload_signature').show();
                $('#upload_init').show();
                $('#Info_button_crop_and_rotate').show();
                $('#tergit').hide();
                $('#init_tergit').hide();
                $('#select_font_family').hide();
            }else{
                $('#type_signature').hide();
                $('#init_type_signature').hide();
                $('#draw_signature_canvas').hide();
                $('#draw_init_signature_canvas').hide();
                $('#upload_signature').hide();
                $('#upload_init').hide();
                $('#Info_button_crop_and_rotate').hide();
                $('#upload_signature_img').empty();
                $('#upload_init_img').empty();
                $('#tergit').show();
                $('#init_tergit').show();
            }    
        });

        //  This onclick function allow user to select font to
        //  type his/her signature.
        $('.active_font_family').on('click', function () {
            var selected;
            var sub_e_sign = '<?php echo $consent; ?>';

            if (sub_e_sign == 0){
                selected = $(this).val();
            }

            if (selected == 1) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_1');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_1');
            } else if (selected == 2) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_2');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_2');
            } else if (selected == 3) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_3');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_3');
            } else if (selected == 4) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_4');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_4');
            } else if (selected == 5) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_5');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_5');
            } else if (selected == 6) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_6');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_6');
            } else if (selected == 11) {
                $('#tergit').removeClass();
                $('#tergit').addClass('e_signature_type_fixed_p e_signature_font_family_11');
                $('#init_tergit').removeClass();
                $('#init_tergit').addClass('e_signature_type_fixed_p e_signature_font_family_11');
            }
        });

        $('.active_signature:checked').trigger('click');

        $('#form_e_signature').validate({
            errorClass: 'text-danger',
            errorElement: 'p',
            errorElementClass: 'text-danger'
        });
    });

    //  This function validate draw signature canvas and
    //  check that is canvas is empty or not.
    function isCanvasBlank(canvas) {
        var blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;

        return canvas.toDataURL() == blank.toDataURL();
    }

    //  This function take user consent and
    //  validate e_signature form.
    function func_save_e_signature() {
        
        if ($('#form_e_signature').valid()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function () {
                    $('#my_loader').show();
                    var f_sign = true;
                    var f_init =  true;
                    if($('input[name="active_signature"]:checked').val() == 'upload'){
                        f_sign = global_upload_file_check('signature_upload');
                        f_init =  global_upload_file_check('init_upload');
                    } else if ($('input[name="active_signature"]:checked').val() == 'drawn') {

                        var blank_signature = isCanvasBlank(document.getElementById('can'));

                        if (blank_signature) {
                            f_sign = false;
                            alertify.error("Please Draw Your Signature");
                        } else {
                            f_sign = true;
                        }

                        var blank_initial = isCanvasBlank(document.getElementById('init_can'));

                        if (blank_initial) {
                            f_init = false;
                            alertify.error("Please Draw Your Initial");
                        } else {
                            f_init = true;
                        }
                    } else if ($('input[name="active_signature"]:checked').val() == 'typed') {
                        html2canvas(document.querySelector("#tergit")).then(canvas => {
                            $("#drawn_signature").val(canvas.toDataURL());
                        });

                        html2canvas(document.querySelector("#init_tergit")).then(canvas => {
                            $("#drawn_init_signature").val(canvas.toDataURL());
                        });
                    }
                    
                    if(f_sign == true && f_init == true ){
                        if($('input[name="active_signature"]:checked').val() == 'upload'){
                            var upload_signature_img_url = $('#upload_signature_img img').attr('src');
                            console.log(upload_signature_img_url);
                            $("#drawn_signature").val(upload_signature_img_url);
                            var upload_init_img_url = $('#upload_init_img img').attr('src');
                            console.log(upload_init_img_url);
                            $("#drawn_init_signature").val(upload_init_img_url);
                        }

                        setTimeout(function(){
                            $('#form_e_signature').submit();
                        }, 3000); 
                    } else {
                        $('#my_loader').hide();
                    }
                    
                },
                function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
        }
    }

    //  This function validate upload signature image and
    //  validate file formate.
    function check_upload_signature(val, element) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'signature_upload') {
                if (ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif" && ext != "JPG" && ext != "JPE" && ext != "JPEG" && ext != "PNG" && ext != "GIF") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid signature format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + val).html(original_selected_file);
                    
                    var file = element.files[0];
                    var reader = new FileReader();

                    reader.onloadend = function() {

                        var img_url = reader.result;
                        var corp_rotate_signature_img = '<div class="figure-wrapper"><figure class="image-container target"><img src="'+img_url+'" alt="DomoKun" id="target_upload_signature"></figure></div><div style="margin-bottom: 50px;"><button class="re_upload_signature_btn btn btn-success" style="margin-top:20px;">Re_Upload Signature</button></div>';
                        $("#upload_signature").hide();
                        $("#upload_signature_img").append(corp_rotate_signature_img);
                        
                        var dkrm = new Darkroom('#target_upload_signature', {
                            minWidth: 100,
                            minHeight: 100,
                            maxWidth: 600,
                            maxHeight: 500,
                            ratio: 4/3,
                            backgroundColor: '#000',
                            plugins: {
                                crop: {
                                    quickCropKey: 67,
                                },
                            },

                            initialize: function() {
                                var cropPlugin = this.plugins['crop'];
                                cropPlugin.requireFocus();
                            }
                        });

                    }

                    reader.readAsDataURL(file);
                    return true;
                }
            }
        } else {
            alertify.error("No signature selected");
            $('#name_' + val).html('<p class="red">Please select signature</p>');
            return false;

        }    
    }

    //  This function validate upload signature initial image and
    //  validate file formate.
    function check_upload_init(val, element) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'init_upload') {
                if (ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif" && ext != "JPG" && ext != "JPE" && ext != "JPEG" && ext != "PNG" && ext != "GIF") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid init format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg, .jpe, .jpeg, .png, .gif) allowed!</p>');
                    return false;
                } else {
                    var selected_file = fileName;
                    var original_selected_file = selected_file.substring(selected_file.lastIndexOf('\\') + 1, selected_file.length);
                    $('#name_' + val).html(original_selected_file);

                    var file = element.files[0];
                        var reader = new FileReader();

                        reader.onloadend = function() {

                            var img_url = reader.result;
                            $("#upload_init").hide();
                            var corp_rotate_initial_img = '<div class="figure-wrapper"><figure class="image-container target" style="width: 50%"><img src="'+img_url+'" alt="DomoKun" id="target_upload_init"></figure></div><div><button class="re_upload_initial_btn btn btn-success" style="margin-top:20px;">Re_Upload Initial</button></div>';
                            $("#upload_init").hide();
                            $("#upload_init_img").append(corp_rotate_initial_img);
                            
                            var dkrm = new Darkroom('#target_upload_init', {
                                minWidth: 100,
                                minHeight: 100,
                                maxWidth: 600,
                                maxHeight: 500,
                                ratio: 4/3,
                                backgroundColor: '#000',
                                plugins: {
                                    crop: {
                                        quickCropKey: 67,
                                    }
                                },

                                initialize: function() {
                                    var cropPlugin = this.plugins['crop'];
                                    cropPlugin.requireFocus();
                                }
                            });

                        }

                    reader.readAsDataURL(file);
                    return true;
                }
            }
        } else {
            alertify.error("No init selected");
            $('#name_' + val).html('<p class="red">Please select init</p>');
            return false;

        }    
    }

    // This function remove old initial-signature image and empty Upload button value.
    $(document).on('click', '.re_upload_initial_btn', function() {
        $('#name_init_upload').html('No Initial selected');
        $( "#init_upload" ).val("")
        $('#upload_init_img').empty();
        $('#upload_init').show();
    });

    // This function remove old signature image and empty Upload button value.
    $(document).on('click', '.re_upload_signature_btn', function() {
        $('#name_signature_upload').html('No Signature selected');
        $( "#signature_upload" ).val("")
        $('#upload_signature_img').empty();
        $('#upload_signature').show();
    });

    //  This function validate upload e_signature image is present or
    //  not when signature type is "Uploaded".
    function global_upload_file_check(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
           return true;
        } else {
            if(val == 'signature_upload') {
                alertify.error("No signature selected");
                $('#name_' + val).html('<p class="red">Please select signature</p>');
            } else {
                alertify.error("No init selected");
                $('#name_' + val).html('<p class="red">Please select init</p>');
            }
            
            return false;

        }    
    }

    //  This function have ajax call to update user_consent from
    //  1 to 0 and allow user to generate e_signature again.
    function func_regenerate_e_signature () {
        var company = '<?php echo $company_sid; ?>';
        var user_sid = '<?php echo $users_sid; ?>';
        var user_type = '<?php echo $users_type; ?>';
        var myurl = "<?= base_url() ?>E_signature/regenerate_e_signature/"+user_sid+"/"+company+"/"+user_type;

        alertify.confirm(
            'Are you Sure?',
            'Are you sure you want to regenerate your E-Signature?',
            function () {
                $.ajax({
                    type: "GET",
                    url: myurl,
                    async : false,
                    success: function (data) {
                       location.reload();
                    },
                    error: function (data) {

                    }
                });
            },
            function () {
                alertify.error('Cancelled!');
            }).set('labels', {ok: 'Yes', cancel: 'Cancel'});  
    }
      
</script>