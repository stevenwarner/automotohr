<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('hr_documents_management'); ?>" class="dashboard-link-btn">
                            <i class="fa fa-chevron-left"></i>Document Management
                        </a>
                        <?php echo $title; ?>
                    </span>
                </div>
                <div class="btn-panel text-right">
                    <div class="row">
                        <a class="btn btn-success" href="<?php echo base_url('hr_documents_management/add_edit_document_group_management'); ?>">+ Document Group</a>
                    </div>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <div class="table-responsive table-outer">
                        <div id="show_no_jobs">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-lg-3">Group Name</th>
                                        <th class="col-lg-3">Group Description</th>
                                        <th class="col-lg-3">Status</th>
                                        <th class="col-lg-3">Documents</th>
                                        <th class="col-lg-3 text-center" colspan="3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!isset($groups) || empty($groups)) { ?>
                                        <tr>
                                            <span class="applicant-not-found">No document group found</span>
                                        <tr>
                                            <?php } else {
                                            foreach ($groups as $group) {

                                                $group_doc_count = 0;
                                                $group_doc_name = "<br>";

                                                if ($group['w4'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; W4 Fillable<br>";
                                                }
                                                if ($group['w9'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; W9 Fillable<br>";
                                                }
                                                if ($group['i9'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; I9 Fillable<br>";
                                                }
                                                if ($group['eeoc'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; EEOC Fillable<br>";
                                                }
                                                if ($group['direct_deposit'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Direct Deposit Information<br>";
                                                }
                                                if ($group['drivers_license'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Drivers License Information<br>";
                                                }
                                                if ($group['occupational_license'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Occupational License Information<br>";
                                                }
                                                if ($group['emergency_contacts'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Emergency Contacts<br>";
                                                }
                                                if ($group['dependents'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Dependents<br>";
                                                }
                                                if (json_decode($group['state_forms_json'], true)) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; State Forms<br>";
                                                }


                                                if ($group['performance_evaluation'] == 1) {
                                                    $group_doc_count = $group_doc_count + 1;
                                                    $group_doc_name = $group_doc_name . "&rarr; Employee Performance Evaluation<br>";
                                                }


                                                $groups_dociments = get_all_group_documents($group['sid'], true);

                                                if (!empty($groups_dociments)) {

                                                    $group_doc_count = $group_doc_count + count($groups_dociments);

                                                    foreach ($groups_dociments as $doc_row) {
                                                        $group_doc_name = $group_doc_name . '&rarr; ' . $doc_row['document_title'] . "<br>";
                                                    }
                                                }


                                            ?>
                                        <tr id='row_<?php echo $group['sid']; ?>'>
                                            <td><?php echo ucwords($group['name']); ?></td>
                                            <td><?php echo html_entity_decode($group['description']); ?></td>
                                            <td><?php echo $group['status'] == 1 ? '<b class="paid">Active</b>' : '<b class="unpaid">Inactive</b>'; ?></td>
                                            <td>
                                                <a id='<?php echo $group['sid']; ?>'><b>Total: <?php echo $group_doc_count; ?></b></a>
                                                <span id='docdetails_<?php echo $group['sid']; ?>'> <?php echo $group_doc_name; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($group['document_status'] == 1 || $group['w4'] == 1 || $group['w9'] == 1 || $group['i9'] == 1 || $group['eeoc'] == 1 || $group['direct_deposit'] == 1 || $group['drivers_license'] == 1 || $group['occupational_license'] == 1 || $group['emergency_contacts'] == 1 || $group['dependents'] == 1 || json_decode($group['state_forms_json'], true)) { ?>
                                                    <button onclick="func_assign_document_group(<?php echo $group['sid']; ?>)" class="btn btn-primary btn-block btn-sm">Bulk Assign</button>
                                                <?php } else { ?>
                                                    No Document Found
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('hr_documents_management/add_edit_document_group_management') . '/' . $group['sid']; ?>" class="btn btn-info btn-sm">
                                                    Edit Group
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('hr_documents_management/document_2_group') . '/' . $group['sid']; ?>" class="btn btn-success btn-sm">
                                                    Assign Document
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($groups) || !empty($groups)) { ?>
    <div id="bulk_assign_document_group_model" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content full-width">
                <div class="modal-header modal-header-bg">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="generated_document_title">Bulk Assign This Document Group</h4>
                </div>
                <div class="modal-body autoheight">
                    <div class="row">
                        <form method='post' id='uploaded-form' action="<?= current_url(); ?>">
                            <input type="hidden" name="perform_action" value="bulk_assign_group" />
                            <input type="hidden" name="group_sid" id="assign_group_sid" />
                            <div class="col-md-12">
                                <div class="form-group" style="min-height: 300px">
                                    <label>Employees<span class="hr-required red"> * </span></label>
                                    <select require multiple="multiple" name="employees[]" id="uploaded-employees" style="display: block">
                                        <option value="" selected>Please Select Employee</option>
                                        <option value="" selected>All</option>
                                    </select>
                                </div>

                                <div class="spinner-border"></div>
                                <div class="col-lg-12"><span class="hr-required" id="empAssigneName"></span></div>
                                <div class="col-lg-12"><span class="hr-required" id='loderText' style="font-size: 18px;font-weight:bold;"></span></div>
                            </div>

                            <div class="col-lg-12" id="uploaded-empty-emp" style="display: none;"><span class="hr-required red">This Document Group Is Assigned To All Employees!</span></div>
                            <div class="col-md-12">
                                <div class="message-action-btn">
                                    <input type="button" value="Bulk Assign This Group" id="jsBulkassigne" class="submit-btn">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/selectize.bootstrap3.css') ?>">
<script src="<?= base_url('assets/js/selectize.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#uploaded-form').validate({
            rules: {
                employees: {
                    required: true
                }
            },
            messages: {
                employees: {
                    required: 'Employee(s) Required'
                }
            },
            submitHandler: function(form) {
                var up_emp = $('#uploaded-employees').val();

                if (up_emp != '' && up_emp != null) {
                    form.submit();
                } else {
                    alertify.error('Please select Employee(s)');
                }
            }
        });
    });


    var upemployees = $('#uploaded-employees').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        allowEmptyOption: false,
        persist: true,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });
    var up_emp = upemployees[0].selectize;

    //
    let companyEmployees = [];
    //
    let companyEmployeesObj = {};

    function func_assign_document_group(group_sid) {
        companyEmployees = [];
        $('#bulk_assign_document_group_model').modal('show');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('hr_documents_management/get_all_company_employees') ?>',
            success: function(data) {
                var employees = JSON.parse(data);

                companyEmployees = JSON.parse(data);

                if (employees.length == 0) {
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {
                        var arr = [{}];
                        arr[0] = {
                            value: '',
                            text: 'Please Select Employee'
                        }
                        callback(arr);
                        up_emp.addItems('');
                        up_emp.refreshItems();
                    });

                    $('#uploaded-empty-emp').show();
                    $('#send-up-doc').hide();
                    up_emp.disable();
                } else {
                    $('#uploaded-empty-emp').hide();
                    $('#send-up-doc').show();
                    up_emp.enable();
                    up_emp.clearOptions();
                    up_emp.load(function(callback) {
                        var arr = [{}];
                        var j = 1;

                        arr[0] = {
                            value: '-1',
                            text: 'All'
                        }

                        for (var i = 0; i < employees.length; i++) {
                            companyEmployeesObj[employees[i].sid] = employees[i]
                            arr[j++] = {
                                value: employees[i].sid,
                                text: employees[i].full_name
                                // text: employees[i].first_name + ' ' + employees[i].last_name
                            }
                        }
                        callback(arr);
                        up_emp.refreshItems();
                    });
                }
            },
            error: function() {

            }
        });
        $('#assign_group_sid').val(group_sid);
    }


    let employeeList = [],
        groupSid = 0,
        totalEmployees = 0,
        currentEmployee = 0


    //
    $("#jsBulkassigne").click(function() {

        employeeList = $('#uploaded-employees').val(),
            groupSid = $('#assign_group_sid').val();

        //
        if (!employeeList || employeeList == null) {
            return alertify.alert("Please select at least one employee.");
        }
        $(this).addClass("hidden")
        //
        $('#loderText').text("Please wait while we are assigning group to the selected employees.");
        //
        $.ajax({
                'url': "<?= base_url("documents/assign_groups_to_employees"); ?>",
                'type': 'POST',
                'data': {
                    'employees': employeeList,
                    'group_sid': groupSid
                },
            })
            .always(function() {})
            .fail(function() {
                return alertify.alert("Something went wrong!");
            })
            .success(function() {
                $('#loderText').text("Group assigned to the selected employees.");
                //
                return startProcessOfAssigningGroupDocuments();

            });

    });


    function startProcessOfAssigningGroupDocuments() {
        // to check if all is selected
        if ($.inArray("-1", employeeList) !== -1) {
            // flush the all
            employeeList = [];
            // move all employees to selected ones
            companyEmployees && companyEmployees.forEach(function(v) {
                //
                employeeList.push(v.sid);
            })
        }
        // if no employees found
        if (!employeeList) {
            return;
        }
        // set the length
        totalEmployees = employeeList.length;
        // set the current employee
        currentEmployee = 0;
        //
        assignGroupDocumentsToEmployee(currentEmployee);
    }


    function assignGroupDocumentsToEmployee(employeeIndex) {
        //
        let employeeId = employeeList[employeeIndex];
        //
        if (employeeId === undefined) {
            return alertify.alert("Group documents assigned to the selected employees.", function() {
                window.location.reload();
            })
        }
        //
        let loaderText = "<p class=\"text-center\">Assigning documents to " + companyEmployeesObj[employeeId]["full_name"] + " <br/>";
        loaderText += (currentEmployee + 1) + " out of " + totalEmployees + "</p>";
        // show loader
        $("#loderText").html(loaderText);
        //
        $.ajax({
                url: "<?= base_url("documents/assign_group_document_to_employee"); ?>",
                type: "post",
                data: {
                    employee_sid: employeeId
                }
            })
            .fail(function() {
                assignGroupDocumentsToEmployee(currentEmployee);
            })
            .success(function() {
                currentEmployee++;
                assignGroupDocumentsToEmployee(currentEmployee)
            })
    }
</script>