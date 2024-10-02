<?php 
    $employeeSelect = $efj['EmployeeRows'];
    if ($load_view) {

        $panelHeading = 'background-color: #3554DC';
    } else {
        $panelHeading = 'background-color: #81b431';
    }
?>


<!-- Reviewees -->
<div class="panel panel-theme">
    <div class="panel-heading" style="<?=$panelHeading?>">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Select Reviewers <small>(The reviewer's are the employee's who will
                        provide feedback).</small></p>
            </div>
            <div class="col-xs-1">
                <span class="pull-right">
                    <i class="fa fa-minus-circle csCP csF18 csB7 jsPageBTN" aria-hidden="true" data-target="basic"></i>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body jsPageBody" data-page="basic">
        <!-- Info -->
        <div class="row">
            <div class="col-xs-12">
                <p class="csF16 pl10"><i class="fa fa-info-circle csF18 csB7" aria-hidden="true"></i>&nbsp;<em
                        class="csInfo">All reviews will submit to the respective reporting managers.</em></p>
            </div>
        </div>

        <!--  -->
        <div class="row">
            <br>
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Reviewer(s) <span class="csRequired"></span> <i
                        class="fa fa-question-circle-o jsHintBtn" data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">Select a type of review.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <!--  -->
                <label class="control control--radio csF14">
                    <input type="radio" class="jsReviewReviewerType" name="jsReviewReviewerType" value="reporting_manager" /> Reporting Manager <i class="fa fa-info-circle csCP" aria-hidden="true" data-content="The reporting managers will provide feedback. The reporting managers can be set from Departments Management." data-toggle="popover" title=""></i>
                    <div class="control__indicator"></div>
                </label>
                <br>
                <label class="control control--radio csF14">
                    <input type="radio" class="jsReviewReviewerType" name="jsReviewReviewerType" value="self_review" /> Self Review <i class="fa fa-info-circle csCP" aria-hidden="true" data-content="The selected reviewees will provide self review." data-toggle="popover" title=""></i>
                    <div class="control__indicator"></div>
                </label> <br>
                <label class="control control--radio csF14">
                    <input type="radio" class="jsReviewReviewerType" name="jsReviewReviewerType" value="peers" /> Peers (Colleagues) <i class="fa fa-info-circle csCP" aria-hidden="true" data-content="The selected reviewers colleagues will provide feedback." data-toggle="popover" title=""></i>
                    <div class="control__indicator"></div>
                </label> <br>
                <label class="control control--radio csF14">
                    <input type="radio" class="jsReviewReviewerType" name="jsReviewReviewerType" value="specific_reviewers" /> Specific Reviewers <i class="fa fa-info-circle csCP" aria-hidden="true" data-content="The selected reviewers will provide the feedback.." data-toggle="popover" title=""></i>
                    <div class="control__indicator"></div>
                </label> <br>
                <!--  -->
                <div class="jsReviewReviewerSpecificReviewers dn">
                    <select id="jsReviewReviewerFilterEmployees" multiple>
                        <?= $employeeSelect; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviwees listing -->
    <div class="row">
        <br />
        <div class="col-sm-12">
            <div class="col-sm-4 col-xs-12">
                <p class="csF16 csB7">Reviewee(s)</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <p class="csF16 csB7">Action</p>
            </div>
        </div>
    </div>

    <!--  -->
    <?php if(!empty($company_employees)): ?>
    <?php   foreach($company_employees as $index => $employee): 
                $style = $index % 2 === 0 ? 'style="background-color: #f1f1f1;"' : '';
                ?>
    <div class="jsReviewReviewersRow" data-id="<?=$employee['Id'];?>" <?=$style;?>>
        <!--  -->
        <div class="row">
            <br />
            <div class="col-sm-12">
                <div class="col-sm-4 col-xs-12">
                    <h6 class="csF14 csB7 mb0"><?=$employee['Name'];?></h6>
                    <p class="csF14 mb0"><?=$employee['Role'];?></p>
                    <p class="csF14">Joined On: <?=formatDate($employee['JoinedDate']);?></p>
                </div>
                <div class="col-sm-8 col-xs-12 jsReviewReviewerCountBox">
                    <button class="btn btn-link csF14 pl0 jsReviewReviewerCountBtn">
                        Reviewer(s) Selected: <span class="jsReviewReviewerCount">0</span> <i class="fa fa-chevron-down csF12" aria-hidden="true"></i>
                    </button>
                    <!--  -->
                    <div class="row dn jsReviewReviewerSelectBox">
                        <div class="col-sm-5 col-xs-12">
                            <select class="select2 jsReviewReviewerSelectBoxIncluded" multiple>
                            </select>
                            <p class="csF14 csB7">Included Reviewers&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" data-content="You can add additional reviewers to provide feedback." data-toggle="popover" title=""></i></p>
                        </div>
                        <div class="col-sm-5 col-xs-12">
                            <select class="select2 jsReviewReviewerSelectBoxExcluded" multiple>
                            </select>
                            <p class="csF14 csB7">Excluded Reviewers&nbsp;<i class="fa fa-info-circle csCP" aria-hidden="true" data-content="The selected employees will not provide feedback against this employee." data-toggle="popover" title=""></i></p>
                        </div>
                        <div class="col-sm-2 col-xs-12">
                            <i class="fa fa-arrow-circle-left csF18 csB7 ma10 csCP jsReviewReviewerBackCountBtn" aria-hidden="true"
                                title="Back to count" placement="top"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php   endforeach; ?>
    <?php endif; ?>
</div>

<!-- Buttons -->
<div class="row">
    <div class="col-sm-12">
        <div class="bbb"></div>
        <br />
        <button class="btn btn-black csF16 jsPageSectionBtn" data-to="reviewees"><i class="fa fa-arrow-circle-o-left"
                aria-hidden="true"></i>&nbsp; Back To Reviewees</button>
        <span class="pull-right">
            <button class="btn btn-success csF16" id="jsReviewReviewersSaveBtn"><i class="fa fa-arrow-circle-o-right"
                    aria-hidden="true"></i>&nbsp; Save & Next</button>
            <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Finish
                Later</button>
        </span>
    </div>
    <div class="clearfix"></div>
</div>


<script>
      $('[data-toggle="popover"]').popover({
          trigger: 'hover'
      })
</script>