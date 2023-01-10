<style>
    .highcharts-figure .chart-container {
        width: 300px;
        height: 200px;
        float: left;
    }

    .highcharts-figure,
    .highcharts-data-table table {
        width: 600px;
        margin: 0 auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    @media (max-width: 600px) {

        .highcharts-figure,
        .highcharts-data-table table {
            width: 100%;
        }

        .highcharts-figure .chart-container {
            width: 300px;
            float: none;
            margin: 0 auto;
        }
    }



    #container {
        height: 185px;
    }

    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 320px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>

<script src="<?= base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.js'); ?>"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>
                    <!--  Filters -->
                    <div class="row">

                        <div class="col-sm-4 col-sm-4 col-xs-12">
                            <label class="_csF14">Filter by Department</label>
                            <select id="jsDepartmentFilter" multiple>
                            </select>
                        </div>

                        <div class="col-sm-4 col-sm-4 col-xs-12">
                            <label class="_csF14">Filter by Gender</label>
                            <select id="jsGenderFilter" multiple>
                                <option value="male">Males </option>
                                <option value="female">Female </option>
                                <option value="other">Other </option>
                            </select>
                        </div>

                        <div class="col-sm-4 col-sm-4 col-xs-12">
                            <label class="_csF14">Filter Tenure Band</label>
                            <select id="jsTenureFilter" multiple>
                                <option value="1">Less then 3 months</option>
                                <option value="2">3-6 months</option>
                                <option value="2">6-12 months</option>
                                <option value="2">1-2 years</option>
                                <option value="2">6-10 years</option>
                            </select>
                        </div>

                    </div>

                    <!--  Graph Survey Result -->
                    <div class="row _csMt10">
                        <div class="col-sm-5 col-sm-5 col-xs-12">
                            <div class="panel panel-default  _csMt10">
                                <div class="panel-heading">
                                    <p class="_csF14 "><b>Survey Results</b></p>
                                </div>
                                <div class="panel-body jsPageBody" data-page="visibility">
                                    <figure class="highcharts-figure">
                                        <div id="surveyresults" class="chart-container"></div>

                                    </figure>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-7 col-sm-7 col-xs-12">
                            <div class="panel  panel-default _csMt10">
                                <div class="panel-heading">
                                    <p class="_csF14 "><b>Response Rate</b></p>
                                </div>
                                <div class="panel-body jsPageBody" data-page="visibility">
                                    <figure class="highcharts-figure">
                                        <div id="container"></div>
                                        <p class="highcharts-description"> </p>

                                    </figure>


                                </div>

                            </div>
                        </div>

                    </div>

                    <!--  Overall Score -->
                    <div class="row _csMt10">
                        <div class="col-sm-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default  _csMt10">
                                <div class="panel-heading">
                                    <p class="_csF14 "><b>Overall Score per Question</b></p>
                                </div>
                                <div class="panel-body" id="jsOverallScorePerQuestion">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--  Engagment Score -->
                    <div class="row _csMt10">
                        <div class="col-sm-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default _csMt10">
                                <div class="panel-heading">
                                    <p class="_csF14 "><b>Engagment Score Heatmap</b></p>
                                </div>
                                <div class="panel-body jsPageBody" data-page="visibility">

                                    <div class="row">

                                        <div class="col-sm-4 col-sm-4 col-xs-12">
                                            <label class="_csF12">Department</label>
                                            <select id="jsDepartmentFilter2" multiple>

                                            </select>
                                        </div>
                                    </div>


                                    <table class="table table-striped table-bordered">
                                        <caption>Questions/Number of Responses.</caption>
                                        <tbody>
                                            <tr>
                                                <td class="col-sm-10">1 . testing testing testijn g</td>
                                                <td class="col-sm-1">68%</td>
                                                <td class="col-sm-1">aa</td>
                                            </tr>
                                            <tr>
                                                <td class="col-sm-10">2 . testing testing testijn g</td>
                                                <td class="col-sm-1">70%</td>
                                                <td class="col-sm-1">2</td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--  Response Distribution -->
                    <div class="row _csMt10">
                        <div class="col-sm-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default _csMt10">
                                <div class="panel-heading">
                                    <p class="_csF14 "><b>Response Distribution</b></p>
                                </div>
                                <div class="panel-body jsPageBody" data-page="visibility">
                                    <figure class="highcharts-figureresponse">
                                        <span>Question 1 <br>
                                            testing question111
                                        </span>

                                        <div id="barchart1" style="height: 130px;"></div>
                                        <script>
                                            Highcharts.chart('barchart1', {
                                                chart: {
                                                    type: 'bar'
                                                },
                                                credits: {
                                                    enabled: false
                                                },
                                                exporting: {
                                                    enabled: false
                                                },
                                                title: {
                                                    text: ''
                                                },
                                                xAxis: {
                                                    categories: ['']
                                                },
                                                yAxis: {
                                                    min: 0,
                                                    title: {
                                                        text: 'Percentage of respondents.'
                                                    }
                                                },
                                                legend: {
                                                    reversed: true
                                                },
                                                plotOptions: {
                                                    series: {
                                                        stacking: 'normal'
                                                    }

                                                },
                                                legend: {
                                                    layout: 'horizontal',
                                                    align: 'right',
                                                    verticalAlign: 'top',
                                                },

                                                series: [{
                                                    name: 'Disagree',
                                                    color: '#000000',
                                                    data: [4]
                                                }, {
                                                    name: 'Neutral',
                                                    data: [5]
                                                }, {

                                                    name: 'Agree',
                                                    color: '#fd7a2a',
                                                    data: [8]
                                                }]
                                            });
                                        </script>
                                    </figure>

                                    <hr>


                                    <span>Question 2 <br>
                                        testing question111 dfgdfg dfg
                                    </span>
                                    <figure class="highcharts-figureresponse">
                                        <div id="barchart2" style="height: 95px;"></div>
                                        <script>
                                            Highcharts.chart('barchart2', {
                                                chart: {
                                                    type: 'bar'
                                                },
                                                credits: {
                                                    enabled: false
                                                },
                                                exporting: {
                                                    enabled: false
                                                },
                                                title: {
                                                    text: ''
                                                },
                                                xAxis: {
                                                    categories: ['']
                                                },
                                                yAxis: {
                                                    min: 0,
                                                    max:100,
                                                    title: {
                                                        text: 'Percentage of respondents.'
                                                    }
                                                },
                                                legend: {
                                                    reversed: true
                                                },
                                                plotOptions: {
                                                    series: {
                                                        stacking: 'normal'
                                                    }

                                                },
                                                series: [{
                                                    showInLegend: false,
                                                    name: 'Disagree',
                                                    color: '#000000',
                                                    data: [14]
                                                }, {
                                                    showInLegend: false,
                                                    name: 'Neutral',
                                                    data: [68]
                                                }, {
                                                    showInLegend: false,
                                                    name: 'Agree',
                                                    color: '#fd7a2a',
                                                    data: [18]
                                                }]
                                            });
                                        </script>

                                    </figure>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>