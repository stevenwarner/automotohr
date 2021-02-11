<?php
    $formData = json_decode($FMLA['serialized_data'], true);
    $employee = isset($formData['employee']) ? $formData['employee'] : array();
    $employer = isset($formData['employer']) ? $formData['employer'] : array();
?>
<link rel="stylesheet" href="<?=base_url('assets/css/fmla.css');?>">
<main role="main" class="<?=isset($pd) ? 'container' : '';?>">
    <section class="sheet padding-10mm" id="js-preview">
         <div class="form-wrp">

                    <div class="row">
                        <div class="col-lg-6 col-sm-4">
                            <h2 style="margin-top: 0;">Certification of Health Care Provider for Employee’s Serious Health Condition</h2>
                            <p>(Family and Medical Leave Act)</p>
                        </div>
                        <div class="col-lg-4  col-sm-4 text-center">
                            <h2 style="margin-top: 0;">U.S. Department of Labor</h2>
                            <p>Wage and Hour Division</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                            <img id="imge" src="<?php echo base_url("assets/images/fmlalogo.png");?>" style="max-width: 100%;" class="fmla1_h1_right"></h1>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-8">
                            <strong>DO NOT SEND COMPLETED FORM TO THE DEPARTMENT OF LABOR; RETURN TO THE PATIENT</strong>
                        </div>
                        <div class="col-lg-4 text-center">
                            <p>OMB Control Number: 1545-0074</p>
                            <strong>Expires: 8/31/2021</strong>
                        </div>
                    </div>
                    <hr>
                    <form id="w4-form" action="" method="post">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>Section 1. For Completion by the EMPLOYER <br>INSTRUCTIONS to the EMPLOYER:</strong> The Family and Medical Leave Act (FMLA) provides that an employer may require an employee seeking FMLA protections because of a need for leave due to a serious health condition to submit a medical certification issued by the employee’s health care provider. Please complete Section I before giving this form to your employee. Your response is voluntary. While you are not required to use this form, you may not ask the employee to provide more information than allowed under the FMLA regulations, 29 C.F.R. §§ 825.306-825.308. Employers must generally maintain records and documents relating to medical certifications, recertifications, or medical histories of employees created for FMLA purposes as confidential medical records in separate files/records from the usual personnel files and in accordance with 29 C.F.R. § 1630.14(c)(1), if the Americans with Disabilities Act applies, and in accordance with 29 C.F.R. § 1635.9, if the Genetic Information Nondiscrimination Act applies.
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Employer Name:</label>
                                                    <input type="text" value="<?=sizeof($employer) ? $employer['employername'] : '' ;?>" name="emp_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Employer Contact:</label>
                                                    <input type="text" value="<?=sizeof($employer) ? $employer['employercontact'] : '' ;?>" name="emp_contact" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Employee’s job title:</label>
                                                    <input type="text" value="<?=sizeof($employer) ? $employer['employeejobtitle'] : '' ;?>" name="emp_job_title" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Regular work schedule:</label>
                                                    <input type="text" value="<?=sizeof($employer) ? $employer['workschedule'] : '' ;?>" name="emp_reg_work_sch" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Employee’s essential job functions:</label>
                                                    <input type="text" value="<?=sizeof($employer) ? $employer['employeejob'] : '' ;?>" name="emp_esse_job_func" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <label>Check if job description is attached:</label>
                                                        </div>    
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="jd_attach" value="1" <?=sizeof($employer) && $employer['jobdescription'] != 0 ? 'checked="true"' : '' ;?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>SECTION II: For Completion by the EMPLOYEE <br>INSTRUCTIONS to the EMPLOYEE:</strong> Please complete Section II before giving this form to your medical provider. The FMLA permits an employer to require that you submit a timely, complete, and sufficient medical certification to support a request for FMLA leave due to your own serious health condition. If requested by your employer, your response is required to obtain or retain the benefit of FMLA protections. 29 U.S.C. §§ 2613, 2614(c)(3). Failure to provide a complete and sufficient medical certification may result in a denial of your FMLA request. 29 C.F.R. § 825.313. Your employer must give you at least 15 calendar days to return this form. 29 C.F.R. § 825.305(b).
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label>First Name:</label>
                                                    <input type="text" value="<?=sizeof($employee) ? $employee['firstname'] : '' ;?>" name="first_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label>Middle Name:</label>
                                                    <input type="text" value="<?=sizeof($employee) && isset($employee['middlename']) ? $employee['middlename'] : '' ;?>" name="middle_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="form-group autoheight">
                                                    <label>Last Name:</label>
                                                    <input type="text" value="<?=sizeof($employee) ? $employee['lastname'] : '' ;?>" name="last_name" class="form-control" />
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>SECTION III: For Completion by the HEALTH CARE PROVIDER <br>INSTRUCTIONS to the HEALTH CARE PROVIDER:</strong> Your patient has requested leave under the FMLA. Answer, fully and completely, all applicable parts. Several questions seek a response as to the frequency or duration of a condition, treatment, etc. Your answer should be your best estimate based upon your medical knowledge, experience, and examination of the patient. Be as specific as you can; terms such as “lifetime,” “unknown,” or “indeterminate” may not be sufficient to determine FMLA coverage. Limit your responses to the condition for which the employee is seeking leave. Do not provide information about genetic tests, as defined in 29 C.F.R. § 1635.3(f), genetic services, as defined in 29 C.F.R. § 1635.3(e), or the manifestation of disease or disorder in the employee’s family members, 29 C.F.R. § 1635.3(b). Please be sure to sign the form on the last page.
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Provider’s name:</label>
                                                    <input type="text" value="" name="provider_name" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Business Address:</label>
                                                    <input type="text" value="" name="business_address" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Type of practice / Medical specialty:</label>
                                                    <input type="text" value="" name="type_of_PM_sep" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Telephone:</label>
                                                    <input type="text" value="" name="telephone" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Fax:</label>
                                                    <input type="text" value="" name="fax" class="form-control" />
                                                </div>
                                            </div>  
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <h3>PART A: MEDICAL FACTS</h3>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>1. Approximate date condition commenced:</label>
                                                    <input type="text" value="" name="app_date_cond_comm" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Probable duration of condition:</label>
                                                    <input type="text" value="" name="prob_dur_of_con" class="form-control" />
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label><strong>Mark below as applicable:</strong><br>Was the patient admitted for an overnight stay in a hospital, hospice, or residential medical care facility?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="overnight_stay_in_hospital" value="0">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="overnight_stay_in_hospital" value="1">
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Dates of admission:</label>
                                                    <input type="text" value="" name="date_of_admission" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Date(s) you treated the patient for condition:</label>
                                                    <input type="text" value="" name="dates_you_treated_the_patient" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Will the patient need to have treatment visits at least twice per year due to the condition?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="visit_twice_per_year" value="0" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="visit_twice_per_year" value="1" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Was medication, other than over-the-counter medication, prescribed?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="over_the_counter_medication" value="0" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="over_the_counter_medication" value="1" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Was the patient referred to other health care provider(s) for evaluation or treatment (e.g., physical therapist)?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="refer_to_other_heal_care_provid_for_eval_or_trea" value="0" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="refer_to_other_heal_care_provid_for_eval_or_trea" value="1" >
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>If so, state the nature of such treatments and expected duration of treatment:</label>
                                                    <input type="text" value="" name="nature_of_such_treat_and_expec_dura" class="form-control" />
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>2. Is the medical condition pregnancy?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="is_pregnancy" value="0" <?php echo !empty($pre_form['is_pregnancy']) && $pre_form['is_pregnancy'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="is_pregnancy" value="1" <?php echo !empty($pre_form['is_pregnancy']) && $pre_form['is_pregnancy'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>If so, expected delivery date:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['delivery_date']) ? $pre_form['delivery_date']: ''?>" name="delivery_date" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>3. Use the information provided by the employer in Section I to answer this question. If the employer fails to provide a list of the employee’s essential functions or a job description, answer these questions based upon the employee’s own description of his/her job functions.<br>Is the employee unable to perform any of his/her job functions due to the condition:</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="unable_to_perform" value="0" <?php echo !empty($pre_form['unable_to_perform']) && $pre_form['unable_to_perform'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="unable_to_perform" value="1" <?php echo !empty($pre_form['unable_to_perform']) && $pre_form['unable_to_perform'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>If so, identify the job functions the employee is unable to perform:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['job_unable_to_perform']) ? $pre_form['job_unable_to_perform']: ''?>" name="job_unable_to_perform" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>4. Describe other relevant medical facts, if any, related to the condition for which the employee seeks leave (such medical facts may include symptoms, diagnosis, or any regimen of continuing treatment such as the use of specialized equipment):</label>
                                                    <textarea class="form-control textarea" name="relevant_medical_facts"><?php echo !empty($pre_form['relevant_medical_facts']) ? $pre_form['relevant_medical_facts']: ''?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <h3>PART B: AMOUNT OF LEAVE NEEDED</h3>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>5. Will the employee be incapacitated for a single continuous period of time due to his/her medical condition, including any time for treatment and recovery?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="will_employee_be_incapacitated" value="0" <?php echo !empty($pre_form['will_employee_be_incapacitated']) && $pre_form['will_employee_be_incapacitated'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="will_employee_be_incapacitated" value="1" <?php echo !empty($pre_form['will_employee_be_incapacitated']) && $pre_form['will_employee_be_incapacitated'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>If so, estimate the beginning and ending dates for the period of incapacity:</p>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Beginning Date:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['beginning_date'])>0 ? $pre_form['beginning_date']: ''?>" name="beginning_date" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Ending Date:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['ending_date']) ? $pre_form['ending_date']: ''?>" name="ending_date" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>6. Will the employee need to attend follow-up treatment appointments or work part-time or on a reduced schedule because of the employee’s medical condition?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="attend_follow_up_treatment" value="0" <?php echo !empty($pre_form['attend_follow_up_treatment']) && $pre_form['attend_follow_up_treatment'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="attend_follow_up_treatment" value="1" <?php echo !empty($pre_form['attend_follow_up_treatment']) && $pre_form['attend_follow_up_treatment'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>If so, are the treatments or the reduced number of hours of work medically necessary?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="reduced_number_of_hours" value="0" <?php echo !empty($pre_form['reduced_number_of_hours']) && $pre_form['reduced_number_of_hours'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="reduced_number_of_hours" value="1" <?php echo !empty($pre_form['reduced_number_of_hours']) && $pre_form['reduced_number_of_hours'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>Estimate the part-time or reduced work schedule the employee needs, if any:</p>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Hour(s) Per Day:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['hour_per_day'])>0 ? $pre_form['hour_per_day']: ''?>" name="hour_per_day" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Days Per Week:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['days_per_week']) ? $pre_form['days_per_week']: ''?>" name="days_per_week" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>From Date:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['from_date'])>0 ? $pre_form['from_date']: ''?>" name="from_date" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="form-group autoheight">
                                                    <label>Through Date:</label>
                                                    <input type="text" value="<?php echo !empty($pre_form['through_date']) ? $pre_form['through_date']: ''?>" name="through_date" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>7. Will the condition cause episodic flare-ups periodically preventing the employee from performing his/her job functions?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="flare_ups_periodically_preventing" value="0" <?php echo !empty($pre_form['flare_ups_periodically_preventing']) && $pre_form['flare_ups_periodically_preventing'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="flare_ups_periodically_preventing" value="1" <?php echo !empty($pre_form['flare_ups_periodically_preventing']) && $pre_form['flare_ups_periodically_preventing'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>Is it medically necessary for the employee to be absent from work during the flare-ups?</label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    No
                                                                    <input type="radio" name="absent_during_flare_ups" value="0" <?php echo !empty($pre_form['absent_during_flare_ups']) && $pre_form['absent_during_flare_ups'] == 0 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group autoheight">
                                                                <label class="control control--checkbox">
                                                                    Yes
                                                                    <input type="radio" name="absent_during_flare_ups" value="1" <?php echo !empty($pre_form['absent_during_flare_ups']) && $pre_form['absent_during_flare_ups'] == 1 ? 'checked="checked"': ''?>>
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-group autoheight">
                                                    <label>If so, explain:</label>
                                                    <textarea class="form-control textarea" name="explain_flare_ups"><?php echo !empty($pre_form['explain_flare_ups']) ? $pre_form['explain_flare_ups']: ''?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>Based upon the patient’s medical history and your knowledge of the medical condition, estimate the frequency of flare-ups and the duration of related incapacity that the patient may have over the next 6 months (e.g., 1 episode every 3 months lasting 1-2 days):</p>
                                                <div class="row form-group autoheight">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Frequency : </label>
                                                    </div> 
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="text" value="<?php echo !empty($pre_form['frequency_times']) ? $pre_form['frequency_times']: ''?>" name="frequency_times" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Times Per </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="text" value="<?php echo !empty($pre_form['frequency_weeks']) ? $pre_form['frequency_weeks']: ''?>" name="frequency_weeks" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                        <label>Week(s) </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="text" value="<?php echo !empty($pre_form['frequency_months']) ? $pre_form['frequency_months']: ''?>" name="frequency_months" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                        <label>Month(s) </label>
                                                    </div> 
                                                </div>
                                                <div class="row form-group autoheight">
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Duration : </label>
                                                    </div> 
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="text" value="<?php echo !empty($pre_form['duration_hours']) ? $pre_form['duration_hours']: ''?>" name="duration_hours" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <label>Hours or </label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <input type="text" value="<?php echo !empty($pre_form['duration_days']) ? $pre_form['duration_days']: ''?>" name="duration_days" class="form-control" />
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label>Day(s) Per Episode </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group autoheight">
                                <h3>
                                    ADDITIONAL INFORMATION: IDENTIFY QUESTION NUMBER WITH YOUR ADDITIONAL ANSWER.
                                </h3>
                                 <textarea class="form-control textarea" name="additional_information"><?php echo !empty($pre_form['additional_information']) ? $pre_form['additional_information']: ''?></textarea>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group autoheight">
                                    <label>Signature of Health Care Provider</label>
                                    <input type="text" value="<?php echo !empty($pre_form['signature']) ? $pre_form['signature']: ''?>" name="signature" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group autoheight">
                                    <label>Date:</label>
                                    <input type="text" value="<?php echo !empty($pre_form['signature_date']) ? $pre_form['signature_date']: ''?>" name="signature_date" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <h3 class="text-center">
                                    PAPERWORK REDUCTION ACT NOTICE AND PUBLIC BURDEN STATEMENT
                                </h3>
                                <p>
                                    If submitted, it is mandatory for employers to retain a copy of this disclosure in their records for three years. 29 U.S.C. § 2616; 29 C.F.R. § 825.500. Persons are not required to respond to this collection of information unless it displays a currently valid OMB control number. The Department of Labor estimates that it will take an average of 20 minutes for respondents to complete this collection of information, including the time for reviewing instructions, searching existing data sources, gathering and maintaining the data needed, and completing and reviewing the collection of information. If you have any comments regarding this burden estimate or any other aspect of this collection information, including suggestions for reducing this burden, send them to the Administrator, Wage and Hour Division, U.S. Department of Labor, Room S-3502, 200 Constitution Ave., NW, Washington, DC 20210. <strong>DO NOT SEND COMPLETED FORM TO THE DEPARTMENT OF LABOR; RETURN TO THE PATIENT.</strong>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
    </section>
</main>

<script>
    $(function(){
        $('#js-preview').find('input, textarea, select').addClass('disabled').prop('disabled', true);
    })
</script>