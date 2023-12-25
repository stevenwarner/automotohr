<?php if ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>State Form(s)</strong>
                </div>
                <div class="panel-body" style="min-height: 200px;">
                    <!-- Data -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <caption></caption>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-center">Assigned On</th>
                                    <th class="text-center">Completion Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <!--  -->
                            <tbody>
                                <?php foreach ($companyStateForms as $companyStateForm) { ?>
                                    <tr data-id="<?= $companyStateForm["sid"]; ?>">
                                        <td class="vam">
                                            <?= $companyStateForm["title"]; ?>
                                        </td>
                                        <td class="text-center vam">
                                            <?php if ($companyStateForm["status"] === "assigned") { ?>
                                                <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                                                <p>
                                                    <?= formatDateToDB($companyStateForm["assigned_at"], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                </p>
                                            <?php } else { ?>
                                                <i class="fa fa-times fa-2x text-danger" aria-hidden="true"></i>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center vam">
                                            <img src="<?= base_url("assets/manage_admin/images/" . ($companyStateForm["is_completed"] ? "on" : "off") . ".gif"); ?>" alt="<?= $companyStateForm["is_completed"] ? "Signed" : "Not signed" ?>" />
                                            <?php if ($companyStateForm["is_completed"]) { ?>
                                                <p>
                                                    <?= formatDateToDB($companyStateForm["assigned_at"], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                </p>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center vam">
                                            <?php if ($companyStateForm["status"] === "not_assigned") { ?>
                                                <button class="btn btn-success jsFormStatusChange" data-event="assign">
                                                    Assign
                                                </button>
                                            <?php } elseif ($companyStateForm["status"] === "assigned") { ?>
                                                <button class="btn btn-danger jsFormStatusChange" data-event="revoke">
                                                    Revoke
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-warning jsFormStatusChange" data-event="reassign">
                                                    Re-assign
                                                </button>
                                            <?php } ?>
                                            <button class="btn btn-success jsFormTrail hidden">
                                                Trail
                                            </button>
                                            <button class="btn btn-success jsFormHistory hidden">
                                                History
                                            </button>
                                            <?php if ($companyStateForm["status"] === "assigned") { ?>
                                                <?php if ($companyStateForm["is_employer_completed"]) { ?>
                                                    <button class="btn blue-button jsEmployerStateSectionPrefill" form_sid="<?php echo $companyStateForm['sid']; ?>">
                                                        Employer Section - Completed
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="btn btn-success jsEmployerStateSectionPrefill" form_sid="<?php echo $companyStateForm['sid']; ?>">
                                                        Employer Section - Not Completed
                                                    </button>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($companyStateForm["is_completed"]) { ?>
                                                <button class="btn btn-success jsPreviewAssignStateForm" form_sid="<?php echo $companyStateForm['sid']; ?>">
                                                    View Signed
                                                </button>
                                                <a class="btn btn-success" target="_blank" href="<?php echo base_url("hr_documents_management/state_form_action/".$user_sid.'/'.$user_type.'/'.$companyStateForm['sid'].'/green/print'); ?>">
                                                    Print
                                                </a>
                                                <a class="btn btn-success" target="_blank" href="<?php echo base_url("hr_documents_management/state_form_action/".$user_sid.'/'.$user_type.'/'.$companyStateForm['sid'].'/green/download'); ?>">
                                                    Download
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function stateForms() {
            /**
             * holds the XHR object
             */
            let xhr = null;
            /**
             * holds the user id
             */
            let userId = <?= $user_sid; ?>;
            /**
             * holds the user type
             */
            let userType = "<?= $user_type; ?>";

            /**
             * state change
             */
            $(".jsFormStatusChange").click(function(event) {
                //
                event.preventDefault();
                //
                if (xhr !== null) {
                    return false;
                }
                //
                const passObj = {
                    formId: $(this).closest("tr").data("id"),
                    eventType: $(this).data("event"),
                };
                //
                const ref = callButtonHook($(this), true);
                let msg = "Do you really want to " + (passObj.eventType) + " this document?";

                alertify.confirm(
                    msg,
                    function() {
                        //
                        xhr = $.ajax({
                                url: "<?= base_url("state/forms/{$user_sid}/{$user_type}"); ?>",
                                method: "POST",
                                data: passObj
                            })
                            .always(function() {
                                xhr = null;
                                callButtonHook(ref, false);
                            })
                            .success(function(resp) {
                                alertify.alert(
                                    resp.msg,
                                    function() {
                                        window.location.reload();
                                    }
                                );
                            });
                    }
                )

            });

            /**
             * button hook
             *
             * @param {object} appendUrl
             * @param {bool}   doShow
             * @return
             */
            function callButtonHook(reference, doShow = true) {
                //
                if (doShow) {
                    const obj = {
                        pointer: reference,
                        html: reference.html(),
                    };
                    reference.html(
                        '<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>'
                    );
                    //
                    return obj;
                }
                //
                reference.pointer.html(reference.html);
            }

        });
    </script>

<?php } ?>