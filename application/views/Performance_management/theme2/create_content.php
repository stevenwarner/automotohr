<div class="col-md-9 col-sm-12">
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Create A Review <span>: Quaterly Review 101</span>
                    </h5>
                </div>
            </div>
        </div>

        <div class="panel-body pa0 pl0 pr0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="arrow-links">
                        <ul>
                            <li style="width: 20%;" class="active">
                                <a href="">
                                    <span class="csF16 csB7">Schedule</span>
                                    <div class="step-text">Getting Started</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="">
                                    <span class="csF16 csB7">Reviewee(s)</span>
                                    <div class="step-text">Select Reviewee(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="">
                                    <span class="csF16 csB7">Reviewer(s)</span>
                                    <div class="step-text">Select Reviewer(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="">
                                    <span class="csF16 csB7">Question(s)</span>
                                    <div class="step-text">Add Question(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="">
                                    <span class="csF16 csB7">Share Feedback</span>
                                    <div class="step-text">Select Feedback</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--  -->
            <br>
            <div class="jsPageContainer p10">
<<<<<<< HEAD
                <!-- Step 1 -->
                <div class="jsPageSection" data-page="getting_started">
                    <!-- Template -->
                    <div class="jsPageSection dn" data-page="template">
                        <?php $this->load->view("{$pp}template"); ?>
                    </div>
                    <!-- Basic -->
                    <div class="jsPageSection dn" data-page="schedule">
                        <?php $this->load->view("{$pp}schedule"); ?>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="jsPageSection" data-page="reviewees">
                    <!-- Reviewees -->
                    <?php $this->load->view("{$pp}reviewees"); ?>
                </div>
=======
                <?php $this->load->view("{$pp}template"); ?>
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
            </div>
        </div>
    </div>
