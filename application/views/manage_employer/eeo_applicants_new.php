<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Male', <?php echo $male_cout; ?>],
            ['Female', <?php echo $female_cout; ?>],
            ['Not Available', <?php echo $notdefined_cout; ?>],

        ]);

        var options = {
            title: 'Total Job <?php echo $recordsfor; ?> <?php echo $totalrecords; ?>',
            pieHole: 0.3,
            height: 300,
            colors: ['#3366cc', '#81b431', '#fd7a2a'],
            pieSliceText: 'value',
            sliceVisibilityThreshold: 0,
            fontSize: 14,
            backgroundColor: {
                fill: '#eeedee'
            },
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);


        var data = google.visualization.arrayToDataTable([
            ['EEOC Detail Summery', 'Male', 'Female', 'Not Available', {
                role: 'annotation'
            }],
            <?php //if ($male_cout_hispanic != 0 || $female_cout_hispanic != 0 || $female_cout_hispanic != 0 || $notdefined_cout_hispanic != 0) { 
            ?>['Hispanic', <?php echo $male_cout_hispanic; ?>, <?php echo $female_cout_hispanic; ?>, <?php echo $notdefined_cout_hispanic; ?>, ''], <?php //} 
                                                                                                                                                    ?>
            <?php //if ($male_cout_white != 0 || $female_cout_white != 0 || $female_cout_white != 0 || $notdefined_cout_white != 0) { 
            ?>['White', <?php echo $male_cout_white; ?>, <?php echo $female_cout_white; ?>, <?php echo $notdefined_cout_white; ?>, ''], <?php //} 
                                                                                                                                        ?>
            <?php //if ($male_cout_black != 0 || $female_cout_black != 0 || $female_cout_black != 0 || $notdefined_cout_black != 0) { 
            ?>['Black', <?php echo $male_cout_black; ?>, <?php echo $female_cout_black; ?>, <?php echo $notdefined_cout_black; ?>, ''], <?php //} 
                                                                                                                                        ?>
            <?php //if ($male_cout_native != 0 || $female_cout_native != 0 || $female_cout_native != 0 || $notdefined_cout_native != 0) { 
            ?>['Native Hawaiian', <?php echo $male_cout_native; ?>, <?php echo $female_cout_native; ?>, <?php echo $notdefined_cout_native; ?>, ''], <?php //} 
                                                                                                                                                        ?>
            <?php //if ($male_cout_asian != 0 || $female_cout_asian != 0 || $female_cout_asian != 0 || $notdefined_cout_asian != 0) { 
            ?>['Asian', <?php echo $male_cout_asian; ?>, <?php echo $female_cout_asian; ?>, <?php echo $notdefined_cout_asian; ?>, ''], <?php //} 
                                                                                                                                        ?>
            <?php //if ($male_cout_american != 0 || $female_cout_american != 0 || $female_cout_american != 0 || $notdefined_cout_american != 0) { 
            ?>['American Indian', <?php echo $male_cout_american; ?>, <?php echo $female_cout_american; ?>, <?php echo $notdefined_cout_american; ?>, ''], <?php //} 
                                                                                                                                                            ?>
            <?php //if ($male_cout_races != 0 || $female_cout_races != 0 || $female_cout_races != 0 || $notdefined_cout_races != 0) { 
            ?>['Races', <?php echo $male_cout_races; ?>, <?php echo $female_cout_races; ?>, <?php echo $notdefined_cout_races; ?>, ''], <?php //} 
                                                                                                                                        ?>
            <?php //if ($male_cout_nogroup != 0 || $female_cout_nogroup != 0 || $female_cout_nogroup != 0 || $notdefined_cout_nogroup != 0) { 
            ?>['Not Available', <?php echo $male_cout_nogroup; ?>, <?php echo $female_cout_nogroup; ?>, <?php echo $notdefined_cout_nogroup; ?>, ''], <?php //} 
                                                                                                                                                        ?>

        ]);

        var barchart_options = {
            title: 'EEO Detail Summery',
            //  width: 600,
            height: 300,
            colors: ['#3366cc', '#81b431', '#fd7a2a'],
            legend: {
                position: 'top',
                maxLines: 3
            },
            bars: 'horizontal',
            isStacked: true,
            hAxis: {
                gridlines: {
                    interval: 0
                }

            }
        };

        var barchart = new google.visualization.BarChart(document.getElementById('barchart_material'));
        barchart.draw(data, barchart_options);

    }
