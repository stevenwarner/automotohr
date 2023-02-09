<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5">
    <div class="panel-heading">
        <h3 class="_csF16">Course Details</h3>
    </div>
    <div class="panel-body">
        <form action="javascript:;">
            <!--  -->
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label>Title <span class="text-danger">*</span></label>
                    <p>What the course will be called. e.g. "Paper Flow"</p>
                </div>
                <div class="col-md-9 col-sm-12">
                    <input type="text" name="course_title" class="form-control" id="jsCourseTitle" required />
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Description </label>
                    <p>What this course is about.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <textarea class="form-control" name="course_description" row="30" id="jsCourseDescription"></textarea>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Course Period <span class="text-danger">*</span></label>
                    <p>When the course will start and end.</p>
                </div>
                <div class="col-md-3 col-sm-12 _csMt10">
                    <input type="text" name="start_date" class="form-control" id="jsStartDate" placeholder="MM/DD/YYYY" readonly>
                </div>
                <div class="col-md-1 col-sm-12 text-center _csVm _csMt10">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </div>
                <div class="col-md-3 col-sm-12 _csMt10">
                    <input type="text" name="end_date" class="form-control" id="jsEndDate" placeholder="MM/DD/YYYY" readonly>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 col-sm-12 _csMt10">
                    <label>Course type <span class="text-danger">*</span></label>
                    <p>Select course type.</p>
                </div>
                <div class="col-md-9 col-sm-12 _csMt10">
                    <label class="control control--radio _csF14">
                        <input type="radio" name="jsCourseChoice" value="upload" />
                        Upload Scorm
                        <span class="control__indicator"></span>
                    </label> 
                    <br />
                    <label class="control control--radio _csF14">
                        <input type="radio" name="jsCourseChoice" value="manual" />
                        Create Manual Course
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
            <!-- Footer -->
            <div class="row">
                <hr />
                <div class="col-sm-12 text-right">
                    <a class="btn _csB1 _csF2 _csR5 _csF14" href="javascript:;">
                        <i class="fa fa-times-circle _csF14" aria-hidden="true"></i>
                        Cancel
                    </a>
                    <a class="btn _csB4 _csF2 _csR5 _csF14 jsSaveCourseBasicDetails">
                        <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                        Save
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>