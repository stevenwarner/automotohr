<div class="row">
    <!--title, short_description-->
    <div id="questionnaire_html" class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Questionnaire Information</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive hr-innerpadding">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <th class="col-xs-2">Name</th>
                                    <td><?php echo $questionnaire['title']; ?></td>
                                </tr>
                                <tr>
                                    <th class="col-xs-2">Short Description</th>
                                    <td><?php echo $questionnaire['short_description'] ; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Interview Questionnaire Sections</strong>
                <?php if(isset($is_manage) && $is_manage == 1) { ?>
                <button id="add-section" onclick="show_add_questionnaire_section_modal(<?php echo $questionnaire['sid']?>, 0);" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;Add New Section</button>
                <?php } ?>
            </div>
            <div class="hr-box-body hr-innerpadding">
                <div class="panel-group-wrp questionaire-area">
                    <div class="panel-group" id="accordion">
                        <?php $questionnaire_sections = $questionnaire['sections']; ?>
                        <?php if(!empty($questionnaire_sections)) { ?>
                            <?php foreach($questionnaire_sections as $key => $questionnaire_section) { ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <div class="row">
                                                <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12">
                                                    <a class="accordion-toggle btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                        <?php echo $questionnaire_section['title']; ?>
                                                    </a>   
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 question-custom-btn">
                                                    <?php if(isset($is_manage) && $is_manage == 1) { ?>
                                                <span class="question-btn-custom pull-right">
                                                    <form id="form_delete_section_<?php echo $questionnaire_section['sid']; ?>" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                        <input type="hidden" id="perform_action" name="perform_action" value="delete_section" />
                                                        <input type="hidden" id="section_sid" name="section_sid" value="<?php echo $questionnaire_section['sid']; ?>" />
                                                        <button onclick="func_delete_section(<?php echo $questionnaire_section['sid']; ?>);" type="button" class="btn btn-xs btn-danger pull-left"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </span>
                                                <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                    <button class="btn btn-xs btn-default pull-left" onclick="show_add_questionnaire_section_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>)"><i class="fa fa-pencil"></i></button>
                                                </span>
                                                <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                    <button class="btn btn-xs btn-default pull-left" onclick="show_add_questionnaire_section_question_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>, 0)"><i class="fa fa-plus"></i>&nbsp;Add Question</button>
                                                </span>
                                                <span class="question-btn-custom pull-right" style="margin-right: 5px;">
                                                    <button class="btn btn-xs btn-default pull-left" onclick="show_add_default_question_modal(<?php echo $questionnaire_section['questionnaire_sid']; ?>, <?php echo $questionnaire_section['sid']; ?>, 0)"><i class="fa fa-plus"></i>&nbsp;Add Question From Template</button>
                                                </span>
                                            <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse in <?php //echo ($key == 0 ? 'in': ''); ?>" style="padding: 0; margin: 0;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php $questions = $questionnaire_section['candidate_questions']; ?>
                                                    <?php $my_data['questions'] = $questions; ?>
                                                    <?php $this->load->view('interview_questionnaires/manage_questionnaire_questions_partial', $my_data); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <span class="no-data">No Sections Defined!</span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });

    function disable_editing(){
        $('input[type=radio]:not(:checked)').each(function () {
            $(this).prop('disabled', true);
        });

        $('input[type=checkbox]:not(:checked)').each(function () {
            $(this).prop('disabled', true);
        });

        $('input[type=text]').each(function () {
            $(this).prop('readonly', true);
        });

        $('textarea').each(function () {
            $(this).prop('readonly', true);
        });

        $('#calculate_btn').prop('disabled', true);
        $('#calculate_btn').addClass('disabled');
    }

    function enable_editing(){
        $('input[type=radio]:not(:checked)').each(function () {
            $(this).prop('disabled', false);
        });

        $('input[type=checkbox]:not(:checked)').each(function () {
            $(this).prop('disabled', false);
        });

        $('input[type=text]').each(function () {
            $(this).prop('readonly', false);
        });

        $('textarea').each(function () {
            $(this).prop('readonly', false);
        });

        $('#calculate_btn').prop('disabled', false);
        $('#calculate_btn').removeClass('disabled');

        $('html, body').animate({scrollTop: $('#questionnaire_html').offset().top}, 1000);
    }

    <?php if($is_already_scored == 1 || $is_preview == 1 || $is_manage == 1 ) { ?>
    $(document).ready(function () {
        disable_editing();
    });
    <?php } ?>
</script>