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
                                    <i class="fa fa-chevron-left"></i>Employee Profile
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
                                        <canvas id="jsTimeoffPieGraph" height="500"></canvas>
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
                                            <tr>
                                                <td><div class="csEBox">
                                                <figure>
                                                    <img src="http://automotohr.local/assets/images/img-applicant.jpg" class="csRadius50" alt="">
                                                </figure>
                                                <div class="csEBoxText">
                                                    <h4 class="mb0"><strong>Mubashir Ahmed</strong></h4>
                                                    <p> (Manager) [Admin]</p>
                                                </div>
                                            </div></td>
                                                <td>Review 101</td>
                                                <td>
                                                    <a href="<?=purl('employee/review/2/11756');?>" class="btn btn-orange">Show Feedback</a>
                                                </td>
                                            </tr>
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

    
<script>

$(document).ready(function(){

    var data = [0,0,0,0,0];
    if("<?=$employerId;?>" == '11757'){
        data = [2,3,4,5,6];
    }

    loadHourGraph();
    function loadHourGraph() {
        new Chart(document.getElementById('jsTimeoffPieGraph'), {
            type: 'bar',
            data: {
                datasets: [{
                    data: [
                        '5',
                        '2',
                        '2',
                        3,
                        5
                    ],
                    backgroundColor: [
                        '#81b431',
                        '#81b435',
                        '#000',
                        '#cc1100',
                        '#cc1111'
                    ],
                    borderColor: [
                        '#81b431',
                        '#81b435',
                        '#000',
                        '#cc1100',
                        '#cc1111'
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Strongly Agree`,
                    `Agree`,
                    `Neutral`,
                    `Disagree`,
                    `Strongly Disagree`
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true
            },
        });
    }

});
</script>