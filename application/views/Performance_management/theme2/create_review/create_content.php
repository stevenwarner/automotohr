<div class="col-md-9 col-sm-12">
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <h5 class="csF16 csB7 csW jsToggleHelp" data-target="assigned_reviews">
                        Create A Review <span id="jsReviewTitleTxt"></span>
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
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Schedule</span>
                                    <div class="step-text">Getting Started</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Reviewee(s)</span>
                                    <div class="step-text">Select Reviewee(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Reviewer(s)</span>
                                    <div class="step-text">Select Reviewer(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Question(s)</span>
                                    <div class="step-text">Add Question(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;">
                                <a href="javascript:void(0);">
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
            <?php $this->load->view("{$pp}loader", ['key' => 'review']); ?>
            <div class="jsPageContainer p10" style="position: relative">
                <!-- Step 1 -->
                <!-- Template -->
                <div class="jsPageSection" data-page="template">
                    <?php $this->load->view("{$pp}create_review/template"); ?>
                </div>
                <!-- Basic -->
                <div class="jsPageSection" data-page="schedule">
                    <?php $this->load->view("{$pp}create_review/schedule"); ?>
                </div>

                <!-- Step 2 -->
                <div class="jsPageSection" data-page="reviewees">
                    <!-- Reviewees -->
                    <?php $this->load->view("{$pp}create_review/reviewees"); ?>
                </div>
                
                <!-- Step 3 -->
                <div class="jsPageSection" data-page="reviewers">
                    <!-- Reviewees -->
                    <?php $this->load->view("{$pp}create_review/reviewers"); ?>
                </div>
            </div>
        </div>
    </div>
