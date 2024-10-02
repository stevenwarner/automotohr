<!-- Schedule -->

<?php if ($load_view) {

$panelHeading = 'background-color: #3554DC';
} else {
$panelHeading = 'background-color: #81b431';
}
?>

<div class="panel panel-theme">
    <div class="panel-heading" style="<?= $panelHeading ?>">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Basic</p>
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
        <!-- Name -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Name <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">What the review will be called. e.g. "Quarterly Review"</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <input type="text" class="form-control" id="jsReviewTitleInp" placeholder="Quarterly Review" />
            </div>
        </div>
        
        <!-- Description -->
        <div class="row">
            <br />
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Description <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">What this review is about.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <textarea class="form-control" rows="5" id="jsReviewDescriptionInp" placeholder="Creating a Quarterly Review for Sales department."></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Schedule -->
<div class="panel panel-theme">
    <div class="panel-heading" style="<?= $panelHeading ?>">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Schedule <small>(When the review will start & end)</small></p>
            </div>
            <div class="col-xs-1">
                <span class="pull-right">
                    <i class="fa fa-minus-circle csCP csF18 csB7 jsPageBTN" aria-hidden="true" data-target="schedule"></i>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body jsPageBody" data-page="schedule">
        <!-- Frequency -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Frequency <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">How you would like to run this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <label class="control control--radio csF16 csB1">
                    One-time Only
                    <input type="radio" class="jsReviewFrequencyInp" value="onetime" name="jsReviewFrequency" checked />
                    <div class="control__indicator"></div>
                </label><br/>
                <label class="control control--radio csF16 csB1">
                    Recurring
                    <input type="radio" class="jsReviewFrequencyInp" value="recurring" name="jsReviewFrequency" />
                    <div class="control__indicator"></div>
                </label><br/>
                <label class="control control--radio csF16 csB1">
                    Custom Schedule
                    <input type="radio" class="jsReviewFrequencyInp" value="custom" name="jsReviewFrequency" />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>

        <!-- Period -->
        <div class="row jsReviewFrequencyRowOne jsReviewFrequencyRowRecur">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Review Period <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When the review will start and end.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <input type="text" id="jsReviewStartDateInp" readonly class="form-control" placeholder="MM/DD/YYYY" />
                    </div>
                    <div class="col-md-1 col-xs-12"><p class="text-center csF22 csB9">-</p></div>
                    <div class="col-md-5 col-xs-12">
                        <input type="text" id="jsReviewEndDateInp" readonly class="form-control" placeholder="MM/DD/YYYY" />
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recur -->
        <div class="row dn jsReviewFrequencyRowRecur">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Recur Every <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When the review will re-run.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <input type="text" class="form-control" id="jsReviewRecurValue" placeholder="5" />
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <select id="jsReviewRecurType">
                            <option value="days">Day(s)</option>
                            <option value="weeks">Week(s)</option>
                            <option value="months">Month(s)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Custom Run -->
        <div class="row dn jsReviewFrequencyRowCustom">
            <br />
            <div class="col-md-12 col-xs-12">
                <p class="csF16"><i class="fa fa-info-circle csF18 csB7" aria-hidden="true"></i>&nbsp;<em class="csInfo">You can create custom occurrences for this review based on reviewees' start date, and continuing on a regular schedule. For instance, you may wish to run a performance review for new employees 30 days after their start date, and then every 6 months thereafter</em>.</p>
            </div>
        </div>

        <!-- Custom Run -->
        <div class="row dn jsReviewFrequencyRowCustom">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Custom Review Runs <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The review will run after the selected time from the employee's hire date.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div id="jsReviewCustomRunContainer">
                    <!-- Row 1 -->
                    <div class="row jsReviewCustomRunRow" data-id="1">
                        <div class="col-md-2 col-xs-3">
                            <input type="text" class="form-control jsReviewCustomRunValue"  placeholder="5" id="jsReviewCustomRunValue"/>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <select class="jsReviewCustomRunType" id="jsReviewCustomRunType">
                                <option value="days">Day(s)</option>
                                <option value="weeks">Week(s)</option>
                                <option value="months">Month(s)</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <p class="csF16">After Employee's (Reviewee's) Hire Date</p>
                        </div>
                    </div>
                    
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-orange btn-xs csCP jsReviewAddCustomRun">
                            <i class="fa fa-plus-circle csF18 csB7 csCP" aria-hidden="true" title="Add a new custom run" placement="top"></i>&nbsp;Add a new custom run
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Custom Run -->
        <div class="row dn jsReviewFrequencyRowCustom">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Continue Review <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The review will re-run after the first run on the same day and month of next year.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <label class="control control--checkbox csF16 csB1">
                    Yes, after the last custom run, recur the review on a regular schedule
                    <input type="checkbox" id="jsReviewCustomRunEveryYear" />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>

        <!-- Custom Run -->
        <div class="row dn jsReviewFrequencyRowCustom">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">When is review due? <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When will be the review end after starting.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-xs-3">
                        <input type="text" class="form-control" placeholder="5" id="jsReviewCustomRunDueValue" />
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <select id="jsReviewCustomRunDueType">
                            <option value="days">Day(s)</option>
                            <option value="weeks">Week(s)</option>
                            <option value="months">Month(s)</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <p class="csF16">after the review starts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Visibility -->
<div class="panel panel-theme">
    <div class="panel-heading" style="<?= $panelHeading ?>">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Visibility <small>(Who shall have access to this review)</small></p>
            </div>
            <div class="col-xs-1">
                <span class="pull-right">
                    <i class="fa fa-minus-circle csCP csF18 csB7 jsPageBTN" aria-hidden="true" data-target="visibility"></i>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body jsPageBody" data-page="visibility">
        <!-- Roles -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Role(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Role(s) can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select id="jsReviewRolesInp" multiple>
                    <?php   foreach(getRoles() as $index => $role): ?>
                        <option value="<?=$index;?>"><?=$role;?></option>
                    <?php   endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Departments -->
        <div class="row">
        <br />
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Department(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Department(s) supervisors can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select id="jsReviewDepartmentsInp" multiple>
                    <?=$efj['DepartmentRows'];?>
                </select>
            </div>
        </div>

        <!-- Teams -->
        <div class="row">
        <br />
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Team(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Team(s) team leads can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select id="jsReviewTeamsInp" multiple>
                <?=$efj['TeamRows'];?>
                </select>
            </div>
        </div>

        <!-- Employees -->
        <div class="row">
        <br />
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Employee(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Employee(s) can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select id="jsReviewEmployeesInp" multiple>
                <?=$efj['EmployeeRows'];?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="bbb"></div>
        <br />
        <!--  -->
        <button class="btn btn-black csF16 jsPageSectionBtn" data-to="template"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;Back To Templates</button>
        <!--  -->
        <span class="pull-right">
            <button class="btn btn-success csF16" id="jsReviewScheduleSaveBtn"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp;Save & Next</button>
            <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Finish Later</button>
        </span>
    </div>
    <div class="clearfix"></div>
</div>