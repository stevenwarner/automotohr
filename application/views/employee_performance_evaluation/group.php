<!-- Employee Performance Evaluation Group -->
<?php if (checkIfAppIsEnabled('performanceevaluation')) { ?>
    <div class="col-md-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default ems-documents">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= EMPLOYEE_PERFORMANCE_EVALUATION_MODULE; ?>">
                                <span class="glyphicon glyphicon-plus"></span>
                                Employee Performance Evaluation
                                <div class="btn btn-xs btn-success">Fillable</div>
                                <div class="pull-right total-records">
                                    <strong>
                                        Total: 1
                                    </strong>
                                </div>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_<?= EMPLOYEE_PERFORMANCE_EVALUATION_MODULE; ?>" class="panel-collapse collapse">
                        <div class="table-responsive">
                            <table class="table table-plane">
                                <thead>
                                    <tr>
                                        <th class="col-xs-6">
                                            Document Name
                                        </th>
                                        <th class="col-xs-6 text-right">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Employee Performance Evaluation
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-primary btn-sm jsBulkAssignEPE">
                                                Bulk Assign
                                            </button>
                                            <button class="btn btn-info btn-sm jsViewDocumentEPE">
                                                Preview
                                            </button>
                                            <button class="btn btn-success btn-sm jsAssignEmployeesEPE">
                                                View Employee(s)
                                            </button>
                                            <button class="btn btn-success btn-sm jsScheduleAssignEPE">
                                                Schedule Document
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<script>
    $(function EmployeePerformanceEvaluation() {
        //
        let XHR = null;
        //
        $(".jsBulkAssignEPE").click(startBulkAssignProcess);
        $(".jsScheduleAssignEPE").click(startScheduleAssignProcess);
        $(".jsAssignEmployeesEPE").click(startAssignEmployeeProcess);
        $(".jsViewDocumentEPE").click(startViewDocumentProcess);


        /**
         * handles bulk assign
         */
        function startBulkAssignProcess(event) {
            event.preventDefault();
            //
            Modal({
                Id: "jsAssignBulkEmployeeEPEModal",
                Loader: "jsAssignBulkEmployeeEPEModalLoader",
                Title: "Employee Performance Evaluation",
                Body: '<div id="jsAssignBulkEmployeeEPEModalBody"></div>'
            }, function() {
                loadCompanyEmployees();
            });
        }

        function loadCompanyEmployees() {
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/get_assign_bulk"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    ml(
                        false,
                        "jsAssignBulkEmployeeEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    //
                    $("#jsAssignBulkEmployeeEPEModalBody").html(response.view);
                    //
                });
        }

        /**
         * handles schedule assign
         */
        function startScheduleAssignProcess(event) {
            event.preventDefault();
            //
            Modal({
                Id: "jsAssignScheduleDocumentEPEModal",
                Loader: "jsAssignScheduleDocumentEPEModalLoader",
                Title: "Employee Performance Evaluation",
                Body: '<div id="jsAssignScheduleDocumentEPEModalBody"></div>'
            }, function() {
                loadScheduleDocumentView();
            });
        }


        function loadScheduleDocumentView() {
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/get_schedule_document_view"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    ml(
                        false,
                        "jsAssignScheduleDocumentEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    //
                    $("#jsAssignScheduleDocumentEPEModalBody").html(response.view);
                    //
                    $('.jsAssignSelectedEmployees').select2({
                        closeOnSelect: false
                    });
                    //
                    $('.assignAndSendDocument[value="none"]').prop('checked', true);
                    $('.jsCustomDateRow').hide();
                    //
                    $('.jsScheduleDate').datepicker({
                        changeMonth: true,
                        dateFormat: 'mm/dd'
                    });
                    //
                    $('.jsScheduleTime').datetimepicker({
                        datepicker: false,
                        format: 'g:i A',
                        formatTime: 'g:i A',
                        step: 15
                    });
                    //
                    if (response.setting) {
                        processScheduleSetting(response.setting);
                    }
                });
        }

        $(document).on("click", ".jsAssignBulkDocument", function() {
            event.preventDefault();
            //
            selected_employees = get_all_selected_employees();
            //
            if (selected_employees.length === 0) {
                alertify.alert('ERROR!', 'Kindly select a minimum of one employee to assign the <b>Employee Performance Evaluation</b> document.');
                return;
            }
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            alertify.confirm(
                'Are you sure?',
                'Are you certain you wish to initiate bulk <b>Employee Performance Evaluation</b> document?',
                function() {
                    //
                    const btnHook = callButtonHook($(".jsAssignBulkDocument"), true);
                    //
                    XHR = $
                        .ajax({
                            url: baseUrl("fillable/epe/assign_bulk_document_to_employee"),
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

        /**
         * select all employees
         */
        $(document).on("click", ".jsSelectAll", function(event) {
            //
            event.preventDefault();
            //
            $('[name="employees[]"]').prop("checked", true);
        });

        /**
         * deselect all employees
         */
        $(document).on("click", ".jsUnSelectAll", function(event) {
            //
            event.preventDefault();
            //
            $('[name="employees[]"]').prop("checked", false);
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

        function processScheduleSetting (setting) {
            //
            $('.assignAndSendDocument[value="'+setting.assign_type+'"]').prop('checked', true);
            $('.jsAssignSelectedEmployees').select2('val', setting.assigned_employee_list);
            $('.jsScheduleDate').val(setting.assign_date);
            $('.jsScheduleTime').val(setting.assign_time);
            $('#jsScheduleCustomDaySLT option[value="'+setting.assign_date+'"]').attr("selected", "selected");
            //
            $('.jsCustomDateRow').show();
            $('#jsScheduleCustomDay').hide();
            $('#jsScheduleCustomLabel').text('Select a date & time');
            $('#jsScheduleCustomDate').show();
            $('.jsScheduleDate').datepicker('option', {
                changeMonth: true
            });
            //
            if (setting.assign_type == 'daily') {
                $('#jsScheduleCustomLabel').text('Select time');
                $('#jsScheduleCustomDate').hide();
            } else if (setting.assign_type == 'monthly') {
                $('#jsScheduleCustomLabel').text('Select a date & time');
                $('.jsScheduleDate').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsScheduleDate').datepicker('option', {
                    changeMonth: false
                });
            } else if (setting.assign_type == 'weekly') {
                $('#jsScheduleCustomDate').hide();
                $('#jsScheduleCustomDay').show();
                $('#jsScheduleCustomLabel').text('Select day & time');
            } else if (setting.assign_type == 'yearly' || setting.assign_type == 'custom') {
                $('.jsScheduleDate').datepicker('option', {
                    dateFormat: 'mm/dd'
                });
            } else if (setting.assign_type == 'none') {
                $('.jsCustomDateRow').hide();
            }
        }

        //
        $(document).on("change", ".assignAndSendDocument", function() {
            //
            $('.jsCustomDateRow').show();
            $('#jsScheduleCustomDay').hide();
            $('#jsScheduleCustomLabel').text('Select a date & time');
            $('#jsScheduleCustomDate').show();
            $('.jsScheduleDate').datepicker('option', {
                changeMonth: true
            });
            //
            if ($(this).val().toLowerCase() == 'daily') {
                $('#jsScheduleCustomLabel').text('Select time');
                $('#jsScheduleCustomDate').hide();
            } else if ($(this).val().toLowerCase() == 'monthly') {
                $('#jsScheduleCustomLabel').text('Select a date & time');
                $('.jsScheduleDate').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsScheduleDate').datepicker('option', {
                    changeMonth: false
                });
            } else if ($(this).val().toLowerCase() == 'weekly') {
                $('#jsScheduleCustomDate').hide();
                $('#jsScheduleCustomDay').show();
                $('#jsScheduleCustomLabel').text('Select day & time');
            } else if ($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom') {
                $('.jsScheduleDate').datepicker('option', {
                    dateFormat: 'mm/dd'
                });
            } else if ($(this).val().toLowerCase() == 'none') {
                $('.jsCustomDateRow').hide();
            }
        });

        //
        $(document).on("click", ".jsSaveScheduleSetting", function(event) {
            //
            event.preventDefault();
            //
            let obj = {};
            obj.scheduleType = $('.assignAndSendDocument:checked').val();
            obj.scheduleEmployees = $('.jsAssignSelectedEmployees').val();
            obj.scheduleDate = $('.jsScheduleDate').val();
            obj.scheduleDay = $('#jsScheduleCustomDaySLT').val();
            obj.scheduleTime = $('.jsScheduleTime').val();
            //
            if (!obj.scheduleEmployees) {
                alertify.alert('ERROR!', 'Kindly select a minimum of one employee to schedule the <b>Employee Performance Evaluation</b> document.');
                return;
            }
            //
            const btnHook = callButtonHook($(".jsSaveScheduleSetting"), true);
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/save_schedule_setting"),
                    method: "POST",
                    data: obj
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
        });

        /**
         * handles assign employees
         */
        function startAssignEmployeeProcess(event) {
            event.preventDefault();
            //
            Modal({
                Id: "jsAssignEmployeesEPEModal",
                Loader: "jsAssignEmployeesEPEModalLoader",
                Title: "Employee Performance Evaluation",
                Body: '<div id="jsAssignEmployeesEPEModalBody"></div>'
            }, function() {
                loadAssignEmployees();
            });
        }

        function loadAssignEmployees() {
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/get_assign_employees"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    ml(
                        false,
                        "jsAssignEmployeesEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    //
                    $("#jsAssignEmployeesEPEModalBody").html(response.view);
                    //
                });
        }

        /**
         * handles assign employees
         */
        function startViewDocumentProcess(event) {
            event.preventDefault();
            //
            Modal({
                Id: "jsPreviewDocumentEPEModal",
                Loader: "jsPreviewDocumentEPEModalLoader",
                Title: "Employee Performance Evaluation",
                Body: '<div id="jsPreviewDocumentEPEModalBody"></div>'
            }, function() {
                loadDocumentPreview();
            });
        }

        function loadDocumentPreview() {
            //
            if (XHR !== null) {
                XHR.abort();
            }
            //
            XHR = $
                .ajax({
                    url: baseUrl("fillable/epe/get_document_preview"),
                    method: "GET"
                })
                .always(function() {
                    XHR = null;
                    ml(
                        false,
                        "jsPreviewDocumentEPEModalLoader"
                    );
                })
                .fail(handleErrorResponse)
                .done(function(response) {
                    //
                    $("#jsPreviewDocumentEPEModalBody").html(response.view);
                    //
                });
        }
    });
</script>
