<?php
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

if (isset($applicant)) {
    $company_sid = $applicant['employer_sid'];
    $users_type = 'applicant';
    $users_sid = $applicant['sid'];
    $back_url = base_url('onboarding/general_information/' . $unique_sid);
    $next_btn = '<a href="javascript:;" class="btn btn-success btn-block" id="go_next" onclick="func_save_e_signature();"> Save And Proceed Next <i class="fa fa-angle-right"></i></a>';
    $center_btn = '<a href="'.base_url('onboarding/documents/' . $unique_sid).'" class="btn btn-warning btn-block"> Bypass This Step <i class="fa fa-angle-right"></i></a>';
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
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-angle-left"> </i> Incident Reporting</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents') ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-info btn-block mb-2"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a></a>
                    </div>
                    <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php //echo base_url('incident_reporting_system/view_general_guide')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Incident Guide </a>
                    </div> -->
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                        <a href="<?php echo base_url('incident_reporting_system/safety_check_list')?>" class="btn btn-info btn-block mb-2"><i class="fa fa-book"></i> Safety Check List </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile"><?php echo $title; ?></h2>
                </div>
                <div class="form-group">
                    <h3 class="text-blue">You are about to report a "<?php echo ucwords($report_type);?>" Report</h3>
                </div>
                <form method="post" action="" id="inc-form" enctype="multipart/form-data">
                    <div class="form-wrp">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>

                        <?php if (sizeof($questions) > 0) { ?>
                            <?php if ($report_type == 'confidential') { ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Your Full Name:<span class="required">*</span></label>
        <!--                                        <input type="text" class="invoice-fields" name="full-name" id="title" value="--><?php //echo $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name'] ?><!--" readonly required="required">-->
                                            <?php echo form_input('full-name', set_value('full-name', $session['employer_detail']['first_name'] . " " . $session['employer_detail']['last_name']), 'class="form-control" id="full-name" readonly'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
<?php
                            foreach ($questions as $question) {
                                echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';

                                if ($question['question_type'] == 'textarea') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <textarea class="form-control textarea" name="text_<?php echo $question['id']; ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
<?php                           } elseif ($question['question_type'] == 'text') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : "";
                                    echo form_input('text_' . $question['id'], set_value('text_' . $question['id']), 'class="form-control" ' . $required); ?>
<?php                           } elseif ($question['question_type'] == 'time') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="time_<?php echo $question['id']; ?>" value="12:00AM" class="form-control start_time"  aria-invalid="false" required="<?php echo $required; ?>">
<?php                           } elseif ($question['question_type'] == 'date') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <input type="text" name="date_<?php echo $question['id']; ?>" value="" class="form-control start_date"  aria-invalid="false" required="<?php echo $required; ?>" autocomplete="off">
<?php                           } elseif ($question['question_type'] == 'signature') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
<?php                               $required = $question['is_required'] ? "required" : ""; ?>
                                    <textarea class="form-control textarea get_signature" name="signature_<?php echo $question['id']; ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('signature_' . $question['id']); ?></textarea>
                                    <div class="img-full" id="draw_upload">
                                        <img style="max-height: 50px;" src=""  id="draw_upload_img" />
                                    </div>
                                    <div class="img-full" id="typed_signature">
                                        <input readonly="readonly" data-rule-required="true" type="text" class="signature-form-field" id="signature" value="" />
                                    </div>

<?php                           } elseif ($question['question_type'] == 'radio') { ?>
                                    <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                Yes<input type="radio" name="radio_<?php echo $question['id']; ?>" value="yes" style="position: relative;" checked>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                            <label class="control control--radio">
                                                No<input type="radio" name="radio_<?php echo $question['id']; ?>" value="no" style="position: relative;">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
<?php                           } elseif ($question['question_type'] == 'single select') { ?>
                                    <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="hr-select-dropdown">
                                        <select name="list_<?php echo $question['id']; ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                            <option value="">-- Please Select --</option>
<?php                                       $options = explode(',', $question['options']);

                                            foreach ($options as $option) { ?>
                                                <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
<?php                                       } ?>
                                        </select>
                                    </div>
<?php                               } elseif ($question['question_type'] == 'multi select') { ?>
                                    <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                    <div class="row">
<?php                                   $options = explode(',', $question['options']); ?>
<?php                                   foreach ($options as $option) { ?>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <label class="control control--checkbox">
                                                    <?php echo $option; ?>
                                                    <input type="checkbox" name="multi-list_<?php echo $question['id']; ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
<?php                                   } ?>
                                    </div>
<?php                               }
                                echo '</div> </div> </div>';
                            } ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Incident Supporting Docs:</label>
                                        <div class="upload-file form-control">
                                            <span class="selected-file" id="name_docs">No file selected</span>
                                            <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                        <div id="file-upload-div" class="file-upload-box"></div>
                                        <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                    </div>
                                    <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Other </div>
                                    <div class="custom_loader">
                                        <div id="loader" class="loader" style="display: none">
                                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                            <span>Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="inc-id" name="inc-id" value="0"/>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <b><h4>BY CLICKING ON "SUBMIT" I CERTIFY THAT I HAVE BEEN TRUTHFUL IN EVERY RESPECT IN FILLING THIS REPORT</h4></b>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <div class="btn-wrp full-width text-right">
                                            <input type="submit" value="Submit" name="submit" class="btn btn-info" id="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php                   } else {
                            echo "<span class='no-data'>No Questions Scheduled For This Type</span>";
                        } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="E_Signature_Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">E-Signture</h4>
            </div>
            <div class="modal-body">
                <form id="form_e_signature" enctype="multipart/form-data" method="post" action="<?= base_url() ?>onboarding/ajax_e_signature">
                    <input type="hidden" id="perform_action" name="perform_action" value="save_e_signature" />
                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                    <input type="hidden" id="user_type" name="user_type" value="<?php echo $users_type; ?>" />
                    <input type="hidden" id="user_sid" name="user_sid" value="<?php echo $users_sid; ?>" />
                    <input type="hidden" id="ip_address" name="ip_address" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
                    <input type="hidden" id="user_agent" name="user_agent" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
                    <input type="hidden" id="first_name" name="first_name" value="<?php echo $first_name; ?>" />
                    <input type="hidden" id="last_name" name="last_name" value="<?php echo $last_name; ?>" />
                    <input type="hidden" id="email_address" name="email_address" value="<?php echo $email; ?>" />
                    <input type="hidden" id="signature_timestamp" name="signature_timestamp" value="<?php echo date('d/m/Y H:i:s'); ?>" />

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
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                            <label class="control control--radio">
                                Type Signature
                                <input <?php echo set_radio('active_signature', 'typed', $active_signature == 'typed'); ?> class="active_signature" type="radio" id="active_signature_typed" name="active_signature" value="typed" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                            <label class="control control--radio">
                                Draw Signature
                                <input <?php echo set_radio('active_signature', 'drawn', $active_signature == 'drawn'); ?> class="active_signature" type="radio" id="active_signature_drawn" name="active_signature" value="drawn" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <label class="control control--radio">
                                Upload Signature
                                <input <?php echo set_radio('active_signature', 'upload', $active_signature == 'upload'); ?> class="active_signature" type="radio" id="active_signature_upload" name="active_signature" value="upload" />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div id="type_signature" style="min-height: 250px;" class="field-row autoheight">
                                <?php $signature = isset($e_signature_data['signature']) ? $e_signature_data['signature'] : '';?>
                                <label>&nbsp;</label>
                                <input data-rule-required="true" type="text" class="signature-field" name="signature" id="e_signature" value="<?php echo set_value('signature', $signature); ?>"  placeholder="John Doe"/>
                                <p>Please type your First and Last Name</p>
                                <?php echo form_error('signature'); ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div id="draw_signature" style="min-height: 250px;">
                                <div class="field-row autoheight canvas-wrapper">
                                    <input type="hidden" id="drawn_signature" name="drawn_signature" value="" />
                                    <canvas class="signature-canvas" id="can" width="500" height="200"></canvas>
                                    <p>Please draw your signature</p>

                                    <button type="button" class="btn btn-danger btn-sm del-signature" onclick="clearArea();"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php if (isset($consent) && $consent == 1 && $active_signature == 'upload') { ?>
                                <div class="form-group autoheight">
                                    <?php $upload_img = isset($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : '';?>
                                    <!-- <img src="<?php //echo AWS_S3_BUCKET_URL . $upload_img; ?>"> -->
                                    <img style="max-height: 50px;" src="<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? 'data:image/png;base64 ,'.$e_signature_data['signature_bas64_image'] : ''; ?>"  />
                                </div>    
                            <?php } else { ?>
                                <div class="form-group autoheight" id="upload_signature">
                                    <label>Upload Signature:</label>
                                    <div class="upload-file form-control">
                                        <span class="selected-file" id="name_signature_upload">No Signature selected</span>
                                        <input name="signature_upload" id="signature_upload" onchange="check_signature('signature_upload', this)" type="file" >
                                        <a href="javascript:;">Choose Signature</a>
                                    </div>
                                </div>
                                <div class="custom_loader">
                                    <div id="loader" class="loader" style="display: none">
                                        <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                        <span>Uploading...</span>
                                    </div>
                                </div>
                            <?php } ?> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-col-100">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                        <label class="" style="font-size:14px;">IP Address</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                        <?php $ip_address = isset($e_signature_data['ip_address']) ? $e_signature_data['ip_address'] : $_SERVER['REMOTE_ADDR']; ?>
                                        <?php echo $ip_address; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                        <label class="" style="font-size:14px;">Date/Time</label>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                <span>
                                    <?php $date_time = isset($e_signature_data['signature_timestamp']) ? $e_signature_data['signature_timestamp'] : date('m/d/Y h:i A'); ?>
                                    <?php echo date('m/d/Y h:i A'); ?>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-xs-12 text-justify">
                            <p>
                                <strong>CONSENT AND NOTICE REGARDING ELECTRONIC COMMUNICATIONS</strong> FOR <strong>AutomotoSocial LLC / <?php echo STORE_NAME; ?></strong><br />
                            </p>
                            <p>1. Electronic Signature Agreement.</p>
                            <p>
                                By selecting the "I Accept" button, you are signing this Agreement electronically. You agree your electronic signature is the legal equivalent of your manual signature on this Agreement. By selecting "I Accept" you consent to be legally bound by this Agreement's terms and conditions.
                                You further agree that your use of a key pad, mouse or other device to select an item, button, icon or similar act/action, or to otherwise
                                provide AutomotoSocial LLC / <?php echo STORE_NAME; ?>, or in accessing or making any transaction regarding any agreement, acknowledgement, consent terms, disclosures or conditions constitutes your signature (hereafter referred to as "E-Signature"), acceptance and agreement as if actually signed by
                                you in writing.
                                You also agree that no certification authority or other third party verification
                                is necessary to validate your E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of your E-Signature or any resulting contract between you and AutomotoSocial LLC / <?php echo STORE_NAME; ?>. You also represent that
                                you are authorized to enter into this Agreement for all persons who own or are authorized to access any of your accounts and that such persons will be bound by the terms of this Agreement.
                                You further agree that each use of your E-Signature in obtaining a AutomotoSocial LLC / <?php echo STORE_NAME; ?> service constitutes your agreement to be bound by the terms and conditions of the AutomotoSocial LLC / <?php echo STORE_NAME; ?> Disclosures and Agreements as they exist on the date of your
                                E-Signature
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $consent = isset($e_signature_data['user_consent']) ? $e_signature_data['user_consent'] : 0; ?>
                            <label class="control control--checkbox">
                                I Consent and Accept Electronic Signature Agreement
                                <input <?php echo set_checkbox('user_consent', 1, $consent == 1); ?> data-rule-required="true" type="checkbox" id="user_consent" name="user_consent" value="1" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>/>
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <button onclick="func_save_e_signature();" type="button" class="btn btn-success break-word-text" <?php echo $consent == 1 ? 'disabled="disabled"' : '' ?>>I Consent and Accept Electronic Signature Agreement</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $("#inc-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            title: {
                required: true
            },
            nature_of_report: {
                required: true
            },
            who_did_inappropriate: {
                required: true
            },
            to_whom: {
                required: true
            },
            when_and_where: {
                required: true
            },
            was_it_isolated: {
                required: true
            },
            why_you_believe_above: {
                required: true
            },
            your_reaction: {
                required: true
            },
            any_witness: {
                required: true
            },
            spoken_to_anyone: {
                required: true
            },
            reported_to_supervisor: {
                required: true
            },
            avoid_future_incidents: {
                required: true
            }
        },
        messages: {
            Title: {
                required: 'Job title is required'
            },
            nature_of_report: {
                required: 'Nature of your report is required'
            },
            who_did_inappropriate: {
                required: 'This field is required'
            },
            to_whom: {
                required: 'This field is required'
            },
            when_and_where: {
                required: 'These details are required'
            },
            was_it_isolated: {
                required: 'These details are required'
            },
            why_you_believe_above: {
                required: 'These details are required'
            },
            your_reaction: {
                required: 'Your reaction is required'
            },
            any_witness: {
                required: 'Please provide this information'
            },
            spoken_to_anyone: {
                required: 'These details are required'
            },
            reported_to_supervisor: {
                required: 'Please provide this information'
            },
            avoid_future_incidents: {
                required: 'Please provide some suggestions'
            }
        }
    });

    $("#inc-form").submit(function () {
        var flag = 0;
        
        $(".multi-checkbox").each(function (index, element) {
            if ($(this).attr('data-attr') != '0') {
                var key = "multi-list_" + $(this).attr('data-key');
                var name = "input:checkbox[name^='" + key + "']:checked";
                var checked = $(name).length;
                
                if (!checked) {
                    alertify.error($(this).attr('data-value') + ' is required');
                    flag = 1;
                    return false;
                }
            }
        });
//        $.each($('.textarea'),function(){
//            console.log($(this).html());
//            if($(this).html()==''){
//                alertify.error('Please Fill All Required Fields');
//                flag = 1;
//            }
//        });
        if (flag) {
            return false;
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group form-control autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">' + fileName + '</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-info" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-info" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div>');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload() {
        $('.upload-file').show();
        
        if ($('#uploaded-files').html() != '') {
            $('#uploaded-files').show();
        }
        
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload() {
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('id', <?php echo $id; ?>);
        
        if ($('#inc-id').val() != '0') {
            form_data.append('inc_id', $('#inc-id').val());
        }
        
        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('incident_reporting_system/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (data) {
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();
                $('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> ' + file_data['name'] + '</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
//                $('#uploaded-files').append(file_data['name'] + '<br>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                
                if (data != "error") {
                    $('#inc-id').val(data);
                } else {
                    alert('Doc error');
                }
            },
            error: function () {
            }
        });
    }

    var mousePressed = false;
    var lastX, lastY;
    var ctx;

    var stored_image = '<?php echo isset($e_signature_data['signature_bas64_image']) && !empty($e_signature_data['signature_bas64_image']) ? $e_signature_data['signature_bas64_image'] : ''; ?>';

    var canvas_id  = 'can';

    function InitThis() {
        ctx = document.getElementById(canvas_id).getContext("2d");

        if(stored_image != '' && stored_image != undefined && stored_image != null){
            console.log('Condition true');
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

    function Draw(x, y, isDown) {
        if (isDown) {
            ctx.beginPath();
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineJoin = "round";

            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);

            ctx.closePath();
            ctx.stroke();
        }
        lastX = x; lastY = y;
    }

    function clearArea() {
        // Use the identity matrix while clearing the canvas
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    }

    function get_signature(){
        canvas = document.getElementById(canvas_id);
        var dataURL = canvas.toDataURL();

        $('#drawn_signature').val(dataURL);
    }

    window.onload = InitThis();

    $(document).ready(function () {
        $('#draw_signature').hide();

        $('.active_signature').on('click', function () {
            var selected = $(this).val();

            if (selected == 'drawn') {
                $('#type_signature').hide();
                $('#draw_signature').show();
                $('#upload_signature').hide();
            } else if (selected == 'typed') {
                $('#type_signature').show();
                $('#draw_signature').hide();
                $('#upload_signature').hide();
            } else if (selected == 'upload') {
                $('#type_signature').hide();
                $('#draw_signature').hide();
                $('#upload_signature').show();
            }    
        });

        $('.active_signature:checked').trigger('click');

        $('#form_e_signature').validate({
            errorClass: 'text-danger',
            errorElement: 'p',
            errorElementClass: 'text-danger'
        });

        $('#draw_upload').hide();
        $('#typed_signature').hide();

        $( ".get_signature" ).click(function() {
            var company = '<?php echo $company_sid; ?>';
            var employee = '<?php echo $employer_sid; ?>';
            var myurl = "<?= base_url() ?>onboarding/get_signature/"+employee+"/"+company;
        
            $.ajax({
                type: "GET",
                url: myurl,
                async : false,
                success: function (data) {
                    if(data == false){
                        $('#E_Signature_Modal').modal('show');
                    }else{
                        var obj = jQuery.parseJSON(data);
                        var signature = obj.signature;
                        var signature_bas64_image = obj.signature_bas64_image;
                        var active_signature = obj.active_signature;
                        
                        if(active_signature == 'typed') {
                            $('#signature').val(signature);
                            $('#typed_signature').show();

                        } else if (active_signature == 'drawn') {
                            var src = signature_bas64_image;
                            $('#draw_upload_img').attr('src', src);
                            $('#draw_upload').show();
                            
                        } else if (active_signature == 'upload') {
                            var src = signature_bas64_image;
                            $('#draw_upload_img').attr('src', src);
                            $('#draw_upload').show();
                            
                        }
                    }
                },
                error: function (data) {

                }
            });

            $('#signatureModel').modal('show');
        });

        $('.start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15
        });

        $('.start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    }); 

    function func_save_e_signature() {
        if ($('#form_e_signature').valid()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function () {
                    save_e_signature();
                },
                function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
        }
    }

    function check_signature(val, element) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            if (val == 'signature_upload') {
                if (ext != "jpg" && ext != "jpe" && ext != "jpeg" && ext != "png" && ext != "gif") {
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

                        $("#drawn_signature").val(reader.result);

                      }

                      reader.readAsDataURL(file);
                    return true;
                }

            }
        } else {
            $('#name_' + val).html('No signature selected');
            alertify.error("No signature selected");
            $('#name_' + val).html('<p class="red">Please select signature</p>');

        }    
    }


    

    function save_e_signature () {

    

       //  var file_data = $('#signature_upload').prop('files')[0];
       //  var forms = new FormData();
       //  forms.append('signature_upload', file_data);
       //  forms.append('e_perform_action', $('#perform_action').val());
       //  forms.append('company_sid', $('#company_sid').val());
       //  forms.append('user_type', $('#user_type').val());
       //  forms.append('user_sid', $('#user_sid').val());
       //  forms.append('ip_address', $('#ip_address').val());
       //  forms.append('user_agent', $('#user_agent').val());
       //  forms.append('first_name', $('#first_name').val());
       //  forms.append('last_name', $('#last_name').val());
       //  forms.append('email_address', $('#email_address').val());
       //  forms.append('signature_timestamp', $('#signature_timestamp').val());
       //  forms.append('active_signature', $('input[name=active_signature]:checked').val());
       //  forms.append('signature', $('#e_signature').val());
       //  forms.append('drawn_signature', $('#drawn_signature').val());
       //  forms.append('user_consent', 1);
    
    
       // console.log('I am here '+forms); 
       //   alert('stop');

        var myurl = "<?= base_url() ?>onboarding/ajax_e_signature";
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
           
            data: $('#form_e_signature').serialize(),
            url: myurl,

            success: function(data){
                alertify.success('Link Update  Successfully');
                $('#E_Signature_Modal').modal('hide');

                var company = '<?php echo $company_sid; ?>';
                var employee = '<?php echo $employer_sid; ?>';
                var myurl = "<?= base_url() ?>onboarding/get_signature/"+employee+"/"+company;
            
                $.ajax({
                    type: "GET",
                    url: myurl,
                    async : false,
                    success: function (data) {
                        if(data == false){
                            $('#E_Signature_Modal').modal('show');
                        }else{
                            var obj = jQuery.parseJSON(data);
                            var signature = obj.signature;
                            var signature_bas64_image = obj.signature_bas64_image;
                            var active_signature = obj.active_signature;
                            
                            if(active_signature == 'typed') {
                                $('#signature').val(signature);
                                $('#typed_signature').show();

                            } else if (active_signature == 'drawn') {
                                var src = signature_bas64_image;
                                $('#draw_upload_img').attr('src', src);
                                $('#draw_upload').show();
                                
                            } else if (active_signature == 'upload') {
                                var src = signature_bas64_image;
                                $('#draw_upload_img').attr('src', src);
                                $('#draw_upload').show();
                                
                            }
                        }
                    },
                    error: function (data) {

                    }
                });

            },
            error: function(){

            }
        });
        
    }; 
</script>