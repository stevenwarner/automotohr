<?php if ($load_view) {

    $panelHeading = 'background-color: #3554DC';
} else {
    $panelHeading = 'background-color: #81b431';
}
?>

<?php if (!$load_view) { ?>
    <style>
        .arrow-links ul li.active a:after {
            border-left: 30px solid #81b431 !important;
        }

        ul li.active a {
            color: #fff;
            background-color: #81b431 !important;
        }

        .arrow-links ul li a:hover,
        .arrow-links ul li.active a {
            color: #fff;
            background-color: #81b431;
        }

        .arrow-links ul li a:hover:after {
            border-left-color: #81b431 !important;
        }

        .csPageWrap .csPageBox {
            background-color: #ffffff;
            min-height: 200px;
            margin: 20px 0 !important;
            border: 1px solid #ccc !important;
        }

        .csPageBox {
            border: 1px solid #ccc !important;
            padding: 10px;
        }

        .csRadius5 {
            border-radius: 5px !important;
            -webkit-border-radius: 5px !important;
            -moz-border-radius: 5px !important;
            -o-border-radius: 5px !important;
            margin-bottom: 25px !important;
        }

        .csPageWrap .csPageBoxHeader {
            border-bottom: 1px solid #ccc !important;

        }

        .csPageWrap .csPageBoxHeader ul {
            list-style: none !important;
            margin: 0 !important;
        }

        .csPageWrap .csPageBoxHeader ul li {
            display: inline-block !important;
            margin-right: 5px !important;
        }

        .jsPageSection .csPageBoxHeader ul li {
            display: inline-block !important;
            margin-right: 5px !important;
        }

        .jsPageSection .csPageBoxHeader {
            border-bottom: 1px solid #ccc !important;
        }

        .arrow-links ul li a:after {
            content: " ";
            display: block;
            width: 0;
            height: 0;
            border-top: 0px solid transparent;
            border-bottom: 0px solid transparent;
            border-left: 0px solid #e6e9ed;
            position: absolute;
            top: 50%;
            margin-top: -31px;
            left: 100%;
            z-index: 2;
        }

        .arrow-links ul li a:before {
            content: " ";
            display: block;
            width: 0;
            height: 0;
            border-top: 0px solid transparent;
            border-bottom: 0px solid transparent;
            position: absolute;
            top: 50%;
            margin-top: -31px;
            margin-left: 1px;
            left: 100%;
            z-index: 1;
        }

        .arrow-links ul li a {
            font-size: 10px;
            display: block;
            padding: 10px 0 10px 45px;
            color: #272728;
            background-color: #e6e9ed;
            line-height: normal;
            position: relative;
        }
    </style>

<?php } ?>

<?php
//
$departmentRows = '';
$teamRows = '';
$employeeRows = '';
//
if (!empty($company_dt['Departments'])):
    foreach ($company_dt['Departments'] as $department):
        $departmentRows .=  '<option value="' . ($department['Id']) . '">' . ($department['Name']) . '</option>';
    endforeach;
endif;
//
if (!empty($company_dt['Teams'])):
    foreach ($company_dt['Teams'] as $team):
        $teamRows .=  '<option value="' . ($team['Id']) . '">' . ($team['Name']) . '</option>';
    endforeach;
endif;
//
if (!empty($company_employees)):
    foreach ($company_employees as $employee):
        $employeeRows .=  '<option value="' . ($employee['Id']) . '">' . ($employee['Name']) . ' ' . ($employee['Role']) . '</option>';
    endforeach;
endif;
//
$efj = [];
$efj['DepartmentRows'] = $departmentRows;
$efj['TeamRows'] = $teamRows;
$efj['EmployeeRows'] = $employeeRows;
?>
<div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
    <!-- Assigned -->
    <div class="panel panel-theme">
        <div class="panel-heading" style="<?= $panelHeading ?>">
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
                            <li style="width: 20%;" <?= !$section ? 'class="active"' : ''; ?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Schedule</span>
                                    <div class="step-text">Getting Started</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;  " <?= $section == 'reviewees' ? 'class="active"' : ''; ?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Reviewee(s)</span>
                                    <div class="step-text">Select Reviewee(s)</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" <?= $section == 'reviewers' ? 'class="active"' : ''; ?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Reviewer(s)</span>
                                    <div class="step-text">Select Reviewer(s)</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" <?= $section == 'questions' ? 'class="active"' : ''; ?>>
                                <a href="javascript:void(0);">
                                    <span class="csF16 csB7">Question(s)</span>
                                    <div class="step-text">Add Question(s)</div>
                                    <i class="star" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li style="width: 20%;" <?= $section == 'feedback' ? 'class="active"' : ''; ?>>
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
                if (empty($section) ||  $section == 'schedule') {
                ?>
                    <!-- Template -->
                    <div class="jsPageSection" data-page="template">
                        <?php $this->load->view("{$pp}create_review/template", ['efj' => $efj, 'panelHeading' => $panelHeading]); ?>
                    </div>
                    <!-- Basic -->
                    <div class="jsPageSection" data-page="schedule">
                        <?php $this->load->view("{$pp}create_review/schedule", ['efj' => $efj]); ?>
                    </div>
                <?php
                }
                ?>

                <?php
                if ($section == 'reviewees') {
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
                if ($section == 'reviewers') {
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
                if ($section == 'questions') {
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
                if ($section == 'feedback') {
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