<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
//$referrerChartArray = array();
//$referrerChartArray[] = array('Referral', 'Count');
$referrerChartArray[] = array('Referral', 'Count');
$appType = array();
$appType[] = array('Type', 'Count');
$domiansArray = [];
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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?php
                                                                    if (isset($flag) && $flag == true) {
                                                                        echo 'opened';
                                                                    }
                                                                    ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area" <?php
                                                                                        if (isset($flag) && $flag == true) {
                                                                                            echo "style='display:block'";
                                                                                        }
                                                                                        ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/applicant_source_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="form-col-100 field-row field-row-autoheight">
                                                    <div class="col-md-3"><b>Please Select : </b><span class="hr-required red"> * </span></div>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Company
                                                                    <input type="radio" name="company_or_brand" value="company" id="company" <?php
                                                                                                                                                if ($company_or_brand == 'company') {
                                                                                                                                                    echo 'checked';
                                                                                                                                                }
                                                                                                                                                ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">Oem,Independent,Vendor
                                                                    <input type="radio" name="company_or_brand" value="brand" id="brand" <?php
                                                                                                                                            if ($company_or_brand == 'brands') {
                                                                                                                                                echo 'checked';
                                                                                                                                            }
                                                                                                                                            ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="control control--radio">All
                                                                    <input type="radio" name="company_or_brand" value="all" id="all" <?php
                                                                                                                                        if ($company_or_brand == 'all') {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        ?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="company_div">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label class="text-left">Company : <span class="hr-required">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <?php if (sizeof($companies) > 0) { ?>
                                                                    <select name="company_sid" id="company_sid">
                                                                        <option value="all">Please Select</option>
                                                                        <?php foreach ($companies as $active_company) { ?>
                                                                            <option <?php if ($this->uri->segment(4) != 'all' && urldecode($this->uri->segment(4)) == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                                <?php echo $active_company['CompanyName']; ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <p>No company found.</p>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label class="text-left">Job Title :</label>
                                                            <div class="hr-select-dropdown">
                                                                <select multiple="multiple" name="filter_value_Title" id="job_sid">
                                                                    <option value="">Please Select Company</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="brand_div">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Oem,Independent,Vendor : <span class="hr-required">*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="hr-select-dropdown">
                                                            <?php if (sizeof($brands) > 0) { ?>
                                                                <select class="invoice-fields" name="brand_sid" id="brand_sid">
                                                                    <option value="all">Please Select</option>
                                                                    <?php foreach ($brands as $brand) { ?>
                                                                        <option <?php if ($this->uri->segment(11) != 'all' && urldecode($this->uri->segment(11)) == $brand['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $brand['sid']; ?>">
                                                                            <?php echo $brand['oem_brand_name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <p>No Oem,Independent,Vendor found.</p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="filter_method_Title" id="filter_method_Title" value="equals" />

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <?php $keyword = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                        <label>Keyword</label>
                                                        <input placeholder="John Doe" class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label>Applicant Type</label>
                                                        <?php $applicant_type = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="applicant_type" id="applicant_type" class="invoice-fields">
                                                                <?php if (!empty($applicant_types)) { ?>
                                                                    <option value="all">All</option>
                                                                    <?php foreach ($applicant_types as $type) { ?>
                                                                        <option <?php echo set_select('applicant_type', $type, $applicant_type == $type); ?> value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <option <?php echo $applicant_type == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                    <option <?php echo $applicant_type == 'Applicant' ? 'selected="selected"' : ''; ?> value="Applicant">Applicant</option>
                                                                    <option <?php echo $applicant_type == 'Talent Network' ? 'selected="selected"' : ''; ?> value="Talent Network">Talent Network</option>
                                                                    <option <?php echo $applicant_type == 'Manual Candidate' ? 'selected="selected"' : ''; ?> value="Manual Candidate">Manual Candidate</option>
                                                                    <option <?php echo $applicant_type == 'Job Fair' ? 'selected="selected"' : ''; ?> value="Job Fair">Job Fair</option>
                                                                    <option <?php echo $applicant_type == 'Re-Assigned Candidates' ? 'selected="selected"' : ''; ?> value="Re-Assigned Candidates">Re-Assigned Candidates</option>
                                                                <?php } ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="">Date From</label>
                                                        <?php $start_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="">Date To</label>
                                                        <?php $end_date = $this->uri->segment(10) != 'all' && $this->uri->segment(10) != '' ? urldecode($this->uri->segment(10)) : date('m-d-Y'); ?>
                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                    </div>
                                                </div>
                                                <div class="field-row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Source : </label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="hr-select-dropdown">
                                                            <?php $source = $this->uri->segment(12) != 'all' ? urldecode($this->uri->segment(12)) : ''; ?>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" name="source" id="source">
                                                                    <option value="all">Please Select</option>
                                                                    <option value="automotosocial" <?php if ($source == 'automotosocial') { ?> selected="selected" <?php } ?>>Automoto Social</option>
                                                                    <option value="autocareers" <?php if ($source == 'autocareers') { ?> selected="selected" <?php } ?>>Auto Careers</option>
                                                                    <option value="jobs2careers" <?php if ($source == 'jobs2careers') { ?> selected="selected" <?php } ?>>Jobs2Careers</option>
                                                                    <option value="ziprecruiter" <?php if ($source == 'ziprecruiter') { ?> selected="selected" <?php } ?>>ZipRecruiter</option>
                                                                    <option value="glassdoor" <?php if ($source == 'glassdoor') { ?> selected="selected" <?php } ?>>Glassdoor</option>
                                                                    <option value="indeed" <?php if ($source == 'indeed') { ?> selected="selected" <?php } ?>>Indeed</option>
                                                                    <option value="juju" <?php if ($source == 'juju') { ?> selected="selected" <?php } ?>>Juju</option>
                                                                    <option value="careerwebsite" <?php if ($source == 'careerwebsite') { ?> selected="selected" <?php } ?>>Career Website</option>
                                                                    <option value="careerbuilder" <?php if ($source == 'careerbuilder') { ?> selected="selected" <?php } ?>>Career Builder</option>
                                                                    <option value="others" <?php if ($source == 'others') { ?> selected="selected" <?php } ?>>Others</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field-row field-row-autoheight col-lg-12 text-right">
                                                    <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                    <!--                                                    <input type="submit" class="btn btn-success" value="Apply Filters" name="submit" id="btn_apply_filters">-->
                                                    <a class="btn btn-success" href="<?php echo base_url('manage_admin/reports/applicant_source_report'); ?>">Reset Filters</a>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="row">
                                        <div id="applicant_graph" style="display: none">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div id="referral_div" style="width: 100%; height: 300px;"></div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div id="type_div" style="width: 100%; height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- *** table *** -->
                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                        Print
                                                    </a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit">
                                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                            Export To Excel
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <span class="pull-left">
                                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                </span>
                                                <?php if (!empty($page_links)) { ?>
                                                    <span class="pull-right">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Source Report</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo sizeof($applicants); ?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-box-header">
                                            <h1 class="hr-registered">Applicants Source Report</h1>
                                        </div>
                                        <div class="table-responsive hr-innerpadding" id="print_div">
                                            <table class="table table-bordered" id="example">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-1">Date Applied</th>
                                                        <th class="col-xs-1">Applicant</th>
                                                        <th class="col-xs-2">Job Title</th>
                                                        <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                            <th class="col-xs-1">Company Name</th>
                                                        <?php } ?>
                                                        <th class="col-xs-3">Applicant Source</th>
                                                        <th class="col-xs-1">IP Address</th>
                                                        <th class="col-xs-1">Applicant Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (isset($applicants) && sizeof($applicants) > 0) {
                                                        $count = 0; ?>
                                                        <?php foreach ($applicants as $applicant) {

                                                            if (!empty($applicant['applicant_type'])) {
                                                                if (!isset($appType[$applicant['applicant_type']])) {
                                                                    $appType[$applicant['applicant_type']] = array($applicant['applicant_type'], 1);
                                                                } else {
                                                                    $appType[$applicant['applicant_type']][1] = $appType[$applicant['applicant_type']][1] + 1;
                                                                }
                                                            } ?>
                                                            <tr>
                                                                <td><?php echo convert_date_to_frontend_format($applicant['date_applied']); ?></td>
                                                                <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                <td><?php
                                                                    $city = '';
                                                                    $state = '';
                                                                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                        $city = ' - ' . ucfirst($applicant['Location_City']);
                                                                    }
                                                                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                        $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                                    }
                                                                    echo  ucwords($applicant['Title'] . $city . $state);
                                                                    ?>
                                                                </td>
                                                                <?php if ($company_or_brand == 'brands' || $company_or_brand == 'all') { ?>
                                                                    <td><?php echo ucwords($applicant['CompanyName']); ?></td>
                                                                <?php } ?>
                                                                <td>
                                                                    <div class="table-responsive applicant_source_link_in_table">
                                                                        <!--                                                                        --><?php //echo $applicant['applicant_source']; 
                                                                                                                                                        ?>
                                                                        <?php
                                                                        $a = domainParser($applicant['applicant_source'], $applicant['main_referral'], true);
                                                                        if (is_array($a)) {
                                                                            $a['sid'] = $applicant['applicant_sid'];
                                                                            // Set chart array
                                                                            if (!isset($referrerChartArray[$a['ReferrerSource']])) {
                                                                                $referrerChartArray[$a['ReferrerSource']] = array($a['ReferrerSource'], 1);
                                                                            } else {
                                                                                $referrerChartArray[$a['ReferrerSource']][1] = $referrerChartArray[$a['ReferrerSource']][1] + 1;
                                                                            }
                                                                            echo $a['Text'] . '<a class="btn btn-link" href="javascript:;" data-html="true" data-toggle="popover" data-placement="left" data-content="Source: ' . $a['ReferrerSource'] . '<br /> Source URL: ' . $a['Original']['Source'] . (!empty($a['Original']['Referrer']) ? (' <br />Referrer: ' . $a['Original']['Referrer']) : '') . '">View More</a>';
                                                                        } else {
                                                                            if ($a == 'N/A') {
                                                                                $referrerChartArray['Direct'] = array('Direct', ++$count);
                                                                                echo '<b>Direct</b>';
                                                                            } else {
                                                                                echo $a;
                                                                            }
                                                                        }
                                                                        $domiansArray[] = $a; ?>
                                                                    </div>
                                                                </td>
                                                                <td><?php echo $applicant['ip_address']; ?></td>
                                                                <td><?php echo $applicant['applicant_type']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td class="text-center" colspan="<?php
                                                                                                if ($company_or_brand == 'brands' || $company_or_brand == 'all') {
                                                                                                    echo '7';
                                                                                                } else {
                                                                                                    echo '6';
                                                                                                }
                                                                                                ?>">
                                                                <?php if (!isset($applicants)) { ?>
                                                                    <div class="no-data">Please select company...</div>
                                                                <?php } else if (isset($applicants) && sizeof($applicants) <= 0) { ?>
                                                                    <div class="no-data">No applicants found.</div>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <span class="pull-left">
                                                    <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                </span>
                                                <?php if (!empty($page_links)) { ?>
                                                    <span class="pull-right">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- *** table *** -->
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    var applicant_count = <?= $applicants_count ?>;
    if (applicant_count > 0) {
        $('#applicant_graph').show();
    }
    $(document).ready(function() {
        $('.btn-link').hover(function() {
            $(this).popover('show');
        }, function() {
            $(this).popover('hide');
        });
        var company_selectize = $('#company_sid').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        var jobs_selectize = $('#job_sid').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        load_jobs();
        var job_select = jobs_selectize[0].selectize;

        $('body').on('change', '#company_sid', function() {
            var selected = $(this).val();
            var my_data = {
                'company_sid': selected,
                'perform_action': 'load_jobs'
            };
            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/reports/applicants_report/ajax_responder'); ?>",
                data: my_data
            });

            myRequest.done(function(response) {

                data = $.parseJSON(response);
                job_select.clearOptions();
                job_select.load(function(callback) {
                    var arr = [{}];
                    var j = 0;
                    arr[j++] = {
                        value: 'all',
                        text: 'All Jobs'
                    };
                    $.each(data, function(i, item) {
                        var job_sid = item.sid;
                        var job_title = item.Title;
                        var status = item.active;
                        if (status == 1) {
                            job_title = job_title + ' (Active)';
                        } else {
                            job_title = job_title + ' (In Active)';
                        }
                        arr[j++] = {
                            value: job_sid,
                            text: job_title
                        }

                    });
                    callback(arr);
                    var selected_job = '<?php echo $job_sid_array; ?>';
                    console.log(selected_job);
                    selected_job = selected_job.split(',');
                    $.each(selected_job, function(i, item) {
                        job_select.addItems(item);
                    });
                    job_select.refreshItems();
                });
            });
        });

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        google.load("visualization", "1", {
            packages: ["corechart"]
        });
        google.setOnLoadCallback(drawGraph);

        function drawGraph() {
            var data = google.visualization.arrayToDataTable(<?= json_encode(array_values($referrerChartArray)) ?>);
            var options = {
                title: 'Referrals',
                is3D: true,
                legend: {
                    position: 'top',
                    maxLines: 3
                }
            };
            var chart = new google.visualization.PieChart(document.getElementById('referral_div'));
            chart.draw(data, options);
        }
        google.load("visualization", "1", {
            packages: ["corechart"]
        });
        google.setOnLoadCallback(drawTypeGraph);

        function drawTypeGraph() {
            var data = google.visualization.arrayToDataTable(<?= json_encode(array_values($appType)) ?>);
            var options = {
                title: 'Applicant Type',
                is3D: true,
                legend: {
                    position: 'top',
                    maxLines: 3
                }
            };
            var chart = new google.visualization.PieChart(document.getElementById('type_div'));
            chart.draw(data, options);
        }
        var div_to_show = $('input[name="company_or_brand"]:checked').val();
        if (div_to_show == 'brand') {
            //            $('#company_sid').val('all');
            //            $('#company_sid').removeProp('disabled','disabled');
            $('#brand_sid').removeProp('disabled', 'disabled');
        } else if (div_to_show == 'company') {
            $('#brand_sid').val('all');
            //            $('#company_sid').removeProp('disabled','disabled');
            $('#brand_sid').removeProp('disabled', 'disabled');
            $('#company_sid').trigger('change');
        } else {
            //            $('#company_sid').attr('disabled','disabled');
            //            $('#company_sid').val('all');
            $('#brand_sid').attr('disabled', 'disabled');
            $('#brand_sid').val('all');
        }
        display(div_to_show);

        $('#filter_value_date_applied').datepicker({
            dateFormat: 'mm-dd-yy'
        });

        $("#company_sid").change(function() {
            load_jobs();
            generate_search_url();
        });

        $('#job_sid').change(function() {
            generate_search_url();
        });
        $('#applicant_type').change(function() {
            generate_search_url();
        });
        $('#brand_sid').change(function() {
            generate_search_url();
        });
        $('#source').change(function() {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('input[name="company_or_brand"]').change(function(e) {
            var company_or_brand = $(this).val();
            if (company_or_brand == 'brand') {
                $('#company_sid').val('all');
                $('#company_sid').removeProp('disabled', 'disabled');
                $('#brand_sid').removeProp('disabled', 'disabled');
            } else if (company_or_brand == 'company') {
                $('#brand_sid').val('all');
                $('#company_sid').removeProp('disabled', 'disabled');
                $('#brand_sid').removeProp('disabled', 'disabled');
            } else {
                $('#company_sid').attr('disabled', 'disabled');
                $('#brand_sid').attr('disabled', 'disabled');
                $('#company_sid').val('all');
                $('#brand_sid').val('all');
                generate_search_url();
            }
            display(company_or_brand);
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
                //console.log(value);
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
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });

    function generate_search_url() {
        var company_sid = $("#company_sid").val();
        var brand_sid = $("#brand_sid").val();
        var keyword = $('#keyword').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var source = $('#source').val();
        var all = 0;

        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        brand_sid = brand_sid != '' && brand_sid != null && brand_sid != undefined && brand_sid != 0 ? encodeURIComponent(brand_sid) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        source = source != '' && source != null && source != undefined && source != 0 ? encodeURIComponent(source) : 'all';

        if ($('input[name="company_or_brand"]:checked').val() == 'all') {
            company_sid = 'all';
            brand_sid = 'all';
            job_sid = 'all';
            all = 1;
        } else if ($('input[name="company_or_brand"]:checked').val() == 'company') {
            brand_sid = 'all';
        } else if ($('input[name="company_or_brand"]:checked').val() == 'oem') {
            company_sid = 'all';
            job_sid = 'all';
        }

        //        var url = '<?php //echo base_url('manage_admin/reports/applicant_source_report'); 
                                ?>//' + '/' + company_sid + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/all/' + start_date_applied + '/' + end_date_applied + '/' + brand_sid + '/' + all + '/' + source;
        var url = '<?php echo base_url('manage_admin/reports/applicant_source_report'); ?>' + '/' + company_sid + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/all/' + start_date_applied + '/' + end_date_applied + '/' + brand_sid + '/' + source;

        $('#btn_apply_filters').attr('href', url);
    }

    function load_jobs() {
        var company_sid = $("#company_sid").val();

        if (company_sid == 0 || company_sid == '') {
            $('#job_sid').find('option').remove().end();
            $('#job_sid').append('<option value="">Please Select Company</option>');
        } else {
            data = {
                'company_sid': company_sid,
                'perform_action': 'load_jobs'
            };

            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/reports/applicant_source_report/ajax_responder'); ?>",
                data: data
            });

            myRequest.done(function(response) {
                $('#job_sid').find('option').remove().end();
                $('#job_sid').append('<option value="">All Jobs</option>');

                data = $.parseJSON(response);
                var selected_job = '<?php echo $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>';

                $.each(data, function(i, item) {
                    var job_sid = item.sid;
                    var job_title = item.Title;

                    if (selected_job != '' && selected_job == job_sid) {
                        $('#job_sid').append('<option value="' + job_sid + '" selected>' + job_title + '</option>');
                    } else {
                        $('#job_sid').append('<option value="' + job_sid + '">' + job_title + '</option>');
                    }
                });
            });
        }
    }

    $('#apply_filters_submit').click(function() {
        $("#search").validate({
            ignore: [],
            rules: {
                company_sid: {
                    required: function(element) {
                        return $('input[name=company_or_brand]:checked').val() == 'company';
                    }
                },
                brand_sid: {
                    required: function(element) {
                        return $('input[name=company_or_brand]:checked').val() == 'brand';
                    }
                },
                company_or_brand: {
                    required: true,
                }
            },
            messages: {
                company_sid: {
                    required: 'Company name is required'
                },
                brand_sid: {
                    required: 'Brand name is required'
                },
                company_or_brand: {
                    required: 'Please select one of the options'
                }
            }
        });
    });

    function display(div_to_show) {
        if (div_to_show == 'company') {
            $('#company_div').show();
            $('#brand_div').hide();
        } else if (div_to_show == 'brand') {
            $('#company_div').hide();
            $('#brand_div').show();
        } else {
            $('#company_div').hide();
            $('#brand_div').hide();
        }
    }


    function print_page(elem) {
        $('table').removeClass('horizontal-scroll');
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
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
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
        $('table').addClass('horizontal-scroll');
    }
</script>