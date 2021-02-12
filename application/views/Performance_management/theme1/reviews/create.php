<div class="container-fluid">
    <div class="csPageBox csRadius5">
        <!-- Page Header -->
        <div class="csPageBoxHeader p10">
            <h4>
                <span class="csBTNBox">
                    <button class="btn btn-black btn-lg mtn8 dn jsFinishLater"><i class="fa fa-edit"></i> Finish
                        Later</button>
                    <a href="<?=purl('reviews');?>" class="btn btn-black btn-lg mtn8"><i
                            class="fa fa-long-arrow-left"></i> All Reviews</a>
                </span>
                <strong>Create a Review </strong><span id="jsReviewTitleHeader"></span>
            </h4>
        </div>

        <!-- Page Body -->
        <div class="csPageBoxBody p10">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="create_review"><i class="fa fa-circle-o-notch fa-spin"></i></div>
            <div class="row">
                <!-- Left menu -->
                <div class="col-sm-2 col-xs-12">
                    <ul class="csPageLeftMenu ma10">
                        <li class="jsReviewStep active" data-to="templates">
                            <span>Templates</span> <i class="fa fa-long-arrow-right"></i>
                        </li>
                        <li class="jsReviewStep" data-to="schedule">
                            <span>Name & Schedule</span>
                        </li>
                        <li class="jsReviewStep" data-to="reviewees">
                            <span>Reviewees</span>
                        </li>
                        <li class="jsReviewStep" data-to="reviewers">
                            <span>Reviewers</span>
                        </li>
                        <li class="jsReviewStep" data-to="questions">
                            <span>Questions</span>
                        </li>
                        <li class="jsReviewStep" data-to="feedback">
                            <span>Sharing Feedback</span>
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
                                <h3>Craft a new review from the ground up or pick a template with insightful questions.</h3>
                            </div>
                            <!-- Box Body -->
                            <div class="csPageBoxBody p10">
                                <div class="csForm pa10 pb10">
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <label>Options <span class="csRequired"></span></label> <br />
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control control--radio">
                                                New Review
                                                <input type="radio" name="templateType" class="jsReviewType" value="new" checked />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Use Template
                                                <input type="radio" name="templateType" class="jsReviewType" value="template" />
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
                                    <h4 class="pl10  pb10">
                                        Templates
                                        <span class="csBTNBox">
                                            <a href="<?=purl('settings/templates');?>" class="btn btn-black mt0">Manage Templates</a>
                                        </span>
                                    </h4>
                                </div>
                                <!-- Box Body -->
                                <div class="csPageBoxBody p10">
                                    <!-- Personal Templates -->
                                    <div class="csSection">
                                        <!--  -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h5>Personal Templates (<?=count($templates['personal']);?>)</h5>
                                            </div>
                                        </div>
                                        <?php if(!empty($templates['personal'])): ?>
                                            <div class="row">
                                        <?php   foreach($templates['personal'] as $template): ?>
                                                <!-- Template row -->
                                                <div class="col-sm-3">
                                                    <div class="csPageBox csRadius5 jsTemplateBox" data-id="<?=$template['sid'];?>" data-type="personal" data-name="<?=$template['name'];?>">
                                                        <div class="csPageBoxHeader p10">
                                                            <span class="csBTNBox ">
                                                                <button class="btn btn-orange btn-xs jsTemplateQuestionsView"><strong><i class="fa fa-eye"></i> View Questions</strong></button>
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
                                                <i class="fa fa-info-circle"></i>
                                                <p>There are currently no custom templates.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Company Templates -->
                                    <div class="csSection">
                                         <!--  -->
                                         <div class="row">
                                            <div class="col-sm-12">
                                                <h5>Company Templates (<?=count($templates['company']);?>)</h5>
                                            </div>
                                        </div>
                                        <?php if(!empty($templates['company'])): ?>
                                            <div class="row">
                                        <?php   foreach($templates['company'] as $template): ?>
                                                <!-- Template row -->
                                                <div class="col-sm-3">
                                                    <div class="csPageBox csRadius5 jsTemplateBox" data-id="<?=$template['sid'];?>" data-type="company" data-name="<?=$template['name'];?>">
                                                        <div class="csPageBoxHeader p10">
                                                            <span class="csBTNBox ">
                                                                <button class="btn btn-orange btn-xs jsTemplateQuestionsView"><strong><i class="fa fa-eye"></i> View Questions</strong></button>
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
                                                <i class="fa fa-info-circle"></i>
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
                                    <button class="btn btn-orange btn-lg jsReviewStep" data-to="schedule"><i class="fa fa-arrow-circle-right"></i> Next</button>
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
                                            <label class="pa10">Name <span class="csRequired"></span></label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" class="form-control csRadius100" id="jsReviewTitle" />
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label>Description</label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <textarea class="form-control csRadius5" rows="3" id="jsReviewDescription"></textarea>
                                        </div>
                                    </div>

                                    <div class="bbt mb10"></div>

                                    <!--  -->
                                    <div class="row mb10">
                                        <div class="col-sm-12">
                                            <h4>Schedule</h4>
                                        </div>
                                    </div>

                                    <!-- Schedule -->
                                    <div class="row mb10">
                                        <div class="col-sm-3 col-xs-12">
                                            <label>Frequency</label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control control--radio">
                                                One-time only
                                                <input type="radio" name="frequency" class="jsReviewFrequency" value="onetime" checked />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Recurring
                                                <input type="radio" name="frequency" class="jsReviewFrequency" value="repeat" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Custom Schedule
                                                <input type="radio" name="frequency" class="jsReviewFrequency" value="custom" />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="jsFrequencyBox jsFrequencyORBox">
                                        <!-- Period -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">Review Period</label>
                                            </div>
                                            <div class="col-sm-3 col-xs-12 pr0">
                                                <input type="text" class="form-control csRadius100" id="jsReviewStartDate" readonly
                                                    placeholder="MM/DD/YYYY" />
                                            </div>
                                            <div class="col-sm-1 col-xs-12 pl0 pr0">
                                                <p class="text-center ma10"><i class="fa fa-minus"
                                                        style="font-size: 22px;"></i></p>
                                            </div>
                                            <div class="col-sm-3 col-xs-12 pl0">
                                                <input type="text" class="form-control csRadius100" id="jsReviewEndDate" readonly
                                                    placeholder="MM/DD/YYYY" />
                                            </div>
                                        </div>

                                        <!-- Repeat -->
                                        <div class="row mb10 jsFrequencyBox jsFrequencyRepeatBox dn">
                                            <div class="col-sm-3 col-xs-12">
                                                <label class="pa10">Recur Every</label>
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <input type="text" class="form-control csRadius100" placeholder="0" id="jsReviewRepeatVal" />
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <select id="jsReviewRepeatType">
                                                    <option value="days">Days</option>
                                                    <option value="weeks">Weeks</option>
                                                    <option value="months">Months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="jsFrequencyBox jsFrequencyCustom bbt dn">
                                        <!-- Info -->
                                        <div class="row mb10">
                                            <div class="col-sm-12">
                                                <p class="pa10 text-justify" style="font-size: 16px;">You can create
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
                                                    <i class="fa fa-plus-circle"></i> Add another run
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Continue -->
                                        <div class="row mb10">
                                            <div class="col-sm-3 col-xs-12">
                                                <label>Continue Review</label>
                                            </div>
                                            <div class="col-sm-9 col-xs-12">
                                                <label class="control control--checkbox">
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
                                                <label class="pa10">When are reviews due?</label>
                                            </div>
                                            <div class="col-sm-2 col-xs-12">
                                                <input type="text" class="form-control csRadius100" placeholder="0" id="jsReviewDue" />
                                            </div>
                                            <div class="col-sm-7 col-xs-12">
                                                <h5>days after they start</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="csPageBoxFooter p10">
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg jsReviewBackStep" data-to="templates"><i class="fa fa-long-arrow-left"></i> Back To Templates</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep" data-to="reviewees"><i class="fa fa-arrow-circle-right"></i>Save & Next</button>
                                    <button class="btn btn-black btn-lg dn jsFinishLater"><i class="fa fa-edit"></i> Finish Later</button>
                                </span>
                                <!--  -->
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Reviewees -->
                        <div class="csPageSection jsPageSection dn" data-key="reviewees">
                            <div class="csPageBoxHeader pl10">
                                <h4><strong>Select Reviewees</strong></h4>
                            </div>
                            <div class="csPageBoxHeader pl10 pa10 pb10">
                                <h5>Use the Rule Settings to define which workers are included (or excluded) from this
                                    review.</h5>
                                <h5>Note: The preview shows the selected workers as of today and may differ from when
                                    the review starts.</h5>
                            </div>
                            <div class="csPageBoxBody">
                                <div class="row">
                                    <!-- Filter Bar -->
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="csFilterBox p10">
                                            <h4>
                                                Rule Settings
                                                <span class="csBTNBox">
                                                    <button class="btn btn-black btn-xs jsResetFilter">Reset</button>
                                                </span>
                                            </h4>
                                            <!-- Individual Row -->
                                            <div class="row pa10 pb10">
                                                <div class="col-sm-12">
                                                    <label>Individuals</label>
                                                    <select id="jsFilterIndividuals" multiple="true"></select>
                                                </div>
                                            </div>
                                            <!-- Include -->
                                            <h5 class="pa10 bbt">INCLUDE</h5>
                                            <!-- Departments Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label>Departments</label>
                                                    <select id="jsFilterDepartments" multiple>
                                                        <?php if(!empty($dnt['departments'])): ?>
                                                        <?php   foreach($dnt['departments'] as $department): ?>
                                                        <option value="<?=$department['sid'];?>"><?=$department['name'];?></option>
                                                        <?php   endforeach; ?>
                                                        <?php   endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Teams Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label>Teams</label>
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
                                                    <label>Employment Type</label>
                                                    <select id="jsFilterEmploymentType" multiple>
                                                        <option value="fulltime">Full-time</option>
                                                        <option value="parttime">Part-time</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Job Titles Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label>Job Titles</label>
                                                    <select id="jsFilterJobTitles" multiple>
                                                    <?php if(!empty($jobTitles)): ?>
                                                        <?php   foreach($jobTitles as $jobTitle):?>
                                                        <option value="<?=$jobTitle['job_title'];?>"><?=$jobTitle['job_title'];?></option>
                                                        <?php   endforeach; ?>
                                                        <?php   endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Exclude -->
                                            <h5 class="pa10 bbt">EXCLUDE</h5>
                                            <!-- Departments Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label>Exclude Individuals</label>
                                                    <select id="jsFilterExcludeEmployees" multiple></select>
                                                </div>
                                            </div>
                                            <!-- Teams Row -->
                                            <div class="row pa10">
                                                <div class="col-sm-12">
                                                    <label>Exclude New Hires</label>
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
                                                    <li><a href="javascript:void(0)" class="active jsShiftTab" data-target="included">Included <span id="jsIncudedEmployeeCount">(0)</span></a></li>
                                                    <li><a href="javascript:void(0)" class="jsShiftTab" data-target="excluded">Excluded <span id="jsExcludedEmployeeCount">(0)</span></a></li>
                                                </ul>
                                            </div>
                                            <div class="csPageBoxBody">
                                                <!-- Loader -->
                                                <div class="csIPLoader jsIPLoader" data-page="review_incexc"><i class="fa fa-circle-o-notch fa-spin"></i></div>
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
                                    <button class="btn btn-black btn-lg jsReviewBackStep" data-to="schedule"><i class="fa fa-long-arrow-left"></i> Back To Schedule</button>
                                    <button class="btn btn-orange btn-lg jsReviewStep" data-to="reviewers"><i class="fa fa-arrow-circle-right"></i>Save & Next</button>
                                    <button class="btn btn-black btn-lg jsFinishLater dn"><i class="fa fa-edit"></i> Finish Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Reviewers -->
                        <div class="csPageSection jsPageSection dn" data-key="reviewers">
                            <div class="csPageBoxHeader p10">
                                <h5>All reviews are submitted to the reporting manager</h5>
                            </div>
                            <div class="csPageBoxBody p10">
                                <div class="csForm">
                                    <!-- Row 1 -->
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-12">
                                            <label>Reviewers</label>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <label class="control control--radio">
                                                Reporting Manager
                                                <input type="radio" name="reviewerType" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Self-Review
                                                <input type="radio" name="reviewerType" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Peers (Colleagues)
                                                <input type="radio" name="reviewerType" />
                                                <div class="control__indicator"></div>
                                            </label> <br />
                                            <label class="control control--radio">
                                                Specific Reviewers
                                                <input type="radio" name="reviewerType" />
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
                                            <h5>Reviewees <span id="jsReviewTotalRevieweeCount"></span></h5>
                                        </div>
                                        <div class="col-sm-9 col-xs-12">
                                            <h5>Assigned Reviewers</h5>
                                        </div>
                                    </div>
                                    <div class="bbt"></div>
                                    <div id="jsReviewRevieweeWrap"></div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="csPageBoxFooter p10">
                                    <span class="csBTNBox">
                                        <button class="btn btn-black btn-lg"><i class="fa fa-long-arrow-left"></i> Back  To Reviewees</button>
                                        <button class="btn btn-orange btn-lg"><i class="fa fa-arrow-circle-right"></i>Save & Next</button>
                                        <button class="btn btn-black btn-lg"><i class="fa fa-edit"></i> Finish Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Questions -->
                        <div class="csPageSection jsPageSection dn" data-key="questions">
                            <!-- Header -->
                            <div class="csPageBoxHeader p10">
                                <h4>
                                    <strong>Questions</strong>
                                    <span class="csBTNBox">
                                        <button class="btn btn-orange mtn8">
                                            <i class="fa fa-plus-circle"></i> Add A Question
                                        </button>
                                    </span>
                                </h4>
                            </div>
                            <!-- Body -->
                            <div class="csPageBoxBody">
                                <?php for($i = 1; $i < 10; $i ++) { ?>
                                <div class="csQuestionRow">
                                    <div class="csFeedbackViewBox p10">
                                        <h4>
                                            <strong>Question <?=$i;?></strong>
                                            <span class="csBTNBox">
                                                <i class="fa fa-long-arrow-down" title="Move down" placement="top"></i>
                                                <i class="fa fa-long-arrow-up" title="Move up" placement="top"></i>
                                                <span>|</span>
                                                <i class="fa fa-clone" title="Clone question" placement="top"></i>
                                                <i class="fa fa-trash"  title="Delete question" placement="top"></i>
                                                <i class="fa fa-pencil" title="Edit question" placement="top"></i>
                                            </span>
                                        </h4>
                                        
                                        <h4><strong>Please add any additional feedback you'd like to note for this
                                                person?</strong></h4>
                                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellendus
                                            molestiae
                                            ducimus nam beatae autem placeat, corrupti quam.
                                        </p>
                                        <label class="control control--checkbox mb10">
                                            Not Applicable
                                            <input type="checkbox" name="" id="">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <br />
                                        <br />
                                        <!--  -->
                                        <ul>
                                            <li>
                                                <div class="csFeedbackViewBoxTab">
                                                    <p class="mb0">1</p>
                                                    <p>Strongly Agree</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="csFeedbackViewBoxTab">
                                                    <p class="mb0">2</p>
                                                    <p>Agree</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="csFeedbackViewBoxTab">
                                                    <p class="mb0">3</p>
                                                    <p>Neutral</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="csFeedbackViewBoxTab">
                                                    <p class="mb0">4</p>
                                                    <p>Disagree</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="csFeedbackViewBoxTab">
                                                    <p class="mb0">5</p>
                                                    <p>Strongly disagree</p>
                                                </div>
                                            </li>
                                        </ul>
                                        
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <!--  -->
                            <div class="csPageBoxFooter ban p10">
                                    <span class="csBTNBox">
                                        <button class="btn btn-black btn-lg"><i class="fa fa-long-arrow-left"></i> Back To Reviewers</button>
                                        <button class="btn btn-orange btn-lg"><i class="fa fa-arrow-circle-right"></i>Save & Next</button>
                                        <button class="btn btn-black btn-lg"><i class="fa fa-edit"></i> Finish Later</button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <!-- Feedback -->
                        <div class="csPageSection jsPageSection dn" data-key="feedback">
                            <!-- Header -->
                            <div class="csPageBoxHeader p10">
                                <h4><strong>Sharing Feedback</strong></h4>
                            </div>
                            <!-- Body -->
                            <div class="csPageBoxBody p10">
                                <div class="csSharingFeedbackBox">
                                    <h5>Tell us how you want managers to share feedback with their reports.</h5>
                                    <br />
                                    <ul>
                                        <li class="active csRadius5">
                                            <i class="fa fa-eye"></i> The manager summarizes all reviews and shares the
                                            summary with their report
                                        </li>
                                        <li class="csRadius5">
                                            <i class="fa fa-eye-slash"></i> Nothing is shared with their report
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Footer -->
                            <div class="csPageBoxFooter pa10">
                                <span class="csBTNBoxLeft">
                                    <button class="btn btn-black btn-lg"><i class="fa fa-edit"></i> Finish Later</button>
                                </span>
                                <span class="csBTNBox">
                                    <button class="btn btn-black btn-lg"><i class="fa fa-long-arrow-left"></i> Back</button>
                                    <button class="btn btn-orange btn-lg"><i class="fa fa-arrow-circle-right"></i>Save & Finish</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>