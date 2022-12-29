<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5">
    <div class="panel-heading">
        <h3 class="_csF16">Survey Details</h3>
    </div>
    <div class="panel-body">
        <form action="javascript:void(0)">
            <!--  -->
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label>Title <span class="text-danger">*</span></label>
                    <p>What the survey will be called. e.g. "Pre Open Enrollment Survey"</p>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" class="form-control" id="jsSurveyTitle" required />
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Description </label>
                    <p>What this survey is about.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <textarea class="form-control _csTA" row="30" id="jsSurveyDescription"></textarea>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Survey Period <span class="text-danger">*</span></label>
                    <p>When the survey will start and end.</p>
                </div>
                <div class="col-md-3 col-sm-12 _csMt10">
                    <input type="text" class="form-control" id="jsStartDate" placeholder="MM/DD/YYYY" readonly>
                </div>
                <div class="col-md-1 col-sm-12 text-center _csVm _csMt10">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </div>
                <div class="col-md-3 col-sm-12 _csMt10">
                    <input type="text" class="form-control" id="jsEndDate" placeholder="MM/DD/YYYY" readonly>
                </div>
            </div>
            <!-- Footer -->
            <div class="row">
                <hr />
                <div class="col-sm-12 text-right">
                    <button class="btn _csB1 _csF2 _csR5 _csF14 jsBackToTemplates">
                        <i class="fa fa-long-arrow-left _csF14" aria-hidden="true"></i>
                        Back To Templates
                    </button>
                    <button class="btn _csB4 _csF2 _csR5 _csF14 jsSaveSurveyDetails">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Save
                    </button>
                    <button class="btn _csB4 _csF2 _csR5 _csF14 jsUpdateSurveyDetails dn">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Update
                    </button>
                    <a href="<?php echo base_url('employee/surveys/create/'.$survey_id.'/questions'); ?>" class="btn _csB3 _csF2 _csR5 _csF14 dn jsQuestionDetailNext">
                        <i class="fa fa-long-arrow-right _csF14" aria-hidden="true"></i>
                        Next
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>