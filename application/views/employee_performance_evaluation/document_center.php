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
                                <th class="text-center">Is Required?</th>
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


<script>
    $(function EmployeePerformanceEvaluationDocument() {

        let XHR = null;


        $(document).on("click", ".jsAssignEPE", handleAssignProcess);
        $(document).on("click", ".jsRevokeEPE", handleRevokeProcess);
        $(document).on("click", ".jsReAssignEPE", handleReAssignProcess);

        // Sections
        $(document).on("click", ".jsEPESectionOne", handleSectionOneProcess);


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
                row += '    <td class="text-center">';
                row += '        <label class="control control--checkbox">';
                row += '            <input type="checkbox" name="jsRequiredCheckboxEPE" disabled />';
                row += '            <div class="control__indicator"></div>';
                row += '        </label>';
                row += '    </td>';
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
                row += '<img src="<?= base_url('assets/manage_admin/images'); ?>/' + (data.completed_on ? "on" : "off") + '.gif" title="Not Completed" />';
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
                row += '        <button class="btn btn-danger jsRevokeEPE jsEPEBtn">Revoke</button>';
                // check for sections
                if (!data.sections[1].status) {
                    row += '        <button class="btn btn-success jsEPESectionOne">Complete Section 1</button>';
                }
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
                setInterval(
                    function() {
                        loadSection("one")
                    },
                    3000
                );

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
                });

        }
        handleSectionOneProcess("asdas");
        //
        getEPE();
    });
</script>