<?php 
    //
    $numberOfEmployees = array_unique(array_column($review['reviewees'], 'employee_sid'), SORT_STRING);
    //
    $list = [];
    //
    if(count($numberOfEmployees)){ 
        //
        foreach($numberOfEmployees as $k0 => $v0){
            //
            foreach($review['reviewees'] as $k1 => $v1){
                //
                if($v1['employee_sid'] != $v0) continue;
                //
                $list[$v0][] = $v1;
            }
        }
    }
?>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('dashboard'),
                                'Dashboard',
                            ],
                            'Link2' => [
                                base_url('performance/feedback/'),
                                'Feedback',
                            ],
                            'Text' => 'Performance Feedback'
                        ]); ?>


                        <div class="clearfix"></div>
                        <!-- Table -->

                        <div class="cs-prpage" style="margin-top: 10px;">
                            <!--  -->
                            <div class="panel panel-success">
                                <div class="panel-heading" style="background-color: #81b431; color: #ffffff;">
                                    <strong><?=$review['main']['title'];?></strong>
                                </div>

                                <?php 
                                    if(count($list)) { 
                                        foreach($list as $k => $v){
                                            ?>
                                <div class="panel-body">
                                    <div class="table-responsive table-outer">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Reviewer -> Reviewee</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="jsFeedbackBody<?=$k+1;?>">
                                                <?php if(count($v)) { ?>
                                                <?php   foreach($v as $v) { ?>
                                                <tr data-id="<?=$review['main']['sid'];?>"
                                                    data-cid="<?=$v['conductor_sid'];?>"
                                                    data-eid="<?=$v['employee_sid'];?>">
                                                    <td>
                                                        <strong><?=remakeEmployeeName($v);?></strong>
                                                        <i class="fa fa-long-arrow-right"
                                                            style="padding-left: 20px; padding-right: 20px;"></i>
                                                        <strong><?=remakeEmployeeName([
                                                                        "first_name" => $v['efirst_name'],
                                                                        "last_name" => $v['elast_name'],
                                                                        "access_level" => $v['eaccess_level'],
                                                                        "access_level_plus" => $v['eaccess_level_plus'],
                                                                        "job_title" => $v['ejob_title'],
                                                                        "is_executive_admin" => $v['eis_executive_admin'],
                                                                        "pay_plan_flag" => $v['epay_plan_flag']
                                                                    ]);?></strong>
                                                    </td>
                                                    <td>
                                                        <?=$v['is_completed'] == 1 ? 'Completed' : 'Pending';?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success btn-xs js-answer"><i
                                                                class="fa fa-eye" title="View Answers"></i></button>
                                                    </td>
                                                </tr>
                                                <?php   } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>

                                    <hr />

                                    <div class="hidden">
                                        <label>Rate <span class="cs-required">*</span></label>
                                        <br />
                                        <input class="jsFeedbackRate" type="number" name="rating"
                                            class="rating form-control hide" min="0" max="5" step="0.2"
                                            data-size="xs" />
                                    </div>
                                    <br />

                                    <div>
                                        <label>Feedback <span class="cs-required">*</span></label>
                                        <br />
                                        <textarea id="jsFeedbackContent<?=$k+1;?>"></textarea>
                                    </div>

                                    <script>
                                        CKEDITOR.replace('jsFeedbackContent<?=$k+1;?>');
                                        <?php 
                                            $key = $v['employee_sid'].'-'.$session['employer_detail']['sid'];
                                            if(isset($review['reporting_manager_feedback'][$key])) { ?> CKEDITOR.instances['jsFeedbackContent<?=$k+1;?>'].setData(`<?=$review['reporting_manager_feedback'][$key]['feedback_content'];?>`); <?php }
                                        ?>
                                    </script>

                                    <div class="clearfix"></div>
                                    <br>
                                    <div>
                                        <button class="btn btn-success jsFeedbackSave" data-id="<?=$k+1;?>">Save Feedback</button>
                                    </div>
                                </div>
                                <hr />
                                <?php
                                        }
                                    }
                                ?>
                                <hr />
                                <div style="padding: 10px;">
                                    <button class="btn btn-success jsFeedbackSaveAll">Save All</button>
                                    <a href="<?=base_url('performance/feedback');?>" class="btn btn-default">Cancel</a>
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
<style>
<?php $this->load->view("manage_employer/PerformanceReview/main.css");

?>.cs-loader-file {
    z-index: 1061 !important;
    display: block !important;
    height: 1353px !important;
}

.cs-loader-box {
    position: fixed;
    top: 100px;
    bottom: 0;
    right: 0;
    left: 0;
    max-width: 300px;
    margin: auto;
    z-index: 1539;
}

.cs-loader-box i {
    font-size: 14em;
    color: #81b431;
}

.cs-loader-box div.cs-loader-text {
    display: block;
    padding: 10px;
    color: #000;
    background-color: #fff;
    border-radius: 5px;
    text-align: center;
    font-weight: 600;
    margin-top: 35px;
}

.rating-container .rating-stars {
    color: #81b431;
}
</style>

<!-- Loader -->
<div class="text-center cs-loader js-loader" style="display: none;">
    <div id="file_loader" class="cs-loader-file"></div>
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we are processing your request.</div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review</h5>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    //
    $('.jsFeedbackSave').click(function(e) {
        //
        e.preventDefault();
        //
        saveFeedback($(this));
    });

    function saveFeedback(_this){
        //
        let _id = _this.data('id');
        //
        let target = $(`#jsFeedbackBody${_id}`);
        //
        let obj = {};
        //
        obj.managerSid = "<?=$session['employer_detail']['sid'];?>";
        obj.reviewSid = "<?=$review['main']['sid'];?>";
        obj.employeeSid = target.find('tr').data('eid');
        obj.rate = 1;
        // obj.rate = target.find('.jsFeedbackRate').val();
        obj.feedback = CKEDITOR.instances[`jsFeedbackContent${_id}`].getData();
        //
        if (obj.rate == '-1') {
            alertify.alert('WARNING!', 'Please select the rate.', () => {});
            return true;
        }
        //
        if (obj.feedback.trim() == '') {
            alertify.alert('WARNING!', 'Feedback is required.', () => {});
            return true;
        }
        //
        loader('show');
        //
        $.post(
            "<?=base_url('performance/handler');?>", {
                Action: 'save_feedback',
                data: obj
            },
            (resp) => {
                loader(false);
                
            }
        );
    }
    
    //
    $('.jsFeedbackSaveAll').click(function(e) {
        //
        e.preventDefault();
        //
        let hasError = false;
        //
        $('.jsFeedbackSave').each(function(){
            if(!hasError) hasError = saveFeedback($(this));
        });
        //
        if(!hasError){
             //
            alertify.alert("SUCCESS!", 'Feedback(s) are successfully saved.', () => {
                window.location.reload();
            });
        }
    });

    //
    $('.js-answer').click(function(e) {
        e.preventDefault();
        //
        let obj = {};
        //
        obj.reviewSid = $(this).closest('tr').data('id');
        obj.conductorSid = $(this).closest('tr').data('cid');
        obj.employeeSid = $(this).closest('tr').data('eid');
        //
        loader('show');
        //
        $.post(
            "<?=base_url('performance/handler');?>", {
                Action: 'get_answers',
                data: obj
            },
            (resp) => {
                if (resp.Data.length === 0) {
                    alertify.alert('WARNING!', 'No record found.', () => {});
                    return;
                }
                //
                loadAnswerModal(resp.Data);
            }
        );
    });
    //
    function loader(doShow) {
        if (doShow) {
            $(".cs-loader").show();
        } else {
            $(".cs-loader").hide();
        }
    }
    //
    function loadAnswerModal(data) {
        loader(false);
        $('#modelId .modal-body').html(data);
        $('#modelId').modal('show');
        console.log(data);
    }
});
</script>