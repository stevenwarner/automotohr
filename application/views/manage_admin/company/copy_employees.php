<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$active_companies = '';
$active_companies .= '<option value="0">[Select Company]</option>';
foreach ($companies as $company)
    $active_companies .= '<option value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-setting-page">
                                            <?php echo form_open(base_url('manage_admin/copy_applicants/'), array('id' => 'copy-form')); ?>
                                            <ul>
                                                <!-- <li>
                                                    <label>Corporates <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-corporate">
                                                           <option value="0">Select Corporate Group</option>
                                                            <?php //foreach ($corporate_groups as $key => $corporate_group) {
                                                            //echo '<option value="'.( $corporate_group['sid'] ).'">'.( $corporate_group['group_name'] ).'</option>';
                                                            //} 
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li> -->
                                                <li>
                                                    <label>Copy From <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company">
                                                            <option value="0">Select Company</option>
                                                            <?php
                                                            foreach ($companies as $key => $company) {
                                                                echo '<option id="from_' . ($company['sid']) . '" value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Copy To <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-to-company">
                                                            <option value="0">Select Company</option>
                                                            <?php
                                                            foreach ($companies as $key => $company) {
                                                                echo '<option id="to_' . ($company['sid']) . '" value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Employees Type</label>
                                                    <div class="hr-fields-wrap">
                                                        <select id="js-employee-type" style="width: 100%;">

                                                            <option value="all" selected="selected">All</option>
                                                            <option value="active">Active</option>
                                                            <option value="leave">Leave</option>
                                                            <option value="suspended">Suspended</option>
                                                            <option value="retired">Retired</option>
                                                            <option value="rehired">Rehired</option>
                                                            <option value="deceased">Deceased</option>
                                                            <option value="terminated">Terminated</option>
                                                            <option value="inactive">Inactive</option>


                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Sort</label>
                                                    <div class="hr-fields-wrap">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                                <select id="js-employee-sort" style="width: 100%;">
                                                                    <option value="first_name">Employee Name</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                                                <select id="js-employee-sort-order" style="width: 100%;">
                                                                    <option value="ASC">ASC</option>
                                                                    <option value="DESC">DESC</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Keywords</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" placeholder="Search by employee email, name, and nickname" name="keyword" class="invoice-fields search-job" value="" id="keyword">
                                                        <strong class="text-danger">
                                                            You can search multiple employees at once. <br />E.G. john.doe@example.com, john smith
                                                        </strong>
                                                        <input type="hidden" name="transferred_note" value="" id="transferred_note">

                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Export Timeoff</label>
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--checkbox vam">
                                                            <strong class="text-danger">
                                                                The selected employee(s) move with there Timeoff requests, policies and balances to other company.
                                                            </strong>
                                                            <input type="checkbox" id="jsMoveTimeoff" />
                                                            <div class="control__indicator"></div>
                                                        </label>

                                                    </div>
                                                </li>

                                                <li>

                                                    <a class="site-btn" id="js-fetch-employees" href="#">Fetch Employees</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                    <!-- Employees listing Block -->
                                    <div id="js-enployees-list-block">
                                        <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-employees">0</span> employees found</span></h4>
                                        <div class="hr-box js-hide-fetch">
                                            <div class="hr-box-header">
                                                <h4>Copy Specific Employees</h4>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="table-responsive">
                                                    <form action="javascript:void(0)" id="js-employee-form" method="POST">
                                                        <button type="button" class="btn btn-success pull-right js-copy-employees-btn" style="margin-bottom: 10px;">Copy Selected Employees</button>
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" class="js-check-all" /></th>
                                                                    <th class="text-center">ID</th>
                                                                    <th>Employee Name</th>
                                                                    <th>Email</th>
                                                                    <th>Employee Type</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="js-employees-list-show-area"></tbody>
                                                        </table>
                                                        <input type="hidden" name="copy_to" id="form-copy" />
                                                        <input type="hidden" name="form_action" />
                                                        <button type="button" class="btn btn-success pull-right js-copy-employees-btn">Copy Selected Employees</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <style>
        .my_loader {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99;
            background-color: rgba(0, 0, 0, .7);
        }

        .loader-icon-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            z-index: 9999;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .loader-icon-box i {
            font-size: 14em;
            color: #81b431;
        }

        .loader-text {
            display: inline-block;
            padding: 10px;
            color: #000;
            background-color: #fff !important;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
    </style>

    <!-- Loader -->
    <div id="js-loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
        <div class="loader-icon-box cs-loader-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...</div>
        </div>
    </div>
    <style>
        #js-enployees-list-block {
            display: none;
        }

        .cs-required {
            font-weight: bolder;
            color: #cc0000;
        }

        /* Alertify CSS */
        .ajs-header {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-ok {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-cancel {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }
    </style>


    <script>
        var old_corporates;
        var old_companies = '<?php echo json_encode($companies); ?>';
        var old_from_company = 0;
        var old_to_company = 0;
        var currentPage = 1;
        var totalRecords = 0;
        var total_records_fetch = 0;
        var totalPages = 0;
        var limit = 0;
        var loadedRecords = 0;
        var records = [];

        var selected_employees = [];
        var copy_employee_count = 0;
        var coped_employees = 0;
        var current_employee = 0;

        $(document).on('click', '.js-copy-employees-btn', function(e) {
            e.preventDefault();
            start_copy_process()
        });

        // Select 2
        $('#js-from-company').select2();
        $('#js-to-company').select2();
        $('#js-employee-type').select2();
        $('#js-corporate').select2();

        $('#js-employee-sort').select2();
        $('#js-employee-sort-order').select2();

        $('#js-corporate').on('change', function() {
            var activeCompanies = '';
            var corporate_id = this.value;
            var myurl = "<?php echo base_url('manage_admin/copy_employees/get_corporate_companies') ?>" + "/" + corporate_id;
            $.ajax({
                type: "GET",
                url: myurl,
                async: false,
                success: function(data) {
                    old_corporates = data;
                    activeCompanies += '<option value="0">Select Company</option>';
                    $.each(JSON.parse(data), function(key, val) {
                        activeCompanies += '<option value="' + val.company_sid + '">' + val.company_name + '</option>';
                    });

                    $("#js-from-company").html(activeCompanies);
                    $("#js-to-company").html(activeCompanies);
                },
                error: function(data) {

                }
            });
        });

        $('#js-from-company').on('change', function() {
            var from_company_sid = this.value;
            old_from_company = from_company_sid;
            var activeCompanies = '';
            activeCompanies += '<option value="0">Select Company</option>';

            $.each(JSON.parse(old_companies), function(key, val) {
                if (from_company_sid != val.sid) {
                    activeCompanies += '<option value="' + val.sid + '">' + val.CompanyName + '</option>';
                }
            });

            $("#js-to-company").html(activeCompanies);

            if (old_to_company != 0) {
                $("#js-to-company").select2("val", old_to_company);
            }
        });

        $('#js-to-company').on('change', function() {

            var to_company_sid = this.value;
            old_to_company = to_company_sid;
            var activeCompanies = '';
            activeCompanies += '<option value="0">Select Company</option>';

            $.each(JSON.parse(old_companies), function(key, val) {

                if (to_company_sid != val.sid) {
                    activeCompanies += '<option value="' + val.sid + '">' + val.CompanyName + '</option>';
                }
            });

            $("#js-from-company").html(activeCompanies);

            if (old_from_company != 0) {
                $("#js-from-company").select2("val", old_from_company);
            }
        });

        $("#js-fetch-employees").on('click', function() {
            var activeCompanies = '';
            var from_company_sid = $("#js-from-company").val();
            var to_company_sid = $("#js-to-company").val();
            var employee_type = $("#js-employee-type").val();

            var employee_sortby = $("#js-employee-sort").val();
            var employee_sort_orderby = $("#js-employee-sort-order").val();
            var employee_keyword = $("#keyword").val();
            //
            if (employee_keyword) {
                employee_keyword = employee_keyword.replace(/\s/g, '--');
            }

            if (from_company_sid == 0 || to_company_sid == 0) {
                alertify.alert('Please select "From & To" company to copy employees');
            } else {
                currentPage = 1;
                totalRecords = 0;
                totalPages = 0;
                limit = 0;
                loadedRecords = 0;
                total_records_fetch = 0;
                $('#js-total-employees').text("");
                $('#js-employees-list-show-area').html("");
                loader();
                $('#js-loader-text').html('Please wait, we are loading employees <br> which may take few minutes!');

                fetch_employee(from_company_sid, employee_type, to_company_sid, employee_sortby, employee_sort_orderby, employee_keyword);
            }
        });

        function fetch_employee(company_sid, employee_type, to_company_sid, employee_sortby, employee_sort_orderby, employee_keyword) {
            var myurl = "<?php echo base_url('manage_admin/copy_employees/get_companies_employees') ?>" + "/" + company_sid + "/" + employee_type + "/" + currentPage + "/" + to_company_sid + "/" + employee_sortby + "/" + employee_sort_orderby + "/" + employee_keyword;
            $.get(myurl, function(resp) {
                resp = JSON.parse(resp)

                if (resp.status === false && currentPage != 1) {
                    loader(false);
                    $('#js-loader-text').html('');
                    alertify.alert('NOTICE', resp.response);
                    return;
                }

                if (resp.status === false && currentPage == 1) {
                    loader(false);
                    $('#js-loader-text').html('');
                    return;
                }

                if (currentPage == 1) {
                    limit = resp.limit;
                    records = resp.records;
                    totalPages = resp.totalPages;
                    totalRecords = resp.totalRecords;
                } else {
                    records = records.concat(resp.records);
                }

                var row = '';
                row += 'Please wait, we are loading employees <br />';
                row += 'This may take a few minutes <br />';
                row += 'Fetching <strong>' + (records.length) + '</strong> of <strong>' + (totalRecords) + '</strong>';

                $('#js-loader-text').html(row);

                make_employees_view(resp.records, currentPage);

                if (currentPage < totalPages) {
                    currentPage++;
                    fetch_employee(company_sid, employee_type, to_company_sid, employee_sortby, employee_sort_orderby, employee_keyword);
                } else {
                    loader(false);
                    $('#js-loader-text').html('');
                }
            });
        }

        function make_employees_view(records, page) {

            $('#js-enployees-list-block').show();
            var rows = '';
            $.each(records, function(key, value) {

                total_records_fetch++;
                employee_name = RemakeEmployeeName(value);
                var active = '';
                var deactive = '';
                var terminated = '';
                if (value.active == 1 && value.terminated_status == 0) {
                    active = 'checked="checked"';
                } else if (value.active == 0 && value.terminated_status == 0) {
                    deactive = 'checked="checked"';
                } else {
                    terminated = 'checked="checked"';
                }

                rows += '<tr class="' + (value.sid != 0 ? 'js-tr' : '') + '">';
                rows += '   <td><input type="checkbox" name="employees_ids[]" value="' + (value.sid) + '" /></td>';
                rows += '   <td>' + (value.sid) + '</td>';
                rows += '   <td class="js-employee-name">' + (employee_name) + '</td>';
                rows += '   <td class="js-employee-email">' + (value.email) + '</td>';
                rows += '   <td>';
                rows += '       <div class="checkbox">';
                rows += '           <label>';
                rows += '               <input type="checkbox" name="txt_archieved" ' + (active) + '" disabled="true" />Active';
                rows += '           </label>';
                rows += '       </div>';
                rows += '       <div class="checkbox" style="margin: 10px;">';
                rows += '           <label>';
                rows += '               <input type="checkbox" name="txt_active" ' + (deactive) + '" disabled="true" />De-active';
                rows += '           </label>';
                rows += '       </div>';

                rows += '       <div class="checkbox" style="margin: 10px;">';
                rows += '           <label>';
                rows += '               <input type="checkbox" name="txt_archieved" ' + (terminated) + '" disabled="true" />Terminated';
                rows += '           </label>';
                rows += '       </div>';
                rows += '   </td>';
                rows += '</tr>';
            });

            if (page == 1) {
                $('#js-employees-list-show-area').html(rows);
            } else {
                $('#js-employees-list-show-area').append(rows);
            }
            $('#js-total-employees').text(total_records_fetch);
            // $('#js-total-employees').text(records.length); 
        }

        function start_copy_process(doBypass) {


            selected_employees = get_all_selected_employees();

            if (selected_employees.length === 0) {
                alertify.alert('ERROR!', 'Please select at least one employee to start the process.');
                return;
            }

            // for policies
            if ($('#jsMoveTimeoff').prop('checked') && policyObj.hasOwnProperty('hasErrors') && doBypass === undefined) {
                return callLoader()
            }

            alertify.prompt('Please Enter a Note', '', '', function(evt, value) {

                    if (value.trim() == '') {
                        alertify.alert('ERROR!', 'Please Enter a Note.');
                        return;
                    }

                    $("#transferred_note").val(value);

                    copy_employee_count = selected_employees.length;
                    loader();
                    $('#js-loader-text').html('Please wait, we are copying employee');
                    copy_employees();

                }, function() {
                    alertify.error('Cancel')
                }

            );


        }

        function get_all_selected_employees() {
            var tmp = [];
            $.each($('input[name="employees_ids[]"]:checked'), function() {
                var obj = {};
                obj.employee_sid = parseInt($(this).val());
                obj.employee_name = $(this).closest('tr').find('td.js-employee-name').text();

                tmp.push(obj);
            });
            return tmp;
        }


        function copy_employees() {
            if (selected_employees.length > 0 && selected_employees[current_employee] === undefined) {
                loader(false);
                $('#js-loader-text').html('');
                alertify.alert('Employees Copy process is completed successfully!').set('onok', function(closeEvent) {
                    selected_employees = [];
                    copy_employee_count = 0;
                    coped_employees = 0;
                    current_employee = 0;
                });
                return;
            }
            if (selected_employees[current_employee] === undefined) {
                loader(false);
                $('#js-loader-text').html('');
                return;
            }

            var employee = selected_employees[current_employee];

            $('#js-loader-text').html('Please wait, we are copying employee <strong>' + (employee.employee_name) + '</strong> ');

            employee.to_company = $('#js-to-company').val();
            employee.from_company = $('#js-from-company').val();
            employee.transferred_note = $("#transferred_note").val();
            employee.timeoff = $('#jsMoveTimeoff').is(':checked') ? 1 : 0;
            employee.policyObj = policyObj;


            var myurl = "<?php echo base_url('manage_admin/copy_employees/copy_companies_employees') ?>";
            $.post(myurl, employee, function(resp) {
                if (resp.status === false) {
                    loader('hide');
                    alertify.alert('NOTICE', resp.response, function() {
                        window.location.reload();
                    });
                    return;
                }

                if (current_employee <= copy_employee_count) {
                    current_employee++;
                    setTimeout(function() {
                        copy_employees()
                    }, 1000);
                } else {
                    loader(false);
                    alertify.alert('Employees copying process is completed successfully!').set('onok', function(closeEvent) {
                        selected_employees = [];
                        copy_employee_count = 0;
                        coped_employees = 0;
                        current_employee = 0;
                    });
                }
            });
        }

        // Loader
        function loader(show_it, msg) {
            msg = msg === undefined ? 'Please, wait while we are processing your request.' : msg;
            show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
            if (show_it === 'show') {
                $('#js-loader').show();
                $('#js-loader-text').html(msg);
            } else {
                $('#js-loader').hide();
                $('#js-loader-text').html('');
            }
        }

        $(document).on('click', '.js-check-all', selectAllInputs);
        $(document).on('click', '.js-tr', selectSingleInput);

        // Select all input: checkbox
        function selectAllInputs() {
            $('.js-tr').find('input[name="employees_ids[]"]').prop('checked', $(this).prop('checked'));
        }

        // Select single input: checkbox
        function selectSingleInput() {
            $(this).find('input[name="employees_ids[]"]').prop('checked', !$(this).find('input[name="employees_ids[]"]').prop('checked'));
        }

        function RemakeEmployeeName(emp) {
            var row = '';
            //
            row += emp['first_name'];
            row += ' ' + emp['last_name'];
            row += ' (' + emp['access_level'];
            row += emp['access_level_plus'] == 1 || emp['pay_plan_flag'] == 1 ? ' Plus' : '';
            row += ' )';
            row += emp['job_title'] ? ' [' + emp['job_title'] + ']' : '';
            //
            return row;
        }


        //
        $("#jsFetchPolicies").on('click', function(event) {
            //
            event.preventDefault()

            callLoader();

        });


        function loadModal(data) {

            //
            let obj = (data);
            //
            let
                modal = '';

            modal += '<div class="modal fade" id="modal-id">';
            modal += '    <div class="modal-dialog modal-lg">';
            modal += '        <div class="modal-content">';
            modal += '            <div class="modal-header">';
            modal += '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            modal += '                <h2 class="modal-title">Manage Policies</h2>';
            modal += '            </div>';
            modal += '            <div class="modal-body" style="min-height: 400px;">';

            modal += '  <div class="row"> ';
            modal += '      <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
            modal += '                         <div class=" page-title">'
            modal += '                             <h1 class="page-title" style="width: 100%;">' + ($('#js-from-company option[value="' + ($('#js-from-company').val()) + '"]').text()) + '</h1>';
            modal += '                         </div>';
            modal += '        </div>';

            modal += '       <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
            modal += '                        <div class="page-title">'
            modal += '                             <h1 class="page-title" style="width: 100%;">' + ($('#js-to-company option[value="' + ($('#js-to-company').val()) + '"]').text()) + '</h1>';
            modal += '                         </div>';
            modal += '        </div>';

            modal += '      </div>';



            if (obj.fromCompanyPolicies.length > 0) {
                $.each(obj.fromCompanyPolicies, function(key, value) {
                    modal += '<br /> <div class="row csPolicyRow" data-key="' + (key) + '"> ';
                    modal += '       <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
                    modal += '                         <div class="csPolicyRowFrom" data-id="' + value.sid + '" >';

                    modal += `<p><strong>${value.title}</strong> (${value.is_paid == 1 ? 'Paid' : 'UnPaid'})</p>`;
                    //
                    let toEmployeeCount = value.assigned_employees && value.assigned_employees != null && value.assigned_employees != '0' ? value.assigned_employees.split(',').length : 0;
                    //
                    if (value.assigned_employees && value.assigned_employees != null && value.assigned_employees.match(/all/ig) !== null) {
                        toEmployeeCount = 'All';
                    }
                    //
                    modal += `<p><strong>${toEmployeeCount ? (value.is_entitled_employee == 1 ? ' ' + (toEmployeeCount) + ' employees included' : ' ' + (toEmployeeCount) + ' employees excluded') : ' All employees excluded'}</strong></p>`;
                    modal += `<p>${value.is_archived == 1 ? 'De-Activated' : 'Active'}</p>`;
                    modal += `<p># of Requests: ${value.requests_count}</p>`;

                    modal += '                         </div>';
                    modal += '        </div>';

                    modal += '        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">';
                    modal += '                         <div> <select id="" style="width: 100%;" class="invoice-fields csPolicyRowTo">';

                    modal += '<option value="0">Please Select a Policy</option>';
                    modal += '<option value="-1">Create a new policy</option>';

                    if (obj.toCompanyPolicies.length > 0) {
                        $.each(obj.toCompanyPolicies, function(key, value) {

                            let employeeCount = value.assigned_employees && value.assigned_employees != null && value.assigned_employees != '0' ? value.assigned_employees.split(',').length : 0;
                            //
                            if (value.assigned_employees && value.assigned_employees != null && value.assigned_employees.match(/all/ig) !== null) {
                                employeeCount = 'All';
                            }


                            modal += '<option class="' + (value.is_archived == 1 ? 'bg-danger' : '') + '" value="' + value.sid + '">' + value.title + '' + (value.is_archived == 1 ? ' [Deactivated]' : '') + '' + (employeeCount ? (value.is_entitled_employee == 1 ? ' - [' + (employeeCount) + ' employees included]' : ' - [' + (employeeCount) + ' employees excluded]') : ' - [All employees excluded]') + '</option>';

                        });
                    }

                    modal += '                                 </select>';
                    modal += '                         </div>';
                    modal += '        </div>';

                    modal += '  </div>';

                });
            }



            modal += '            </div>';
            modal += '            <div class="modal-footer">';
            modal += '                <button type="button" class="btn btn-success js-btn-save">Save</button>';
            modal += '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            modal += '            </div>';
            modal += '        </div>';
            modal += '    </div>';
            modal += '</div>';

            //
            $('#modal-id').remove();
            $('body').append(modal);
            $('#modal-id').modal();
            //
        }


        let policyObj = {
            hasErrors: []
        };



        function callLoader() {
            // get the employees
            let selectedEmployees = [];
            $.each($('input[name="employees_ids[]"]:checked'), function() {
                selectedEmployees.push(parseInt($(this).val()));
            });
            //
            policyObj = {
                hasErrors: []
            };
            // Get company policies
            let fromCompanySid = $('#js-from-company').val();
            let toCompanySid = $('#js-to-company').val();

            if (fromCompanySid == 0 || toCompanySid == 0) {
                return alertify.alert('Please select "From & To" company to proceed.');
            }

            //Get From and to Company Policies
            var myurl = "<?php echo base_url('manage_admin/copy_employees/getCompaniesPolicies') ?>" + "/" + fromCompanySid + "/" + toCompanySid;
            $.ajax({
                type: "POST",
                url: myurl,
                async: false,
                data: {
                    employeeIds: selectedEmployees
                },
                success: function(data) {
                    if (data.fromCompanyPolicies.length === 0) {
                        return start_copy_process('bypass');
                    }
                    loadModal(data);
                },
                error: function(data) {}
            });
        }


        //
        $(document).on('click', '.js-btn-save', function(event) {
            //
            event.preventDefault();
            //
            let errorArray = [];

            policyObj = {
                hasErrors: []
            };

            let tmpPolicyHolder = {};
            //
            $('.csPolicyRow').map(function(index) {
                let fromId = $(this).find('.csPolicyRowFrom').data('id');
                let toId = $(this).find('.csPolicyRowTo option:selected').val();
                //
                if (!toId || toId == 0) {
                    errorArray.push("Please match the policy at row " + (index + 1) + "");
                } else {
                    tmpPolicyHolder[fromId] = toId;
                }
            });
            //
            if (errorArray.length) {
                policyObj.hasErrors = errorArray;
                return alertify.alert(
                    'Error!',
                    errorArray.join('<br />')
                );
            }

            policyObj = tmpPolicyHolder;

            //
            $('#modal-id').modal('hide');
            $('#modal-id').remove();
            $('body').removeClass('modal-open')
            $('.modal-backdrop').remove()

            start_copy_process()
        });
    </script>