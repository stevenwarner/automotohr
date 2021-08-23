<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-heading mt0 mb0 pb0">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <h5 class="csF16 csW csB7">
                        Course(s)
                    </h5>
                </div>
                <div class="col-xs-12 col-md-10">
                    <span class="pull-right">
                        <a title="Show me active reviews" placement="top" href="<?=current_url();?>?type=active" class="btn btn-orange <?=$type == 'active' ? 'active' : ''?>">Active</a>
                        <a title="Show me archived reviews" placement="top" href="<?=current_url();?>?type=archived" class="btn btn-orange <?=$type == 'archived' ? 'active' : ''?>">Archived</a>
                        <a title="Show me reviews that are in draft" placement="top" href="<?=current_url();?>?type=draft" class="btn btn-orange <?=$type == 'draft' ? 'active' : ''?>">Draft</a>
                    </span>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <p class="csF16">
                        <button class="btn btn-warning btn-xs csRadius5">PENDING</button>&nbsp; The course is active but hasn't started.
                    </p>
                    <p class="csF16">
                        <button class="btn btn-success btn-xs csRadius5">STARTED</button>&nbsp; The course is active and started.
                    </p>
                    <p class="csF16">
                        <button class="btn btn-danger btn-xs csRadius5">ENDED</button>&nbsp; The course is active and ended.
                    </p>
                </div>
            </div>
            <br />
            <!--  -->
            <div class="row">
            <?php
                //
                if(!empty($course_listing)):
                    //
                    foreach($course_listing as $course):
                        //
                        $statusClass = 'warning';
                        //
                        if($course['status'] == 'started'){
                            $statusClass = 'success';
                        } else if($course['status'] == 'ended'){
                            $statusClass = 'danger';
                        }
                        ?>
                        
                            <div class="col-md-4 col-xs-12">
                                <div class="panel panel-theme jsReviewBox" data-id="<?=$course['sid'];?>" data-title="<?=$course['title'];?>">
                                    <div class="panel-heading pl5 pr5">
                                    <?php
                                    if(!$course['is_draft']){?>
                                        <button class="btn btn-<?=$statusClass;?> btn-xs csF14 csRadius5"><?=strtoupper($course['status']);?></button>
                                        <?php } ?>
                                        <span class="pull-right">
                                            <a href="javascript:void(0)" class="btn btn-black csF16 btn-xs edit_course_btn" data-id="<?=$course['sid'];?>" title="Edit Course" placement="top">
                                                <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                            </a>
                                            
                                            <a href="<?=purl('view_course/'.$course['sid']);?>" class="btn btn-black csF16 btn-xs" title="View Course Details" placement="top">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                            </a>
                                            <a href="<?=purl('add_employees/'.$course['sid']);?>" class="btn btn-black csF16 btn-xs jsAddReviewers"  title="Add Employees" placement="top">
                                                <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                                            </a>
                                            <button class="btn btn-black csF16 btn-xs jsReviewVisibility"  title="Manage Visibility" placement="top">
                                                <i class="fa fa-users csF16" aria-hidden="true"></i>
                                            </button>

                                            <?php 
                                            if(!$course['is_draft']){
                                                if($course['is_archived']){
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsActivateReview"  title="Activate Review" placement="top">
                                                        <i class="fa fa-check csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn btn-black csF16 btn-xs jsArchiveReview"  title="Archive Course" placement="top">
                                                        <i class="fa fa-archive csF16" aria-hidden="true"></i>
                                                    </button>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="csF14 csB7 mb0">Title</p>
                                        <p class="csF14"><?=$course['title'];?></p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Cycle Period</p>
                                        <p class="csF14">
                                            <?=formatDateToDB($course['course_start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($course['course_start_date'], DB_DATE, DATE);?> <br>
                                        </p>
                                        <hr />
                                        <p class="csF14 csB7 mb0">Reviewer(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsReviewerProgress<?=$course['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsReviewerProgress<?=$course['sid'];?>">The percentage of reviewers who have submitted the review.</p>
                                        <!-- <p class="csF14"><?=getCompletedPercentage($course['Reviewees'], 'reviewers');?>% Completed</p> -->
                                        <hr />
                                        <p class="csF14 csB7 mb0">Manager(s) Progress <i class="fa fa-question-circle-o csF14 csB7 csCP jsHintBtn" aria-hidden="true" data-target="jsManagerProgress<?=$course['sid'];?>"></i></p>
                                        <p class="jsHintBody" data-hint="jsManagerProgress<?=$course['sid'];?>">The percentage of reporting managers who have submitted the review.</p>
                                        <!-- <p class="csF14"><?=getCompletedPercentage($course['Reviewees'], 'manager');?>% Completed</p> -->
                                    </div>
                                </div>
                            </div>
                            <?php
                    endforeach;
                else:
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="csF16 text-center">
                                No review(s) found.
                            </p>
                        </div>
                    </div>
                    <?php
                endif;
                ?>
            </div>  
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script>
    var employees = <?=json_encode($company_employees);?>;

    $(".edit_course_btn").on('click', function(){
        var course_sid = $(this).data('id');
        var company_sid = $("#company_sid").val();
        var employer_sid = $("#employer_sid").val();
        //
        var form_data = new FormData();
        form_data.append('course_sid', course_sid);
        form_data.append('company_sid', company_sid);
        form_data.append('employer_sid', employer_sid);
        form_data.append('action', 'get_course_info');
        //       
        $('#create_course_loader').show();
        //
        $.ajax({
            url: '<?= base_url('course/handler');?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(resp){
                $("#course_title").val(resp.Data.title);
                $('#course_start_date').datepicker("setDate", resp.Data.course_start_date);
                if (resp.Data.is_course_expired == 'yes') {
                    $("#course_expired_section").show();
                    $("#yes_course_expired").attr("checked","checked");
                    $("#course_expired_day").val(resp.Data.course_expired_day);
                    $('#course_expired_type option[value="'+resp.Data.course_expired_type+'"]').attr("selected", "selected");
                } else {
                    $("#no_course_expired").attr("checked","checked");
                }

                if (resp.Data.course_status == 1) {
                    $("#course_active").attr("checked","checked");
                } else {
                    $("#course_inactive").attr("checked","checked");
                }
                //
                $('.create_course_loader').hide();
                $("#courseModal").modal('show');
            },
            error: function(){
            }
        });
        //
        
    });
</script>