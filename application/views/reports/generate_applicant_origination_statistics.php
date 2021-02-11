<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="box-wrapper">
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <form method="GET" action="<?php echo base_url('/reports/generate_applicant_origination_statistics_report'); ?>" name="search" id="search">

                                                        <div class="form-col-100 field-row field-row-autoheight">
                                                            <div class="col-md-3"><b>Please Select : </b><span class="hr-required red"> * </span></div>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label class="control control--radio">Today
                                                                            <input type="radio" name="date_option" value="daily" id="daily" checked>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="control control--radio">Select Date Range
                                                                            <input type="radio" name="date_option" value="by_date" id="by_date" <?php
                                                                            if (isset($search['date_option']) && $search['date_option'] == 'by_date') {
                                                                                echo 'checked';
                                                                            }
                                                                            ?>>
                                                                            <div class="control__indicator"></div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="daily_div">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <div class="field-row">
                                                                    <label class="text-left">
                                                                        Applicants from today
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="by_date_div">
                                                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-6">
                                                                <div class="field-row">
                                                                    <label>Date From: <span class="hr-required red"> * </span></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-6">
                                                                <div class="field-row">
                                                                    <input class="invoice-fields"
                                                                           placeholder="Select Start Date"
                                                                           type="text"
                                                                           name="startdate"
                                                                           id="startdate"
                                                                           value="<?php echo isset($search['startdate']) ? $search['startdate'] : date('m-d-Y'); ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-6">
                                                                <div class="field-row">
                                                                    <label>Date To: <span class="hr-required red"> * </span></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-6">
                                                                <div class="field-row">
                                                                    <input class="invoice-fields"
                                                                           placeholder="Select End Date"
                                                                           type="text"
                                                                           name="enddate"
                                                                           id="enddate"
                                                                           value="<?php echo isset($search['enddate']) ? $search['enddate'] : date('m-d-Y'); ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="field-row field-row-autoheight col-lg-12 text-right">
                                                            <input type="submit" class="btn btn-success" value="Generate Report" name="submit" id="apply_filters_submit">
                                                            <a class="btn btn-success" href="<?php echo base_url('reports/generate_applicant_origination_statistics_report'); ?>">Reset Report</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php if (isset($companies_applicants_by_source) && !empty($companies_applicants_by_source)) { ?>
                                                <div class="box-view reports-filtering">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <form method="post" id="export" name="export">
                                                                    <input type="submit" name="submit" class="submit-btn pull-right" value="Export" />
                                                                </form>
                                                                <a href="javascript:;" class="submit-btn pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <!-- table -->
                                            <div class="row" id="print_div">
                                                <div class="col-xs-12">
                                                    <div class="add-new-company">

                                                        <div class="hr-box">
                                                            <div class="hr-box-header bg-header-green">
                                                                <h1 class="hr-registered">
                                                                    Applicant Statistics
                                                                    <?php
                                                                    echo ($search['date_option'] == 'daily') ? 'for ' . date('F j, Y') : 'between ' . $search['startdate'] . ' and ' . $search['enddate'];
                                                                    ?>
                                                                </h1>
                                                            </div>
                                                            <div class="hr-box-body hr-innerpadding">
                                                                <div class="row">
                                                                    <div class="col-xs-6 col-xs-offset-3">
                                                                        <div class="">
                                                                            <div class="hr-box-body hr-innerpadding">
                                                                                <canvas id="pie-chart"></canvas>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="col-xs-10 text-center">Applicant Source</th>
                                                                            <th class="col-xs-2 text-center">Source Count</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>Automoto Social</td>
                                                                            <td><?php echo sizeof($automoto_social); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Glassdoor</td>
                                                                            <td><?php echo sizeof($glassdoor); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Indeed</td>
                                                                            <td><?php echo sizeof($indeed); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>JuJu</td>
                                                                            <td><?php echo sizeof($juju); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>ZipRecruiter</td>
                                                                            <td><?php echo $zip_recruiter_count; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Jobs2Career</td>
                                                                            <td><?php echo $jobs_2_career_count; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>AutomotoHR</td>
                                                                            <td><?php echo sizeof($automotohr); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Other</td>
                                                                            <td><?php echo sizeof($other); ?></td>
                                                                        </tr>
                                                                        <!--                                                    <tr>
                                                            <td>Career Website</td>
                                                            <td><?php echo sizeof($automotohr); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Other</td>
                                                            <td><?php echo sizeof($other); ?></td>
                                                        </tr>-->
<!--                                                                        --><?php //foreach($career_sites_array as $key => $value) { ?>
<!--                                                                            <tr>-->
<!--                                                                                <td>-->
<!--                                                                                    <div class="table-responsive" style="width:100%;">-->
<!--                                                                                        --><?php //echo $key; ?>
<!--                                                                                    </div>-->
<!--                                                                                </td>-->
<!--                                                                                <td>--><?php //echo sizeof($value); ?><!--</td>-->
<!--                                                                            </tr>-->
<!--                                                                        --><?php //} ?>
<!--                                                                        --><?php //foreach($other_sites_array as $key => $value) { ?>
<!--                                                                            <tr>-->
<!--                                                                                <td>-->
<!--                                                                                    <div class="table-responsive" style="width:100%;">-->
<!--                                                                                        --><?php //echo $key; ?>
<!--                                                                                    </div>-->
<!--                                                                                </td>-->
<!--                                                                                <td>--><?php //echo sizeof($value); ?><!--</td>-->
<!--                                                                            </tr>-->
<!--                                                                        --><?php //} ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                        <tr>
                                                                            <th class="col-xs-12 text-center">Others Applicant Source</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        <?php foreach($other as $value){
                                                                            echo $value['applicant_source'] != '' ? "<tr><td>". $value['applicant_source'] ."</td></tr>" : "";
                                                                        }?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- -->
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- table -->
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
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/Chart.bundle.min.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"  />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script>
    jQuery(function () {
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            changeYear: true,
            changeMonth: true,
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

    });

    $(document).ready(function(){
        var div_to_show = $('input[name="date_option"]').val();
        var search = '<?php echo isset($search['date_option']) ? $search['date_option'] : ''; ?>';
        if (search != '') {
            div_to_show = search;
        }
        display(div_to_show);

        $('input[name="date_option"]').change(function (e) {
            var div_to_show = $(this).val();
            display(div_to_show);
        });
    });

    function display(div_to_show) {
        if (div_to_show == 'daily') {
            $('#daily_div').show();
            $('#by_date_div').hide();
        } else {
            $('#daily_div').hide();
            $('#by_date_div').show();
        }
    }

    function fApplyDateFilters(){
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();

        var url = '<?php echo base_url();?>' + 'reports/applicant_origination_tracker_report/';

        if(startDate != '' && endDate == ''){
            url += encodeURI(startDate) + '/end-of-days/';
        }

        if(endDate != '' && startDate == ''){
            url += 'beginning-of-time/' + encodeURI(endDate) + '/';
        }

        if((startDate != '') && (endDate != '')){
            url += encodeURI(startDate) + '/' + encodeURI(endDate) + '/';
        }


        $('#form-filters').attr('action', url);
        $('#form-filters').submit();

    }

    function fClearDateFilters(){
        $('#startdate').val('');
        $('#enddate').val('');

        var url = '<?php echo base_url();?>' + 'reports/applicant_origination_tracker_report/';
        window.location = url;
    }
    //Chart Code

    window.chartColors = {
        indeed: '#3163f2',
        automotosocial: '#09f',
        ziprecruiter: '#71af05',
        juju: '#000268',
        jobstwocareers: '#ff6900',
        glassdoor: '#006C3E',
        automotohr: '#81b431',
        grey: 'rgb(231,233,237)'
    };

    var pie_config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    <?php echo $indeed_count; ?>,
                    <?php echo $automoto_social_count; ?>,
                    <?php echo $glassdoor_count; ?>,
                    <?php echo $juju_count; ?>,
                    <?php echo $automotohr_count; ?>,
                    <?php echo $zip_recruiter_count; ?>,
                    <?php echo $jobs_2_career_count; ?>,
                    <?php echo $other_count; ?>,
                ],
                backgroundColor: [
                    '<?php echo COLOR_INDEED; ?>',
                    '<?php echo COLOR_AUTOMOTOSOCIAL; ?>',
                    '<?php echo COLOR_GLASSDOOR; ?>',
                    '<?php echo COLOR_JUJU; ?>',
                    '<?php echo COLOR_AUTOMOTOHR; ?>',
                    '<?php echo COLOR_ZIPRECRUITER; ?>',
                    '<?php echo COLOR_JOBSTOCAREERS; ?>',
                    '#ddd'
                ],
                label: 'Applicant Sources'
            }],
            labels: [
                "Indeed",
                "Automoto Social",
                "Glassdoor",
                "JuJu",
                "Automoto HR",
                "ZipRecruiter",
                "Jobs2Careers",
                "Others"
            ]
        },
        options: {
            responsive: true,
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    };



    window.onload = function () {
        var ctx = document.getElementById("pie-chart").getContext("2d");
        window.myPie = new Chart(ctx, pie_config);


    };


    //Chart Code
</script>

