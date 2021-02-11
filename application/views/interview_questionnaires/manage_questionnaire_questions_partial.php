<!--<pre>
    <?php /*print_r($questions); */?>
</pre>-->
<div class="question-listing">
    <?php foreach($questions as $ques_key => $question) { ?>
        <div class="question-row">

            <div class="<?php echo ($question['answer_required'] == 1 ? 'question-title' : 'text-title') ?>">
                <?php echo $question['question_text']; ?>

                <?php if(isset($is_manage) && $is_manage == 1) { ?>
                    <div class="btn-div">
                        <span class="pull-right">
                            <form id="form_delete_question_<?php echo $question['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url();?>">
                                <input type="hidden" id="perform_action" name="perform_action" value="delete_question" />
                                <input type="hidden" id="question_sid" name="question_sid" value="<?php echo $question['sid']; ?>" />
                                <button onclick="func_delete_question(<?php echo $question['sid']; ?>)" type="button" class="btn btn-xs btn-danger pull-left"><i class="fa fa-trash"></i></button>
                            </form>
                        </span>
                        <span class="pull-right" style="margin-right: 5px;">
                            <button onclick="show_add_questionnaire_section_question_modal(<?php echo $question['questionnaire_sid']; ?>, <?php echo $question['questionnaire_section_sid']; ?>, <?php echo $question['sid']; ?>);" class="btn btn-xs btn-success pull-left"><i class="fa fa-pencil"></i></button>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <div class="listing-inner">
                <?php if($question['answer_required'] == 1) { ?>
                    <?php if($question['answer_type'] == 'textual') { ?>
                        <?php $temp = (isset($questionnaire_form) && isset($questionnaire_form['q_' . $question['sid']]) ? $questionnaire_form['q_' . $question['sid']] : '' );?>

                        <textarea style="height:100px;" name="q_<?php echo $question['sid']; ?>" rows="5" class="invoice-fields-textarea"><?php echo set_value('q_' . $question['sid'], $temp); ?></textarea>
                    <?php } elseif ($question['answer_type'] == 'mca_m') { ?>
                        <?php $temp = (isset($questionnaire_form) && isset($questionnaire_form['q_' . $question['sid']]) ? $questionnaire_form['q_' . $question['sid']] : array() );?>

                        <?php $temp = (!is_array($temp) ? array($temp) : $temp ); ?>

                        <div class="checkboxes_container">
                            <?php foreach($question['answer_options'] as $ans_key => $answer_option) { ?>
                                <div class="options-row">
                                    <label class="control control--checkbox">
                                        <?php $default_selected_value = ( in_array($ans_key, $temp) ? true : false );?>
                                        <?php $default_selected_empty = ( $ans_key == 0 ? true : false );?>
                                        <?php $default_selected = ($default_selected_value == true ? true : $default_selected_empty )?>
                                        <input <?php echo set_checkbox('q_' . $question['sid'], $ans_key, $default_selected); ?> name="q_<?php echo $question['sid']; ?>" id="q_<?php echo $question['sid']; ?>_<?php echo clean(trim($ans_key)); ?>" value="<?php echo $ans_key; ?>" type="checkbox">
                                        <?php echo $answer_option; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } elseif ($question['answer_type'] == 'mca_s') { ?>
                        <?php $temp = (isset($questionnaire_form) && isset($questionnaire_form['q_' . $question['sid']]) ? $questionnaire_form['q_' . $question['sid']] : array() );?>

                        <div class="radios_container">
                            <?php foreach($question['answer_options'] as $ans_key => $answer_option) { ?>
                                <div class="options-row">
                                    <label class="control control--radio">
                                        <?php $default_selected_value = ( $ans_key == $temp ? true : false );?>
                                        <?php $default_selected_empty = ( $ans_key == 0 ? true : false );?>
                                        <?php $default_selected = ($default_selected_value == true ? true : $default_selected_empty )?>
                                        <input <?php echo set_checkbox('q_' . $question['sid'], $ans_key, $default_selected); ?> name="q_<?php echo $question['sid']; ?>" id="q_<?php echo $question['sid']; ?>_<?php echo $ans_key; ?>" value="<?php echo $ans_key; ?>" type="radio">
                                        <?php echo $answer_option; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-success"><small>( This section does not require any response to be stored in the system )</small></p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

