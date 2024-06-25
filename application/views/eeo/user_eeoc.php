<?php
$id = $eeo_form_info['sid'] ?? 0;
$linkText = 'Employee Profile';
$linkURL = base_url('employee_profile/' . $user_sid);
//
if ($user_type == 'applicant') {
    $linkText = 'Applicant Profile';
    $linkURL = base_url('applicant_profile/' . $user_sid . '/' . $job_list_sid);
}
?>
<!--  -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                <!--  -->
                <div class="page-header-area margin-top">
                    <span class="page-heading down-arrow">
                        <?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a class="dashboard-link-btn" href="<?= $linkURL; ?>">
                            <i aria-hidden="true" class="fa fa-chevron-left"></i><?= $linkText; ?>
                        </a>
                        <?= $title; ?>
                    </span>
                </div>
                <!-- EEOC -->
                <?php if (empty($eeo_form_info)) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="text-center">
                                The <?php echo $user_type == 'applicant' ? 'applicant' : 'employee'; ?> has not completed the EEOC form.
                                <br />
                                <br />
                                <a class="btn btn-success btn-lg jsResendEEOC" ref="javascript:void(0);" title="Assign EEOC form to <?= ucwords($user_name); ?>" placement="top">Assign EEO Form</a>
                            </h2>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php if ($eeo_form_info['status'] != 1) { ?>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button onclick="func_assign_EEOC('active');" class="btn btn-warning">Re-Assign</button>
                                <?php if (!empty($track_history)) { ?>
                                    <button onclick="show_document_track('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View action trail for EEOC form" placement="top">EEOC Trail</button>
                                <?php } ?>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <h2>The <?php echo $user_type == 'applicant' ? 'applicant' : 'employee'; ?> has not completed the EEOC form.</h2>
                            </div>
                        </div>
                    <?php } elseif ($eeo_form_info["is_opt_out"]) { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <br>
                                <p class="alert alert-info text-center">
                                    You have Opt-out from the EEOC.
                                </p>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <?php if ($eeo_form_info["is_expired"] == 1) { ?>
                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_eeoc_form/download' . '/' . $user_sid . '/' . $user_type); ?>" class="btn btn-success" title="Download EEOC Form" placement="top">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        Download PDF
                                    </a>
                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_eeoc_form/print' . '/' . $user_sid . '/' . $user_type); ?>" class="btn btn-success" title="Print EEOC Form" placement="top">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                        Print PDF
                                    </a>
                                <?php } ?>
                                <button onclick="func_remove_EEOC('deactive');" class="btn btn-danger">Revoke</button>
                                <?php if (!empty($track_history)) { ?>
                                    <button onclick="show_document_track('eeoc', <?= $eeo_form_info['sid']; ?>);" class="btn btn-success" title="View action trail for EEOC form" placement="top">EEOC Trail</button>
                                <?php } ?>
                                <?php if ($eeo_form_info["is_expired"] != 1) { ?>
                                    <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Send EEOC form to <?= ucwords($user_name); ?>" placement="top">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        Send Email Reminder
                                    </a>
                                    <button class="btn btn-orange csW jsEEOCOptOut" data-id="<?= $eeo_form_info["sid"]; ?>" title="You will be opt-out of the EEOC form.">
                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        Opt-out
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <span>
                                    Assigned On :
                                    <strong>
                                        <?= !empty($eeo_form_info['last_sent_at']) ? formatDateToDB($eeo_form_info['last_sent_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : ""; ?>
                                    </strong>
                                </span>
                                <br>
                                <span>
                                    Assigned By :
                                    <strong>
                                        <?= getUserNameBySID($eeo_form_info['last_assigned_by']); ?>
                                    </strong>
                                </span>
                            </div>
                        </div>
                        <hr>
                        <!-- FORM -->
                        <?php $this->load->view('eeo/eeoc_new_form'); ?>
                    <?php } ?>
                <?php } ?>

                <!-- EEOC History -->
                <?php if (!empty($verification_documents_history)) { ?>
                    <hr />
                    <?php $this->load->view('hr_documents_management/verification_documents_history'); ?>
                <?php } ?>
            </div>
            <!-- Side bar -->
            <?php $this->load->view($left_navigation); ?>
        </div>
    </div>
</div>


<?php if (!empty($eeo_form_info)) { ?>
    <?php $this->load->view('hr_documents_management/document_track'); ?>
<?php } ?>


<script>
    $('[data-toggle="tooltip"]').tooltip({
        trigger: "hover"
    });
    //
    function func_remove_EEOC(status) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to revoke this document?',
            function() {
                $.post(
                    "<?= base_url('change_form_status'); ?>", {
                        userId: <?= $user_sid; ?>,
                        userType: "<?= $user_type; ?>",
                        action: status
                    }
                ).done(function(resp) {
                    //
                    if (resp == 'success') {
                        alertify.alert('Success!', 'Document Successfully Revoked!', function() {
                            window.location.reload();
                        });
                    } else {
                        //
                        alertify.alert('Error!', 'Something went wrong while resending the EEOC form.')
                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }
    //
    function func_assign_EEOC(status) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to assign this document?',
            function() {
                $.post(
                    "<?= base_url('change_form_status'); ?>", {
                        userId: <?= $user_sid; ?>,
                        userType: "<?= $user_type; ?>",
                        action: status
                    }
                ).done(function(resp) {
                    //
                    if (resp == 'success') {
                        alertify.alert('Success!', 'Document Successfully Assigned!', function() {
                            window.location.reload();
                        });
                    } else {
                        //
                        alertify.alert('Error!', 'Something went wrong while resending the EEOC form.')
                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            });
    }
    //
    $(function(e) {
        //
        $('.jsResendEEOC').click(function(event) {
            //
            event.preventDefault();
            //
            alertify.confirm(
                "Are you sure you want to send EEOC form?",
                function() {
                    //
                    $.post(
                        "<?= base_url('send_eeoc_form'); ?>", {
                            userId: <?= $user_sid; ?>,
                            userType: "<?= $user_type; ?>",
                            userJobId: "<?= $job_list_sid; ?>",
                            userLocation: "EEOC Form"
                        }
                    ).done(function(resp) {
                        //
                        if (resp == 'success') {
                            alertify.alert('Success!', 'EEOC form has been sent.', function() {
                                window.location.reload();
                            });
                        } else {
                            //
                            alertify.alert('Error!', resp, function() {
                                window.location.reload();
                            })
                        }
                    });
                }
            ).setHeader('Confirm!');
        });

        //
        $('.jsSaveEEOC').click(function() {
            //
            var citizenFlag = <?php echo $dl_citizen; ?>
            //
            var obj = {
                id: <?= $id; ?>,
                citizen: $('input[name="citizen"]:checked').val(),
                group: $('input[name="group"]:checked').val(),
                veteran: $('input[name="veteran"]:checked').val(),
                disability: $('input[name="disability"]:checked').val(),
                gender: $('input[name="gender"]:checked').val(),
                location: "<?= $location; ?>",
                eeoc_action: "update"
            };

            //
            if (citizenFlag == 1 && obj.citizen === undefined) {
                alertify.alert('Please, select a citizen.');
                return;
            }

            alertify.confirm(
                'Are you sure?',
                'Are you certain about updating this document?',
                function() {
                    $.post(
                        "<?= base_url("eeoc_form_submit"); ?>",
                        obj
                    ).done(function(resp) {
                        //
                        if (resp === 'success') {
                            alertify.alert('Success!', 'The EEOC form successfully saved.', function() {
                                window.location.reload();
                            });
                            return;
                        }
                        //
                        alertify.alert('Success!', 'You have successfully submitted the EEOC form.');
                    });
                },
                function() {
                    alertify.alert("Warning", 'Cancelled!');
                });
        });

        $('.jsConsentEEOC').click(function() {
            //
            var citizenFlag = <?php echo $dl_citizen; ?>
            //
            var obj = {
                id: <?= $id; ?>,
                citizen: $('input[name="citizen"]:checked').val(),
                group: $('input[name="group"]:checked').val(),
                veteran: $('input[name="veteran"]:checked').val(),
                disability: $('input[name="disability"]:checked').val(),
                gender: $('input[name="gender"]:checked').val(),
                location: "<?= $location; ?>",
                eeoc_action: "consent"
            };

            //
            if (citizenFlag == 1 && obj.citizen === undefined) {
                alertify.alert('Please, select a citizen.');
                return;
            }

            alertify.confirm(
                'Are you sure?',
                'Are you certain about providing consent for this document?',
                function() {
                    $.post(
                        "<?= base_url("eeoc_form_submit"); ?>",
                        obj
                    ).done(function(resp) {
                        //
                        if (resp === 'success') {
                            alertify.alert('Success!', 'The EEOC form successfully concent EEOC.', function() {
                                window.location.reload();
                            });
                            return;
                        }
                        //
                        alertify.alert('Success!', 'You have successfully consent the EEOC form.');
                    });
                },
                function() {
                    alertify.alert("Warning", 'Cancelled!');
                });
        });
    });
</script>


<script>
    $(function() {
        let eeoId;
        $(".jsEEOCOptOut").click(function(event) {
            event.preventDefault();
            eeoId = $(this).data("id");
            _confirm(
                "Do you really want to 'Opt-out' of the EEOC form?",
                startOptOutProcess
            );
        });

        function startOptOutProcess() {
            const _hook = callButtonHook(
                $(".jsEEOCOptOut"),
                true
            );
            $.ajax({
                    url: baseUrl("eeoc/" + (eeoId) + "/opt_out"),
                    method: "PUT",
                })
                .always(function() {
                    callButtonHook(_hook, false)
                })
                .fail(handleErrorResponse)
                .success(function(resp) {
                    _success(
                        resp.message,
                        window.location.refresh
                    )
                });
        }
    })
</script>