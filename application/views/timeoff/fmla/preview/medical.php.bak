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
                <div class="col-lg-5 col-sm-4">
                    <h2 style="margin-top: 0;">Notice of Eligibility and Rights & Responsibilities</h2>
                    <p>(Family and Medical Leave Act)</p>
                </div>
                <div class="col-lg-5 col-sm-4 text-center">
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
                </div>
                <div class="col-lg-4 text-center">
                    <p>OMB Control Number: 1235-0003</p>
                    <strong>Expires: 8/31/2021</strong>
                </div>
            </div>
            <hr>
            <form id="w4-form" action="" method="post">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        In general, to be eligible an employee must have worked for an employer for at least 12 months, meet the hours of service requirement in the 12 months preceding the leave, and work at a site with at least 50 employees within 75 miles. While use of this form by employers is optional, a fully completed Form WH-381 provides employees with the information required by 29 C.F.R. § 825.300(b), which must be provided within five business days of the employee notifying the employer of the need for FMLA leave. Part B provides employees with information regarding their rights and responsibilities for taking FMLA leave, as required by 29 C.F.R. § 825.300(b), (c). 
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Part A – NOTICE OF ELIGIBILITY</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>To :</label>
                                            <input type="text" value="<?=$FMLA['full_name'];?>" name="employee" class="form-control" placeholder="Employee"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>From :</label>
                                            <input type="text" value="" name="employer_representative" class="form-control" placeholder="Employer Representative"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>Date:</label>
                                            <input type="text" value="<?=$FMLA['created_at'];?>" name="emp_job_title" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row form-group autoheight">
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                <label>On </label>
                                            </div> 
                                            <div class="col-lg-3 col-md-2 col-xs-12 col-sm-2">
                                                <input type="text" value="<?=$FMLA['created_at'];?>" name="informed_date" class="form-control" placeholder="Informed Date"/>
                                            </div>
                                            <div class="col-lg-4 col-md-5 col-xs-12 col-sm-5">
                                                <label>,  you informed us that you needed leave beginning on</label>
                                            </div>
                                            <div class="col-lg-3 col-md-2 col-xs-12 col-sm-2">
                                                <input type="text" value="<?=$FMLA['request_from_date'];?>" name="beginning_date" class="form-control" placeholder="Leave Start Date"/>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1">
                                                <label>for: </label>
                                            </div>
                                        </div>
                                    </div> 
                                   <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <div class="row">   
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                The birth of a child, or placement of a child with you for adoption or foster care; 
                                                                <input type="checkbox" id="js-fmla-medical-childcare" <?=$employee['childcare'] == 1 ? 'checked="true"' : '';?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <div class="row">   
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                Your own serious health condition; 
                                                                <input type="checkbox" id="js-fmla-medical-healthcondition" <?=$employee['healthcondition'] == 1 ? 'checked="true"' : '';?>/>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <div class="row">    
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                Because you are needed to care for your
                                                                <input type="checkbox" id="js-fmla-medical-care"  <?=$employee['care'] == 1 ? 'checked="true"' : '';?> />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                spouse
                                                                <input type="checkbox" id="js-fmla-medical-care-spouse" <?=$employee['carespouse'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                child
                                                                <input type="checkbox" id="js-fmla-medical-care-child" <?=$employee['carechild'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                parent due to his/her serious health condition
                                                                <input type="checkbox" id="js-fmla-medical-care-parent" <?=$employee['careparent'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <div class="row">    
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                Because of a qualifying exigency arising out of the fact that your
                                                                <input type="checkbox" id="js-fmla-medical-qualify" <?=$employee['qualify'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                spouse
                                                                <input type="checkbox" id="js-fmla-medical-qualify-spouse" <?=$employee['qualifyspouse'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                son or daughter
                                                                <input type="checkbox" id="js-fmla-medical-qualify-child" <?=$employee['qualifychild'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                parent is on covered active duty or call to covered active duty status with the Armed Forces
                                                                <input type="checkbox" id="js-fmla-medical-qualify-parent" <?=$employee['qualifyparent'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <div class="row">    
                                                    <div class="col-lg-12">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                Because you are the
                                                                <input type="checkbox" id="js-fmla-medical-other" <?=$employee['other'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                spouse
                                                                <input type="checkbox" id="js-fmla-medical-other-spouse" <?=$employee['otherspouse'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                son or daughter
                                                                <input type="checkbox" id="js-fmla-medical-other-child" <?=$employee['otherchild'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                parent
                                                                <input type="checkbox" id="js-fmla-medical-other-parent" <?=$employee['otherparent'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                next of kin of a covered servicemember with a serious injury or illness
                                                                <input type="checkbox" id="js-fmla-medical-other-kin" <?=$employee['otherkin'] == 1 ? 'checked="true"' : '';?>  />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <label>This Notice is to inform you that you:</label>
                                        </div>    
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Are eligible for FMLA leave (See Part B below for Rights and Responsibilities) 
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Are you not eligible for FMLA leave, because (only one reason need be checked, although you may not be eligible for other reasons):  
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Are eligible for FMLA leave (See Part B below for Rights and Responsibilities) 
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="control control--checkbox">
                                                            You have not met the FMLA’s 12-month length of service requirement. As of the first date of requested leave, you will have worked approximately
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6" style="padding-left: 70px;">
                                                            <input type="text" value="" name="informed_date" class="form-control" placeholder="Enter Number Of Months"/>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <label> months towards this requirement. </label>
                                                        </div>
                                                    </div>    
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="control control--checkbox">
                                                            You have not met the FMLA’s hours of service requirement. 
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">    
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="control control--checkbox">
                                                            You do not work and/or report to a site with 50 or more employees within 75-miles.  
                                                            <input type="checkbox" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row form-group autoheight">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>If you have any questions, contact</label>
                                            </div> 
                                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                <input type="text" value="" name="informed_date" class="form-control" placeholder="Enter Contact"/>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                <label>or view the</label>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row form-group autoheight">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>FMLA poster located in</label>
                                            </div> 
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <input type="text" value="" name="informed_date" class="form-control" placeholder="Enter Location"/>
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
                                <strong>PART B-RIGHTS AND RESPONSIBILITIES FOR TAKING FMLA LEAVE</strong>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row form-group autoheight">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>As explained in Part A, you meet the eligibility requirements for taking FMLA leave and still have FMLA leave available in the applicable 12-month period.</p>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="row form-group autoheight">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <label>However, in order for us to determine whether your absence qualifies as FMLA leave, you must return the following information to us by</label>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <input type="text" value="" name="informed_date" class="form-control" placeholder="Enter Way Of Communication"/>
                                            </div> 
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <p>. (If a certification is requested, employers must allow at least 15 calendar</p>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>days from receipt of this notice; additional time may be required in some circumstances.) If sufficient information is not provided in a timely manner, your</p>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <p>leave may be denied.</p>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Sufficient certification to support your request for FMLA leave. A certification form that sets forth the information necessary to support your  request
                                                            <input type="checkbox" name="child_birth" value="1" <?php echo !empty($pre_form['child_birth']) && $pre_form['child_birth'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="col-lg-1">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                is  
                                                                <input type="checkbox" name="certification_form_enclose" value="1" <?php echo !empty($pre_form['certification_form_enclose']) && $pre_form['certification_form_enclose'] == 1 ? 'checked="checked"': ''?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="form-group autoheight">
                                                            <label class="control control--checkbox">
                                                                is not 
                                                                <input type="checkbox" name="certification_form_enclose" value="0" <?php echo !empty($pre_form['certification_form_enclose']) && $pre_form['certification_form_enclose'] == 0 ? 'checked="checked"': ''?>>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <div class="form-group autoheight">
                                                            <label>
                                                                enclosed.
                                                            </label>  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Sufficient documentation to establish the required relationship between you and your family member.  
                                                            <input type="checkbox" name="sufficient_documentation" value="1" <?php echo !empty($pre_form['sufficient_documentation']) && $pre_form['sufficient_documentation'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Other information needed (such as documentation for military family leave):  
                                                            <input type="checkbox" name="is_military_other_information_added" value="1" <?php echo !empty($pre_form['is_military_other_information_added']) && $pre_form['is_military_other_information_added'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <textarea class="form-control textarea" name="military_other_information" placeholder="Enter Other Information"><?php echo !empty($pre_form['military_other_information']) ? $pre_form['military_other_information']: ''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            No additional information requested   
                                                            <input type="checkbox" name="no_additional_info_requested" value="1" <?php echo !empty($pre_form['no_additional_info_requested']) && $pre_form['no_additional_info_requested'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <strong>If your leave does qualify</strong> as FMLA leave you will have the following <strong>responsibilities</strong> while on FMLA leave (only checked blanks apply): 
                                        </div>
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-2">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            Contact   
                                                            <input type="checkbox" name="no_additional_info_requested" value="1" <?php echo !empty($pre_form['no_additional_info_requested']) && $pre_form['no_additional_info_requested'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group autoheight">
                                                        <input type="text" value="<?php echo !empty($pre_form['contact_person']) ? $pre_form['contact_person']: ''?>" name="contact_person" class="form-control" placeholder="Enter Contact Person"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1">
                                                    <div class="form-group autoheight">
                                                        <label>
                                                            at
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group autoheight">
                                                        <input type="text" value="<?php echo !empty($pre_form['contact_time']) ? $pre_form['contact_time']: ''?>" name="contact_time" class="form-control" placeholder="Enter Contact Time"/>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <p>to make arrangements to continue to make your share of the premium payments on your health insurance to maintain health benefits while you are on leave. You have a minimum 30-day (or, indicate longer period, if applicable) grace period in which to make premium payments. If payment is not made timely, your group health insurance may be cancelled, provided we notify you in writing at least 15 days before the date that your health coverage will lapse, or, at our option, we may pay your share of the premiums during FMLA leave, and recover these payments from you upon your return to work.</p>
                                                    </div>
                                                </div>       
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <div class="row">   
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            You will be required to use your available paid
                                                            <input type="checkbox" name="available_paid_leave" value="1" <?php echo !empty($pre_form['available_paid_leave']) && $pre_form['available_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            sick,
                                                            <input type="checkbox" name="available_paid_leave" value="1" <?php echo !empty($pre_form['available_paid_leave']) && $pre_form['available_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="form-group autoheight">
                                                        <label class="control control--checkbox">
                                                            vacation, and/or
                                                            <input type="checkbox" name="available_paid_leave" value="1" <?php echo !empty($pre_form['available_paid_leave']) && $pre_form['available_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="control control--checkbox">
                                                            other leave during your FMLA absence.
                                                        <input type="checkbox" name="available_paid_leave" value="1" <?php echo !empty($pre_form['available_paid_leave']) && $pre_form['available_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group autoheight">
                                                        <p>This means that you will receive your paid leave and the leave will also be considered protected FMLA leave and counted against your FMLA leave entitlement. </p>
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