</script>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">EEO form Candidates</span>
                    </div>
                    <div class="row">
                        <div class="applicant-reg-date">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="<?php echo base_url('eeo/export_excel'); ?>">

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="control control--radio">Applicant
                                                        <input type="radio" name="applicantoption" value="applicant" id="applicant" <?php echo $keyword != 'employee' ? 'checked' : ''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control control--radio">Employee
                                                        <input type="radio" name="applicantoption" value="employee" id="employee" <?php echo $keyword == 'employee' ? 'checked' : ''; ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-col-100">
                                                <label for="startdate" id="lblkeyword">Applicant Name</label>
                                                <?php $keyword = $keyword != 'all' ? $keyword : ''; ?>
                                                <input type="text" id="keyword" class="invoice-fields" name="keyword" value="<?php echo $keyword != 'employee' ? $keyword : ''; ?>">

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="div_employee_status">
                                                    <label for="employeestatus">Employee Status</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="employee_status" id="employee_status">
                                                            <option value="all" <?php echo $employee_status == 'all' ? 'selected="selected"' : "" ?>>All</option>
                                                            <option value="active" <?php echo $employee_status == 'active' ? 'selected="selected"' : "" ?>>Active</option>
                                                            <option value="inactive" <?php echo $employee_status == 'inactive' ? 'selected="selected"' : "" ?>>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="div_employee">
                                                    <label for="enddate">Opt Type</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="opt_type1" id="opt_type1">
                                                            <option value="all" <?php echo $opt_type == 'all' ? 'selected="selected"' : "" ?>>All</option>
                                                            <option value="completed" <?php echo $opt_type == 'completed' ? 'selected="selected"' : "" ?>>Completed</option>
                                                            <option value="notcompleted" <?php echo $opt_type == 'notcompleted' ? 'selected="selected"' : "" ?>>Not completed</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-col-100">
                                                <label for="startdate">Start Date</label>
                                                <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo $startdate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="form-col-100">
                                                <label for="enddate">End Date</label>
                                                <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo $enddate; ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" id="div_opt_type">
                                            <label for="enddate">Opt Type</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="opt_type" id="opt_type">
                                                    <option value="all" <?php echo $opt_type == 'all' ? 'selected="selected"' : "" ?>>All Applicants</option>
                                                    <option value="no" <?php echo $opt_type == 'no' ? 'selected="selected"' : "" ?>>Opted Out Applicants</option>
                                                    <option value="yes" <?php echo $opt_type == 'yes' ? 'selected="selected"' : "" ?>>Opted In Applicants</option>
                                                    <option value="other" <?php echo $opt_type == 'other' ? 'selected="selected"' : "" ?>>Opt Status Not Available</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" id="div_opt_type_space"></div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <div class="report-btns">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <button type="button" class="form-btn" onclick="fclearDateFilters();">Clear Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="report-btns">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="submit" class="form-btn">Export CSV</button>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                        <button type="button" class="form-btn" onclick="fApplyDateFilters();">Apply Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>


                    <?php if ($totalrecords != 0) { ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                                <div id="barchart_material"></div>
                            </div>

                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                <div id="donutchart"></div>
                            </div>

                        </div>

                        <div class="hr-innerpadding">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead style="background-color: #eeedee;">
                                        <tr>
                                            <th>Race/Ethnicty</th>
                                            <th colspan="2">Male</th>
                                            <th colspan="2">Female</th>
                                            <!--   <th colspan="2">Decline to Answer</th>-->
                                            <th colspan="2">Total</th>
                                        </tr>
                                        <tr>
                                            <th>All Job Openings</th>
                                            <th>Total</th>
                                            <th>Hired</th>
                                            <th>Total</th>
                                            <th>Hired</th>

                                            <!--  <th>Total</th>
                                            <th>Hired</th> -->

                                            <th>Total</th>
                                            <th>Hired</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td><b>Hispanic</b></td>
                                            <td><?php echo $male_cout_hispanic; ?></td>
                                            <td><?php echo $male_cout_hispanic_hired; ?></td>
                                            <td><?php echo $female_cout_hispanic; ?></td>
                                            <td><?php echo $female_cout_hispanic_hired; ?></td>
                                            <td><?php echo $male_cout_hispanic + $female_cout_hispanic; ?></td>
                                            <td><?php echo $male_cout_hispanic_hired + $female_cout_hispanic_hired; ?></td>

                                        </tr>

                                        <tr>
                                            <td><b>White</b></td>
                                            <td><?php echo $male_cout_white; ?></td>
                                            <td><?php echo $male_cout_white_hired; ?></td>
                                            <td><?php echo $female_cout_white; ?></td>
                                            <td><?php echo $female_cout_white_hired; ?></td>
                                            <td><?php echo $male_cout_white + $male_cout_white_hired; ?></td>
                                            <td><?php echo $male_cout_white_hired + $female_cout_white_hired; ?></td>

                                        </tr>
                                        <tr>
                                            <td><b>Black</b></td>
                                            <td><?php echo $male_cout_black; ?></td>
                                            <td><?php echo $male_cout_black_hired; ?></td>
                                            <td><?php echo $female_cout_black; ?></td>
                                            <td><?php echo $female_cout_black_hired; ?></td>
                                            <td><?php echo $male_cout_black + $female_cout_black; ?></td>
                                            <td><?php echo $male_cout_black_hired + $female_cout_black_hired; ?></td>

                                        </tr>
                                        <tr>
                                            <td><b>Native Hawaiian</b></td>
                                            <td><?php echo $male_cout_native; ?></td>
                                            <td><?php echo $male_cout_native_hired; ?></td>
                                            <td><?php echo $female_cout_native; ?></td>
                                            <td><?php echo $female_cout_native_hired; ?></td>
                                            <td><?php echo $male_cout_native + $female_cout_native; ?></td>
                                            <td><?php echo $male_cout_native_hired + $female_cout_native_hired; ?></td>

                                        </tr>

                                        <tr>
                                            <td><b>Asian</b></td>
                                            <td><?php echo $male_cout_asian; ?></td>
                                            <td><?php echo $male_cout_asian_hired; ?></td>
                                            <td><?php echo $female_cout_asian; ?></td>
                                            <td><?php echo $female_cout_asian_hired; ?></td>
                                            <td><?php echo $male_cout_asian + $female_cout_asian; ?></td>
                                            <td><?php echo $male_cout_asian_hired + $female_cout_asian_hired; ?></td>

                                        </tr>

                                        <tr>
                                            <td><b>American Indian</b></td>
                                            <td><?php echo $male_cout_american; ?></td>
                                            <td><?php echo $male_cout_american_hired; ?></td>
                                            <td><?php echo $female_cout_american; ?></td>
                                            <td><?php echo $female_cout_american_hired; ?></td>
                                            <td><?php echo $male_cout_american + $female_cout_american; ?></td>
                                            <td><?php echo $male_cout_american_hired + $female_cout_american_hired; ?></td>
                                        </tr>

                                        <tr>
                                            <td><b>Races</b></td>
                                            <td><?php echo $male_cout_races; ?></td>
                                            <td><?php echo $male_cout_races_hired; ?></td>
                                            <td><?php echo $female_cout_races; ?></td>
                                            <td><?php echo $female_cout_races_hired; ?></td>
                                            <td><?php echo $male_cout_races + $female_cout_races; ?></td>
                                            <td><?php echo $male_cout_races_hired + $female_cout_races_hired; ?></td>
                                        </tr>

                                        <tr style="background-color: #eeedee;">
                                            <td><b>Total</b></td>
                                            <td><b><?php echo $male_cout_hispanic + $male_cout_white + $male_cout_black + $male_cout_native + $male_cout_asian + $male_cout_american + $male_cout_races; ?></b></td>
                                            <td><b><?php echo $male_cout_hispanic_hired + $male_cout_white_hired + $male_cout_black_hired + $male_cout_native_hired + $male_cout_asian_hired + $male_cout_american_hired + $male_cout_races_hired; ?></b></td>
                                            <td><b><?php echo $female_cout_hispanic + $female_cout_white + $female_cout_black + $female_cout_native + $female_cout_asian + $female_cout_american + $female_cout_races; ?></b></td>
                                            <td><b><?php echo $female_cout_hispanic_hired + $female_cout_white_hired + $female_cout_black_hired + $female_cout_native_hired + $female_cout_asian_hired + $female_cout_american_hired + $female_cout_races_hired; ?></b></td>
                                            <td><b><?php echo $male_cout_hispanic + $female_cout_hispanic + $male_cout_white + $male_cout_white_hired + $male_cout_black + $female_cout_black + $male_cout_asian + $female_cout_asian + $male_cout_american + $female_cout_american + $male_cout_races + $female_cout_races; ?></b></td>
                                            <td><b><?php echo $male_cout_hispanic_hired + $female_cout_hispanic_hired + $male_cout_white_hired + $female_cout_white_hired + $male_cout_black_hired + $female_cout_black_hired + $male_cout_native_hired + $female_cout_native_hired + $male_cout_asian_hired + $female_cout_asian_hired + $male_cout_american_hired + $female_cout_american_hired + $male_cout_races_hired + $female_cout_races_hired; ?></b></td>
                                        </tr>

                                    </tbody>
                                </table>


                            </div>

                        </div>
                    </div>


                    <?php } ?>



                    <div class="hr-box">
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($total_records > 0) { ?>
                                        <span class="pull-left">
                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                        </span>
                                    <?php } ?>
                                    <span class="pull-right">
                                        <?php echo $links; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Opt Status</th>
                                            <th>
                                                EEO Information
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($eeo_candidates)) { ?>
                                            <tr>
                                                <td colspan="4">
                                                    <span class="no-data">No Applicants</span>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($eeo_candidates as $item) { ?>
                                                <tr id="manual_row<?= $item["application_list_sid"] ?>">
                                                    <td><?php echo isset($item["date_applied"]) ? reset_datetime(array('datetime' => $item["date_applied"], '_this' => $this)) : ''; ?></td>
                                                    <td>
                                                        <strong><?php echo ucwords($item['first_name'] . '&nbsp;' . $item['last_name']); ?></strong>
                                                        <small class="text-success">( <?php echo $item['applicant_type']; ?> )</small>
                                                        <br />
                                                        <small>IP: <?php echo $item["ip_address"]; ?></small>
                                                        <br />
                                                        <small>Job Title : <?php echo isset($item["job_title"]) && !empty($item["job_title"]) ? $item["job_title"] : 'Not Available'; ?></small>
                                                    </td>
                                                    <td>
                                                        <?php $opt_status = $item['eeo_form']; ?>
                                                        <?php if ($opt_status == 'Yes') { ?>
                                                            <span class="text-success">Opted In</span>
                                                        <?php } else if ($opt_status == 'No') { ?>
                                                            <span class="text-danger">Opted Out</span>
                                                        <?php } else if ($opt_status == null) { ?>
                                                            <span class="">Not Available</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <table class="table table-bordered table-condensed table-hover">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="col-xs-4">Us Citizen</th>
                                                                    <td class="col-xs-8"><?php echo $item['eeo_form'] != null && !empty($item["us_citizen"]) ? $item["us_citizen"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Visa Status</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["visa_status"]) ? $item["visa_status"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Group Status</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["group_status"]) ? $item["group_status"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Veteran</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["veteran"]) ? $item["veteran"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Disability</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["disability"]) ? $item["disability"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Gender</th>
                                                                    <td><?php echo $item['eeo_form'] != null && !empty($item["gender"]) ? $item["gender"] : '<small>Not Available</small>'; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>

                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($total_records > 0) { ?>
                                        <span class="pull-left">
                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                        </span>
                                    <?php } ?>
                                    <span class="pull-right">
                                        <?php echo $links; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php //echo '<pre>'; print_r($eeo_candidates);
?>
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($keyword != 'employee') { ?>
            $('#div_employee').hide();
            $('#div_employee_status').hide();
            $('#div_opt_type_space').hide();
        <?php } else { ?>
            $('#keyword').hide();
            $('#lblkeyword').hide();
            $('#div_opt_type').hide();

        <?php } ?>

    });


    jQuery(function() {
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function() {
            $(this).prop('readonly', true);
        }).on('focusout', function() {
            $(this).prop('readonly', false);
        });
    });

    function fApplyDateFilters() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();

        var url = '<?php echo base_url('eeo'); ?>';
        var eeo_form_candidates = $('input[name="applicantoption"]:checked').val();

        var employee_status = $('#employee_status').val();

        if (eeo_form_candidates == 'employee') {
            keyword = 'employee';
            var opt_type = $('#opt_type1').val();
        } else {
            var opt_type = $('#opt_type').val();
            keyword = keyword != '' && keyword != null && keyword != undefined ? encodeURIComponent(keyword) : 'all';
        }

        startDate = startDate != '' && startDate != null && startDate != undefined ? encodeURIComponent(startDate) : 'all';
        endDate = endDate != '' && endDate != null && endDate != undefined ? encodeURIComponent(endDate) : 'all';
        opt_type = opt_type != '' && opt_type != null && opt_type != undefined ? encodeURIComponent(opt_type) : 'all';

        if (eeo_form_candidates == 'employee') {
            url += '/' + keyword + '/' + opt_type + '/' + startDate + '/' + endDate + '/' + employee_status
        } else {
            url += '/' + keyword + '/' + opt_type + '/' + startDate + '/' + endDate + '/'
        }

        //console.log(url);
        window.location = url;
    }





    function excel_export() {

        console.log('i am in ajx');
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();
        var opt_type = $('#opt_type').val();

        var url = '<?php echo base_url('eeo/export_excel'); ?>';
        var dataToSend = {
            'startDate': startDate,
            'endDate': endDate,
            'keyword': keyword,
            'opt_type': opt_type,
            'action': 'export_csv'
        };

        var myRequest;
        myRequest = $.ajax({
            url: url,
            data: dataToSend,
            type: 'POST'
        });

        myRequest.done(function(response) {
            console.log(response);
        });
    }

    function fclearDateFilters() {
        var url = '<?php echo base_url(); ?>' + 'eeo/all/all';
        window.location = url;
    }


    $('#applicant').on('click', function() {
        $('#keyword').show();
        $('#lblkeyword').show();
        $('#div_opt_type').show();
        $('#div_employee').hide();
        $('#div_employee_status').hide();
        $('#div_opt_type_space').hide();


    });
    $('#employee').on('click', function() {
        $('#keyword').hide();
        $('#lblkeyword').hide();
        $('#div_opt_type').hide();
        $('#div_employee').show();
        $('#div_employee_status').show();
        $('#div_opt_type_space').show();
    });
</script>