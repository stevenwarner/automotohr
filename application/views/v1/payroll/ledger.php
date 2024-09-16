<?php
$filter_state = $this->uri->segment(3) != '' ? true : false;
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back
                                    </a>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="panel-group-wrp">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                            <h4 class="panel-title" style="padding-left: 12px;">
                                                                Advanced Search Filters <span class="glyphicon glyphicon-plus"></span>
                                                            </h4>
                                                        </a>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse <?php if (isset($filter_state) && $filter_state) {
                                                                                                                echo 'in';
                                                                                                            } ?>">
                                                        <form method="get" enctype="multipart/form-data">
                                                            <div class="panel-body">

                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <label>Employees</label>
                                                                        <select id="js-filter-employee" class="js-filter-employee" multiple="multiple">
                                                                            <option value="all">All</option>
                                                                            <?php foreach ($allemployees as $empRow) { ?>
                                                                                <option value="<?php echo $empRow['userId']; ?>" <?= in_array($empRow["userId"], $filter_employees) ? "selected" : ""; ?>>
                                                                                    <?= remakeEmployeeName($empRow); ?>
                                                                                </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <label>Departments / Teams</label>
                                                                        <br>
                                                                        <?= get_company_departments_teams_dropdown($company_sid, 'teamId', $filter_team ?? 0); ?>
                                                                    </div>

                                                                    <div class="col-sm-3">
                                                                        <label>Job Title</label>
                                                                        <br>
                                                                        <?= get_jobTitle_dropdown_for_search($company_sid, 'jobtitleId') ?>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label><strong>Search by</strong></label> <br>

                                                                        <label class="control control--radio">
                                                                            Transaction Date &nbsp;&nbsp;
                                                                            <input type="radio" name="dateselection" class="assignAndSendDocument" value="transaction" <?php echo $this->uri->segment(8) == 'transaction' || $this->uri->segment(8) == '' ? 'checked' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                        <label class="control control--radio">
                                                                            Period Date &nbsp;&nbsp;
                                                                            <input type="radio" name="dateselection" class="assignAndSendDocument" value="period" <?php echo $this->uri->segment(8) == 'period' ? 'checked' : ''; ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div><br>
                                                                    <br><br>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="">Start Period</label>
                                                                        <?php $start_date = $this->uri->segment(3) != 'all' && $this->uri->segment(3) != '' ? urldecode($this->uri->segment(3)) : date('m-1-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                                    </div>

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label class="">End Period</label>
                                                                        <?php $end_date = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : date('m-t-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                        <div class="field-row autoheight text-right">

                                                                            <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                                            <a id="btn_reset_filters" class="btn btn-success" href="<?php echo base_url('payrolls/ledger'); ?>">Reset Filters</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bottom here-->


                                        <?php if (isset($employeesLedger) && sizeof($employeesLedger) > 0) { ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-3">
                                                    <div class="thumbnail bg-green">
                                                        <div class="caption" style="color: #fff;">
                                                            <h3><?= _a($totalArray["debit_amount"]); ?></h3>
                                                            <h4><strong>Debit</strong></h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-md-3">
                                                    <div class="thumbnail bg-red">
                                                        <div class="caption" style="color: #fff;">
                                                            <h3><?= _a($totalArray["credit_amount"]); ?></h3>
                                                            <h4><strong>Credit</strong></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="box-view reports-filtering">
                                                <form method="post" id="export" name="export" type="form/multipart">
                                                    <div class="panel panel-default cs_margin_panel">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-lg-9 col-xs-10 ">
                                                                    <label class="control control--checkbox">
                                                                        <input type="checkbox" name="" id="check_all" value="">
                                                                        <div class="control__indicator" style="background: #fff;"></div>
                                                                    </label>
                                                                    <p class="cs_line" style="padding-left:35px;margin-top: -12px;"><strong>Include columns in export file</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="collapse1" class="panel-collapse ">

                                                            <div class="panel-body" style="min-height:100px;">

                                                                <?php $index = 1; ?>
                                                                <?php foreach ($columns as $v0) : ?>
                                                                    <?php if ($index == 1) {
                                                                        echo '<div class="row">';
                                                                    } ?>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <div class="checkbox cs_full_width" style="width: 100%;">
                                                                            <label class="control control--checkbox" style="padding-left:35px;">
                                                                                <?= $v0["value"] ?? SlugToString($v0["slug"]); ?>
                                                                                <input type="checkbox" class="check_it jsExtraColumn" data-target="<?= stringToSlug($v0["slug"], ""); ?>" name="columns[<?= $v0["slug"]; ?>]" <?= $v0["selected"] ? "checked" : "" ?> />
                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($index == 4) {
                                                                        echo "</div>";
                                                                        $index = 0;
                                                                    } ?>
                                                                    <?php $index++; ?>
                                                                <?php endforeach; ?>

                                                                <div class="row">
                                                                    <?php if ($extraColumns) : ?>
                                                                        <?php foreach ($extraColumns as $v0) : ?>
                                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6 cs_adjust_margin">
                                                                                <div class="checkbox cs_full_width" style="width: 100%;">
                                                                                    <label class="control control--checkbox" style="padding-left:35px;">
                                                                                        <?= $v0; ?>
                                                                                        <input type="checkbox" class="check_it jsExtraColumn" data-target="<?= stringToSlug($v0, ""); ?>" name="columns[<?= stringToSlug($v0, "_"); ?>]">
                                                                                        <div class="control__indicator"></div>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>

                                                                </div>

                                                            </div>


                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <input type="submit" name="submit" class="submit-btn pull-right" value="Export">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>


                                            <!-- table -->
                                            <div class="hr-box">
                                                <div class="hr-box-header bg-header-green">
                                                    <span class="pull-left">
                                                        <h1 class="hr-registered">Ledger</h1>
                                                    </span>
                                                    <span class="pull-right">
                                                        <h1 class="hr-registered">Total Records Found : <?php echo $ledgerCount; ?></h1>
                                                    </span>
                                                </div>
                                                <div class="hr-innerpadding">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <span class="pull-left">
                                                                <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $ledgerCount ?></p>
                                                            </span>
                                                            <span class="pull-right" style="margin-top: 20px; margin-bottom: 20px;">
                                                                <?php echo $page_links ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">

                                                            <div class="table-responsive" id="print_div">
                                                                <table class="table table-bordered horizontal-scroll" id="example">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Employee/Company</th>
                                                                            <?php if ($columns) : ?>
                                                                                <?php foreach ($columns as $v0) : ?>
                                                                                    <th class="text-right jsExtraColumnBody jsExtraColumnBody<?= stringToSlug($v0["slug"], ""); ?> <?= $v0["selected"] ? "" : "hidden"; ?>"><?= $v0["name"] ?? SlugToString($v0["slug"]); ?></th>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>


                                                                            <?php if ($extraColumns) : ?>
                                                                                <?php foreach ($extraColumns as $v0) : ?>
                                                                                    <th class="text-right jsExtraColumnBody jsExtraColumnBody<?= stringToSlug($v0, ""); ?> hidden"><?= $v0; ?></th>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>

                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <?php foreach ($employeesLedger as $rowEmployee) {
                                                                            $employeeName = '';
                                                                        ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <strong>
                                                                                        <?= $rowEmployee['employee_sid']
                                                                                            ? remakeEmployeeName($rowEmployee)
                                                                                            : $session["company_detail"]["CompanyName"];
                                                                                        ?>
                                                                                    </strong>
                                                                                </td>
                                                                                <?php if ($columns) : ?>
                                                                                    <?php foreach ($columns as $v0) {

                                                                                        $columnName = $v0["name"] ?? SlugToString($v0["slug"]);
                                                                                        $columnSlug = stringToSlug($v0["slug"], "_");

                                                                                        $value = $rowEmployee[$columnSlug];

                                                                                    ?>
                                                                                        <td class="text-right jsExtraColumnBody jsExtraColumnBody<?= stringToSlug($columnSlug, ""); ?> <?= $v0["selected"] ? "" : "hidden"; ?> <?= $columnSlug === "debit_amount" ? "bg-success" : ""; ?> <?= $columnSlug === "credit_amount" ? "bg-danger" : ""; ?>">
                                                                                            <?php
                                                                                            if (isDateTime($value)) {
                                                                                                echo formatDateToDB(
                                                                                                    $value,
                                                                                                    false,
                                                                                                    SITE_DATE
                                                                                                );
                                                                                            } elseif (in_array($columnSlug, [
                                                                                                "debit_amount",
                                                                                                "credit_amount",
                                                                                                "net_pay",
                                                                                                "gross_pay",
                                                                                                "taxes"
                                                                                            ])) {

                                                                                                //
                                                                                                echo _a($value ? $value : 0);
                                                                                            } else {
                                                                                                echo $value;
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    <?php
                                                                                    } ?>
                                                                                <?php endif; ?>


                                                                                <?php if ($extraColumns) : ?>
                                                                                    <?php foreach ($extraColumns as $v0) : ?>
                                                                                        <td class="jsExtraColumnBody jsExtraColumnBody<?= stringToSlug($v0, ""); ?> hidden">
                                                                                            <?= $rowEmployee["extra_column"][stringToSlug($v0, "")] ?? "-"; ?>
                                                                                        </td>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>


                                                                                <td>
                                                                                    <?php if ($rowEmployee['is_regular'] == 1 ||  $rowEmployee['is_regular_employee'] == 1 || $rowEmployee['is_external'] == 1) { ?>

                                                                                        <button type="button" class="btn btn-success jsProfileHistory" data-id="<?php echo $rowEmployee['payroll_sid']; ?>" data-isregular="<?php echo $rowEmployee['is_regular']; ?>" data-isregularemployee="<?php echo $rowEmployee['is_regular_employee']; ?>" data-isexternal="<?php echo $rowEmployee['is_external']; ?>" data-name="<?php echo $employeeName; ?>">View Breakdown</button>

                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <span class="pull-right">
                                                                <?php echo $page_links ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- table -->
                                        <?php } else { ?>
                                            <p class="alert alert-info text-center">
                                                <strong>
                                                    Please apply the filter in order to see the ledger details.
                                                </strong>
                                            </p>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        //
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var employees = $('#js-filter-employee').val();
        var departments = $('#teamId').val();
        var jobTitles = $('#jobtitleId').val();
        var dateSelection = $("input[name=dateselection]:checked").val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';

        var url = '<?php echo base_url('payrolls/ledger'); ?>' + '/' + start_date_applied + '/' + end_date_applied + '/' + employees + '/' + departments + '/' + jobTitles + '/' + dateSelection;
        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {

        //
        $('#js-filter-employee').select2();
        $('#teamId').select2();
        $('#jobtitleId').select2();

        <?php if ($this->uri->segment(5) != '') { ?>
            let filteremployes = "<?php echo $this->uri->segment(5); ?>";
            let filteremployeesArray = filteremployes.split(',');
            $('#js-filter-employee').select2('val', filteremployeesArray);
        <?php } else { ?>
            $('#js-filter-employee').select2('val', 'all');
        <?php } ?>

        <?php if ($this->uri->segment(6) != '') { ?>
            let filterDepartment = "<?php echo $this->uri->segment(6); ?>";
            let filterDepartmentArray = filterDepartment.split(',');
            $('#teamId').select2('val', filterDepartmentArray);
        <?php } else { ?>
            $('#teamId').select2('val', '0');
        <?php } ?>

        <?php if ($this->uri->segment(7) != '') { ?>
            let filterJobtitle = "<?php echo urldecode($this->uri->segment(7)); ?>";
            let filterJobTitleArray = filterJobtitle.split(',');
            $('#jobtitleId').select2('val', filterJobTitleArray);
        <?php } else { ?>
            $('#jobtitleId').select2('val', 'all');
        <?php } ?>


        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        // Search Area Toggle Function
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#end_date_applied').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#start_date_applied').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

        <?php if ($this->uri->segment(3) == '' || $this->uri->segment(4) == '') { ?>
            $('#btn_apply_filters').click();
        <?php  } ?>

    });

    $("#check_all").click(function() {


        if ($(this).prop("checked") === true) {
            $(`.jsExtraColumn`).prop("checked", true);
            $(`.jsExtraColumnBody`).removeClass("hidden");
        } else {
            $(`.jsExtraColumn`).prop("checked", false);
            $(`.jsExtraColumnBody`).addClass("hidden");
        }

    });

    //
    $("#general_entry_number").click(function() {
        if ($("#general_entry_number").is(':checked')) {
            $(".general_entry_number_td").show();
        } else {
            $(".general_entry_number_td").hide();
        }
    });

    $("#reference_number").click(function() {
        if ($("#reference_number").is(':checked')) {
            $(".reference_number_td").show();
        } else {
            $(".reference_number_td").hide();
        }
    });

    $("#account_number").click(function() {
        if ($("#account_number").is(':checked')) {
            $(".account_number_td").show();
        } else {
            $(".account_number_td").hide();
        }
    });

    $("#account_name").click(function() {
        if ($("#account_name").is(':checked')) {
            $(".account_name_td").show();
        } else {
            $(".account_name_td").hide();
        }
    });
    $("#imported_at").click(function() {
        if ($("#imported_at").is(':checked')) {
            $(".imported_at_td").show();
        } else {
            $(".imported_at_td").hide();
        }
    });

    $("#end_period").click(function() {
        if ($("#end_period").is(':checked')) {
            $(".end_period_td").show();
        } else {
            $(".end_period_td").hide();
        }
    });

    $("#start_period").click(function() {
        if ($("#start_period").is(':checked')) {
            $(".start_period_td").show();
        } else {
            $(".start_period_td").hide();
        }
    });

    $("#transaction_date").click(function() {
        if ($("#transaction_date").is(':checked')) {
            $(".transaction_date_td").show();
        } else {
            $(".transaction_date_td").hide();
        }
    });

    $("#description").click(function() {
        if ($("#description").is(':checked')) {
            $(".description_td").show();
        } else {
            $(".description_td").hide();
        }
    });

    $("#taxes").click(function() {
        if ($("#taxes").is(':checked')) {
            $(".taxes_td").show();
        } else {
            $(".taxes_td").hide();
        }
    });

    $("#net_pay").click(function() {
        if ($("#net_pay").is(':checked')) {
            $(".net_pay_td").show();
        } else {
            $(".net_pay_td").hide();
        }
    });

    $("#gross_pay").click(function() {
        if ($("#gross_pay").is(':checked')) {
            $(".gross_pay_td").show();
        } else {
            $(".gross_pay_td").hide();
        }
    });

    $("#debit_amount").click(function() {
        if ($("#debit_amount").is(':checked')) {
            $(".debit_amount_td").show();
        } else {
            $(".debit_amount_td").hide();
        }
    });

    $("#credit_amount").click(function() {
        if ($("#credit_amount").is(':checked')) {
            $(".credit_amount_td").show();
        } else {
            $(".credit_amount_td").hide();
        }
    });

    $("#first_name").click(function() {
        if ($("#first_name").is(':checked')) {
            $(".first_name_td").show();
        } else {
            $(".first_name_td").hide();
        }
    });

    $("#middle_name").click(function() {
        if ($("#middle_name").is(':checked')) {
            $(".middle_name_td").show();
        } else {
            $(".middle_name_td").hide();
        }
    });

    $("#last_name").click(function() {
        if ($("#last_name").is(':checked')) {
            $(".last_name_td").show();
        } else {
            $(".last_name_td").hide();
        }
    });


    $("#job_title").click(function() {
        if ($("#job_title").is(':checked')) {
            $(".job_title_td").show();
        } else {
            $(".job_title_td").hide();
        }
    });
    $("#department").click(function() {
        if ($("#department").is(':checked')) {
            $(".department_td").show();
        } else {
            $(".department_td").hide();
        }
    });

    $("#team").click(function() {
        if ($("#team").is(':checked')) {
            $(".team_td").show();
        } else {
            $(".team_td").hide();
        }
    });

    $("#ssn").click(function() {
        if ($("#ssn").is(':checked')) {
            $(".ssn_td").show();
        } else {
            $(".ssn_td").hide();
        }
    });
    $("#employee_number").click(function() {
        if ($("#employee_number").is(':checked')) {
            $(".employee_number_td").show();
        } else {
            $(".employee_number_td").hide();
        }
    });
    $("#email").click(function() {
        if ($("#email").is(':checked')) {
            $(".email_td").show();
        } else {
            $(".email_td").hide();
        }
    });

    $("#phone_number").click(function() {
        if ($("#phone_number").is(':checked')) {
            $(".phone_number_td").show();
        } else {
            $(".phone_number_td").hide();
        }
    });

    $("#employee_id").click(function() {
        if ($("#employee_id").is(':checked')) {
            $(".employee_id_td").show();
        } else {
            $(".employee_id_td").hide();
        }
    });


    $("#extra").click(function() {
        if ($("#extra").is(':checked')) {
            $(".extra_td").show();
        } else {
            $(".extra_td").hide();
        }
    });


    //
    $('.jsProfileHistory').click(getbreakDown);

    function getbreakDown(e) {

        sId = $(this).data('id');
        isRegular = $(this).data('isregular');
        isRegularEmployee = $(this).data('isregularemployee');
        isExternal = $(this).data('isexternal');

        applicantName = $(this).data('name');
        //
        Model({
            Id: 'jsEmployeeProfileHistoryModel',
            Loader: 'jsEmployeeProfileHistoryLoader',
            Body: '<div class="container"><div id="jsLedgerBreakdown"></div></div>',
            Title: 'Ledger Breakdown of ' + applicantName
        }, getData);

    }

    //
    function getData() {
        //
        $.get(
            "<?= base_url('get_ledger_brakdown/'); ?>/" + sId + '/' + isRegular + '/' + isRegularEmployee + '/' + isExternal,
            function(resp) {
                $('#jsLedgerBreakdown').html(resp.view);
                ml(false, 'jsEmployeeProfileHistoryLoader');
            });

    }

    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<table> <tr><td>&nbsp;</td></tr><tr><td><b><?php echo $companyName; ?></b></td></tr><tr><td>&nbsp;</td></tr></table >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
        $("table").addClass("horizontal-scroll");
    }


    $(".jsExtraColumn").click(function() {
        const column = $(this).data("target");
        if ($(this).prop("checked") === true) {
            $(`.jsExtraColumnBody${column}`).removeClass("hidden");
        } else {
            $(`.jsExtraColumnBody${column}`).addClass("hidden");
        }
    })
</script>