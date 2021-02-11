<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('dashboard'),
                                'Dashboard',
                            ],
                            'Text' => 'Performance Review - Report'
                        ]); ?>


                        <div class="clearfix"></div>
                        <!-- Table -->

                        <div class="cs-prpage" style="margin-top: 10px;">
                            <!-- Company Employee Graph -->
                            <div class="row">
                                <!-- Review Status Graph -->
                                <div class="col-sm-6">
                                    <div id="jsReviewStatusChart"></div>
                                </div>
                                <!-- Review Status Graph -->
                                <div class="col-sm-6">
                                    <div id="jsReviewScoreChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {
    'packages': ['corechart']
});
//
google.charts.setOnLoadCallback(drawReviewStatusChart);
function drawReviewStatusChart() {

    var data = google.visualization.arrayToDataTable(<?=json_encode($report['Review']);?>);

    var options = {
       title: 'Review(s) Status'
    };

    var chart = new google.visualization.PieChart(document.getElementById('jsReviewStatusChart'));
    chart.draw(data, options);
}
//
google.charts.setOnLoadCallback(drawReviewScoreChart);
function drawReviewScoreChart() {

    var data = google.visualization.arrayToDataTable(<?=json_encode($report['Rating']);?>);

    var options = {
       title: 'Review(s) Score'
    };

    var chart = new google.visualization.BarChart(document.getElementById('jsReviewScoreChart'));
    chart.draw(data, options);
}
</script>