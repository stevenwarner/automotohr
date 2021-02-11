<div class="row">
    <div class="col-xs-12">
        <div class="heading-title">
            <div class="page-title">
                <span class="">Section:</span>&nbsp;<?php echo $questionnaire_section['title']; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <form id="form_add_default_questions" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
            <input type="hidden" id="perform_action" name="perform_action" value="add_default_questions" />
            <input type="hidden" id="questionnaire_sid" name="questionnaire_sid" value="<?php echo $questionnaire['sid']; ?>" />
            <input type="hidden" id="questionnaire_section_sid" name="questionnaire_section_sid" value="<?php echo $questionnaire_section['sid']; ?>" />

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
                                        <input name="questions[]" id="question_<?php echo $question['sid']; ?>" value="<?php echo $question['sid']; ?>" type="checkbox">
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
        </form>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <button type="button" onclick="func_add_default_question();" class="btn btn-success"><?php echo $submit_btn_text; ?></button>
        <button type="button" data-dismiss="modal" class="btn black-btn">Cancel</button>
    </div>
</div>