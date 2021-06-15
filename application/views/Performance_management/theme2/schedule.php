<!-- Schedule -->
<div class="panel panel-theme">
    <div class="panel-heading">
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
    <div class="panel-body pl0 pr0 jsPageBody" data-page="basic">
        <!-- Name -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Name <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">What the review will be called. e.g. "Quarterly Review"</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <input type="text" class="form-control" placeholder="Quarterly Review" />
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
                <textarea class="form-control" rows="5" placeholder="Creating a Quarterly Review for Sales department."></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Schedule -->
<div class="panel panel-theme">
    <div class="panel-heading">
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
    <div class="panel-body pl0 pr0 jsPageBody" data-page="schedule">
        <!-- Frequency -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Frequency <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">How you would like to run this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <label class="control control--radio csF16 csB1">
                    One-time Only
                    <input type="radio" name="1" />
                    <div class="control__indicator"></div>
                </label><br/>
                <label class="control control--radio csF16 csB1">
                    Recurring
                    <input type="radio" name="1" />
                    <div class="control__indicator"></div>
                </label><br/>
                <label class="control control--radio csF16 csB1">
                    Custom Schedule
                    <input type="radio" name="1" />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>

        <!-- Period -->
        <div class="row">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Review Period <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When the review will start and end.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <input type="text" readonly class="form-control" />
                    </div>
                    <div class="col-md-1 col-xs-12"><p class="text-center csF22 csB9">-</p></div>
                    <div class="col-md-5 col-xs-12">
                        <input type="text" readonly class="form-control" />
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recur -->
        <div class="row">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Recur Every <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When the review will re-run.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <input type="text" class="form-control" />
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <select class="select2"></select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Custom Run -->
        <div class="row">
            <br />
            <div class="col-md-12 col-xs-12">
                <p class="csF16"><i class="fa fa-info-circle csF18 csB7" aria-hidden="true"></i>&nbsp;<em class="csInfo">You can create custom occurrences for this review based on reviewees' start date, and continuing on a regular schedule. For instance, you may wish to run a performance review for new employees 30 days after their start date, and then every 6 months thereafter</em>.</p>
            </div>
        </div>

        <!-- Custom Run -->
        <div class="row">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Custom Review Runs <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The review will run after the selected time from the employee's hire date.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-xs-3">
                        <input type="text" class="form-control"  placeholder="5"/>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <select class="select2"></select>
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <p class="csF16">After Employee's (Reviewee's) Hire Date</p>
                    </div>
                    <div class="col-md-1 col-xs-1">
                        <i class="fa fa-trash-o csF18 csB7 csCP" aria-hidden="true" title="Delete this custom run" placement="top"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Custom Run -->
        <div class="row">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">Continue Review <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The review will re-run after the first run on the same day and month of next year.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <label class="control control--checkbox csF16 csB1">
                    Yes, after the last custom run, recur the review on a regular schedule
                    <input type="checkbox" />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>

        <!-- Custom Run -->
        <div class="row">
            <br />
            <div class="col-md-4 col-xs-12">
                <label class="csF16 csB7">When is review due? <span class="csRequired"></span> <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">When will be the review end after starting.</p>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-xs-3">
                        <input type="text" class="form-control" placeholder="5" />
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <select class="select2"></select>
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
    <div class="panel-heading">
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
    <div class="panel-body pl0 pr0 jsPageBody" data-page="visibility">
        <!-- Roles -->
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Role(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Role(s) can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select class="select2" multiple></select>
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
                <select class="select2" multiple></select>
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
                <select class="select2" multiple></select>
            </div>
        </div>

        <!-- Departments -->
        <div class="row">
        <br />
            <div class="col-sm-4 col-xs-12">
                <label class="csF16 csB7">Employee(s) <i class="fa fa-question-circle-o jsHintBtn"  data-target="title" aria-hidden="true"></i></label>
                <p class="csF14 jsHintBody" data-hint="title">The selected Employee(s) can manage this review.</p>
            </div>
            <div class="col-sm-8 col-xs-12">
                <select class="select2" multiple></select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="bbb"></div>
        <br />
        <span class="pull-right">
            <button class="btn btn-orange csF16"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp; Save & Next</button>
            <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Finish Later</button>
        </span>
    </div>
    <div class="clearfix"></div>
</div>



<script>$('.select2').select2({
    placeholder: "Please select"
});</script>