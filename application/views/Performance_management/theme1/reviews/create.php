<div class="container-fluid">
    <div class="csPageBox csRadius5">
        <!-- Page Header -->
        <div class="csPageBoxHeader p10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="csF18">
                        <span class="csF18 csB7">Create a Review</span>
                        <span id="jsReviewTitleHeader"></span>
                    </h1>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <span class="csBTNBox">
                        <button class="btn btn-black btn-lg dn  csF16 jsFinishLater"><i aria-hidden="true" class="fa fa-edit csF16"></i> Finish
                            Later</button>
                        <a href="<?=purl('reviews');?>" class="btn btn-black btn-lg csF16"><i
                        aria-hidden="true"   class="fa fa-long-arrow-left csF16"></i> All Reviews</a>
                    </span>

                </div>
            </div>
        </div>

        <!-- Page Body -->
        <div class="csPageBoxBody p10">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="create_review"><i aria-hidden="true" class="fa fa-circle-o-notch fa-spin"></i>
            </div>
            <div class="row">
                <!-- Left menu -->
                <div class="col-sm-2 col-xs-12 csSticky csStickyTop">
                    <ul class="csPageLeftMenu ma10">
                        <li class="jsReviewStep active csF16" data-to="templates">
                            <span class="csF16">Templates</span> <i aria-hidden="true" class="fa fa-long-arrow-right"></i>
                        </li>
                        <li class="jsReviewStep csF16" data-to="schedule">
                            <span class="csF16">Name & Schedule</span>
                        </li>
                        <li class="jsReviewStep csF16" data-to="reviewees">
                            <span class="csF16">Reviewees</span>
                        </li>
                        <li class="jsReviewStep csF16" data-to="reviewers">
                            <span class="csF16">Reviewers</span>
                        </li>
                        <li class="jsReviewStep csF16" data-to="questions">
                            <span class="csF16">Questions</span>
                        </li>
                        <li class="jsReviewStep csF16" data-to="feedback">
                            <span class="csF16">Sharing Feedback</span>
                        </li>
                    </ul>
                </div>
                <!-- Content Area -->
                <div class="col-sm-10 col-xs-12">
                    <div class="csPageContent">

                        <!-- Template Section -->
                        <div class="csPageSection jsPageSection" data-key="templates">
                            <!-- Box Header -->
                            <div class="csPageBoxHeader p10">
                                <h2 class="csF16">
                                    <em>Craft a new review from the ground up or pick a template with insightful questions.</em>
                                </h2>
                            </div>
                            <!-- Box Body -->
                            <div class="csPageBoxBody p10">
                                <div class="csForm pa10 pb10">
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="csF16 csB7">Options <span class="csRequired"></span></label> <br />
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control csB1 csF16 control--radio csF16 csB1">
                                                New Review
                                                <input type="radio" name="templateType" class="jsReviewType" value="new"
                                                    checked />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--radio csF16 csB1">
                                                Use Template
                                                <input type="radio" name="templateType" class="jsReviewType"
                                                    value="template" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Templates list -->
                            <div class="csPageBoxBody jsTemplateWrap bbt dn">
                                <!-- Box Header -->
                                <div class="csPageBoxHeader">
                                    <h3 class="pl10 pb10 csF18 csB7">
                                        Templates
                                    </h3>
                                </div>
                                <!-- Box Body -->
                                <div class="csPageBoxBody p10">
                                    <!-- Personal Templates -->
                                    <div class="csSection">
                                        <!--  -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h3 class="csF18 csB7">Personal Templates (<?=count($templates['personal']);?>)</h3>
                                            </div>
                                        </div>
                                        <?php if(!empty($templates['personal'])): ?>
                                        <div class="row">
                                            <?php   foreach($templates['personal'] as $template): ?>
                                            <!-- Template row -->
                                            <div class="col-sm-3">
                                                <div class="csPageBox csRadius5 jsTemplateBox"
                                                    data-id="<?=$template['sid'];?>" data-type="personal"
                                                    data-name="<?=$template['name'];?>">
                                                    <div class="csPageBoxHeader p10">
                                                        <span class="csBTNBox ">
                                                            <button
                                                                class="btn btn-orange btn-xs jsTemplateQuestionsView"><strong><i
                                                                        aria-hidden="true" class="fa fa-eye"></i> View
                                                                    Questions</strong></button>
                                                        </span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="csPageBoxBody p10">
                                                        <h4><strong><?=$template['name'];?></strong></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php   endforeach; ?>
                                        </div>
                                        <?php else: ?>
                                        <!-- Error Box -->
                                        <div class="csErrorBox">
                                            <i aria-hidden="true" class="fa fa-info-circle"></i>
                                            <p>There are currently no custom templates.</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Company Templates -->
                                    <div class="csSection">
                                        <!--  -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h3 class=" csF18 csB7">Company Templates (<?=count($templates['company']);?>)</h3>
                                            </div>
                                        </div>
                                        <?php if(!empty($templates['company'])): ?>
                                        <div class="row">
                                            <?php   foreach($templates['company'] as $template): ?>
                                            <!-- Template row -->
                                            <div class="col-sm-3 col-xs-12">
                                                <div class="csPageBox csRadius5 jsTemplateBox"
                                                    data-id="<?=$template['sid'];?>" data-type="company"
                                                    data-name="<?=$template['name'];?>">
                                                    <div class="csPageBoxHeader p10">
                                                        <span class="csBTNBox ">
                                                            <button
                                                                class="btn btn-orange btn-xs jsTemplateQuestionsView csF16"><strong><i
                                                                        aria-hidden="true" class="fa fa-eye csF16"></i> View
                                                                    Questions</strong></button>
                                                        </span>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="csPageBoxBody p10">
                                                        <h4 class="csF16 csB7"><?=$template['name'];?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php   endforeach; ?>
                                        </div>
                                        <?php else: ?>
                                        <!-- Error Box -->
                                        <div class="csErrorBox">
                                            <i aria-hidden="true" class="fa fa-info-circle"></i>
                                            <p>There are currently no custom templates.</p>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <!-- Page Footer -->
                            <div class="csPageBoxFooter pa10">
                                <!--  -->
                                <span class="csBTNBox">
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16 csF16" data-to="schedule"><i aria-hidden="true"
                                            class="fa fa-arrow-circle-right csF16"></i> Next</button>
                                </span>
                                <!--  -->
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Name & Schedule -->
                        <div class="csPageSection jsPageSection dn" data-key="schedule">
                            <!-- Body -->
                            <div class="csPageBoxBody p10">
                                <div class="csForm">
                                    <!-- Name -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="pa10 csF16 csB7">Name <span class="csRequired"></span></label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control csF16 csRadius100" id="jsReviewTitle" />
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="csF16 csB7">Description</label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <textarea class="form-control csF16 csRadius5" rows="3"
                                                id="jsReviewDescription"></textarea>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="csF16 csB7">Visibility</label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <div class="mb10">
                                                <label class="csF16 csB7">Roles</label>
                                                <select id="jsReviewVisibilityRoles" multiple>
                                                    <option value="admin">Admin</option>
                                                    <option value="hiring_manager">Hiring Manager</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="employee">Employee</option>
                                                </select>
                                            </div>
                                            <div class="mb10">
                                                <label class="csF16 csB7">Departments</label>
                                                <select id="jsReviewVisibilityDepartments" multiple>
                                                    <?php if(!empty($dnt['departments'])): ?>
                                                    <?php   foreach($dnt['departments'] as $department): ?>
                                                    <option value="<?=$department['sid'];?>">
                                                        <?=$department['name'];?></option>
                                                    <?php   endforeach; ?>
                                                    <?php   endif; ?>
                                                </select>
                                            </div>
                                            <div class="mb10">
                                                <label class="csF16 csB7">Teams</label>
                                                <select id="jsReviewVisibilityTeams" multiple>
                                                    <?php if(!empty($dnt['teams'])): ?>
                                                    <?php   foreach($dnt['teams'] as $team): ?>
                                                    <option value="<?=$team['sid'];?>"><?=$team['name'];?></option>
                                                    <?php   endforeach; ?>
                                                    <?php   endif; ?>
                                                </select>
                                            </div>
                                            <div class="mb10">
                                                <label class="csF16 csB7">Employees</label>
                                                <select id="jsReviewVisibilityIndividuals" multiple></select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bbt mb10"></div>

                                    <!--  -->
                                    <div class="row mb10">
                                        <div class="col-sm-12">
                                            <h3><strong>Schedule</strong></h3>
                                        </div>
                                    </div>


                                    <!-- Schedule -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="csF16 csB7">Frequency <span class="csRequired"></span></label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control csB1 csF16 control--radio">
                                                One-time only
                                                <input type="radio" name="frequency" class="jsReviewFrequency"
                                                    value="onetime" checked />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--radio">
                                                Recurring
                                                <input type="radio" name="frequency" class="jsReviewFrequency"
                                                    value="repeat" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--radio">
                                                Custom Schedule
                                                <input type="radio" name="frequency" class="jsReviewFrequency"
                                                    value="custom" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="jsFrequencyBox jsFrequencyORBox">
                                        <!-- Period -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">Review Period <span class="csRequired"></span></label>
                                            </div>
                                            <div class="col-sm-3 col-xs-12 pr0">
                                                <input type="text" class="form-control csF16 csRadius100"
                                                    id="jsReviewStartDate" readonly placeholder="MM/DD/YYYY" />
                                            </div>
                                            <div class="col-sm-1 col-xs-12 pl0 pr0">
                                                <p class="text-center ma10"><i aria-hidden="true" class="fa fa-minus"
                                                        style="font-size: 22px;"></i></p>
                                            </div>
                                            <div class="col-sm-3 col-xs-12 pl0">
                                                <input type="text" class="form-control csF16 csRadius100" id="jsReviewEndDate"
                                                    readonly placeholder="MM/DD/YYYY" />
                                            </div>
                                        </div>

                                        <!-- Repeat -->
                                        <div class="row mb10 jsFrequencyBox jsFrequencyRepeatBox dn">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">Recur Every <span class="csRequired"></span></label>
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <input type="text" class="form-control csF16 csRadius100" placeholder="0"
                                                    id="jsReviewRepeatVal" />
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <select id="jsReviewRepeatType">
                                                    <option value="day">Days</option>
                                                    <option value="week">Weeks</option>
                                                    <option value="month">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="jsFrequencyBox jsFrequencyCustom bbt dn">
                                        <!-- Info -->
                                        <div class="row mb10">
                                            <div class="col-sm-12">
                                                <p class="pa10 text-justify csF16">You can create
                                                    custom occurrences for this review based on
                                                    reviewees' start date, and continuing on a regular schedule. For
                                                    instance, you may wish to run a performance review for new employees
                                                    30 days after their start date, and then every 6 months thereafter.
                                                </p>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">Custom Review Runs</label>
                                            </div>
                                            <div class="col-sm-9 col-sm-12">
                                                <div id="jsReviewCustomRunWrap"></div>
                                                <!-- Add button -->
                                                <button class="btn btn-link pl0 jsReviewCustomRunAdd">
                                                    <i aria-hidden="true" class="fa fa-plus-circle"></i> Add another run
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Continue -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="csF16 csB7">Continue Review</label>
                                            </div>
                                            <div class="col-sm-9 col-xs-12">
                                                <label class="control csB1 csF16 control--checkbox">
                                                    Yes, after the last custom run, recur the review on a regular
                                                    schedule
                                                    <input type="checkbox" id="jsReviewContinue" />
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Review Due -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">When are reviews due? <span class="csRequired"></span></label>
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <input type="text" class="form-control csF16 csRadius100" placeholder="0"
                                                    id="jsReviewDue" />
                                            </div>
                                            <div class="col-sm-7 col-xs-12">
                                                <p class="csF16">days after they start.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="csPageBoxFooter p10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="templates"><i
                                            class="fa fa-long-arrow-left csF16"></i> Back To Templates</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16" data-to="reviewees"><i
                                            class="fa fa-arrow-circle-right csF16"></i>Save & Next</button>
                                    <button class="btn btn-black btn-lg dn  csF16 jsFinishLater"><i aria-hidden="true" class="fa fa-edit csF16"></i>
                                        Finish Later</button>
                                </span>
                                <!--  -->
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Reviewees -->
                        <div class="csPageSection jsPageSection dn" data-key="reviewees">
                            <div class="csPageBoxHeader pl10">
                                <h2 class="csF16 csB7">Select Reviewees</h2>
                            </div>
                            <div class="csPageBoxHeader pl10 pa10 pb10">
                                <p class="csF16">Use the Rule Settings to define which workers are included (or excluded) from this
                                    review.</p>
                                <p class="csF16">Note: The preview shows the selected workers as of today and may differ from when
                                    the review starts.</p>
                            </div>
                            <div class="csPageBoxBody">
                                <div class="row">
                                    <!-- Filter Bar -->
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="csFilterBox p10">
                                            <p class="csF16">
                                                <span class="csF16 csB7">Rule Settings</span>
                                                <span class="csBTNBox">
                                                    <button class="btn btn-black btn-xs jsResetFilter">Reset</button>
                                                </span>
                                            </p>
                                            <!-- Individual Row -->
                                            <div class="row pa10 pb10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Individuals</label>
                                                    <select id="jsFilterIndividuals" multiple="true"></select>
                                                </div>
                                            </div>
                                            <!-- Include -->
                                            <h5 class="pa10 bbt  csF16 csB7">Include Employees</h5>
                                            <!-- Departments Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Departments</label>
                                                    <select id="jsFilterDepartments" multiple>
                                                        <?php if(!empty($dnt['departments'])): ?>
                                                        <?php   foreach($dnt['departments'] as $department): ?>
                                                        <option value="<?=$department['sid'];?>">
                                                            <?=$department['name'];?></option>
                                                        <?php   endforeach; ?>
                                                        <?php   endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Teams Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Teams</label>
                                                    <select id="jsFilterTeams" multiple>
                                                        <?php if(!empty($dnt['teams'])): ?>
                                                        <?php   foreach($dnt['teams'] as $team): ?>
                                                        <option value="<?=$team['sid'];?>"><?=$team['name'];?></option>
                                                        <?php   endforeach; ?>
                                                        <?php   endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Employment Type Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Employment Type</label>
                                                    <select id="jsFilterEmploymentType" multiple>
                                                        <option value="fulltime">Full-time</option>
                                                        <option value="parttime">Part-time</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Job Titles Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Job Titles</label>
                                                    <select id="jsFilterJobTitles" multiple>
                                                        <?php if(!empty($jobTitles)): ?>
                                                        <?php   foreach($jobTitles as $jobTitle):?>
                                                        <option value="<?=$jobTitle['job_title'];?>">
                                                            <?=$jobTitle['job_title'];?></option>
                                                        <?php   endforeach; ?>
                                                        <?php   endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Exclude -->
                                            <h5 class="pa10 bbt  csF16 csB7">Exclude Employees</h5>
                                            <!-- Departments Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Exclude Individuals</label>
                                                    <select id="jsFilterExcludeEmployees" multiple></select>
                                                </div>
                                            </div>
                                            <!-- Teams Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label class="csF16 csB7">Exclude New Hires</label>
                                                    <select id="jsFilterExcludeNewHires">
                                                        <option value="0">None</option>
                                                        <option value="30">up to 30 days from hire date</option>
                                                        <option value="60">up to 60 days from hire date</option>
                                                        <option value="90">up to 90 days from hire date</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Content Area -->
                                    <div class="col-sm-8 col-xs-12 pl0">
                                        <div class="csContentArea">
                                            <div class="csPageBoxHeader pa10">
                                                <ul>
                                                    <li><a href="javascript:void(0)" class="active jsShiftTab csF18 csB7"
                                                            data-target="included">Included <span
                                                                id="jsIncudedEmployeeCount">(0)</span></a></li>
                                                    <li><a href="javascript:void(0)" class="jsShiftTab csF18 csB7"
                                                            data-target="excluded">Excluded <span
                                                                id="jsExcludedEmployeeCount">(0)</span></a></li>
                                                </ul>
                                            </div>
                                            <div class="csPageBoxBody">
                                                <!-- Loader -->
                                                <div class="csIPLoader jsIPLoader" data-page="review_incexc"><i
                                                        class="fa fa-circle-o-notch fa-spin"></i></div>
                                                <div class="jsTabSection" data-id="included">
                                                    <table class="table table-striped table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th>Full Name</th>
                                                                <th>Department</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="jsReviewIncludedWrap"></tbody>
                                                    </table>
                                                </div>
                                                <div class="jsTabSection dn" data-id="excluded">
                                                    <table class="table table-striped table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th>Full Name</th>
                                                                <th>Department</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="jsReviewExcludedWrap"></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="csPageBoxFooter p10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="schedule"><i
                                            class="fa fa-long-arrow-left csF16"></i> Back To Schedule</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16" data-to="reviewers"><i
                                            class="fa fa-arrow-circle-right csF16"></i>Save & Next</button>
                                    <button class="btn btn-black btn-lg  csF16 jsFinishLater dn"><i aria-hidden="true" class="fa fa-edit csF16"></i>
                                        Finish Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Reviewers -->
                        <div class="csPageSection jsPageSection dn" data-key="reviewers">
                            <div class="csPageBoxHeader p10">
                                <h2 class="csF16"><em>All reviews will be submitted to respective reporting managers.</em></h2>
                            </div>
                            <div class="csPageBoxBody p10">
                                <div class="csForm">
                                    <!-- Row 1 -->
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <label class="csF16 csB7">Reviewers <span class="csRequired"></span></label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control csB1 csF16 control--checkbox">
                                                Reporting Manager
                                                <input type="checkbox" name="reviewerTypeRM" class="jsReviewerType" value="reporting_manager" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--checkbox">
                                                Self-Review
                                                <input type="checkbox" name="reviewerTypeSR" class="jsReviewerType" value="self_review" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--checkbox">
                                                Peers (Colleagues)
                                                <input type="checkbox" name="reviewerTypeP" class="jsReviewerType" value="peer" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control csB1 csF16 control--checkbox">
                                                Specific Reviewers
                                                <input type="checkbox" name="reviewerTypeSR" class="jsReviewerType" value="specific" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <div class="dn" id="jsReviewSpecificReviewersBox">
                                                <select id="jsReviewSpecificReviewers" multiple="multiple"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bbt ma10"></div>
                                    <!-- Row 2 -->
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <h4 class="csF16"><strong>Reviewees</strong> <span id="jsReviewTotalRevieweeCount"></span></h4>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <h4 class="csF16"><strong>Assigned Reviewers</strong></h4>
                                        </div>
                                    </div>
                                    <div class="bbt"></div>
                                    <div id="jsReviewRevieweeWrap"></div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="csPageBoxFooter p10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="reviewees"><i aria-hidden="true" class="fa fa-long-arrow-left csF16"></i> Back To
                                        Reviewees</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16" data-to="questions"><i aria-hidden="true" class="fa fa-arrow-circle-right csF16"></i>Save &
                                        Next</button>
                                    <button class="btn btn-black btn-lg  csF16 jsFinishLater"><i aria-hidden="true" class="fa fa-edit csF16"></i> Finish
                                        Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Questions -->
                        <div class="csPageSection jsPageSection  dn" data-key="questions">
                            <!-- Header -->
                            <div class="csPageBoxHeader csSticky csStickyTop p10" style="background-color: #fff;">
                                <h2>
                                    <span class="csF18 csB7">Questions</span>
                                    <span class="csBTNBox">
                                        <button class="btn btn-orange mtn8 jsReviewBackStep csF16" data-to="add_question">
                                            <i aria-hidden="true" class="fa fa-plus-circle"></i> Add A Question
                                        </button>
                                    </span>
                                </h2>
                            </div>
                            <!-- Body -->
                            <div class="csPageBoxBody jsQuestionViewWrap bbb p10">
                                <div class="csQuestionRow">
                                    <h4 class="alert alert-info text-center">You haven't added any questions.</h4>
                                </div>
                            </div>
                            <!--  -->
                            <div class="csPageBoxFooter p10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="reviewers"><i aria-hidden="true" class="fa fa-long-arrow-left csF16"></i> Back To
                                        Reviewers</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16" data-to="feedback"><i aria-hidden="true" class="fa fa-arrow-circle-right csF16"></i>Save &
                                        Next</button>
                                    <button class="btn btn-black btn-lg  csF16 jsFinishLater"><i aria-hidden="true" class="fa fa-edit csF16"></i> Finish
                                        Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Feedback -->
                        <div class="csPageSection jsPageSection dn" data-key="feedback">
                            <!-- Header -->
                            <div class="csPageBoxHeader p10">
                                <h2 class="csF18 csB7">Sharing Feedback</h2>
                            </div>
                            <!-- Body -->
                            <div class="csPageBoxBody p10">
                                <div class="csSharingFeedbackBox">
                                    <p class="csF16">Tell us how you want managers to share feedback with their reports.</p>
                                    <br />
                                    <ul>
                                        <li class="csRadius5 jsReviewFeedback csCursorSelect csF16" data-share="1">
                                            <i aria-hidden="true" class="fa fa-eye csF16"></i> The manager summarizes all reviews and shares the
                                            summary with their report
                                        </li>
                                        <li class="csRadius5 jsReviewFeedback csCursorSelect csF16" data-share="0">
                                            <i aria-hidden="true" class="fa fa-eye-slash csF16"></i> Nothing is shared with their report
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="csPageBoxFooter pa10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="questions"><i aria-hidden="true" class="fa fa-long-arrow-left csF16"></i>
                                        Back To Questions</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep csF16" ><i aria-hidden="true" class="fa fa-arrow-circle-right csF16"></i>Save &
                                        Finish</button>
                                    <button class="btn btn-black btn-lg  csF16 jsFinishLater"><i aria-hidden="true" class="fa fa-edit csF16"></i> Finish
                                        Later</button>
                                </span>
                            </div>
                        </div>
                        <!-- Add Question -->
                        <div class="csPageSection jsPageSection dn" data-key="add_question">
                            <div class="csPageBoxHeader p10">
                                <h2>
                                    <span class="csF18 csB7">Add a Question</span>
                                    <span class="csBTNBox">
                                        <button class="btn btn-black btn-lg mtn8 jsReviewBackStep csF16" data-to="questions"><i aria-hidden="true" class="fa fa-times-circle csF16"></i> Cancel</button>
                                        <button class="btn btn-orange btn-lg mtn8 jsSaveQuestion csF16"><i aria-hidden="true" class="fa fa-save csF16"></i> Save Question</button>
                                    </span>
                                </h2>
                            </div>
                            <!-- Body2 -->
                            <div class="csPageBoxBody p10">
                                <!-- Add Question -->
                                <div class="jsAddQuestion">
                                    <!-- Question Body -->
                                    <div class="csPageBoxBody p10">
                                        <!--  -->
                                        <div class="csForm">
                                            <!-- Question  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Question <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <input class="form-control csF16"
                                                        placeholder="e.g. What would you do differently next quarter?"
                                                        id="jsQuestionVal" />
                                                </div>
                                            </div>

                                            <!-- Description  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Description</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <textarea class="form-control csF16"
                                                        id="jsQuestionDescription"></textarea>
                                                </div>
                                            </div>
                                            
                                            <!-- Options  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Options</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionUseLabels" /> Use Labels
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionIncludeNA" /> Include N/A
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsStartVideoRecord" /> Include a Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <!--  -->
                                                    <div class="csVideoHelpBox">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="jsVideoRecorderBox dn">
                                                                    <p class="alert alert-danger csF16"><strong>To use this
                                                                            feature, please, make sure you have allowed
                                                                            microphone and camera access.</strong></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="jsVideoRecorderBox dn">
                                                                    <video id="jsVideoRecorder" width="100%"></video>
                                                                    <!--  -->
                                                                    <button class="btn btn-orange btn-lg csF16 dn"
                                                                        id="jsVideoRecordButton">Start Recording</button>
                                                                    <!--  -->
                                                                    <button class="btn btn-black btn-lg  csF16 dn"
                                                                        id="jsVideoPauseButton"><i aria-hidden="true" class="fa fa-pause-circle csF16"></i> Pause Recording</button>
                                                                    <!--  -->
                                                                    <button class="btn btn-black btn-lg csF16 dn"
                                                                        id="jsVideoResumeButton"><i aria-hidden="true" class="fa fa-play-circle csF16"></i> Resume Recording</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="jsVideoPreviewBox dn">
                                                                    <video id="jsVideoPreview" width="100%"></video>
                                                                    <button class="btn btn-orange btn-lg csF16"
                                                                        id="jsVideoPlayVideo"><i aria-hidden="true" class="fa fa-play csF16"></i>
                                                                        Play Video</button>
                                                                    <button class="btn btn-black btn-lg csF16"
                                                                        id="jsVideoRemoveButton"><i
                                                                            class="fa fa-times-circle csF16"></i> Remove
                                                                        Video</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Type  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Response Type <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <select id="jsQuestionType">
                                                        <option value="text">Text Box</option>
                                                        <option value="rating">Rating Scale</option>
                                                        <option value="text-n-rating">Rating Scale & Text Box</option>
                                                        <option value="multiple-choice">Multiple Choice</option>
                                                        <option value="multiple-choice-with-text">Multiple Choice & Text Box
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Rating Scale  -->
                                            <div class="row mb10 jsQuestionRatingScaleBox dn">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Rating Scale</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <select id="jsQuestionRatingScale">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Ratings -->
                                            <div class="row mb10 jsQuestionRatingValBox dn">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Ratings</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <div class="form-group jsQuestionRatingScaleValBox">
                                                        <label class="csF16 csB7">Rating 1</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleVal"
                                                            value="<?=getDefaultLabel()[1];?>" data-id="1" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBox">
                                                        <label class="csF16 csB7">Rating 2</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleVal"
                                                            value="<?=getDefaultLabel()[2];?>" data-id="2" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBox">
                                                        <label class="csF16 csB7">Rating 3</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleVal"
                                                            value="<?=getDefaultLabel()[3];?>" data-id="3" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBox">
                                                        <label class="csF16 csB7">Rating 4</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleVal"
                                                            value="<?=getDefaultLabel()[4];?>" data-id="4" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBox">
                                                        <label class="csF16 csB7">Rating 5</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleVal"
                                                            value="<?=getDefaultLabel()[5];?>" data-id="5" />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Assigned to  -->
                                            <div class="row mb10 ">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Assigned to <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionReportingManager" />
                                                        Reporting Manager
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionSelf" /> Self
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionPeer" /> Peers and others
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Preview Box -->
                                            <div class="jsPreviewQuestionBox"></div>
                                        </div>
                                    </div>
                                    <!-- Question Footer -->
                                    <div class="csPageBoxFooter p10">
                                        <span class="csBTNBox">
                                            <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="questions"><i
                                                    class="fa fa-times-circle csF16"></i> Cancel</button>
                                            <button class="btn btn-orange btn-lg jsSaveQuestion csF16"><i
                                                    class="fa fa-save csF16"></i> Save Question</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Question -->
                        <div class="csPageSection jsPageSection dn" data-key="edit_question">
                            <div class="csPageBoxHeader p10">
                                <h4>
                                    <strong>Edit a Question</strong>
                                    <span class="csBTNBox">
                                        <button class="btn btn-black btn-lg mtn8 jsReviewBackStep csF16" data-to="questions"><i aria-hidden="true" class="fa fa-times-circle csF16"></i> Cancel</button>
                                        <button class="btn btn-orange btn-lg mtn8 jsUpdateQuestion csF16"><i aria-hidden="true" class="fa fa-save csF16"></i> Update Question</button>
                                    </span>
                                </h4>
                            </div>
                            <!-- Body2 -->
                            <div class="csPageBoxBody p10">
                                <!-- Edit Question -->
                                <div class="jsEditQuestion">
                                    <!-- Question Body -->
                                    <div class="csPageBoxBody p10">
                                        <!--  -->
                                        <div class="csForm">
                                            <!-- Question  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Question <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <input class="form-control csF16"
                                                        placeholder="e.g. What would you do differently next quarter?"
                                                        id="jsQuestionValEdit" />
                                                </div>
                                            </div>

                                            <!-- Description  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Description</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <textarea class="form-control csF16"
                                                        id="jsQuestionDescriptionEdit"></textarea>
                                                </div>
                                            </div>
                                            
                                            <!-- Options  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Options</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionUseLabelsEdit" /> Use Labels
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionIncludeNAEdit" /> Include N/A
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsStartVideoRecordEdit" /> Include a Video
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <!--  -->
                                                    <div class="csVideoHelpBox">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="jsVideoRecorderBoxEdit dn">
                                                                    <p class="alert alert-danger csF16"><i>To use this
                                                                            feature, please, make sure you have allowed
                                                                            microphone and camera access.</i></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="jsVideoRecorderBoxEdit dn csF16">
                                                                    <video id="jsVideoRecorderEdit" width="100%"></video>
                                                                    <!--  -->
                                                                    <button class="btn btn-orange btn-lg dn csF16"
                                                                        id="jsVideoRecordButtonEdit"><i aria-hidden="true" class="fa fa-stop csF16"></i> Start Recording</button>
                                                                    <!--  -->
                                                                    <button class="btn btn-orange btn-lg dn csF16"
                                                                        id="jsVideoPauseButtonEdit"><i aria-hidden="true" class="fa fa-pause-circle csF16"></i> Pause Recording</button>
                                                                    <!--  -->
                                                                    <button class="btn btn-orange btn-lg dn csF16"
                                                                        id="jsVideoResumeButtonEdit"><i aria-hidden="true" class="fa fa-play-circle csF16"></i> Resume Recording</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="jsVideoPreviewBoxEdit dn">
                                                                    <video id="jsVideoPreviewEdit" width="100%"></video>
                                                                    <button class="btn btn-orange btn-lg csF16"
                                                                        id="jsVideoPlayVideoEdit"><i aria-hidden="true" class="fa fa-play csF16"></i>
                                                                        Play Video</button>
                                                                    <button class="btn btn-black btn-lg csF16"
                                                                        id="jsVideoRemoveButtonEdit"><i
                                                                            class="fa fa-times-circle csF16"></i> Remove
                                                                        Video</button>
                                                                </div>
                                                                <div class="jsVideoPreview2BoxEdit dn">
                                                                    <video id="jsVideoPreview2Edit" width="100%"></video>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Type  -->
                                            <div class="row mb10">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Response Type <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <select id="jsQuestionTypeEdit">
                                                        <option value="text">Text Box</option>
                                                        <option value="rating">Rating Scale</option>
                                                        <option value="text-n-rating">Rating Scale & Text Box</option>
                                                        <option value="multiple-choice">Multiple Choice</option>
                                                        <option value="multiple-choice-with-text">Multiple Choice & Text Box
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Rating Scale  -->
                                            <div class="row mb10 jsQuestionRatingScaleBoxEdit dn">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Rating Scale</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <select id="jsQuestionRatingScaleEdit">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Ratings -->
                                            <div class="row mb10 jsQuestionRatingValBoxEdit dn">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Ratings</label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <div class="form-group jsQuestionRatingScaleValBoxEdit">
                                                        <label class="csF16 csB7">Rating 1</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleValEdit"
                                                            value="<?=getDefaultLabel()[1];?>" data-id="1" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBoxEdit">
                                                        <label class="csF16 csB7">Rating 2</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleValEdit"
                                                            value="<?=getDefaultLabel()[2];?>" data-id="2" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBoxEdit">
                                                        <label class="csF16 csB7">Rating 3</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleValEdit"
                                                            value="<?=getDefaultLabel()[3];?>" data-id="3" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBoxEdit">
                                                        <label class="csF16 csB7">Rating 4</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleValEdit"
                                                            value="<?=getDefaultLabel()[4];?>" data-id="4" />
                                                    </div>
                                                    <div class="form-group jsQuestionRatingScaleValBoxEdit">
                                                        <label class="csF16 csB7">Rating 5</label>
                                                        <input type="text" class="form-control csF16 jsQuestionRatingScaleValEdit"
                                                            value="<?=getDefaultLabel()[5];?>" data-id="5" />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Assigned to  -->
                                            <div class="row mb10 dn">
                                                <div class="col-sm-3 col-xs-12">
                                                    <label class="csF16 csB7">Assigned to <span class="csRequired"></span></label>
                                                </div>
                                                <div class="col-sm-9 col-xs-12">
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionReportingManagerEdit" />
                                                        Reporting Manager
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionSelfEdit" /> Self
                                                        <div class="control__indicator"></div>
                                                    </label> <br />
                                                    <label class="control csB1 csF16 control--checkbox">
                                                        <input type="checkbox" id="jsQuestionPeerEdit" /> Peers and others
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Question Footer -->
                                    <div class="csPageBoxFooter p10">
                                        <span class="csBTNBox">
                                            <input type="hidden" id="jsQuestionId" />
                                            <button class="btn btn-black btn-lg jsReviewBackStep csF16" data-to="questions"><i
                                                    class="fa fa-times-circle csF16"></i> Cancel</button>
                                            <button class="btn btn-orange btn-lg jsUpdateQuestion csF16"><i
                                                    class="fa fa-save csF16"></i> Update Question</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>