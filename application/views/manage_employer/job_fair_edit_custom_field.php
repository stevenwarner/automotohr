<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?= $title ?></span>
                    </div>
                    <div class="user-profile-wraper">                        
                        <section class="info-wraper">
                            <section class="personal-info" style="width:100%">
                                <div class="universal-form-style-v2">
                                    <ul>
                                        <li id="show_hide_child" style="display:block;">
                                            <form name="child_question" action="<?php echo $action_url; ?>" method="POST" enctype="multipart/form-data">  
                                                <span class="notes_area" style="height:auto;">
                                                    <li class="form-col-100">
                                                        <label>Question: <samp style="color:red;">*</samp></label>
                                                        <input class="invoice-fields" type="text" name="caption" id="caption" value="<?php echo $custom_field_values[0]['field_name']?>" required />
                                                    </li>
                                                    <li class="form-col-100 autoheight send-email">
                                                        <input id="is_required" type="checkbox" value="1" name="is_required" <?php echo $custom_field_values[0]['is_required'] ? 'checked="checked"' : ''?>>
                                                        <label for="is_required">Is Required</label>
                                                    </li>
                                                    <label>Answer Type: <samp style="color:red;">*</samp></label>
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="string" id="string" name="question_type" onclick="string_radio();" <?php echo $custom_field_values[0]['question_type']=='string'?'checked="checked"':''?>>
                                                            <label for="string">Text</label>
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="boolean" id="boolean" name="question_type" onclick="boolean_radio();"  <?php echo $custom_field_values[0]['question_type']=='boolean'?'checked="checked"':''?>>
                                                            <label for="boolean">Yes / No</label>
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="multilist" id="multilist" name="question_type" onclick="multilist_radio();"  <?php echo $custom_field_values[0]['question_type']=='multilist'?'checked="checked"':''?>>
                                                            <label for="multilist">List of answers with multiple choice</label>
                                                        </div>
                                                    </li>
                                                    <li class="form-col-100 autoheight">
                                                        <div class="hr-radio-btns">
                                                            <input type="radio" value="list" id="list" name="question_type" onclick="list_radio();" <?php echo $custom_field_values[0]['question_type']=='list'?'checked="checked"':''?>>
                                                            <label for="list">List of answers with single choice</label>
                                                        </div>
                                                    </li>
                                                    <div id="question_multilist" class="question_multilist" style="display:none;">
                                                        <div class="form-col-100 question-row">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Answer Choice</label>
                                                                            <input class="invoice-fields" id="multi-list-first" type="text" name="multilist_value[]" value="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="answerAdd"></div>
                                                        <div class="form-col-100" id="add_answer"><a href="javascript:;" onclick="addAnswerBlock(); return false;" class="add"> + Add Answer</a></div>
                                                    </div>
                                                    <div id="question_singlelist" style="display:none;">
                                                        <div class="form-col-100 question-row">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Answer Choice</label>
                                                                            <input class="invoice-fields" id="single-list-first" type="text" name="singlelist_value[]" value="" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="answerAddsingle"></div>
                                                        <div class="form-col-100" id="add_answersingle"><a href="javascript:;" onclick="addAnswerBlocksingle(); return false;" class="add"> + Add Answer</a></div>
                                                    </div>
                                                    <input type="hidden" name="perform_action" value="update_custom_field">
                                                    <div class="btn-panel">
                                                        <input class="submit-btn" type="submit" name="add_child_submit" value="Update Question">
                                                        <input class="submit-btn btn-cancel" onclick="location.href='<?php echo base_url('job_fair_configuration/view_edit/'.$id); ?>';"" type="button" name="cancel" value="Cancel">
                                                    </div>
                                                </span>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<script type="text/javascript">
    function string_radio() {
        //$('#question_yes_no').hide();
        $('#question_multilist').hide();
        $('#question_singlelist').hide();
    }
    
     function boolean_radio() {
        //$('#question_yes_no').show();
        $('#question_multilist').hide();
        $('#question_singlelist').hide();
    }

    function multilist_radio() {
        $('#question_multilist').show();
        $('#question_singlelist').hide();
    }

    function list_radio() {
        $('#question_multilist').hide();
        $('#question_singlelist').show();
    }


    $(function () {
        $('input[name="add_question_submit"]').click(function () {
            if ($('#name').val() == '') {
                alertify.alert("Please provide Questionnaire Name");
                return false;
            }
        });
        setTimeout(function () {
            $(".success").slideUp();
        }, 5000);
        $('input[name="add_child_submit"]').click(function () {
            alertify.defaults.glossary.title = 'Screening Questionnaires Module';
            if ($('#caption').val() == '') {
                alertify.alert("Please provide Question");
                return false;
            }

            if (!$("input[name='question_type']:checked").val()) {
                alertify.alert("Please select 'Answer Type'");
                return false;
            }

            var question_type = $("input[name='question_type']:checked").val();
            
            if (question_type == 'list') {
                console.log(question_type+'    152');
                var answer_single = document.getElementsByName('singlelist_value[]');
                for (var i = 0; i < answer_single.length; i++) {
                    var singlelist_value = answer_single[i].value;
                    //var singlelist_score_value = score_single[i].value;
                    if (singlelist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List of answers with single choice'");
                        return false;
                        break;
                    }
                }
            }

            if (question_type == 'multilist') {
                console.log(question_type+'    165');
                var answer_multi = document.getElementsByName('multilist_value[]');
                for (var i = 0; i < answer_multi.length; i++) {
                    var multilist_value = answer_multi[i].value;
                    if (multilist_value == '') {
                        alertify.alert("Missing 'Answer' for 'List Of Answers With Multiple Choice'");
                        return false;
                        break;
                    }
                }
            }
        });
        setTimeout(function () {
            $(".success").slideUp();
        }, 5000);
    });
    var i = 1;
    function addAnswerBlock(val = '') {
        var id = "answerAdd" + i;
        $("<div id='" + id + "'><\/div>").appendTo("#answerAdd");
        $('#' + id).html($('#' + id).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='multilist_value[]' value='"+val+"' /></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlock('" + id + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div></div><\/div>");
        i++;
    }

    function deleteAnswerBlock(id) {
        $('#' + id).remove();
    }

    var j = 1;
    function addAnswerBlocksingle(val = '') {
        var idj = "answerAddsingle" + j;
        $("<div id='" + idj + "'><\/div>").appendTo("#answerAddsingle");
        $('#' + idj).html($('#' + idj).html() + "<div class='form-col-100 question-row'><div class='container-fluid'><div class='row'><div class='col-lg-11 col-md-11 col-xs-12 col-sm-11'><div class='form-group'><label>Answer Choice</label><input class='invoice-fields' type='text' name='singlelist_value[]' value='"+val+"' /></div></div><div class='col-lg-1 col-md-1 col-xs-12 col-sm-1'><div class='delete-row-new'><a href='javascript:;' onclick=\"deleteAnswerBlocksingle('" + idj + "'); return false;\" class=\"remove\"><i class='fa fa-times'></i><\/a></div></div></div></div></div><\/div>");
        j++;
    }

    function deleteAnswerBlocksingle(idj) {
        $('#' + idj).remove();
    }
    // Pre filling selects.
    $(document).ready(function(){
        var flag = 0;
        <?php foreach($custom_field_values as $value){
            if($value['question_type']=='list'){ ?>
                $('#question_singlelist').show();
                if(flag==0){
                    $('#single-list-first').val('<?=$value['value']?>');
                    flag=1;
                }else{
                    addAnswerBlocksingle('<?=$value['value']?>');
                }
        <?php }
            elseif($value['question_type']=='multilist'){ ?>
                $('#question_multilist').show();
                if(flag==0){
                    $('#multi-list-first').val('<?=$value['value']?>');
                    flag=1;
                }else{
                    addAnswerBlock('<?=$value['value']?>');
                }

        <?php }
        } ?>
    });


</script>