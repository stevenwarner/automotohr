<br />
<div class="container">
    <div class="jsSectionOneFormSection">
        <form id="jsSectionOneForm">
            <div class="panel panel-default">
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsSaveSectionOne">Save</button>
                </div>
            </div>

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
                            <input type="text" name="epe_employee_name" class="form-control input-bg" value="<?php echo $section_1['epe_employee_name'] ? $section_1['epe_employee_name']: ''?>" />
                            <br>
                        </label>
                        <label class="col-sm-6">
                            Job Title
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_job_title" class="form-control input-bg" value="<?php echo $section_1['epe_job_title'] ? $section_1['epe_job_title']: ''?>" />
                            <br>
                        </label>
                    </div>

                    <div class="row">
                        <label class="col-sm-6">
                            Department
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_department" class="form-control input-bg" value="<?php echo $section_1['epe_department'] ? $section_1['epe_department']: ''?>" />
                            <br>
                        </label>
                        <label class="col-sm-6">
                            Manager
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_manager" class="form-control input-bg" value="<?php echo $section_1['epe_manager'] ? $section_1['epe_manager']: ''?>" />
                            <br>
                        </label>
                    </div>

                    <div class="row">
                        <label class="col-sm-6">
                            Hire Date
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_hire_date" class="form-control input-bg jsDatePicker" value="<?php echo $section_1['epe_hire_date'] ? $section_1['epe_hire_date']: ''?>" />
                            <br>
                        </label>
                        <label class="col-sm-6">
                            Start Date in Current Position
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_start_date" class="form-control input-bg jsDatePicker" value="<?php echo $section_1['epe_start_date'] ? $section_1['epe_start_date']: ''?>" />
                            <br>
                        </label>
                    </div>

                    <div class="row">
                        <label class="col-sm-6">
                            Review Period Start
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_review_start" class="form-control input-bg jsDatePicker" value="<?php echo $section_1['epe_review_start'] ? $section_1['epe_review_start']: ''?>" />
                            <br>
                        </label>
                        <label class="col-sm-6">
                            Review Period End
                            <strong class="text-danger">*</strong>
                            <input type="text" name="epe_review_end" class="form-control input-bg jsDatePicker" value="<?php echo $section_1['epe_review_end'] ? $section_1['epe_review_end']: ''?>" />
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
                            <input type="radio" name="position_knowledgeable_radio" value="1" <?php echo $section_1['position_knowledgeable_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Knowledge is sufficient to perform the requirements of the position.
                            </span>
                            <input type="radio" name="position_knowledgeable_radio" value="2" <?php echo $section_1['position_knowledgeable_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee is exceptionally well informed and competent in all aspects of the position.
                            </span>
                            <input type="radio" name="position_knowledgeable_radio" value="3" <?php echo $section_1['position_knowledgeable_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="position_knowledgeable_comments" rows="10" class="form-control"><?=$section_1['position_knowledgeable_comments'] ?? ''?></textarea>
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
                                <input type="text" name="position_improved" class="invoice-fields" value="<?php echo $section_1['position_improved'] ? $section_1['position_improved']: ''?>">
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
                            <input type="radio" name="position_improved_radio" value="1" <?php echo $section_1['position_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>    
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Output meets that required of the position.
                            </span>
                            <input type="radio" name="position_improved_radio" value="2" <?php echo $section_1['position_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Output consistently exceeds that required of the position.
                            </span>
                            <input type="radio" name="position_improved_radio" value="3" <?php echo $section_1['position_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="position_improved_comment" rows="10" class="form-control"><?=$section_1['position_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="quantity_improved" class="invoice-fields" value="<?php echo $section_1['quantity_improved'] ? $section_1['quantity_improved']: ''?>">
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
                            <input type="radio" name="quantity_improved_radio" value="1" <?php echo $section_1['quantity_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Quality of work meets position requirements.
                            </span>
                            <input type="radio" name="quantity_improved_radio" value="2" <?php echo $section_1['quantity_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Quality of work consistently exceeds position requirements.
                            </span>
                            <input type="radio" name="quantity_improved_radio" value="3" <?php echo $section_1['quantity_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="quantity_improved_comment" rows="10" class="form-control"><?=$section_1['quantity_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="quality_improved" class="invoice-fields" value="<?php echo $section_1['quality_improved'] ? $section_1['quality_improved']: ''?>">
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
                                Employee is frequently non-supportive.  Improvement is mandatory.
                            </span>
                            <input type="radio" name="quality_improved_radio" value="1" <?php echo $section_1['quality_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>    
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately contributes to supportive environment.
                            </span>
                            <input type="radio" name="quality_improved_radio" value="2" <?php echo $section_1['quality_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>    
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee consistently contributes to supportive work environment.
                            </span>
                            <input type="radio" name="quality_improved_radio" value="3" <?php echo $section_1['quality_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="quality_improved_comment" rows="10" class="form-control"><?php echo $section_1['quality_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="relations_improved" class="invoice-fields" value="<?php echo $section_1['relations_improved'] ? $section_1['relations_improved']: ''?>">
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
                            <input type="radio" name="relations_improved_radio" value="1" <?php echo $section_1['relations_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">    
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately contributes to high quality mission.
                            </span>
                            <input type="radio" name="relations_improved_radio" value="2" <?php echo $section_1['relations_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee consistently demonstrates exceptional commitment to the mission. 
                            </span>
                            <input type="radio" name="relations_improved_radio" value="3" <?php echo $section_1['relations_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="relations_improved_comment" rows="10" class="form-control jsRelationsImprovedComment"><?=$section_1['relations_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="skill_improved" class="invoice-fields" value="<?php echo $section_1['skill_improved'] ? $section_1['skill_improved']: ''?>">
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
                                Employee is late, absent, misses deadlines.  Improvement is mandatory.
                            </span>
                            <input type="radio" name="skill_improved_radio" value="1" <?php echo $section_1['skill_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">       
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately attends work, rarely misses or late, meets deadlines.
                            </span>
                            <input type="radio" name="skill_improved_radio" value="2" <?php echo $section_1['skill_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee consistently on time, at work and completes deadlines ahead of schedule.
                            </span>
                            <input type="radio" name="skill_improved_radio" value="3" <?php echo $section_1['skill_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="skill_improved_comment" rows="10" class="form-control"><?=$section_1['skill_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="dependability_improved" class="invoice-fields" value="<?php echo $section_1['dependability_improved'] ? $section_1['dependability_improved']: ''?>">
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
                            <input type="radio" name="dependability_improved_radio" value="1" <?php echo $section_1['dependability_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">      
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately adheres to standard operating policies and procedures with few reminders. 
                            </span>
                            <input type="radio" name="dependability_improved_radio" value="2" <?php echo $section_1['dependability_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee is consistently exceptional in following standard operating policies and procedures.
                            </span>
                            <input type="radio" name="dependability_improved_radio" value="3" <?php echo $section_1['dependability_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="dependability_improved_comment" rows="10" class="form-control"><?=$section_1['dependability_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="policy_procedure_improved" class="invoice-fields" value="<?php echo $section_1['policy_procedure_improved'] ? $section_1['policy_procedure_improved']: ''?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <strong>
                                OTHER:
                            </strong>
                            <br>
                            <input type="text" name="policy_procedure_improved_other" class="invoice-fields" value="<?php echo $section_1['policy_procedure_improved_other'] ? $section_1['policy_procedure_improved_other']: ''?>">
                        </div>
                    </div>    
                    <br>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee frequently falls below acceptable standard as outlined above.
                            </span>
                            <input type="radio" name="policy_procedure_improved_radio" value="1" <?php echo $section_1['policy_procedure_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately meets standard as outlined above. 
                            </span>
                            <input type="radio" name="policy_procedure_improved_radio" value="2" <?php echo $section_1['policy_procedure_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee is consistently exceptional in meeting performance standard.
                            </span>
                            <input type="radio" name="policy_procedure_improved_radio" value="3" <?php echo $section_1['policy_procedure_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="policy_procedure_improved_comment" rows="10" class="form-control"><?=$section_1['policy_procedure_improved_comment'] ?? ''?></textarea>
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
                                <input type="text" name="standard_improved" class="invoice-fields" value="<?php echo $section_1['standard_improved'] ? $section_1['standard_improved']: ''?>">
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
                            <input type="text" name="standard_improved_other" class="invoice-fields" value="<?php echo $section_1['standard_improved_other'] ? $section_1['standard_improved_other']: ''?>">
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee frequently falls below acceptable standard as outlined above.
                            </span>
                            <input type="radio" name="standard_improved_radio" value="1" <?php echo $section_1['standard_improved_radio'] == 1 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee adequately meets standard as outlined above. 
                            </span>
                            <input type="radio" name="standard_improved_radio" value="2" <?php echo $section_1['standard_improved_radio'] == 2 ? 'checked' : ''?>/>
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">   
                        <label class="control control--radio">
                            <span class="text-large">
                                Employee is consistently exceptional in meeting performance standard.
                            </span>
                            <input type="radio" name="standard_improved_radio" value="3" <?php echo $section_1['standard_improved_radio'] == 3 ? 'checked' : ''?>/>
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
                        <textarea name="standard_improved_comment" rows="10" class="form-control"><?=$section_1['standard_improved_comment'] ?? ''?></textarea>
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
                        <textarea name="managers_additional_comment" rows="10" class="form-control"><?=$section_1['managers_additional_comment'] ?? ''?></textarea>
                    </p>
                </div>
            </div>
        
            <div class="panel panel-default">
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsSaveSectionOne">Save</button>
                </div>
            </div>

        </form>
    </div>

    <div class="jsSectionOneEmployees hidden">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-users text-orange" aria-hidden="true"></i>
                    Employees
                </h2>
            </div>
            <div class="panel-body">

                <!--  -->
                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-orange jsSelectAll" type="button">
                            Select all
                        </button>
                        <button class="btn btn-black jsRemoveAll" type="button">
                            Clear all
                        </button>
                    </div>
                </div>

                <hr>

                <?php if ($employees) {
                    $counter = 1;
                    foreach ($employees as $employee) {
                        if ($counter == 1) {
                            echo '<div class="row">';
                        }
                ?>
                        <!--  -->
                        <div class="col-sm-6">
                            <label class="control control--checkbox">
                                <input type="checkbox" class="jsVerificationEmployees" value="<?= $employee["sid"]; ?>" name="employees[]" />
                                <?= remakeEmployeeName($employee); ?>
                                <div class="control__indicator"></div>
                            </label>
                        </div>

                <?php
                        if ($counter === 2) {
                            echo '</div><br />';
                            $counter = 1;
                        } else {
                            $counter++;
                        }
                    }
                } ?>
            </div>

            <div class="panel-footer text-right">
                <button class="btn btn-orange jsSendVerificationRequest">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    &nbsp;Send Verification Request
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>
    </div>
</div>