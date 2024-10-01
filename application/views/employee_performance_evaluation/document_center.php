<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" style="position: relative;">
            <div class="panel-heading">
                <strong>
                    Employee Performance Evaluation
                </strong>
            </div>
            <div class="panel-body" style="min-height: 200px;">
                <!-- Loader -->
                <div class="cs-inner-loader jsEPELoader">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                </div>
                <!-- Data -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th class="text-center">Assigned On</th>
                                <th class="text-center">Completion Status</th>
                                <!-- <th class="text-center">Is Required?</th> -->
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <!--  -->
                        <tbody id="jsEPEBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url("assets/css/SystemModel.css"); ?>">
<script src="<?= base_url("assets/js/SystemModal.js"); ?>"></script>
<script type="text/javascript" src="https://automotohr.local/assets/js/app_helper.js?v=1691667825"></script>
<script>
    $(function EmployeePerformanceEvaluationDocument() {

        let XHR = null;

        $(document).on("click", ".jsAssignEPE", handleAssignProcess);
        $(document).on("click", ".jsRevokeEPE", handleRevokeProcess);
        $(document).on("click", ".jsReAssignEPE", handleReAssignProcess);

        // Sections
        $(document).on("click", ".jsEPESectionOne", handleSectionOneProcess);
        $(document).on("click", ".jsSaveSectionOne", handleSectionOneSave);
        $(document).on("click", ".jsEPESectionThree", handleSectionThreeProcess);
        $(document).on("click", ".jsSaveSectionThree", handleSectionThreeSave);
        $(document).on("click", ".jsEPESectionFour", handleSectionFourProcess);
        $(document).on("click", ".jsGetEmployeeSignature", handleSectionFourSignature);
        $(document).on("click", ".jsSaveEsignature:enabled", handleSectionFourSave);
        $(document).on("click", ".jsEPESectionFive", handleSectionFiveProcess);
        $(document).on("click", ".jsSaveSectionFive", handleSectionFiveSave);
        $(document).on("click", ".jsSaveHRManagerApproval", handleSectionFiveApprovalSave);

        $(document).on("click", ".jsSendVerificationRequest", function() {
            event.preventDefault();
            //
            selected_employees = get_all_selected_employees();
            //
            if (selected_employees.length === 0) {
                alertify.alert('ERROR!', 'Please choose at least one manager to receive the performance evaluation verification email.');
                return;
            }
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            alertify.confirm(
                'Are you sure?',
                'Are you certain you wish to send the performance evaluation verification?',
                function() {
                    //
                    const btnHook = callButtonHook($(".jsSendVerificationRequest"), true);
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/send_verification_request/<?= $user_sid; ?>/1"),
                            method: "POST",
                            data: {
                                employees: selected_employees
                            }
                        })
                        .always(function() {
                            XHR = null;
                            callButtonHook(btnHook, false);
                        })
                        .fail(handleErrorResponse)
                        .done(function(resp) {
                            return _success(
                                resp.message,
                                function() {
                                    window.location.href = window.location.href;
                                }
                            )
                        });
                },
                function() {
                    alertify.error('Cancelled!');
                });
        });

        function get_all_selected_employees() {
            //
            var tmp = [];
            //
            $.each($('input[name="employees[]"]:checked'), function() {
                var obj = {};
                obj.employee_sid = parseInt($(this).val());
                tmp.push(obj);
            });
            //
            return tmp;
        }

        /**
         * get the form
         */
        function getEPE() {
            if (XHR !== null) {
                return;
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/<?= $user_sid; ?>/get"),
                    method: "GET",
                })
                .always(function() {
                    XHR = null;
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    setView(response.data);
                });
        }

        /**
         * set the view
         */
        function setView(data) {
            let row = "";

            row += '<tr>';
            row += '    <td>';
            row += '        <strong>';
            row += 'Employee Performance Evaluation';
            row += '        </strong>';
            row += '    </td>';
            // when not assigned
            if (data.status === "not_assigned") {
                // not assigned
                row += '    <td class="text-center">';
                row += '<i class="fa fa-times fa-2x text-danger"></i>';
                row += '    </td>';
                // not completed
                row += '    <td class="text-center">';
                row += '<img src="<?= base_url('assets/manage_admin/images'); ?>/off.gif" title="Not Completed" />';
                row += '    </td>';
                // is required
                // row += '    <td class="text-center">';
                // row += '        <label class="control control--checkbox">';
                // row += '            <input type="checkbox" name="jsRequiredCheckboxEPE" disabled />';
                // row += '            <div class="control__indicator"></div>';
                // row += '        </label>';
                // row += '    </td>';
                // assign action
                row += '    <td class="text-center">';
                row += '        <button class="btn btn-success jsAssignEPE jsEPEBtn">Assign</button>';
                row += '    </td>';
                row += '</tr>';
            } else if (data.status === "assigned") {
                row += '    <td class="text-center">';
                row += '        <i class="fa fa-check fa-2x text-success"></i>';
                row += '        <p>';
                row += '            <strong>';
                row += '                Assigned by: ' + data.assigned_by;
                row += '            </strong>';
                row += '        </p>';
                row += '        <p>';
                row += '            <strong>';
                row += '                Assigned On: ' + (moment(data.assigned_on).format("MMM Do YYYY, ddd H:m:s"));
                row += '            </strong>';
                row += '        </p>';
                row += '    </td>';
                // not completed
                row += '    <td class="text-center">';
                row += '<img src="<?= base_url('assets/manage_admin/images'); ?>/' + (data.completion_status ? "on" : "off") + '.gif" title="Not Completed" />';
                row += '    </td>';
                // is required
                // row += '    <td class="text-center">';
                // row += '        <label class="control control--checkbox">';
                // row += '            <input type="checkbox" name="jsRequiredCheckboxEPE" disabled />';
                // row += '            <div class="control__indicator"></div>';
                // row += '        </label>';
                // row += '    </td>';
                // assign action
                row += '    <td class="text-center">';
                row += '        <button class="btn btn-danger jsRevokeEPE jsEPEBtn">Revoke</button>';
                row += '        <a target="_blank" href="' + baseUrl("fillable/epe/<?= $user_sid; ?>/manager/print") + '" class="btn btn-orange">Print</a>';
                row += '        <a target="_blank" href="' + baseUrl("fillable/epe/<?= $user_sid; ?>/manager/download") + '" class="btn btn-black">Download</a>';
                // check for sections
                if (!data.sections[1].is_verified) {
                    row += '        <button class="btn btn-success jsEPESectionOne">Complete Section 1</button>';
                }
                //
                if (data.sections[1].is_verified && data.sections[2].status && !data.sections[3].status) {
                    row += '        <button class="btn btn-success jsEPESectionThree">Complete Section 3</button>';
                }
                //
                if (data.sections[3].status && !data.sections[4].status) {
                    row += '        <button class="btn btn-success jsEPESectionFour">Complete Section 4</button>';
                }
                //
                if (data.sections[4].status && !data.sections[5].status) {
                    if (!data.sections[5].manager_completed) {
                        row += '        <button class="btn btn-success jsEPESectionFive">Complete Section 5</button>';
                    } else if (data.sections[5].manager_completed) {
                        row += '        <button class="btn btn-success jsEPESectionFive">Section 5 Approval</button>';
                    }

                }
                //
                row += '    </td>';
                row += '</tr>';
            } else if (data.status === "revoked") {
                row += '    <td class="text-center">';
                row += '        <i class="fa fa-times fa-2x text-danger"></i>';
                row += '    </td>';
                // not completed
                row += '    <td class="text-center">';
                row += '<img src="<?= base_url('assets/manage_admin/images'); ?>/off.gif" title="Not Completed" />';
                row += '    </td>';
                // is required
                row += '    <td class="text-center">';
                row += '        <label class="control control--checkbox">';
                row += '            <input type="checkbox" name="jsRequiredCheckboxEPE" disabled />';
                row += '            <div class="control__indicator"></div>';
                row += '        </label>';
                row += '    </td>';
                // assign action
                row += '    <td class="text-center">';
                row += '        <button class="btn btn-warning jsReAssignEPE jsEPEBtn">Re-assign</button>';
                row += '    </td>';
                row += '</tr>';
            }

            // handle section status
            if (data.status === "assigned") {
                row += '<tr>';
                row += '<td colspan="5">';
                row += '    <table class="table table-bordered">';
                row += '        <thead>';
                row += '            <tr>';
                row += '                <td>Section</td>';
                row += '                <td class="text-center">Completion<br/>Status</td>';
                row += '                <td class="text-center">Completed<br/>By</td>';
                row += '                <td class="text-center">Completed<br/>On</td>';
                row += '                <td class="text-center">Action</td>'
                row += '            </tr>';
                row += '        </thead>';
                row += '        </tbody>';
                $.each(data.sections, function(i, section) {
                    row += '            <tr>';
                    row += '                <td>Section ' + (i) + '</td>';
                    row += '                <td class="text-center">';
                    row += '                    <img src="<?= base_url('assets/manage_admin/images'); ?>/' + (section.status ? "on" : "off") + '.gif" />';
                    row += '                </td>';
                    row += '                <td class="text-center">';
                    row += '                     <p>';
                    row += '                        <strong>';
                    row += section.completed_by ? section.completed_by : "-";
                    if (i == 1) {
                        if (data.sections[1].status && !data.sections[1].is_verified) {
                            row += '                <br><p class="text-danger">Verification Pending</p>';
                        }
                    }
                    row += '                        </strong>';
                    row += '                    </p>';
                    row += '                </td>';
                    row += '                <td class="text-center">';
                    row += '                     <p>';
                    row += '                        <strong>';
                    row += section.completed_on ? moment(section.completed_on).format("MMM Do YYYY, ddd H:m:s") : "-";
                    row += '                        </strong>';
                    row += '                    </p>';
                    row += '                </td>';
                    row += '                <td class="text-center">';
                    row += '                     <p>';
                    row += '                        <strong>';
                    row += section.completed_on ? moment(section.completed_on).format("MMM Do YYYY, ddd H:m:s") : "-";
                    row += '                        </strong>';
                    row += '                    </p>';
                    row += '                </td>';
                    row += '            </tr>';
                });
                row += '        </tbody>';
                row += '    </table>';
                row += '</td>';
                row += '</tr>';
            }

            $("#jsEPEBody").html(row);
            $('.jsEPELoader').hide();
        }

        /**
         * handle the assign process
         */
        function handleAssignProcess(event) {
            event.preventDefault();
            //
            if (XHR !== null) {
                return false;
            }
            //
            return _confirm(
                "Do you really want to assign this document?",
                function() {
                    processAssignDocument("assign")
                }
            )
        }

        /**
         * handle the revoke process
         */
        function handleRevokeProcess(event) {
            event.preventDefault();
            //
            if (XHR !== null) {
                return false;
            }
            //
            return _confirm(
                "Do you really want to revoke this document?",
                function() {
                    processAssignDocument("revoke")
                }
            )
        }

        /**
         * handle the re-assign process
         */
        function handleReAssignProcess(event) {
            event.preventDefault();
            //
            if (XHR !== null) {
                return false;
            }
            //
            return _confirm(
                "Do you really want to Re-assign this document?",
                function() {
                    processAssignDocument("assign")
                }
            )
        }

        /**
         * Process document assignment
         *
         * @param {string} action
         * */
        function processAssignDocument(action) {
            //
            const buttonRef = callButtonHook(
                $(".jsEPEBtn"),
                true
            );
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/<?= $user_sid; ?>"),
                    method: "POST",
                    data: {
                        action: action
                    }
                })
                .always(function() {
                    XHR = null;
                    callButtonHook(
                        buttonRef,
                        false
                    );
                })
                .fail(handleErrorResponse)
                .done(function(resp) {
                    return _success(
                        resp.message,
                        getEPE
                    )
                });
        }

        /**
         * start process of section one
         */
        function handleSectionOneProcess(event) {
            // event.preventDefault();
            //
            Modal({
                Id: "jsSectionOneEPEModal",
                Loader: "jsSectionOneEPEModalLoader",
                Title: "Employee Performance Evaluation - Section One",
                Body: '<div id="jsSectionOneEPEModalBody"></div>'
            }, function() {
                loadSection("one");
            });
        }


        /**
         * start process of section three
         */
        function handleSectionThreeProcess(event) {
            //
            Modal({
                Id: "jsSectionOneEPEModal",
                Loader: "jsSectionOneEPEModalLoader",
                Title: "Employee Performance Evaluation - Section Three",
                Body: '<div id="jsSectionOneEPEModalBody"></div>'
            }, function() {
                loadSection("three");
            });
        }

        /**
         * start process of section four
         */
        function handleSectionFourProcess(event) {
            //
            Modal({
                Id: "jsSectionOneEPEModal",
                Loader: "jsSectionOneEPEModalLoader",
                Title: "Employee Performance Evaluation - Section Four",
                Body: '<div id="jsSectionOneEPEModalBody"></div>'
            }, function() {
                loadSection("four");
            });
        }

        /**
         * start process of section five
         */
        function handleSectionFiveProcess(event) {
            //
            Modal({
                Id: "jsSectionOneEPEModal",
                Loader: "jsSectionOneEPEModalLoader",
                Title: "Employee Performance Evaluation - Section Five",
                Body: '<div id="jsSectionOneEPEModalBody"></div>'
            }, function() {
                loadSection("five");
            });
        }

        /**
         * loads the section
         *
         * @param {string} section
         */
        function loadSection(section) {
            if (XHR !== null) {
                XHR.abort();
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/<?= $user_sid; ?>/section/" + section + "/manager"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    ml(
                        false,
                        "jsSectionOneEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    $("#jsSectionOneEPEModalBody").html(response.view);
                    //
                    $(".jsDatePicker").datepicker({
                        dateFormat: 'mm-dd-yy',
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "<?php echo DOB_LIMIT; ?>",
                    });
                });

        }
        //

        /**
         * 
         * save the section one
         *
         */
        function handleSectionOneSave() {
            //
            
            //
            $("#jsSectionOneForm").validate({
                rules: {
                    "epe_employee_name": {
                        required: true
                    },
                    "epe_job_title": {
                        required: true
                    },
                    "epe_department": {
                        required: true
                    },
                    "epe_manager": {
                        required: true
                    },
                    "epe_hire_date": {
                        required: true
                    },
                    "epe_start_date": {
                        required: true
                    },
                    "epe_review_start": {
                        required: true
                    },
                    "epe_review_end": {
                        required: true
                    },
                    "epe_review_end": {
                        required: true
                    },
                    "position_knowledgeable_radio": {
                        required: true
                    },
                    "position_knowledgeable_comments": {
                        required: true
                    },
                    "position_improved": {
                        required: true
                    },
                    "position_improved_radio": {
                        required: true
                    },
                    "position_improved_comment": {
                        required: true
                    },
                    "quantity_improved": {
                        required: true
                    },
                    "quantity_improved_radio": {
                        required: true
                    },
                    "quantity_improved_comment": {
                        required: true
                    },
                    "quality_improved": {
                        required: true
                    },
                    "quality_improved_radio": {
                        required: true
                    },
                    "quality_improved_comment": {
                        required: true
                    },
                    "relations_improved": {
                        required: true
                    },
                    "relations_improved_radio": {
                        required: true
                    },
                    "relations_improved_comment": {
                        required: true
                    },
                    "skill_improved": {
                        required: true
                    },
                    "skill_improved_radio": {
                        required: true
                    },
                    "skill_improved_comment": {
                        required: true
                    },
                    "dependability_improved": {
                        required: true
                    },
                    "dependability_improved_radio": {
                        required: true
                    },
                    "dependability_improved_comment": {
                        required: true
                    },
                    "policy_procedure_improved": {
                        required: true
                    },
                    "policy_procedure_improved_other": {
                        required: true
                    },
                    "policy_procedure_improved_radio": {
                        required: true
                    },
                    "policy_procedure_improved_comment": {
                        required: true
                    },
                    "standard_improved": {
                        required: true
                    },
                    "standard_improved_other": {
                        required: true
                    },
                    "standard_improved_radio": {
                        required: true
                    },
                    "standard_improved_comment": {
                        required: true
                    },
                    "managers_additional_comment": {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    //
                    const btnHook = callButtonHook($(".jsSaveSectionOne"), true);
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/one"),
                            method: "POST",
                            data: $(form).serialize()
                        })
                        .always(function() {
                            XHR = null;
                            callButtonHook(btnHook, false);
                        })
                        .fail(handleErrorResponse)
                        .done(function(resp) {
                            return _success(
                                resp.message,
                                function() {
                                    $(".jsSectionOneFormSection").addClass("hidden");
                                    $(".jsSectionOneManagers").removeClass("hidden");
                                    var managers = resp.extra['verification_managers'];
                                    console.log(managers)
                                    var i;
                                    for (i = 0; i < managers.length; ++i) {
                                        console.log(managers[i])
                                        $('input[name="employees[]"][value="' + managers[i] + '"]').prop('checked', true);
                                    }
                                }
                            )
                        });
                }
            });
        }

        /**
         * 
         * save the section three
         *
         */
        function handleSectionThreeSave() {
            //
            
            //
            $("#jsSectionThreeForm").validate({
                rules: {
                    "additional_comment_one": {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    //
                    const btnHook = callButtonHook($(".jsSaveSectionThree"), true);
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/three"),
                            method: "POST",
                            data: $(form).serialize()
                        })
                        .always(function() {
                            XHR = null;
                            callButtonHook(btnHook, false);
                        })
                        .fail(handleErrorResponse)
                        .done(function(resp) {
                            return _success(
                                resp.message,
                                function() {
                                    window.location.href = window.location.href;
                                }
                            )
                        });
                }
            });
        }

        /**
         * 
         * get employee signature
         *
         */
        function handleSectionFourSignature() {
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            const btnHook = callButtonHook($(".jsGetEmployeeSignature"), true);
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/get_employee_signature"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    callButtonHook(btnHook, false);
                    ml(
                        false,
                        "jsSectionOneEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(resp) {
                    //
                    if (!resp.success) {
                        return _success(
                            resp.message,
                        )
                    } else {
                        $('.jsSaveEsignature').removeClass('not_sign_yet');
                        $('.jsSaveHRManagerApproval').removeClass('not_sign_yet');
                        $('.jsGetEmployeeSignature').hide();
                        $('#jsDrawEmployeeSignature').attr('src', resp.signature_base64);
                    }
                });
        }
        //

        /**
         * 
         * save the section four
         *
         */
        function handleSectionFourSave() {
            //
            
            //
            if ($(".jsSaveEsignature").hasClass("not_sign_yet")) {
                alertify.alert(
                    'Warning!',
                    'Signing is mandatory to complete Section 4',
                )
                return true;
            } else {
                //
                if (XHR !== null) {
                    XHR.abort();
                }
                //
                const btnHook = callButtonHook($(".jsSaveEsignature"), true);
                //
                XHR = $
                    .ajax({
                        url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/four"),
                        method: "POST",
                        data: {
                            "action": "save_signature",
                            "user_type": "manager"
                        }
                    })
                    .always(function() {
                        XHR = null;
                        callButtonHook(btnHook, false);
                    })
                    .fail(handleErrorResponse)
                    .done(function(resp) {
                        return _success(
                            resp.message,
                            function() {
                                window.location.href = window.location.href;
                            }
                        )
                    });
            }

        }

        function handleSectionFiveSave() {
            //
            
            //
            $("#jsSectionFiveForm").validate({
                rules: {
                    "current_pay": {
                        required: true,
                        number: true
                    },
                    "recommended_pay": {
                        required: true,
                        number: true
                    },
                },
                submitHandler: function(form) {
                    //
                    if (XHR !== null) {
                        XHR.abort();
                    }
                    //
                    const btnHook = callButtonHook($(".jsSaveSectionFive"), true);
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/five"),
                            method: "POST",
                            data: $(form).serialize()
                        })
                        .always(function() {
                            XHR = null;
                            callButtonHook(btnHook, false);
                        })
                        .fail(handleErrorResponse)
                        .done(function(resp) {
                            return _success(
                                resp.message,
                                function() {
                                    window.location.href = window.location.href;
                                }
                            )
                        });
                }
            });
        }

        function handleSectionFiveApprovalSave() {
            //
            
            //
            if ($(".jsSaveHRManagerApproval").hasClass("not_sign_yet")) {
                alertify.alert(
                    'Warning!',
                    'Signing is mandatory to complete Section 5',
                )
                return false;
            } else {
                $("#jsSectionFiveForm").validate({
                    rules: {
                        "approved_amount": {
                            required: true,
                            number: true
                        },
                        "effective_increase_date": {
                            required: true
                        }
                    },
                    submitHandler: function(form) {
                        //
                        if (XHR !== null) {
                            XHR.abort();
                        }
                        //
                        const btnHook = callButtonHook($(".jsSaveHRManagerApproval"), true);
                        //
                        XHR = $
                            .ajax({
                                url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/five"),
                                method: "POST",
                                data: $(form).serialize()
                            })
                            .always(function() {
                                XHR = null;
                                callButtonHook(btnHook, false);
                            })
                            .fail(handleErrorResponse)
                            .done(function(resp) {
                                return _success(
                                    resp.message,
                                    function() {
                                        window.location.href = window.location.href;
                                    }
                                )
                            });
                    }
                });
            }
            //

        }

        //
        getEPE();
    });
</script>