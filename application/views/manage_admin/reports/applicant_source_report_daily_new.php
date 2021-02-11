<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
    if(!function_exists('domainParser')){
        function domainParser($domain){
            $domain = preg_replace('/(http|https):\/\/(www.)?/', '', $domain);
            return ucwords(explode('.', $domain)[0]);
        }
    }
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
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="row">
                                            <div id="applicant_graph" style="display: none">
                                                <div class="hr-search-criteria opened">
                                                    <strong>Source Report Chart</strong>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div id="city_div" style="width: 100%; height: 300px;"></div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div id="state_div" style="width: 100%; height: 300px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script type="text/javascript" src="<?= base_url() ?>assets/js/jsapi.js"></script>
                                            <div class="bt-panel">
                                                <a href="<?php echo $filter_button_url;?>" class="btn btn-success"><i aria-hidden="true"></i><?php echo $filter_button_text;?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <h2 class="hr-registered"><?php echo convert_date_to_frontend_format($filter_date);?> Total Applicants: <?php echo sizeof($all_applicants); ?></h2>
                                        </div>

                                        <div class="table-responsive hr-innerpadding" id="print_div">
                                            <table class="table table-bordered" id="example">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-1">Name / Email</th>
                                                        <th class="col-lg-3">Job Title</th>
                                                        <th class="col-lg-3">Company Name</th>
                                                        <th class="col-lg-1">IP Address</th>
                                                        <th class="col-lg-2">Applicant Source</th>
<!--                                                        <th class="col-lg-2">Time</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(!empty($all_applicants)) { ?>
                                                    <?php foreach($all_applicants as $applicant) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                                <?php echo '<br><b>'.$applicant['email'].'</b>'; ?>
                                                            </td>
                                                            <td><?php echo $applicant['job_title']; ?> - <?php echo $applicant['city']; ?>, <?php echo $applicant['state']; ?></td>
                                                            <td><?php echo $applicant['CompanyName'];?></td>
                                                            <td><?php echo $applicant['ip_address']; ?><br>
                                                                <?php echo '<b>Time: </b><br>'. date('h:i A',strtotime($applicant['date_applied'])); ?>
                                                            </td>
                                                            <td>
                                                                <div class="table-responsive applicant_source_link_in_table">
                                                                    <?php
                                                                        $applicantSource = domainParser($applicant['applicant_source']);
                                                                        if($applicant['main_referral'] != null && $applicant['main_referral'] != 'null'){
                                                                            $applicantSource = domainParser($applicant['main_referral']);
                                                                            echo  "https://www.automotosocial.com/";
                                                                            if($applicantSource != 'Automotosocial') echo  "<br /><b>(".$applicantSource.")</b>";
                                                                        } else{
                                                                           echo  "https://www.automotosocial.com/";
                                                                           if($applicantSource != 'Automotosocial') echo  "<br /><b>(".$applicantSource.")</b>";
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <!--<td><?php //echo date('h:i A',strtotime($applicant['date_applied'])); ?></td>-->
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">
                                                            <span class="no-data">No Applicants Found</span>
                                                        </td>
                                                    </tr>
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
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    var applicant_count = <?= $applicant_count?>;
    if(applicant_count > 0){
        $('#applicant_graph').show();
    }
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(draw_city_area_chart);

    function draw_city_area_chart() {
        var data = google.visualization.arrayToDataTable(<?php echo $citychart; ?>);
        var options = {
            title: '',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        var chart = new google.visualization.PieChart(document.getElementById('city_div'));
        chart.draw(data, options);
    }

    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(draw_state_area_chart);

    function draw_state_area_chart() {
        var data = google.visualization.arrayToDataTable(<?php echo $statechart; ?>);
        var options = {
            title: '',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        var chart = new google.visualization.PieChart(document.getElementById('state_div'));
        chart.draw(data, options);
    }
</script>
