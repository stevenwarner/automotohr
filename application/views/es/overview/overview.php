<!-- Main page -->
<div class="csPage">
    
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt20">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
             
                <!--  -->
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="csPageBox _csB2 _csR5 _csPt20 _csPb20">
                                <h2 class="_csF28"><strong>Are your employees engaged at work?</strong></h2>
                                <p class="_csF14">We tailored some surveys to understand better your employee's engagement.</p>
                                <a href="<?php echo base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5 _csMt20"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                            </div>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">Finished Surveys (0) <span class="pull-right"><a href="" class="_csF14 _csF3">See All</a></span></h3>
                        </div>
                        <div class="panel-body">
                            <!-- Row 1 -->
                            <div class="csSurveyRow">
                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <div>
                                            <span>Employee Net Promoter Score (eNPS)</span>
                                            <span class="pull-right">Oct 28, 2022</span>
                                        </div>
                                        <div class="progress _csMt10">
                                            <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: 0%;"></div>
                                        </div>
                                        <p>0% Completed</p>
                                    </div>
                                    <div class="col-md-2 col-sm-12 text-left">
                                        <a href="" class="btn _csB4 _csF2 _csMt20 _csR5">Results</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="csSurveyRow _csBDt _csBD6 _csPt20">
                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <div>
                                            <span>Employee Net Promoter Score (eNPS)</span>
                                            <span class="pull-right">Oct 28, 2022</span>
                                        </div>
                                        <div class="progress _csMt10">
                                            <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: 70%;"></div>
                                        </div>
                                        <p>70% Completed</p>
                                    </div>
                                    <div class="col-md-2 col-sm-12 text-left">
                                        <a href="" class="btn _csB4 _csF2 _csMt20 _csR5">Results</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="csSurveyRow _csBDt _csBD6 _csPt20">
                                <div class="row">
                                    <div class="col-md-10 col-sm-12">
                                        <div>
                                            <span>Employee Net Promoter Score (eNPS)</span>
                                            <span class="pull-right">Oct 28, 2022</span>
                                        </div>
                                        <div class="progress _csMt10">
                                            <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: 35%;"></div>
                                        </div>
                                        <p>35% Completed</p>
                                    </div>
                                    <div class="col-md-2 col-sm-12 text-left">
                                        <a href="" class="btn _csB4 _csF2 _csMt20 _csR5">Results</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">Running Surveys</h3>
                        </div>
                        <div class="panel-body">
                            <p class="text-center _csPt20 _csPb20">
                                <i class="fa fa-info-circle _csF40" aria-hidden="true"></i> <br>
                                <span class="_csF16">No engagement surveys created yet.</span>
                            </p>
                        </div>
                    </div>

                    <!-- Assigned surveys -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">Assigned Surveys</h3>
                        </div>
                        <div class="panel-body">
                            <p class="text-center _csPt20 _csPb20">
                                <i class="fa fa-check _csF40" aria-hidden="true"></i> <br>
                                <span class="_csF16">You are all caught up!</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <div class="csPageSideBar _csR5 _csPb10">
                        <h3 class="_csM0 _csB4 _csP10 _csF2 _csF16">Statistics</h3>
                        <canvas id="myChart"></canvas>
                    </div>
                    <?php $this->load->view('es/partials/faqs'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!--  -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const data = {
        labels: [
            'Finished',
            'Running',
            'Assigned'
        ],
        datasets: [{
            label: 'Surveys',
            data: [5, 10, 20],
            backgroundColor: [
                'rgb(129, 180, 49)',
                'rgb(53, 84, 220)',
                'rgb(253, 122, 42)',
            ],
            hoverOffset: 4
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
    };

    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>