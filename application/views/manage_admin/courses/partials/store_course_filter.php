<!-- Filter -->
<div class="csFilterSection jsFilterSection hidden" data-key="jsStorePageLoader">
    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-10">
                    <p class="csPanelHeading"><strong><i class="fa fa-filter"></i>&nbsp;Filter</strong></p>
                </div>
                <div class="col-xs-2 text-right">
                    <button class="btn btn-black jsFilterSectionHideBtn" data-key="jsStorePageLoader">
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
                    <input type="text" class="jsCourseTitleStoreCourse form-control" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <label><strong>Job Titles</strong></label>
                    <select class="jsCourseJobTitleStoreCourse" style="width: 100%;" multiple></select>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <button class="btn btn-theme form-control jsApplyFilterStoreCourse">Apply Search</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <a href="<?= base_url('sa/lms/courses/'.$companyId); ?>" class="btn btn-black  form-control">Clear Search</a>
                </div>
            </div>
        </div>
    </div>

</div>