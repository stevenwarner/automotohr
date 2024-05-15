<br />
<div class="container">

    <?php if (isset($section_1)) { ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default hr-documents-tab-content js-search-header">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section_one">
                                <span class="glyphicon glyphicon-plus"></span>
                                Section One
                            </a>
                        </h4>
                    </div>

                    <div id="collapse_section_one" class="panel-collapse collapse">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                Manager Section 1: Employee Year in Review Evaluation
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <label class="col-sm-6">
                                                Employee Name
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_employee_name" class="form-control input-bg" value="<?php echo $section_1['epe_employee_name'] ? $section_1['epe_employee_name'] : '' ?>" />
                                                <br>
                                            </label>
                                            <label class="col-sm-6">
                                                Job Title
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_job_title" class="form-control input-bg" value="<?php echo $section_1['epe_job_title'] ? $section_1['epe_job_title'] : '' ?>" />
                                                <br>
                                            </label>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-6">
                                                Department
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_department" class="form-control input-bg" value="<?php echo $section_1['epe_department'] ? $section_1['epe_department'] : '' ?>" />
                                                <br>
                                            </label>
                                            <label class="col-sm-6">
                                                Manager
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_manager" class="form-control input-bg" value="<?php echo $section_1['epe_manager'] ? $section_1['epe_manager'] : '' ?>" />
                                                <br>
                                            </label>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-6">
                                                Hire Date
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_hire_date" class="form-control input-bg" value="<?php echo $section_1['epe_hire_date'] ? $section_1['epe_hire_date'] : '' ?>" />
                                                <br>
                                            </label>
                                            <label class="col-sm-6">
                                                Start Date in Current Position
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_start_date" class="form-control input-bg" value="<?php echo $section_1['epe_start_date'] ? $section_1['epe_start_date'] : '' ?>" />
                                                <br>
                                            </label>
                                        </div>

                                        <div class="row">
                                            <label class="col-sm-6">
                                                Review Period Start
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_review_start" class="form-control input-bg" value="<?php echo $section_1['epe_review_start'] ? $section_1['epe_review_start'] : '' ?>" />
                                                <br>
                                            </label>
                                            <label class="col-sm-6">
                                                Review Period End
                                                <strong class="text-danger">*</strong>
                                                <input type="text" readonly name="epe_review_end" class="form-control input-bg" value="<?php echo $section_1['epe_review_end'] ? $section_1['epe_review_end'] : '' ?>" />
                                                <br>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <h3>
                                    <br />
                                    <strong>
                                        Rate the employee in each area below. Comments are required for each section.
                                    </strong>
                                    <br />
                                    <br />
                                </h3>

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p class="text-large">
                                            <strong>
                                                POSITION KNOWLEDGE:
                                            </strong>
                                            To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.
                                        </p>
                                        <br>
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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="position_knowledgeable_comments" rows="10" class="form-control"><?= $section_1['position_knowledgeable_comments'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee's position knowledge be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="position_improved" class="invoice-fields" value="<?php echo $section_1['position_improved'] ? $section_1['position_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                QUALITY OF WORK:
                                            </strong>
                                            Evaluate the quality of work produced.
                                        </p>
                                        <br>

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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="position_improved_comment" rows="10" class="form-control"><?= $section_1['position_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee's quantity of work be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="quantity_improved" class="invoice-fields" value="<?php echo $section_1['quantity_improved'] ? $section_1['quantity_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                QUALITY OF WORK:
                                            </strong>
                                            Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.
                                        </p>
                                        <br>

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
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="quantity_improved_comment" rows="10" class="form-control"><?= $section_1['quantity_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employees’ quality of work be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="quality_improved" class="invoice-fields" value="<?php echo $section_1['quality_improved'] ? $section_1['quality_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                INTERPERSONAL RELATIONS:
                                            </strong>
                                            To what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?
                                        </p>
                                        <br>

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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="quality_improved_comment" rows="10" class="form-control"><?php echo $section_1['quality_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee’s interpersonal relations be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="relations_improved" class="invoice-fields" value="<?php echo $section_1['relations_improved'] ? $section_1['relations_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                Mission:
                                            </strong>
                                            To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?
                                        </p>
                                        <br>

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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="relations_improved_comment" rows="10" class="form-control jsRelationsImprovedComment"><?= $section_1['relations_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee’s customer service skills/delivery be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="skill_improved" class="invoice-fields" value="<?php echo $section_1['skill_improved'] ? $section_1['skill_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                DEPENDABILITY:
                                            </strong>
                                            To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?
                                        </p>
                                        <br>

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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="skill_improved_comment" rows="10" class="form-control"><?= $section_1['skill_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee’s dependability be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="dependability_improved" class="invoice-fields" value="<?php echo $section_1['dependability_improved'] ? $section_1['dependability_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-large">
                                            <strong>
                                                ADHERENCE TO POLICY & PROCEDURE:
                                            </strong>
                                            To what level does the employee adhere to standard operating policies and procedures?
                                        </p>
                                        <br>

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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="dependability_improved_comment" rows="10" class="form-control"><?= $section_1['dependability_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may the employee’s adherence to policy and procedure be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="policy_procedure_improved" class="invoice-fields" value="<?php echo $section_1['policy_procedure_improved'] ? $section_1['policy_procedure_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <strong>
                                                    OTHER:
                                                </strong>
                                                <br>
                                                <input type="text" readonly name="policy_procedure_improved_other" class="invoice-fields" value="<?php echo $section_1['policy_procedure_improved_other'] ? $section_1['policy_procedure_improved_other'] : '' ?>">
                                            </div>
                                        </div>
                                        <br>
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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="policy_procedure_improved_comment" rows="10" class="form-control"><?= $section_1['policy_procedure_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                How may employee’s performance in meeting this standard be improved?
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <input type="text" readonly name="standard_improved" class="invoice-fields" value="<?php echo $section_1['standard_improved'] ? $section_1['standard_improved'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <strong>
                                                    OTHER:
                                                </strong>
                                                <br>
                                                <input type="text" readonly name="standard_improved_other" class="invoice-fields" value="<?php echo $section_1['standard_improved_other'] ? $section_1['standard_improved_other'] : '' ?>">
                                            </div>
                                        </div>
                                        <br>
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

                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Comments
                                                <strong class="text-danger">
                                                    *
                                                </strong>
                                            </span>
                                            <textarea readonly name="standard_improved_comment" rows="10" class="form-control"><?= $section_1['standard_improved_comment'] ?? '' ?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                Managers Additional Comments for the Review Period:
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <p class="text-large">
                                            <textarea readonly name="managers_additional_comment" rows="10" class="form-control"><?= $section_1['managers_additional_comment'] ?? '' ?></textarea>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($section_2)) { ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default hr-documents-tab-content js-search-header">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section_two">
                                <span class="glyphicon glyphicon-plus"></span>
                                Section Two
                            </a>
                        </h4>
                    </div>

                    <div id="collapse_section_two" class="panel-collapse collapse">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                Employee Section 2: The Year in Review
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <!-- Question Start -->
                                        <p>
                                            List 3-4 top accomplishments & add a reflection on overall performance for the year.
                                        </p>
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
                                                        <input type="text" readonly name="accomplishment_1" class="form-control" value="<?= $section_2['accomplishment_1'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="accomplishment_comment_1" rows="4" class="form-control"><?= $section_2['accomplishment_comment_1'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>
                                                        <input type="text" readonly name="accomplishment_2" class="form-control jsAccomplishment" data-key="jsAccomplishment2" value="<?= $section_2['accomplishment_2'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="accomplishment_comment_2" rows="4" class="form-control jsAccomplishment2"><?= $section_2['accomplishment_comment_2'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>
                                                        <input type="text" readonly name="accomplishment_3" class="form-control jsAccomplishment" data-key="jsAccomplishment3" value="<?= $section_2['accomplishment_3'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="accomplishment_comment_3" rows="4" class="form-control jsAccomplishment3"><?= $section_2['accomplishment_comment_3'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>
                                                        <input type="text" readonly name="accomplishment_4" class="form-control jsAccomplishment" data-key="jsAccomplishment4" value="<?= $section_2['accomplishment_4'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="accomplishment_comment_4" rows="4" class="form-control jsAccomplishment4"><?= $section_2['accomplishment_comment_4'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <p>
                                            Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year.
                                        </p>
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
                                                        <input type="text" readonly name="opportunities_1" class="form-control" value="<?= $section_2['opportunities_1'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="opportunities_comment_1" rows="4" class="form-control"><?= $section_2['opportunities_comment_1'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>
                                                        <input type="text" readonly name="opportunities_2" class="form-control jsOpportunities" data-key="jsOpportunities2" value="<?= $section_2['opportunities_2'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="opportunities_comment_2" rows="4" class="form-control jsOpportunities2"><?= $section_2['opportunities_comment_2'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>
                                                        <input type="text" readonly name="opportunities_3" class="form-control jsOpportunities" data-key="jsOpportunities3" value="<?= $section_2['opportunities_3'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="opportunities_comment_3" rows="4" class="form-control jsOpportunities3"><?= $section_2['opportunities_comment_3'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">4</th>
                                                    <td>
                                                        <input type="text" readonly name="opportunities_4" class="form-control jsOpportunities" data-key="jsOpportunities4" value="<?= $section_2['opportunities_4'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="opportunities_comment_4" rows="4" class="form-control jsOpportunities4"><?= $section_2['opportunities_comment_4'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <p>
                                            List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                                        </p>
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
                                                        <input type="text" readonly name="goal_1" class="form-control" value="<?= $section_2['goal_1'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="goal_comment_1" rows="4" class="form-control"><?= $section_2['goal_comment_1'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">2</th>
                                                    <td>
                                                        <input type="text" readonly name="goal_2" class="form-control jsGoal" data-key="jsGoal2" value="<?= $section_2['goal_2'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="goal_comment_2" rows="4" class="form-control jsGoal2"><?= $section_2['goal_comment_2'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">3</th>
                                                    <td>
                                                        <input type="text" readonly name="goal_3" class="form-control jsGoal" data-key="jsGoal3" value="<?= $section_2['goal_3'] ?? '' ?>">
                                                    </td>
                                                    <td>
                                                        <textarea readonly name="goal_comment_3" rows="4" class="form-control jsGoal3"><?= $section_2['goal_comment_3'] ?? '' ?></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <h4>
                                            1. Have you and your manager reviewed your job description for this review period?
                                        </h4>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label class="control control--radio">
                                                <span class="text-large">
                                                    Yes
                                                </span>
                                                <input type="radio" disabled name="review_period_radio" value="1" <?php echo $section_2['review_period_radio'] == 1 ? 'checked' : '' ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label class="control control--radio">
                                                <span class="text-large">
                                                    No
                                                </span>
                                                <input type="radio" disabled name="review_period_radio" value="2" <?php echo $section_2['review_period_radio'] == 2 ? 'checked' : '' ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <p>
                                            (Please attach a copy of your job description for review with your manager)
                                        </p>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <h4>
                                            2. Do you have access to equipment and resources necessary to perform your job function?
                                        </h4>
                                        <p>
                                            (If No, please list the equipment you deem necessary subject to Managers approval and budgeting)
                                        </p>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label class="control control--radio">
                                                <span class="text-large">
                                                    Yes
                                                </span>
                                                <input type="radio" disabled name="equipment_resources_radio" value="1" <?php echo $section_2['equipment_resources_radio'] == 1 ? 'checked' : '' ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <label class="control control--radio">
                                                <span class="text-large">
                                                    No
                                                </span>
                                                <input type="radio" disabled name="equipment_resources_radio" value="2" <?php echo $section_2['equipment_resources_radio'] == 2 ? 'checked' : '' ?> />
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Necessary Equipment or Resources Needed
                                            </span>
                                            <input type="text" readonly name="equipment_resources_needed" class="form-control" value="<?= $section_2['equipment_resources_needed'] ?? '' ?>">
                                        </label>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                3. Is there any additional support or training you feel would be helpful for <?= $companyName ?> to provide for you to help you succeed in your current role?
                                            </span>
                                            <textarea readonly name="additional_support" rows="10" class="form-control"><?= $section_2['additional_support'] ?? '' ?></textarea>
                                        </label>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                4. Employee Additional Comments:
                                            </span>
                                            <textarea readonly name="additional_comment" rows="10" class="form-control"><?= $section_2['additional_comment'] ?? '' ?></textarea>
                                        </label>
                                        <!-- Question End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($section_3)) { ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default hr-documents-tab-content js-search-header">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_section_three">
                                <span class="glyphicon glyphicon-plus"></span>
                                Section Three
                            </a>
                        </h4>
                    </div>

                    <div id="collapse_section_three" class="panel-collapse collapse">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="m0">
                                            <strong>
                                                Employee Section 3: The Year in Review
                                            </strong>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <!-- Question Start -->
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Additional Comments, Feedback - Managers Comments:
                                            </span>
                                            <textarea readonly name="additional_comment_one" rows="10" class="form-control"><?= $section_3['additional_comment_one'] ?? '' ?></textarea>
                                        </label>
                                        <!-- Question End -->
                                        <!-- Question Start -->
                                        <label class="col-sm-12">
                                            <br>
                                            <span class="text-large">
                                                Additional Comments, Feedback - Managers Comments:
                                            </span>
                                            <textarea readonly name="additional_comment_two" rows="10" class="form-control"><?= $section_3['additional_comment_two'] ?? '' ?></textarea>
                                        </label>
                                        <!-- Question End -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <hr />

    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <p><strong>Authorization</strong> (enter your company name in the blank space below)</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <p>This authorizes</p>
        </div>
        <div class="col-sm-8">
            <input disabled="true" type="text" class="form-control" value="<?= isset($companyName) ? $companyName : ''; ?>" />
        </div>
        <div class="col-sm-2">
            <p>(the “Company”)</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p class="text-justify">to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-6">
            <p><strong>Authorized signature: <span class="cs-required">*</span><strong></p>
            <?php if (!$signature) { ?>
                <p>
                    <a class="btn blue-button btn-sm jsGetEmployeeSignature" href="javascript:;">Create E-Signature</a>
                    <div class="img-full">
                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="" id="jsDrawEmployeeSignature" />
                    </div>
                </p>
            <?php } else { ?>
                <p>
                    <div class="img-full">
                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?=$signature?>" />
                    </div>
                </p>
            <?php } ?>    
        </div>
        <div class="col-sm-6">
            <p><strong>Date: <span class="cs-required">*</span><strong></p>
            <p><input type="text" name="signature_date" class="form-control" value="<?= isset($signDate) ? $signDate : ''; ?>" readonly /></p>
        </div>
    </div>

    <hr />
    <!--  -->
    <div class="btn-wrp full-width mrg-top-20 text-center">
        <button class="btn btn-info btn-success pull-right green_panel_consent_btn disabled jsSaveEsignature">I CONSENT AND ACCEPT</button>
    </div>
</div>