<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <!-- Top Menu -->
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top');?>
                    <div class="clearfix"></div>
                    <!-- Main Content area -->
                    <div class="csPageWrap">
                        <br>
                        <div class="clearfix"></div>
                        <!--  -->
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h1 class="csF16 csB7 csW mt0 mb0">Search Filter</h1>
                            </div>
                            <div class="panel-body">
                                <form action="<?=current_url();?>" method="get">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <label class="csF16 csB7">
                                                Reviewer(s)
                                            </label>
                                            <select name="reviewers[]" class="jsReviewers" multiple>
                                                <?php
                                                    if(!empty($employeesList)){
                                                        foreach($employeesList as $emp){
                                                            ?>
                                                            <option value="<?=$emp['Id'];?>" <?=in_array($emp['Id'], $filter['Reviewers']) ? 'selected' : '';?>><?=$emp['Name'];?> <?=$emp['Role'];?></option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <label class="csF16 csB7">
                                                Start Date
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="start_date" readonly id="jsGoalStartDate"  />
                                                <div class="input-group-addon"><i class="fa fa-calendar csF16 csB7"
                                                        aria-hidden="true"></i></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-xs-12">
                                            <label class="csF16 csB7">
                                                End Date
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="end_date" readonly id="jsGoalEndDate" />
                                                <div class="input-group-addon"><i class="fa fa-calendar csF16 csB7"
                                                        aria-hidden="true"></i></div>
                                            </div>
                                        </div>

                                        
                                    </div>
                                    <br>
                                    <!--  -->
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12">
                                            <label class="csF16 csB7">
                                                Status
                                            </label>
                                            <select id="jsGoalStatus" multiple name="goal_types[]">
                                                <option value="0" <?=in_array("0", $filter['Types']) ? 'selected' : '';?>>Pending</option>
                                                <option value="1" <?=in_array("1", $filter['Types']) ? 'selected' : '';?>>Completed</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-8">
                                            <br>
                                            <span class="pull-right">
                                                <a href="<?=current_url();?>" class="btn btn-black">Clear Filter</a>
                                                <button type="submit" class="btn btn-success">Apply Filter</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                if(!empty($MyReviews)){
                                    foreach($MyReviews as $review){
                                     //
                                        $statusClass = 'warning';
                                        //
                                        if($review['is_completed'] == 1){
                                            $statusClass = 'success';
                                        } else{
                                            $statusClass = 'danger';
                                        }
                                        ?>
                        
                                    <div class="col-md-4 col-xs-12">
                                        <div class="panel panel-theme jsReviewBox">
                                            <div class="panel-heading pl5 pr5">
                                                <button class="btn btn-<?=$statusClass;?> btn-xs csF14 csRadius5"><?=strtoupper($review['is_completed'] == 1 ? "COMPLETED" : "PENDING");?></button>
                                                <span class="pull-right">
                                                    <a href="<?=purl('review/'.$review['reviewId'].'/'.$filter['Id'].'/'.$review['reviewer_sid']);?>" target="_blank" class="btn btn-black csF16 btn-xs "  title="View Review" placement="top">
                                                        <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                    </a>
                                                </span>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <p class="csF14 csB7 mb0">Title</p>
                                                <p class="csF14"><?=$review['review_title'];?></p>
                                                <hr />
                                                <p class="csF14 csB7 mb0">Cycle Period</p>
                                                <p class="csF14">
                                                    <?=formatDateToDB($review['review_start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($review['review_end_date'], DB_DATE, DATE);?> <br>
                                                </p>
                                                <hr />
                                                <p class="csF14 csB7 mb0">Reviewer</p>
                                                <p class="csF14">
                                                    <?=ucwords($review['first_name'].' '.$review['last_name']);?> <br>
                                                    <?=remakeEmployeeName($review, false);?> <br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                } else{
                                    ?>
                            <div class="col-sm-12">
                                <p class="csF26 csB7 text-center">
                                    <i class="fa fa-check csF40" aria-hidden="true"></i><br />
                                    No reviews found
                                </p>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Right side bar -->
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>





<div id="my_loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" aria-hidden="true" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;"></div>
    </div>
</div>



<script>
$(function() {
    //
    $('#jsGoalStartDate').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#jsGoalEndDate').datepicker({
        changeYear: true,
        changeMonth: true
    });
    //
    $('#jsGoalStatus').select2({
        closeOnSelect: false
    });
    //
    $('#jsGoalStartDate').val("<?=$filter['start_date'];?>");
    $('#jsGoalEndDate').val("<?=$filter['end_date'];?>");
    //
    $('.jsReviewers').select2();
});
</script>
