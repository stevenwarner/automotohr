<?php
    //
    $departmentRows = '';
    $teamRows = '';
    $employeeRows = '';
    //
    if(!empty($company_dt['Departments'])): 
        foreach($company_dt['Departments'] as $department):
            $departmentRows .=  '<option value="'.($department['Id']).'">'.($department['Name']).'</option>';
        endforeach;
    endif;
    //
    if(!empty($company_dt['Teams'])): 
        foreach($company_dt['Teams'] as $team):
            $teamRows .=  '<option value="'.($team['Id']).'">'.($team['Name']).'</option>';
        endforeach;
    endif;
    //
    if(!empty($company_employees)): 
        foreach($company_employees as $employee):
            $employeeRows .=  '<option value="'.($employee['Id']).'">'.($employee['Name']).' '.($employee['Role']).'</option>';
        endforeach;
    endif;
    //
    $efj = [];
    $efj['DepartmentRows'] = $departmentRows;
    $efj['TeamRows'] = $teamRows;
    $efj['EmployeeRows'] = $employeeRows;
?>
<div class="col-md-12 col-sm-12">
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
                            <li style="width: 20%;" <?=!$section ? 'class="active"' : '';?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Schedule</span>
                                    <div class="step-text">Getting Started</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" <?=$section == 'reviewees' ? 'class="active"' : '';?>>
                                <a href="javascript:void(0);" >
                                    <span class="csF16 csB7">Reviewee(s)</span>
                                    <div class="step-text">Select Reviewee(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" <?=$section == 'reviewers' ? 'class="active"' : '';?>>
                                <a href="javascript:void(0);"  >
                                    <span class="csF16 csB7">Reviewer(s)</span>
                                    <div class="step-text">Select Reviewer(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;"  <?=$section == 'questions' ? 'class="active"' : '';?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Question(s)</span>
                                    <div class="step-text">Add Question(s)</div> 
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;"  <?=$section == 'feedback' ? 'class="active"' : '';?>>
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
                <?php 
                    if(empty($section) ||  $section == 'schedule'){
                        ?>
                        <!-- Template -->
                        <div class="jsPageSection" data-page="template">
                            <?php $this->load->view("{$pp}create_review/template", ['efj' => $efj]); ?>
                        </div>
                        <!-- Basic -->
                        <div class="jsPageSection" data-page="schedule">
                            <?php $this->load->view("{$pp}create_review/schedule", ['efj' => $efj]); ?>
                        </div>
                    <?php 
                }
                ?>

                <?php 
                    if($section == 'reviewees'){
                        ?>
                        <!-- Step 2 -->
                        <div class="jsPageSection" data-page="reviewees">
                            <!-- Reviewees -->
                            <?php $this->load->view("{$pp}create_review/reviewees", ['efj' => $efj]); ?>
                        </div>
                        <?php
                    }
                ?>

                <?php 
                    if($section == 'reviewers'){
                        ?>
                        <!-- Step 3 -->
                        <div class="jsPageSection" data-page="reviewers">
                            <!-- Reviewees -->
                            <?php $this->load->view("{$pp}create_review/reviewers", ['efj' => $efj]); ?>
                        </div>
                    <?php
                    }
                ?>


                <?php 
                    if($section == 'questions'){
                        ?>
                        <!-- Step 4 -->
                        <div class="jsPageSection" data-page="questions">
                            <!-- Reviewees -->
                            <?php $this->load->view("{$pp}create_review/questions", ['efj' => $efj]); ?>
                        </div>
                        <?php 
                    }
                ?>
                
                <?php 
                    if($section == 'feedback'){
                        ?>
                        <!-- Step 5 -->
                        <div class="jsPageSection" data-page="feedback">
                            <!-- Reviewees -->
                            <?php $this->load->view("{$pp}create_review/feedback", ['efj' => $efj]); ?>
                        </div>
                        <?php 
                    }
                ?>
            </div>
        </div>
    </div>
