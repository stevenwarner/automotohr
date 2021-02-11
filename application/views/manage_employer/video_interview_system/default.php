<div class="row">
    <div class="col-xs-12">
        <form id="form_add_default_questions" enctype="multipart/form-data" method="post" action="<?php echo base_url('video_interview_system/add_default'); ?>">
            <?php if(!empty($questions)) { ?>
                <?php foreach($questions as $category_questions) { ?>
                    <div style="color: #00a700; font-size: 18px; border-bottom: solid #00a700 thin; margin-bottom: 10px;">
                        <strong><?php echo $category_questions['name']; ?></strong>
                    </div>
                    <ul style="margin-left: 20px;" class="list-group">
                        <?php if(!empty($category_questions['questions'])) { ?>
                            <?php foreach($category_questions['questions'] as $question) { ?>
                                <li class="list-group-item">
                                    <label class="control control--checkbox">
                                        <?php echo $question['question_text']; ?>
                                        <input name="questions[]" id="question_<?php echo $question['sid']; ?>" value="<?php echo $question['question_text']; ?>" type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="list-group-item list-group-item-info text-center">No Default Questions Available</li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            <?php } ?>
            <?php if(isset($template_sid)){ ?>
                <input type="hidden" name="template_sid" id="template_sid" value="<?php echo $template_sid; ?>" />
            <?php } ?>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <button type="button" onclick="func_add_default_question();" class="btn btn-success"><?php echo $submit_btn_text; ?></button>
        <button type="button" data-dismiss="modal" class="btn black-btn">Cancel</button>
    </div>
</div>

<script>
    function func_add_default_question(){
        var total_selected = $('input[type=checkbox]:checked').length;

        if(total_selected > 0){
            $('#form_add_default_questions').submit();
        } else {
            alertify.error('Please Select Questions to Add');
        }
    }
</script>