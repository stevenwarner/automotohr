<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <?php if($company_has_applicant_approval_rights == 1) { ?>
                                    <div class="box-view reports-filtering">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <button id="btn-pending" class="page-heading pending-color no-margin"><span><?php echo count($pending_applicants); ?></span> Pending Approvals</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button id="btn-approved" class="page-heading no-margin"><span><?php echo count($approved_applicants); ?></span> Approved</button>
                                            </div>
                                            <div class="col-xs-4">
                                                <button id="btn-rejected" class="page-heading reject-btn no-margin"><?php echo count($rejected_applicants); ?></span> Rejected</button>
                                            </div>
                                        </div>
                                    </div>
                                    <header id="pending" class="category-sec-header">
                                        <h2>All Pending Applicant Approval Requests</h2>
                                    </header>
                                    <?php if(!empty($pending_applicants)) { ?>
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp border-none mylistings-wrp">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date</th>
                                                        <th class="col-xs-2">Applicant Name</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Requested By</th>
                                                        <th class="col-xs-1">Status</th>
                                                        <th class="col-xs-3 text-center" colspan="3">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($pending_applicants as $applicant) { ?>
                                                        <tr>
                                                            <td>
                                                                <?=$applicant['approval_date'] == '0000-00-00' ? 'N/A' : reset_datetime(array( 'datetime' => $applicant['approval_date'], '_this' => $this));?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['Title']); ?>
                                                            </td>
                                                            <td>
                                                                <?php $approving_auth = ucwords($applicant['approver_fname'] . ' ' . $applicant['approver_lname']); ?>
                                                                <?php $approving_auth = (trim($approving_auth) == '' ? 'N/A' : $approving_auth); ?>
                                                                <?php echo $approving_auth;?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['approval_status']);?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button data-approval-status-reason="<?php echo htmlentities(trim($applicant['approval_status_reason'])); ?>" data-approval-status-reason-response="<?php echo htmlentities(trim($applicant['approval_status_reason_response'])); ?>" onclick="fSetStatus('approved', <?php echo $applicant['portal_job_applications_sid']; ?>, this, <?php echo $applicant['job_sid'] ?>);" class="submit-btn" type="button" title="Approve">Approve</button>
                                                            </td>
                                                            <td class="text-center">
                                                                <button onclick="func_show_applicant_rejection_form(<?php echo $applicant['portal_job_applications_sid']; ?>, <?php echo $applicant['company_sid']; ?>, <?php echo $applicant['job_sid']; ?> );" class="submit-btn reject-btn" type="button" title="Reject">Reject</button>
                                                            </td>
                                                            <td class="text-center">
                                                                <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('applicant_profile') . '/' . $applicant['portal_job_applications_sid']; ?>" title="View / Edit">View</i></a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <div class="table-responsive table-outer">
                                        <div class="table-wrp border-none mylistings-wrp">
                                            <table class="table table-bordered table-hover table-stripped">
                                                <thead>
                                                <tr>
                                                    <th class="col-xs-1">Date</th>
                                                    <th class="col-xs-2">Applicant Name</th>
                                                    <th class="col-xs-4">Job Title</th>
                                                    <th class="col-xs-2">Requested By</th>
                                                    <th class="col-xs-1">Status</th>
                                                    <th class="col-xs-2">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6">No Requests</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <header id="approved" class="category-sec-header">
                                        <h2>All Approved Applicants</h2>
                                    </header>
                                    <?php if(!empty($approved_applicants)) { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date</th>
                                                        <th class="col-xs-2">Applicant Name</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Approved By</th>
                                                        <th class="col-xs-1">Status</th>
                                                        <th class="col-xs-3 text-center" colspan="2">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($approved_applicants as $applicant) { ?>
                                                        <tr>
                                                            <td>
                                                                <?=$applicant['approval_date'] == '0000-00-00' ? 'N/A' : reset_datetime(array( 'datetime' => $applicant['approval_date'], '_this' => $this, 'format' => 'm-d-Y'));?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['Title']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['approver_fname'] . ' ' . $applicant['approver_lname']);?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['approval_status']);?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button onclick="func_show_applicant_rejection_form(<?php echo $applicant['portal_job_applications_sid']; ?>, <?php echo $applicant['company_sid']; ?>, <?php echo $applicant['job_sid']; ?> );" class="submit-btn reject-btn" type="button" title="Reject">Reject</button>
                                                            </td>
                                                            <td class="text-center">
                                                                <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('applicant_profile') . '/' . $applicant['portal_job_applications_sid']?>">View</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date</th>
                                                        <th class="col-xs-2">Applicant Name</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Approved By</th>
                                                        <th class="col-xs-1">Status</th>
                                                        <th class="col-xs-3 text-center">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="6">No Applicants</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <header id="rejected" class="category-sec-header">
                                        <h2>All Rejected Applicants</h2>
                                    </header>
                                    <?php if(!empty($rejected_applicants)){ ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date</th>
                                                        <th class="col-xs-2">Applicant Name</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Approved By</th>
                                                        <th class="col-xs-1">Status</th>
                                                        <th class="col-xs-3 text-center" colspan="2">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($rejected_applicants as $applicant) { ?>
                                                        <tr>
                                                            <td>
                                                                <?=$applicant['approval_date'] == '0000-00-00' ? 'N/A' : reset_datetime(array( 'datetime' => $applicant['approval_date'], '_this' => $this, 'format' => 'm-d-Y'));?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['Title']); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['approver_fname'] . ' ' . $applicant['approver_lname']);?>
                                                            </td>
                                                            <td>
                                                                <?php echo ucwords($applicant['approval_status']);?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button data-approval-status-reason="<?php echo htmlentities(trim($applicant['approval_status_reason'])); ?>" data-approval-status-reason-response="<?php echo htmlentities(($applicant['approval_status_reason_response'])); ?>" onclick="fSetStatus('approved', <?php echo $applicant['portal_job_applications_sid'] ?>, this, <?php echo $applicant['job_sid'] ?>);" class="submit-btn" type="button" title="Approve">Approve</button>
                                                            </td>
                                                            <td>
                                                                <a target="_blank" class="submit-btn" type="button" href="<?php echo base_url('applicant_profile') . '/' . $applicant['portal_job_applications_sid']?>">View</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="table-responsive table-outer">
                                            <div class="table-wrp border-none mylistings-wrp">
                                                <table class="table table-bordered table-hover table-stripped">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date</th>
                                                        <th class="col-xs-2">Applicant Name</th>
                                                        <th class="col-xs-3">Job Title</th>
                                                        <th class="col-xs-2">Approved By</th>
                                                        <th class="col-xs-1">Status</th>
                                                        <th class="col-xs-3">Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="4">No Job Listings</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="create-job-box">
                                        <div class="dash-box">
                                            <h2>You don't have Job Approvals Module Enabled!</h2>
                                            <span>Get it Enabled from Site Admin?</span>
                                            <div class="button-panel">
                                                <a class="site-btn" href="javascript:void(0);">Learn More</a>
                                            </div>
                                            <p>With this module you can Approve / Reject jobs created by employees</p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fValidateRejectionForm(form_to_validate) {
        $('#' + form_to_validate).validate({
            ignore:'[disabled]'
        });

        if($('#' + form_to_validate).valid()){
            $('#' + form_to_validate).submit();
        }
    }

    function func_show_applicant_rejection_form(applicant_sid, company_sid, job_sid) {
        var data_to_send =  {
                            'perform_action': 'get_applicant_rejection_form',
                            'applicant_sid': applicant_sid,
                            'company_sid': company_sid,
                            'job_sid': job_sid
                            };

        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('application_tracking_system/ajax_responder'); ?>',
            'type': 'POST',
            'responseType': 'json',
            'data': data_to_send
        });

        my_request.done(function (response) {
            if(response != '') {
                response = JSON.parse(response);
            }
            //console.log(response);
            $('#popupmodal #popupmodallabel').html('Reject Applicant')
            $('#popupmodal #popupmodalbody').html(response.view);
            $('#popupmodal').modal('toggle');
        });
    }

    function fSetStatusRejected(status, applicantId) {
        alertify.genericDialog ($('#form_rejection_reason_' + applicantId)[0]).set('selector', '#reason_' + applicantId);
    }

    function fSetStatus(status, applicantId, source, job_sid) {
        var status_reason = $(source).attr('data-approval-status-reason');
        var status_reason_response = $(source).attr('data-approval-status-reason-response');
        var status_reason_formated = '';
        
        if(status_reason != ''){
            status_reason_formated = '<br/><div class="panel panel-default"><div class="panel-heading"><strong>Approval Requirements</strong></div> <div class="panel-body">' + status_reason + '</div> </div>';
        }

        var status_reason_response_formated = '';
        
        if(status_reason_response != ''){
            status_reason_response_formated = '<div class="panel panel-default"><div class="panel-heading"><strong>Employer Response</strong></div><div class="panel-body">' + status_reason_response + '</div></div>';
        }

        alertify.confirm(
            'Are you sure?',
            '<p>Are you sure you want to mark this Applicant as ' + status + ' for Hiring?</p>' + status_reason_formated + status_reason_response_formated,
            function (event) {
                var myUrl = '<?php echo base_url("applicant_approval_management/ajax_responder")?>';
                var valid_flag = false;
                var value = '';
                var dataToPost = {
                                    'perform_action': 'update_applicant_approval_status',
                                    'status': status,
                                    'applicant_id': applicantId,
                                    'reason': value,
                                    'job_sid': job_sid
                                };

                //console.log(dataToPost);
                var myRequest;
                myRequest = $.ajax({
                                    url: myUrl,
                                    type: 'POST',
                                    data: dataToPost
                                });

                myRequest.done(function (response) {
                    if (response == 'success') {
                        var myLocation = window.location.href;
                        window.location = myLocation;
                    } else { //console.log(response);
                        alertify.notify('Failed to Update Approval Status');
                    }
                });
            },
            function () {
                //cancel
            }
        );
    }

    alertify.genericDialog || alertify.dialog('genericDialog',function() {
        return {
            main:function(content){
                this.setContent(content);
            },
            setup:function(){
                return {
                    focus:{
                        element:function(){
                            return this.elements.body.querySelector(this.get('selector'));
                        },
                        select:true
                    },
                    options:{
                        basic: false,
                        maximizable: false,
                        resizable: false,
                        padding: true,
                        title: "Are you sure?"
                    }
                };
            },
            settings:{
                selector:undefined
            }
        };
    });
    
    $(document).ready(function () {
        $('#popupmodal').on('shown.bs.modal', function () {
            $('.rejection_type').on('click', function(){
                if($(this).prop('checked') == true){
                    if($(this).val() == 'rejected_conditionally'){
                        //console.log('conditionally');
                        $('#approval_status_reason').prop('disabled', false);
                    } else {
                        //console.log('un conditionally');
                        $('#approval_status_reason').prop('disabled', true);
                    }
                }
            });
        });

        $('input[type=radio]').each(function () {
            $(this).on('click', function () {
                var value = $(this).val();

                if(value == 'rejected_conditionally'){
                    $('.reason').prop('disabled', false);
                } else {
                    $('.reason').prop('disabled', true);
                    $('label.error').hide();
                }
            });
        });

        $('.hidden_form').each(function () {
            $(this).hide();
        });

        $('#btn-pending').on('click', function(){
            $('html, body').animate({scrollTop: $('#pending').offset().top}, 1000);
        });

        $('#btn-approved').on('click', function(){
            $('html, body').animate({scrollTop: $('#approved').offset().top}, 1000);
        });

        $('#btn-rejected').on('click', function(){
            $('html, body').animate({scrollTop: $('#rejected').offset().top}, 1000);
        });
    });

    function func_reject_applicant(applicant_sid, company_sid, job_sid){
        $('#form_applicant_rejection').validate();
    }
    
    function fValidateRejectionResponseForm() {
        $('#form_applicant_rejection').validate({
            'ignore': '[disabled]'
        });

        if ($('#form_applicant_rejection').valid()) {
            var form_data = func_convert_form_to_json_object('form_applicant_rejection');
            form_data.perform_action = 'REJECT_APPLICANT';
            var my_request;

            my_request = $.ajax({
                'url': '<?php echo base_url('applicant_approval_management/ajax_responder'); ?>',
                'type': 'POST',
                'responseType': 'json',
                'data': form_data
            });

            my_request.done(function(response) {
                if(response == 'success'){
                    window.location.href = window.location.href;
                } else {
                    alertify.error('Something Went Wrong!');
                }
            });
        }
    }
</script>

