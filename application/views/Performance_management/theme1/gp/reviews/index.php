<?php if($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) $this->load->view('timeoff/popups/policies'); ?>


<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div>
                        <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');?>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <div>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <a href="<?=base_url('employee_profile/'.( $sid ).'');?>" class="dashboard-link-btn">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>Employee Profile
                                </a>
                                <span>Performance Management</span>
                            </span>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <!--  -->
                    <div class="">
                        <div class="pa10">
                            <!--  -->
                            <div class="csPageBox">
                                <div class="csPageBoxHeader pa10 pl10 pr10">
                                    <div class="row">
                                        <div class="col-sm-12 col-sm-12">
                                            <h3 class="csF16 csB7">Collective Report</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="csPageBody pa10 pl10 pr10">
                                    <div class="row">
                                        <div class="col-sm-12 col-sm-12">
                                            <div id="jsTimeoffPieGraph" style="width: 100%; height: 100px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="csPageBox">
                                <div class="csPageBoxHeader pa10 pl10 pr10">
                                    <div class="row">
                                        <div class="col-sm-12 col-sm-12">
                                            <ul>
                                                <li><a href="javascript:void(0)" data-id="1" class="jsVGType active">Reviews</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="csPageBoxBody p10 jsGoalWrap">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                                <th scope="table">Reviewer</th>
                                                <th scope="table">Review</th>
                                                <th scope="table">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if(!empty($myReviews)){
                                                foreach($myReviews as $review){
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="csEBox">
                                                                <figure>
                                                                    <img src="<?=$employees[$review['reviewer_sid']]['img'];?>" class="csRadius50" alt="">
                                                                </figure>
                                                                <div class="csEBoxText">
                                                                    <h4 class="mb0"><strong><?=$employees[$review['reviewer_sid']]['name'];?></strong></h4>
                                                                    <p><?=$employees[$review['reviewer_sid']]['role']?></p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?=$review['review_title'];?></td>
                                                        <td>
                                                            <a href="<?=purl('employee/review/'.( $review['sid'] ).'/'.( $review['reviewer_sid'] ).'');?>" class="btn btn-orange">View Details</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
    <?php
        //
    $employeeCategory = [];
    //
    if(!empty($allReviews['employees'])){
        foreach($allReviews['employees'] as $key => $review){
            //
            $empDetail = $employeeData[$key];
            //
            $employeeCategory[] = [
                'id' => 'jsTimeoffPieGraph',
                'data' => $review
            ];
        }
    }
    ?>

    
<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
    .highcharts-credits{ display: none;}
</style>

<script>

$(document).ready(function(){
    //
    var densen = <?=json_encode($employeeCategory);?>;
    //
    if(densen.length){
        densen.map(function(den){
            makeContainer(
                den.id,
                '',
                [
                    {
                        data: [{
                            name: 'Disagree',
                            y: den.data.disagree,
                            color: '#cc1100'
                        }]
                    },
                    {
                        data: [{
                            name: 'Neutral',
                            y: den.data.neutral,
                            color: '#0000ff'
                        }]
                    },
                    {
                        data: [{
                            name: 'Agree',
                            y: den.data.agree,
                            color: '#81b431'
                        }]
                    },
                ],{
                    xAxis: {
                        labels:{
                            enabled: false
                        }
                    }
                }
            );
        });
    }
     //
     function makeContainer(
        target,
        categories,
        data,
        additionalOptions
    ){
        //
        var options = {
            chart: {
                type: 'bar'
            },
            title: {
                text: ''
            },
            tooltip: {
            formatter: function() {
                return this.key+' '+this.y+'%';
            }
        },
            xAxis: {
                categories: categories
            },
            yAxis: {
                min: 0,
                tickInterval: 25,
                max: 100
            },
            legend: false,
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: data
        };
        //
        if(additionalOptions !== undefined){
            options = Object.assign(options, additionalOptions);
        }
        Highcharts.chart(target, options);
    }
});
</script>