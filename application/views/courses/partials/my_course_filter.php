<!-- Filter -->
<div class="csFilterSection jsFilterSection jsMyFilterSection hidden" data-key="jsPageLoader">
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
                    <input type="text" class="jsCourseTitleMyCourse form-control" />
                </div>
                <div class="col-xs-12 col-md-12">
                    <label><strong>Course Status</strong></label>
                    <select class="jsCourseStatus form-control" style="width: 100%;"> 
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <br>
            
            <hr>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <button class="btn btn-theme form-control jsApplyFilterMyCourse">Apply Search</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 col-md-12 text-right">
                    <a href="<?= base_url('lms/courses/my'); ?>" class="btn btn-black  form-control">Clear Search</a>
                </div>
            </div>
        </div>
    </div>

</div>