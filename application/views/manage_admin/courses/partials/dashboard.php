<!-- Filter -->
<div class="csFilterSection jsFilterSection hidden" data-key="jsPageLoader">
    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-10">
                    <p class="csPanelHeading"><strong><i class="fa fa-filter"></i>&nbsp;Filter</strong></p>
                </div>
                <div class="col-xs-2 text-right">
                    <button class="btn btn-black jsFilterSectionHideBtn" data-key="jsPageLoader">
                        <i class="fa fa-times-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body jsFilterPanel">
            <!-- Filter -->
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Title</strong></label>
                    <input type="text" class="jsCourseTitleDefaultCourse form-control" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Status</strong></label>
                    <select class="jsCourseStatusDefaultCourse form-control">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">InActive</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Job Titles</strong></label>
                    <select class="jsCourseJobTitleDefaultCourse" style="width: 100%;" multiple></select>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <button class="btn btn-theme form-control jsApplyFilterDefaultCourse">Apply Search</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <a href="<?= base_url('sa/lms/courses'); ?>" class="btn btn-black  form-control">Clear Search</a>
                </div>
            </div>
        </div>
    </div>

</div>