<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$referrerChartArray = array();
$referrerChartArray[] = array('Referral', 'Count');
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
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="row">
                                            <div id="applicant_graph" style="display: none">
                                                <div class="hr-search-criteria opened">
                                                    <strong>Source Report Chart</strong>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div id="city_div" style="width: 100%; height: 300px;"></div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div id="state_div" style="width: 100%; height: 300px;"></div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <div id="referral_div" style="width: 100%; height: 300px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/jsapi.js"></script> -->
                                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                                                <?php if(!empty($all_applicants)) { $count = 0; ?>
                                                    <?php foreach($all_applicants as $applicant) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?>
                                                                <?php echo '<br><b>'.$applicant['email'].'</b>'; ?>
                                                            </td>
                                                            <?php
                                                            $city = '';
                                                            $state='';
                                                            if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                                                $city = ' - '.ucfirst($applicant['Location_City']);
                                                            }
                                                            if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                                                $state = ', '.db_get_state_name($applicant['Location_State'])['state_name'];
                                                            }
                                                            ?>
                                                            <td><?php echo $applicant['job_title']; ?><?php echo $city . $state; ?></td>
                                                            <td><?php echo $applicant['CompanyName'];?></td>
                                                            <td><?php echo $applicant['ip_address']; ?><br>
                                                                <?php echo '<b>Time: </b><br>'. date('h:i A',strtotime($applicant['date_applied'])); ?>
                                                            </td>
                                                            <td>
                                                                <div class="table-responsive applicant_source_link_in_table">
                                                                    <?php
                                                                        $a = domainParser($applicant['applicant_source'], $applicant['main_referral'], true);
                                                                        if(is_array($a)){
                                                                            $a['sid'] = $applicant['sid'];
                                                                            // Set chart array
                                                                            if(!isset($referrerChartArray[$a['ReferrerSource']])){
                                                                                $referrerChartArray[$a['ReferrerSource']] = array($a['ReferrerSource'], 1);
                                                                            }else{
                                                                                $referrerChartArray[$a['ReferrerSource']][1] = $referrerChartArray[$a['ReferrerSource']][1] + 1;
                                                                            }
                                                                            echo $a['Text'] . '<a class="btn btn-link" href="javascript:;" data-html="true" data-toggle="popover" data-placement="left" data-content="Source: '.$a['ReferrerSource'].'<br /> Source URL: '.$a['Original']['Source']. (!empty($a['Original']['Referrer']) ? (' <br />Referrer: '.$a['Original']['Referrer']):'').'">View More</a>';
                                                                        } else{
                                                                            if($a == 'N/A'){
                                                                                $referrerChartArray['Direct'] = array('Direct', ++$count);
                                                                                echo '<b>Direct</b>';
                                                                            }else{
                                                                                echo $a;
                                                                            }
                                                                        }
                                                                        $domiansArray[] = $a;
                                                                    ?>
<!--                                                                    <button class="btn btn-link js-source-report" data-id="--><?//=$applicant['sid'];?><!--">View More</button>-->
                                                                </div>
                                                            </td>
                                                            <!--<td><?php //echo date('h:i A',strtotime($applicant['date_applied'])); ?></td>-->
                                                        </tr>
                                                    <?php }?>
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
    $(document).ready(function(){
        $('.btn-link').hover(function(){
            $(this).popover('show');
        },function(){
            $(this).popover('hide');
        });
    });
    var applicant_count = <?= $applicant_count?>;
    if(applicant_count > 0){
        $('#applicant_graph').show();
    }
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(draw_city_area_chart);

    function draw_city_area_chart() {
        var data = google.visualization.arrayToDataTable(<?php echo $citychart; ?>);
        var options = {
            title: 'Cities',
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
            title: 'States',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        var chart = new google.visualization.PieChart(document.getElementById('state_div'));
        chart.draw(data, options);
    }

    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawGraph);

    function drawGraph() {
        var data = google.visualization.arrayToDataTable(<?=json_encode(array_values($referrerChartArray))?>);
        var options = {
            title: 'Referrals',
            is3D: true,
            legend: {position: 'top', maxLines: 3}
        };
        var chart = new google.visualization.PieChart(document.getElementById('referral_div'));
        chart.draw(data, options);
    }

    $(function(){
        //
        $('.js-source-report').on('click',function(e){
            console.log('here');
            e.preventDefault();
            var source = getData($(this).data('id'));
            console.log(source, $(this).data('id'));
            if(source.length == 0) {
                return;
            }
            var rows = `
            <div class="modal fade" id="js-source-modal-id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Applicant Source Report</h4>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Source URL:</strong>
                                                <p class="js-md-asl">${source.Original.Source}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Referrer URL:</strong>
                                                <p class="js-md-arl">${source.Original.Referral}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Source:</strong>
                                                <p class="js-md-as">${source.ReferrerSource}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;
            //
            $('#js-source-modal-id').remove();
            $('body').append(rows);
            $('#js-source-modal-id').modal();
        });


        function getData(sid){
            var i = 0,
            data = <?=json_encode($domiansArray);?>,
            l = data.length;
            for(i; i < l; i++){
                if(data[i]['sid'] == sid) return data[i];
            }
            return [];
        }
    })

</script>