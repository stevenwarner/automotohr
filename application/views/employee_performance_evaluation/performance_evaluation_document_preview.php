<style>
    .jsSectionOne:nth-child(even) {
        background: #eee;
    }

    .jsSectionTwo:nth-child(even) {
        background: #eee;
    }

    .jsSectionThree:nth-child(even) {
        background: #eee;
    }

    .jsSectionTwo {
        padding: 10px 5px !important;
    }

    textarea {
        resize: none;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <a href="<?= base_url('employee_management_system'); ?>" class="btn btn-info csRadius5">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <!-- / -->
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <h4 class="alert alert-default" style="padding: 0">
                                                    <strong>Employee Performance Evaluation Document</strong>
                                                </h4>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <hr>
                                        <!--  -->
                                        <div role="tabpanel" id="js-main-page">
                                            <!-- Employee, Applicant boxes -->
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <!-- Tab panes -->
                                                    <div class="tab-content">
                                                        <!-- Employee Box -->
                                                        <div role="tabpanel" class="tab-pane active" id="employee-box">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <h5><strong>Employee Name :</strong> <?=$assignTo?></h5>
                                                                    <h5><strong>Assign On :</strong> <?=$assignOn?></h5>
                                                                    <h5><strong>Assign By :</strong> <?=$assignBy?></h5>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <?php
                                                                    $section_1 = [];
                                                                    if ($sectionData['section_1_json']) {
                                                                        $section_1 = json_decode($sectionData['section_1_json'], true)['data'];
                                                                    }
                                                                    ?>

                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section_one">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                    Manager Section 1: Employee Year in Review Evaluation
                                                                                </a>
                                                                            </h4>
                                                                        </div>

                                                                        <div id="collapse_section_one" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="panel-body">
                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Employee Name</label>
                                                                                            <input type="text" readonly name="epe_employee_name" class="form-control input-bg" value="<?= $section_1['epe_employee_name'] ?? $defaultData['epe_employee_name'] ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Job Title</label>
                                                                                            <input type="text" readonly name="epe_job_title" class="form-control input-bg" value="<?= $section_1['epe_job_title'] ?? $defaultData['epe_job_title'] ?>" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Department</label>
                                                                                            <input type="text" readonly name="epe_department" class="form-control input-bg" value="<?= $section_1['epe_department'] ?? $defaultData['epe_department'] ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Manager</label>
                                                                                            <input type="text" readonly name="epe_manager" class="form-control input-bg" value="<?= $section_1['epe_manager'] ?? $defaultData['epe_manager'] ?>" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Hire Date</label>
                                                                                            <input type="text" readonly name="epe_hire_date" class="form-control input-bg" value="<?= $section_1['epe_hire_date'] ?? $defaultData['epe_hire_date'] ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Start Date in Current Position</label>
                                                                                            <input type="text" readonly name="epe_start_date" class="form-control input-bg" value="<?= $section_1['epe_start_date'] ?? $defaultData['epe_hire_date'] ?>" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Review Period Start</label>
                                                                                            <input type="text" readonly name="epe_review_start" class="form-control input-bg" value="<?= $section_1['epe_review_start'] ?? "" ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Review Period End</label>
                                                                                            <input type="text" readonly name="epe_review_end" class="form-control input-bg" value="<?= $section_1['epe_review_end'] ?? "" ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                Rate the employee in each area below. Comments are required for each section.
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                POSITION KNOWLEDGE:
                                                                                            </strong>
                                                                                            To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Knowledge is below the minimum requirements of the position. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_knowledgeable_radio" value="1" <?php echo $section_1['position_knowledgeable_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Knowledge is sufficient to perform the requirements of the position.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_knowledgeable_radio" value="2" <?php echo $section_1['position_knowledgeable_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is exceptionally well informed and competent in all aspects of the position.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_knowledgeable_radio" value="3" <?php echo $section_1['position_knowledgeable_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="position_knowledgeable_comments" rows="10" class="form-control"><?= $section_1['position_knowledgeable_comments'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee's position knowledge be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="position_improved" class="form-control" value="<?php echo $section_1['position_improved'] ? $section_1['position_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                QUALITY OF WORK:
                                                                                            </strong>
                                                                                            Evaluate the quality of work produced.
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Output is below that required of the position. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_improved_radio" value="1" <?php echo $section_1['position_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Output meets that required of the position.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_improved_radio" value="2" <?php echo $section_1['position_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Output consistently exceeds that required of the position.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="position_improved_radio" value="3" <?php echo $section_1['position_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <strong>
                                                                                                Comments
                                                                                            </strong>
                                                                                            <br>
                                                                                            <textarea readonly name="position_improved_comment" rows="10" class="form-control"><?= $section_1['position_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee's quantity of work be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="quantity_improved" class="form-control" value="<?php echo $section_1['quantity_improved'] ? $section_1['quantity_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                QUALITY OF WORK:
                                                                                            </strong>
                                                                                            Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Quality of work is frequently below position requirements. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quantity_improved_radio" value="1" <?php echo $section_1['quantity_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Quality of work meets position requirements.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quantity_improved_radio" value="2" <?php echo $section_1['quantity_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Quality of work consistently exceeds position requirements.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quantity_improved_radio" value="3" <?php echo $section_1['quantity_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="quantity_improved_comment" rows="10" class="form-control"><?= $section_1['quantity_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employees’ quality of work be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="quality_improved" class="form-control" value="<?php echo $section_1['quality_improved'] ? $section_1['quality_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                INTERPERSONAL RELATIONS:
                                                                                            </strong>
                                                                                            To what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is frequently non-supportive. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quality_improved_radio" value="1" <?php echo $section_1['quality_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately contributes to supportive environment.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quality_improved_radio" value="2" <?php echo $section_1['quality_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee consistently contributes to supportive work environment.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="quality_improved_radio" value="3" <?php echo $section_1['quality_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="quality_improved_comment" rows="10" class="form-control"><?= $section_1['quality_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee’s interpersonal relations be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="relations_improved" class="form-control" value="<?php echo $section_1['relations_improved'] ? $section_1['relations_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                Mission:
                                                                                            </strong>
                                                                                            To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Level of mission focus is often below the required/acceptable standard. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="relations_improved_radio" value="1" <?php echo $section_1['relations_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately contributes to high quality mission.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="relations_improved_radio" value="2" <?php echo $section_1['relations_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee consistently demonstrates exceptional commitment to the mission.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="relations_improved_radio" value="3" <?php echo $section_1['relations_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="relations_improved_comment" rows="10" class="form-control"><?= $section_1['relations_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee’s customer service skills/delivery be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="skill_improved" class="form-control" value="<?php echo $section_1['skill_improved'] ? $section_1['skill_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                DEPENDABILITY:
                                                                                            </strong>
                                                                                            To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is late, absent, misses deadlines. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="skill_improved_radio" value="1" <?php echo $section_1['skill_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately attends work, rarely misses or late, meets deadlines.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="skill_improved_radio" value="2" <?php echo $section_1['skill_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee consistently on time, at work and completes deadlines ahead of schedule.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="skill_improved_radio" value="3" <?php echo $section_1['skill_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="skill_improved_comment" rows="10" class="form-control"><?= $section_1['skill_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee’s dependability be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="dependability_improved" class="form-control" value="<?php echo $section_1['dependability_improved'] ? $section_1['dependability_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <p class="text-large">
                                                                                            <strong>
                                                                                                ADHERENCE TO POLICY & PROCEDURE:
                                                                                            </strong>
                                                                                            To what level does the employee adhere to standard operating policies and procedures?
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is frequently coached on standard operating policies and procedures. Improvement is mandatory.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="dependability_improved_radio" value="1" <?php echo $section_1['dependability_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately adheres to standard operating policies and procedures with few reminders.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="dependability_improved_radio" value="2" <?php echo $section_1['dependability_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is consistently exceptional in following standard operating policies and procedures.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="dependability_improved_radio" value="3" <?php echo $section_1['dependability_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="dependability_improved_comment" rows="10" class="form-control"><?= $section_1['dependability_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may the employee’s adherence to policy and procedure be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="policy_procedure_improved" class="form-control" value="<?php echo $section_1['policy_procedure_improved'] ? $section_1['policy_procedure_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 mrg-bottom-20">
                                                                                        <label for="">OTHER:</label>
                                                                                        <textarea readonly name="policy_procedure_improved_other" rows="10" class="form-control"><?= $section_1['policy_procedure_improved_other'] ?? '' ?></textarea>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee frequently falls below acceptable standard as outlined above.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="policy_procedure_improved_radio" value="1" <?php echo $section_1['policy_procedure_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately meets standard as outlined above.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="policy_procedure_improved_radio" value="2" <?php echo $section_1['policy_procedure_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is consistently exceptional in meeting performance standard.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="policy_procedure_improved_radio" value="3" <?php echo $section_1['policy_procedure_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Comments</label>
                                                                                            <textarea readonly name="policy_procedure_improved_comment" rows="10" class="form-control"><?= $section_1['policy_procedure_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                How may employee’s performance in meeting this standard be improved?
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="field-row mrg-bottom-20">
                                                                                            <input type="text" readonly name="standard_improved" class="form-control" value="<?php echo $section_1['standard_improved'] ? $section_1['standard_improved'] : '' ?>">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 mrg-bottom-20">
                                                                                        <label>OTHER:</label>
                                                                                        <textarea readonly name="standard_improved_other" rows="10" class="form-control"><?= $section_1['standard_improved_other'] ?? '' ?></textarea>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee frequently falls below acceptable standard as outlined above.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="standard_improved_radio" value="1" <?php echo $section_1['standard_improved_radio'] == 1 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee adequately meets standard as outlined above.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="standard_improved_radio" value="2" <?php echo $section_1['standard_improved_radio'] == 2 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <label class="control control--radio">
                                                                                            <span class="text-large">
                                                                                                Employee is consistently exceptional in meeting performance standard.
                                                                                            </span>
                                                                                            <input type="radio" disabled name="standard_improved_radio" value="3" <?php echo $section_1['standard_improved_radio'] == 3 ? 'checked' : '' ?> />
                                                                                            <div class="control__indicator"></div>
                                                                                        </label>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="license_notes">Comments</label>
                                                                                            <textarea name="standard_improved_comment" rows="10" readonly class="form-control"><?= $section_1['standard_improved_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                Managers Additional Comments for the Review Period:
                                                                                            </strong>
                                                                                        </h3>
                                                                                    </div>

                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <textarea name="managers_additional_comment" rows="10" readonly class="form-control"><?= $section_1['managers_additional_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    $section_2 = [];
                                                                    if ($sectionData['section_2_json']) {
                                                                        $section_2 = json_decode($sectionData['section_2_json'], true)['data'];
                                                                    }
                                                                    ?>

                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section_two">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                    Employee Section 2: The Year in Review
                                                                                </a>
                                                                            </h4>
                                                                        </div>

                                                                        <div id="collapse_section_two" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="panel-body">
                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                List 3-4 top accomplishments & add a reflection on overall performance for the year.
                                                                                            </strong>
                                                                                        </h3>

                                                                                        <table class="table table-bordered">
                                                                                            <thead class="thead-dark">
                                                                                                <tr>
                                                                                                    <th scope="col"></th>
                                                                                                    <th scope="col">Accomplishment</th>
                                                                                                    <th scope="col">Employee Comments/Reflection</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th scope="row">1</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="accomplishment_1" readonly class="form-control" value="<?= $section_2['accomplishment_1'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="accomplishment_comment_1" rows="4" readonly class="form-control"><?= $section_2['accomplishment_comment_1'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">2</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="accomplishment_2" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment2" value="<?= $section_2['accomplishment_2'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="accomplishment_comment_2" readonly rows="4" class="form-control jsAccomplishment2"><?= $section_2['accomplishment_comment_2'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">3</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="accomplishment_3" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment3" value="<?= $section_2['accomplishment_3'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="accomplishment_comment_3" readonly rows="4" class="form-control jsAccomplishment3"><?= $section_2['accomplishment_comment_3'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">3</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="accomplishment_4" readonly class="form-control jsAccomplishment" data-key="jsAccomplishment4" value="<?= $section_2['accomplishment_4'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="accomplishment_comment_4" readonly rows="4" class="form-control jsAccomplishment4"><?= $section_2['accomplishment_comment_4'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year.
                                                                                            </strong>
                                                                                        </h3>
                                                                                        <table class="table table-bordered">
                                                                                            <thead class="thead-dark">
                                                                                                <tr>
                                                                                                    <th scope="col"></th>
                                                                                                    <th scope="col">Opportunity for Improvement</th>
                                                                                                    <th scope="col">Employee Comments</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th scope="row">1</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="opportunities_1" readonly class="form-control" value="<?= $section_2['opportunities_1'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="opportunities_comment_1" rows="4" readonly class="form-control"><?= $section_2['opportunities_comment_1'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">2</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="opportunities_2" readonly class="form-control jsOpportunities" data-key="jsOpportunities2" value="<?= $section_2['opportunities_2'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="opportunities_comment_2" readonly rows="4" class="form-control jsOpportunities2"><?= $section_2['opportunities_comment_2'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">3</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="opportunities_3" readonly class="form-control jsOpportunities" data-key="jsOpportunities3" value="<?= $section_2['opportunities_3'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="opportunities_comment_3" readonly rows="4" class="form-control jsOpportunities3"><?= $section_2['opportunities_comment_3'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">4</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="opportunities_4" readonly class="form-control jsOpportunities" data-key="jsOpportunities4" value="<?= $section_2['opportunities_4'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="opportunities_comment_4" readonly rows="4" class="form-control jsOpportunities4"><?= $section_2['opportunities_comment_4'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                                                                                            </strong>
                                                                                        </h3>

                                                                                        <table class="table table-bordered">
                                                                                            <thead class="thead-dark">
                                                                                                <tr>
                                                                                                    <th scope="col"></th>
                                                                                                    <th scope="col">Goal</th>
                                                                                                    <th scope="col">General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors.</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th scope="row">1</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="goal_1" readonly readonly class="form-control" value="<?= $section_2['goal_1'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="goal_comment_1" readonly rows="4" readonly class="form-control"><?= $section_2['goal_comment_1'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">2</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="goal_2" readonly class="form-control jsGoal" data-key="jsGoal2" value="<?= $section_2['goal_2'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="goal_comment_2" readonly rows="4" class="form-control jsGoal2"><?= $section_2['goal_comment_2'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th scope="row">3</th>
                                                                                                    <td>
                                                                                                        <input type="text" name="goal_3" readonly class="form-control jsGoal" data-key="jsGoal3" value="<?= $section_2['goal_3'] ?? '' ?>">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="goal_comment_3" readonly rows="4" class="form-control jsGoal3"><?= $section_2['goal_comment_3'] ?? '' ?></textarea>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                1. Have you and your manager reviewed your job description for this review period?
                                                                                            </strong>
                                                                                        </h3>

                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                            <label class="control control--radio">
                                                                                                <span class="text-large">
                                                                                                    Yes
                                                                                                </span>
                                                                                                <input type="radio" <?= $disabled ?> name="review_period_radio" value="1" <?php echo $section_2['review_period_radio'] == 1 ? 'checked' : '' ?> />
                                                                                                <div class="control__indicator"></div>
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                            <label class="control control--radio">
                                                                                                <span class="text-large">
                                                                                                    No
                                                                                                </span>
                                                                                                <input type="radio" <?= $disabled ?> name="review_period_radio" value="2" <?php echo $section_2['review_period_radio'] == 2 ? 'checked' : '' ?> />
                                                                                                <div class="control__indicator"></div>
                                                                                            </label>
                                                                                        </div>
                                                                                        <p>
                                                                                            (Please attach a copy of your job description for review with your manager)
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <h3>
                                                                                            <strong>
                                                                                                2. Do you have access to equipment and resources necessary to perform your job function?
                                                                                            </strong>
                                                                                        </h3>

                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                            <label class="control control--radio">
                                                                                                <span class="text-large">
                                                                                                    Yes
                                                                                                </span>
                                                                                                <input type="radio" <?= $disabled ?> name="equipment_resources_radio" class="jsEquipmentResourcesRadio" value="1" <?php echo $section_2['equipment_resources_radio'] == 1 ? 'checked' : '' ?> />
                                                                                                <div class="control__indicator"></div>
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                            <label class="control control--radio">
                                                                                                <span class="text-large">
                                                                                                    No
                                                                                                </span>
                                                                                                <input type="radio" <?= $disabled ?> name="equipment_resources_radio" class="jsEquipmentResourcesRadio" value="2" <?php echo $section_2['equipment_resources_radio'] == 2 ? 'checked' : '' ?> />
                                                                                                <div class="control__indicator"></div>
                                                                                            </label>
                                                                                        </div>
                                                                                        <p>
                                                                                            (If No, please list the equipment you deem necessary subject to Managers approval and budgeting)
                                                                                        </p>

                                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                            <div class="form-group">
                                                                                                <label for="license_notes">Necessary Equipment or Resources Needed</label>
                                                                                                <input type="text" name="equipment_resources_needed" class="form-control jsEquipmentResourcesNeeded" value="<?= $section_2['equipment_resources_needed'] ?? '' ?>">
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <h3>
                                                                                                <strong>
                                                                                                    3. Is there any additional support or training you feel would be helpful for <?= $companyName ?> to provide for you to help you succeed in your current role?
                                                                                                </strong>
                                                                                            </h3>
                                                                                            <textarea name="additional_support" rows="10" readonly class="form-control"><?= $section_2['additional_support'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionTwo ">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <h3>
                                                                                                <strong>
                                                                                                    4. Employee Additional Comments:
                                                                                                </strong>
                                                                                            </h3>
                                                                                            <textarea name="additional_comment" rows="10" readonly class="form-control"><?= $section_2['additional_comment'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    $section_3 = [];
                                                                    if ($sectionData['section_3_json']) {
                                                                        $section_3 = json_decode($sectionData['section_3_json'], true)['data'];
                                                                    }
                                                                    ?>
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#collapse_section_three">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                    Manager & Employee Section 3: The Year in Review
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_section_three" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="panel-body">
                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionThree  mrg-bottom-20">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <h3>
                                                                                                <strong>
                                                                                                    Additional Comments, Feedback - Managers Comments:
                                                                                                </strong>
                                                                                            </h3>
                                                                                            <textarea name="additional_comment_one" rows="10" readonly class="form-control"><?= $section_3['additional_comment_one'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->

                                                                                <!-- Question Start -->
                                                                                <div class="row jsSectionThree  mrg-bottom-20">
                                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                                        <div class="form-group autoheight">
                                                                                            <h3>
                                                                                                <strong>
                                                                                                    Additional Comments, Feedback - Managers Comments:
                                                                                                </strong>
                                                                                            </h3>
                                                                                            <textarea name="additional_comment_two" rows="10" readonly class="form-control"><?= $section_3['additional_comment_two'] ?? '' ?></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Question End -->
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <?php
                                                                    $section_4 = [];
                                                                    if ($sectionData['section_4_json']) {
                                                                        $section_4 = json_decode($sectionData['section_4_json'], true);
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    $section_5 = [];
                                                                    if ($sectionData['section_5_json']) {
                                                                        $section_5 = json_decode($sectionData['section_5_json'], true);
                                                                    }
                                                                    ?>

                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#collapse_section_four">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                    Section 4: Signatures
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_section_four" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="panel-body">
                                                                                <div class="row jsSectionTwo  mrg-bottom-20">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <table class="table table-bordered">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <h3>
                                                                                                            <strong>Employee</strong>
                                                                                                        </h3>
                                                                                                        <span>Signature:</span>
                                                                                                        <?php if ($sectionData['employee_signature']) { ?>
                                                                                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['employee_signature'] ?>" />
                                                                                                        <?php } ?>
                                                                                                        <br>
                                                                                                        <span>Date:</span>
                                                                                                        <?php if ($sectionData['employee_signature']) { ?>
                                                                                                            <?= formatDateToDB($section_4['employee_signature_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <h3>
                                                                                                            <strong>Manager</strong>
                                                                                                        </h3>
                                                                                                        <span>Signature:</span>
                                                                                                        <?php if ($sectionData['manager_signature']) { ?>
                                                                                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['manager_signature'] ?>" />
                                                                                                        <?php } ?>
                                                                                                        <br>
                                                                                                        <span>Date:</span>
                                                                                                        <?php if ($sectionData['manager_signature']) { ?>
                                                                                                            <?= formatDateToDB($section_4['manager_signature_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        <h3>
                                                                                                            <strong>Next Level Approval</strong>
                                                                                                        </h3>
                                                                                                        <span>Signature:</span>
                                                                                                        <br>
                                                                                                        <span>Date:</span>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <h3>
                                                                                                            <strong>Human Resources</strong>
                                                                                                        </h3>
                                                                                                        <span>Signature:</span>
                                                                                                        <?php if ($sectionData['hr_signature']) { ?>
                                                                                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?= $sectionData['hr_signature'] ?>" />
                                                                                                        <?php } ?>
                                                                                                        <br>
                                                                                                        <span>Date:</span>
                                                                                                        <?php if ($sectionData['hr_signature']) { ?>
                                                                                                            <?= formatDateToDB($section_5['hr_manager_completed_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                                                        <?php } ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title">
                                                                                <a class="collapsed" data-toggle="collapse" aria-expanded="false" data-parent="#accordion" href="#collapse_section_five">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                    Section 5: Salary Recommendation: For Manager Use Only:
                                                                                </a>
                                                                            </h4>
                                                                        </div>
                                                                        <div id="collapse_section_five" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="panel-body">
                                                                                <div class="row jsSectionOne">
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Employees Current Pay Rate:</label>
                                                                                            <input type="text" readonly name="current_pay" class="form-control input-bg" value="<?= $section_5['current_pay'] ?? ''; ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Recommended Pay Increase:</label>
                                                                                            <input type="text" readonly name="recommended_pay" class="form-control input-bg" value="<?= $section_5['recommended_pay'] ?? '' ?>" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Approved Amount</label>
                                                                                            <input type="text" readonly name="approved_amount" class="form-control input-bg" value="<?= $section_5['approved_amount'] ?? '' ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Approved by</label>
                                                                                            <input type="text" readonly name="hr_manager_completed_by" class="form-control input-bg" value="<?= $section_5['hr_manager_completed_by'] ? getUserNameBySID($section_5['hr_manager_completed_by']) : '' ?>" />
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Approved Date</label>
                                                                                            <input type="text" readonly name="hr_manager_completed_at" class="form-control input-bg" value="<?= $section_5['hr_manager_completed_at'] ? formatDateToDB($section_5['hr_manager_completed_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '' ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group autoheight">
                                                                                            <label for="">Effective Date of Increase</label>
                                                                                            <input type="text" readonly name="effective_increase_date" class="form-control input-bg" value="<?= $section_5['effective_increase_date'] ? formatDateToDB($section_5['effective_increase_date'], 'm-d-Y', DATE) : '' ?>" />
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
        </div>
    </div>
</div>