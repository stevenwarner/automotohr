<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default hr-documents-tab-content">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle open_not_completed_performance_doc" data-toggle="collapse" data-parent="#accordion" href="#jsPerformanceNotCompletedDocuments">
                        <span class="glyphicon glyphicon-plus"></span>
                        Performance Documents
                        <div class="pull-right total-records"><b>Total: 1</b></div>
                    </a>

                </h4>
            </div>

            <div id="jsPerformanceNotCompletedDocuments" class="panel-collapse collapse in">
                <div class="table-responsive">
                    <table class="table table-plane cs-w4-table">
                        <thead>
                            <tr>
                                <th class="col-lg-8 hidden-xs">Document Name</th>
                                <th class="col-lg-10 hidden-md hidden-lg hidden-sm">Document</th>
                                <th class="col-xs-4 text-center hidden-xs" colspan="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jsEPEBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function EmployeePerformanceEvaluationDocument() {

        let XHR = null;

        $(document).on("click", ".jsAssignEPE", handleAssignProcess);
        $(document).on("click", ".jsRevokeEPE", handleRevokeProcess);
        $(document).on("click", ".jsReAssignEPE", handleReAssignProcess);

        // Sections
        $(document).on("click", ".jsEPESectionTwo", handleSectionOneProcess);
        $(document).on("click", ".jsSaveSectionTwo", handleSectionOneSave);

        $(document).on("click", ".jsEPEActionButton", function() {
            var action = $(this).data("key");
            
        });

        $(document).on("keyup", ".jsOpportunities", function() {
            var name = $(this).data("key");
            if ($(this).val().length == 1) {
                // 
                $("."+name).rules("add", {
                    required: true
                }); 
            } else if ($(this).val().length == 0) {
                $("."+name).rules("remove");
            }
        });

        $(document).on("keyup", ".jsGoal", function() {
            var name = $(this).data("key");
            if ($(this).val().length == 1) {
                // 
                $("."+name).rules("add", {
                    required: true
                }); 
            } else if ($(this).val().length == 0) {
                $("."+name).rules("remove");
            }
        });

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
            row += '            Performance Evaluation';
            row += '            <i class="fa fa-asterisk text-danger"></i>';
            row += '            <br>';
            row += '            Assigned On: ' + (moment(data.assigned_on).format("MMM Do YYYY, ddd H:m:s"));
            row += '        </strong>';
            row += '    </td>';
            row += '    <td class="text-center">';
            row += '        <a target="_blank" href="'+baseUrl("fillable/epe/<?= $user_sid; ?>/employee/print")+'" class="btn btn-info btn-orange">Print</a>';
            row += '        <a target="_blank" href="'+baseUrl("fillable/epe/<?= $user_sid; ?>/employee/download")+'" class="btn btn-info btn-black">Download</a>';
            row += '        <button class="btn btn-info jsEPESectionTwo">View Sign</button>';
            row += '    </td>';
            row += '</tr>';


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
                Title: "Employee Performance Evaluation - Section Two",
                Body: '<div id="jsSectionOneEPEModalBody"></div>'
            }, function() {
                loadSection("two");
            })
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
                    url: baseUrl("fillable/epe/<?= $user_sid; ?>/section/" + section),
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
                    handleSectionOneSave();
                });

        }
        //

        /**
         * 
         * save the section one
         *
         */
        function handleSectionOneSave() {

            $("#jsSectionOneForm").validate({
                rules: {
                    "review_period_radio": {
                        required: true
                    },
                    "equipment_resources_radio": {
                        required: true
                    },
                    "additional_support": {
                        required: true
                    },
                    "additional_comment": {
                        required: true
                    },
                    "accomplishment_1": {
                        required: true
                    },
                    "accomplishment_comment_1": {
                        required: true
                    },
                    "opportunities_1": {
                        required: true
                    },
                    "opportunities_comment_1": {
                        required: true
                    },
                    "goal_1": {
                        required: true
                    },
                    "goal_comment_1": {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/<?= $user_sid; ?>/save_section/two"),
                            method: "POST",
                            data: $(form).serialize()
                        })
                        .always(function() {
                            XHR = null;
                        })
                        .fail(handleErrorResponse)
                        .done(function(resp) {
                            return _success(
                                resp.message
                            )
                        });
                }
            });
        }
        //
        getEPE();
    });
</script>