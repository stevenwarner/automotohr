<?php
    $currentOfferLetter = [];
    //
    if(isset($assigned_offer_letter_sid)){
        foreach($offer_letters as $l){
            if($l['sid'] == $assigned_offer_letter_sid){
                $currentOfferLetter = $l;
                break;
            }
        }
    }
?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo isset($user_info['pictures']) && $user_info['pictures'] != NULL && $user_info['pictures'] != '' ? AWS_S3_BUCKET_URL . $user_info['pictures'] : base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <div class="start-rating">
                                            <?php if ($user_type == 'applicant') { ?>
                                                <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2  data-size="xs" />
                                            <?php } else if ($user_type == 'employee') { ?>
                                                <?php if ($this->session->userdata('logged_in')['employer_detail']['access_level_plus']) { ?>
                                                    <a class="btn-employee-status btn-warning" href="<?php echo base_url('employee_status/' . $employer['sid']); ?>">Employee Status</a>
                                                <?php } ?>
                                            <?php } ?>     
                                        </div>
                                        <?php if(isset($employee_terminate_status) && !empty($employee_terminate_status)){
                                            echo '<h4>'.$employee_terminate_status.'</h4>';
                                        } else if (isset($employer['active'])) { ?>
                                            <h4>
                                                <?php if ($employer['active']) { ?>
                                                    Active Employee
                                                <?php } else { ?>
                                                    <?php if(isset($employer['archived']) && $employer['archived']!='1') { ?>
                                                        Onboarding or Deactivated Employee
                                                    <?php } else { ?>
                                                        Archived Employee
                                                    <?php } ?>
                                                <?php } ?>
                                            </h4>
                                        <?php } else { ?>
                                            <span> <?php echo 'Applicant'; ?></span>
                                        <?php } ?>    
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <?php if ($user_type == 'applicant') { ?>
                                    <span class="page-heading down-arrow">
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                        Send Offer Letter / Pay Plan
                                    </span>
                                <?php } else if ($user_type == 'employee') { ?>
                                    <span class="page-heading down-arrow">
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/' . $user_info['sid']); ?>">
                                            <i class="fa fa-chevron-left"></i>Employee Profile</a>
                                        Send Offer Letter / Pay Plan
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div id="step_onboarding">
                                <div id="getting_started" class="getting-started">                               
                                    <form id="form_offer_letter" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="" />
                                        <input type="hidden" id="selected_letter_type" name="letter_type" value="" />
                                        <input type="hidden" id="selected_document_original_name" name="document_original_name" value="">
                                        <input type="hidden" id="selected_document_s3_name" name="document_s3_name" value="">
                                        <div id="offer_letter" class="offer-letter">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="well well-sm">
                                                        <strong>Instructions:</strong>
                                                        <p>Please select the Offer Letter / Pay Plan for <b><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></b></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <span class="pull-right">
                                                        <button type="button" class="btn btn-success js-offer-letter-btn">Add Offer Letter / Pay Plan</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php foreach ($offer_letters as $offer_letter) { ?>
                                                        <input type="hidden" id="letter_name_<?php echo $offer_letter['sid']; ?>" value="<?php echo $offer_letter['letter_name'] ?>" />
                                                        <input type="hidden" id="letter_body_<?php echo $offer_letter['sid']; ?>" value="<?php echo htmlentities(html_entity_decode($offer_letter['letter_body'])); ?>" />
                                                    <?php } ?>
                                                    <div class="form-group">
                                                        <label>Offer Letter  / Pay Plan</label>
                                                        <select id="offer_letter_select" name="offer_letter_select">
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($offer_letters as $offer_letter) { 
                                                                    $offer_letter['letter_body'] = html_entity_decode($offer_letter['letter_body']);
                                                                    $allOfferLetters[] = $offer_letter;
                                                                ?>
                                                                <option value="<?php echo $offer_letter['sid']; ?>"><?php echo $offer_letter['letter_name']; ?> ( <?php echo $offer_letter['letter_type']; ?> )</option>
                                                            <?php } ?>
                                                        </select>
                                                        <span id="title_error" class="text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="uploaded_offer_letter" style="display: none">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="panel-body">
                                                            <div class="accordion-colored-header header-bg-gray">
                                                                <div class="panel-group" id="onboarding-configuration-accordions">
                                                                    <div class="panel panel-default parent_div">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                Uploaded Offer Letter / Pay Plan
                                                                            </h4>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div id="uploaded_offer_letter_iframe"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                    </div>
                                                </div>        
                                            </div>  
                                            
                                            

                                            <div id="generated_offer_letter" style="display: none">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label>Letter Body<span class="staric">*</span></label>
                                                            <textarea id="letter_body" name="letter_body" class="ckeditor"></textarea>
                                                        </div>
                                                        <span id="body_error" class="text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="generated_offer_letter" style="display: none">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label>Authorized Management Signers </label>
                                                            <select name="js-signers[]" id="js-signers" multiple="">
                                                                <?php 
                                                                    if(sizeof($managers_list)){
                                                                        foreach ($managers_list as $key => $value) {
                                                                            echo '<option value="'.( $value['sid'] ).'" '.( in_array($value['sid'], empty($currentOfferLetter['signers']) ? [] :  explode(',', $currentOfferLetter['signers'])) ? 'selected' : '' ).'>'.( remakeEmployeeName($value) ).'</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Visibility Block -->
                                            <br />
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h5>
                                                                <strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true"  data-target="visibilty"></i>
                                                                <p class="jsHintBody" data-hint="visibilty"><br /><?=getUserHint('visibility_hint');?></p>
                                                            </h5>
                                                        </div>
                                                        <div class="panel-body">
                                                            <!-- Payroll -->
                                                            <label class="control control--checkbox">
                                                                Visible To Payroll
                                                                <input type="checkbox" name="visible_to_payroll" class="jsVisibleToPayroll" value="1"/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <hr />
                                                            <!-- Roles -->
                                                            <label>Roles</label>
                                                            <select name="roles[]" id="jsRoles" multiple>
                                                            <?php
                                                                //
                                                                foreach(getRoles() as $k => $v){
                                                                    ?>
                                                                    <option value="<?=$k;?>"><?=$v;?></option>
                                                                    <?php
                                                                }
                                                            ?>
                                                            </select>
                                                            <br />
                                                            <br />
                                                            <!-- Departments -->
                                                            <label>Departments</label>
                                                            <select name="departments[]" id="jsDepartment" multiple>
                                                            <?php 
                                                                //
                                                                if(!empty($departments)){
                                                                    foreach($departments as $v){
                                                                        ?>
                                                                        <option value="<?=$v['sid'];?>"><?=$v['name'];?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                            </select>
                                                            <br />
                                                            <br />
                                                            <!-- Teams -->
                                                            <label>Teams</label>
                                                            <select name="teams[]" id="jsTeams" multiple>
                                                            <?php 
                                                                //
                                                                if(!empty($teams)){
                                                                    foreach($teams as $v){
                                                                        ?>
                                                                        <option value="<?=$v['sid'];?>"><?=$v['name'];?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                            </select>
                                                            <br />
                                                            <br />
                                                            <!-- Employees -->
                                                            <label>Employees</label>
                                                            <select name="employees[]" id="jsEmployees" multiple>
                                                            <?php 
                                                                //
                                                                if(!empty($managers_list)){
                                                                    foreach($managers_list as $v){
                                                                        ?>
                                                                        <option value="<?=$v['sid'];?>"><?=remakeEmployeeName($v);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            <div class="generated_offer_letter" style="display: none">

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="offer-letter-help-widget full-width form-group">
                                                            <div class="how-it-works-insturction">
                                                                <strong>How it Works:</strong>
                                                                <p class="how-works-attr">1. Generate a new Electronic Document</p>
                                                                <p class="how-works-attr">2. Enable a Document Acknowledgment</p>
                                                                <p class="how-works-attr">3. Enable an Electronic Signature</p>
                                                                <p class="how-works-attr">4. Insert multiple tags where applicable</p>
                                                                <p class="how-works-attr">5. Save the Document</p>
                                                            </div>

                                                            <div class="tags-arae">
                                                                <div class="col-md-12">
                                                                    <h5><strong>Company Information Tags:</strong></h5>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{company_name}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{company_address}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{company_phone}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{career_site_url}}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tags-arae">
                                                                <div class="col-md-12">
                                                                    <h5><strong>Employee / Applicant Tags :</strong></h5>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{first_name}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{last_name}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{email}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{job_title}}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tags-arae">
                                                                <div class="col-md-12">
                                                                    <h5><strong>Signature tags:</strong></h5>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{signature}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{signature_print_name}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{inital}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{sign_date}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{authorized_signature_date}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{short_text}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{text}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{text_area}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group autoheight">
                                                                        <input type="text" class="form-control tag" readonly="" value="{{checkbox}}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right" style="top: 0;">
                                                    <div class="form-group">
                                                        <?php $offer_letter_status = count_offer_letter($user_type, $user_info['sid']); ?>
                                                        <?php if(empty($user_assigned_offer_letter_sid)){ ?>
                                                            <a href="javascript:;" class="btn btn-success assign-offer-letter">Save</a>
                                                            <a href="javascript:;" class="btn btn-success assign-offer-letter">Save And Send Email</a>
                                                        <?php } else if($user_assigned_offer_letter_sid > 0 && ($offer_letter_status == 'sent' || $offer_letter_status == 'sign')){
                                                                $key = $user_type == 'applicant' ? base_url('onboarding/my_offer_letter').'/'.$user_info['verification_key'] : base_url('onboarding/my_offer_letter').'/'.$user_info['verification_key'] . '/e';
                                                            ?>
                                                            <a href="<?php echo $key;?>" class="btn btn-primary" target="_blank">Preview Offer Letter / Pay Plan</a>
                                                            <a href="javascript:;" onclick="revoke_offer_letter(<?php echo $user_assigned_offer_letter_sid; ?>)" class="btn btn-danger">Revoke</a>
                                                            <a href="javascript:;" class="btn btn-warning assign-offer-letter">Reassign</a>
                                                            <a href="javascript:;" class="btn btn-warning assign-offer-letter">Reassign and Send Email</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } elseif($user_type == 'employee'){
                    $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new');
                } ?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/mFileUploader/index.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/mFileUploader/index.js"></script>
<?php $this->load->view('iframeLoader'); ?>
<?php $this->load->view('hr_documents_management/hybrid/scripts'); ?>
<?php 
if(isset($allOfferLetters)){}else{$allOfferLetters='';}
$this->load->view('hr_documents_management/scripts/index', ['offerLetters' => $allOfferLetters]); ?>
<!-- Main End -->
<script type="text/javascript">
    $(document).ready(function () {

         //
        $('#offer_letter_select').select2();
        //
        var 
            assignedOfferLetter = <?=json_encode($assignedOfferLetter);?>,
            offerLetters = <?=json_encode($allOfferLetters);?>,
            assignedSid = <?=$assigned_offer_letter_sid?>;
        //
        setTimeout(function(){
            $('#offer_letter_select').select2('val', assignedSid);
        }, 2000);

        //
        function loadOfferLetterView(
            sid
        ){
            var 
            l = [],
            i = 0,
            il = offerLetters.length;
            for (i; i < il; i++) {
                if(offerLetters[i]['sid'] == sid) l = offerLetters[i];
            }
            //
            if( l.length === 0 ) return;
            //
            if(sid == assignedSid){
                l = assignedOfferLetter;
                l.letter_type = l.offer_letter_type;
                l.letter_body = l.document_description;
                l.uploaded_document_s3_name = l.document_s3_name;
                l.uploaded_document_original_name = l.document_original_name;
            }
            console.log(l);
            //
            $('.jsVisibleToPayroll').prop('checked', l.visible_to_payroll);
            $('#jsRoles').select2('val', l.allowed_roles && l.allowed_roles != 'null' ? l.allowed_roles.split(',') :  null);
            $('#jsDepartment').select2('val', l.allowed_departments && l.allowed_departments != 'null' ? l.allowed_departments.split(',') :  null);
            $('#jsTeams').select2('val', l.allowed_teams && l.allowed_teams != 'null' ? l.allowed_teams.split(',') :  null);
            $('#jsEmployees').select2('val', l.allowed_employees && l.allowed_employees != 'null' ? l.allowed_employees.split(',') :  null);
            //
            $('#js-signers').select2({
                closeOnSelect: false
            });
            $('#js-signers').select2('val', l.signers != null ? l.signers.split(',') : null);

            $('#selected_letter_type').val(l.letter_type);
            //
            var f = getUploadedFileAPIUrl( l.uploaded_document_s3_name, 'original' );
            //
            if (l.letter_type == 'hybrid_document') {
                $("#uploaded_offer_letter").show();
                $("#generated_offer_letter").show();
                $(".generated_offer_letter").show();
                //
                $("#uploaded_offer_letter_iframe").html( f.getHTML() );
                $('#selected_document_s3_name').val(l.uploaded_document_s3_name);
                $('#selected_document_original_name').val(l.uploaded_document_original_name);
                loadIframe(
                    f.URL,
                    f.Target
                );
                //
                CKEDITOR.instances['letter_body'].setData(l.letter_body);
            } else if (l.letter_type == 'generated') {
                $("#generated_offer_letter").show();
                $(".generated_offer_letter").show();
                $("#uploaded_offer_letter").hide();
                //
                $('#selected_document_s3_name').val('');
                $('#selected_document_original_name').val('');
                ///
                CKEDITOR.instances['letter_body'].setData(l.letter_body);
            } else {
                $("#uploaded_offer_letter").show();
                $("#generated_offer_letter").hide();
                $(".generated_offer_letter").hide();
                //
                $("#uploaded_offer_letter_iframe").html( f.getHTML() );
                $('#selected_document_s3_name').val(l.uploaded_document_s3_name);
                $('#selected_document_original_name').val(l.uploaded_document_original_name);
                loadIframe(
                    f.URL,
                    f.Target
                );
                //
                $('#letter_body').val('');
            }
        }


        $('#offer_letter_select').on('change', function () {
            loadOfferLetterView( $(this).val() );
            return;
            var selected_offer_letter_type      = $(this).find(':selected').attr('data-olt');
            var selected_offer_letter_s3_url    = $(this).find(':selected').attr('data-s3-url');
            var selected_offer_letter_name      = $(this).find(':selected').attr('data-file-name');
            $('#selected_letter_type').val(selected_offer_letter_type);

            if (selected_offer_letter_type == 'generated') {
                $("#generated_offer_letter").show();
                $(".generated_offer_letter").show();
                $("#uploaded_offer_letter").hide();
                
                var body = $('#letter_body_' + selected).val();
                CKEDITOR.instances['letter_body'].setData(body);
                $('#selected_document_s3_name').val('');
                $('#selected_document_original_name').val('');
            } else {
                $("#uploaded_offer_letter").show();
                $("#generated_offer_letter").hide();
                $(".generated_offer_letter").hide();
                var selected_offer_letter_url = $(this).find(':selected').attr('data-olt-url');
                $("#uploaded_offer_letter_iframe").attr("src", selected_offer_letter_url);
                $('#selected_document_s3_name').val(selected_offer_letter_s3_url);
                $('#selected_document_original_name').val(selected_offer_letter_name);
                $('#letter_body').val('');

            }
        });

        // var offer_letter_type = "<?php echo $assigned_offer_letter_type; ?>";
        // if (offer_letter_type == 'generated') {
        //     $('#selected_letter_type').val(offer_letter_type);
        //     $("#generated_offer_letter").show();
        //     $(".generated_offer_letter").show();
        // } else {
        //     $('#selected_document_s3_name').val("<?php echo $assign_offer_letter_s3_url; ?>");
        //     $('#selected_document_original_name').val("<?php echo $assign_offer_letter_name; ?>");
        //     $('#selected_letter_type').val(offer_letter_type);
        //     $("#uploaded_offer_letter").show();
        // }
         
    }); 

    $('.assign-offer-letter').on('click', function () {
        var btn_type = $(this).text();
        if (btn_type == 'Save' || btn_type == 'Reassign') {
            $('#perform_action').val('save_offer_letter');
        } else if (btn_type == 'Save And Send Email' || btn_type == 'Reassign and Send Email') {
            $('#perform_action').val('save_and_send_offer_letter');
        }
        
        var letter_body = CKEDITOR.instances['letter_body'].getData();
        var letter_type = $('#selected_letter_type').val();
        
        if (letter_type == 'uploaded') {
            if (btn_type == 'Reassign' || btn_type == 'Reassign and Send Email') {
                alertify.confirm(
                    'Are you sure?',
                    'Are you sure you want to Reassign the Offer Letter? <br> <strong>Note:</strong>This action will revoke any assigned Offer letter / Pay plan.',
                    function () {
                        $('#form_offer_letter').submit();
                    },
                    function () {
                        alertify.alert('Reassign Process Canceled!');
                    }
                );  
            } else {
                $('#form_offer_letter').submit();
            }    
        } else {
            if (letter_body == '') {
                $('#title_error').html('');
                $('#body_error').html('<b>Letter Body is required.</b>');
            } else {
                $('#body_error').html('');
                if (btn_type == 'Reassign' || btn_type == 'Reassign and Send Email') {
                    alertify.confirm(
                        'Are you sure?',
                        'Are you sure you want to Reassign the Offer Letter? <br> <strong>Note:</strong>This action will revoke any assigned Offer letter / Pay plan.',
                        function () {
                            $('#form_offer_letter').submit();
                        },
                        function () {
                            alertify.alert('Reassign Process Canceled!');
                        }
                    );  
                } else {
                    $('#form_offer_letter').submit();
                }
            }
        }
        
    });   

    function revoke_offer_letter (offer_letter_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Revoke the Offer Letter / Pay Plan?',
            function () {
                var myurl = "<?php echo base_url() ?>onboarding/revoke_offer_letter/"+offer_letter_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async : false,
                    success: function (data) {
                        alertify.alert('Offer Letter / Pay Plan Revoke Successfully!');
                        location.reload();
                    },
                    error: function (data) {

                    }
                });
            },
            function () {
                alertify.error('Canceled!');
            });
    };
    


    $(function(){
        // 
        $('#jsRoles').select2({closeOnSelect: false});
        $('#jsDepartment').select2({closeOnSelect: false});
        $('#jsTeams').select2({closeOnSelect: false});
        $('#jsEmployees').select2({closeOnSelect: false});
    });
</script>
<style>
    .select2-container--default .select2-selection--single{
        background-color: #fff !important;
        border: 1px solid #aaa !important;
    }
    .select2-container .select2-selection--single{

        height: 38px !important;
    }
</style>
