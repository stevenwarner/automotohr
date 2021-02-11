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
                            'Text' => 'Performance Review - Statics'
                        ]); ?>

                        
                    </div>

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <div class="applicant-applied">
                        <div class="applicant-graphic-info">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="graphical-info">
                                    <div id="piechart" style="width: 100%; height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $chart; ?>);
        var options = {
            title: '',
            is3D: true,
            legend: {position: 'top', maxLines: 3},
            slices: {
                0: { color: '#0000FF' },
                1: { color: '#980b1e' },
                2: { color: '#81b431' },
                3: { color: '#b4048a' }
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }    
</script>