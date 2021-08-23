<style>
    .csRoundImg{
        width: 60px !important;
        height: 60px !important;
        border: 2px solid #ddd;
    }
</style>
<div class="col-md-9 col-sm-12">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <span class="pull-right">
                <a class="btn btn-black" href="<?php echo purl('courses'); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back To Courses</a>
            </span>    
        </div>
    </div>
    <br>

    <!--  -->
    <div class="panel panel-theme">
        <!--  -->
        <div class="panel-body">
            <!--  -->
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Basic Info&nbsp;
                        <button class="btn btn-success btn-xs csF16 csRadius5" style="font-size: 16px !important">STARTED</button>
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Title
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo $basic_info['title']; ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                    Start date
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo date("M d Y, D", strtotime($basic_info['course_start_date'])); ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Is the course expire after a certain period of time?
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php echo strtoupper($basic_info['is_course_expired']); ?> 
                    </h5>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <?php if ($basic_info['is_course_expired'] == 'yes') { ?> 
                        <h5 class="csF16 csB7" style="font-size: 16px !important">
                        The Course will expire after
                        </h5>
                        <h5 class="csF14" style="font-size: 14px !important">
                            <?php echo $basic_info['course_expired_day'].' '.ucfirst($basic_info['course_expired_type']); ?> 
                        </h5>
                    <?php } ?> 
                </div>

                <div class="col-sm-6 col-xs-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Course Status
                    </h5>
                    <h5 class="csF14" style="font-size: 14px !important">
                        <?php 
                            if ($basic_info['course_status'] == 1) {
                                echo "Active";
                            } else {
                                echo "Inactive";
                            }
                        ?> 
                    </h5>
                </div>
            </div>  
        </div>
    </div>

    <!--  -->
    <div class="panel panel-theme">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="csF16 csB7" style="font-size: 16px !important">
                        Add Employee(s)&nbsp;
                    </h5>
                </div>
            </div>
            <hr>
            <!--  -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <label>Select Employees <i class="fa fa-question-circle hit_info" data-id="info_select_employee"></i></label>
                    <p id="info_select_employee"><em>Select employee(s) to assign this course.</em></p>
                    <select name="selecte_employees[]" id="jsSelectEmployees" multiple>
                        <?php 
                            //
                            $allowedOnes = empty($document_info['allowed_employees']) ? [] : explode(',', $document_info['allowed_employees']);
                            //
                            if(!empty($employeesList)){
                                foreach($employeesList as $v){
                                    ?>
                                    <option value="<?=$v['sid'];?>" <?=in_array($v['sid'], $allowedOnes) ? 'selected' : '';?>><?=remakeEmployeeName($v);?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">    
                    <div class="form-group">
                        <button class="save_visibility" data-active="employee_section">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        var config = { closeOnSelect: false };
        $('#jsSelectEmployees').select2(config);
    });
</script>    