<link rel="stylesheet" href="<?=base_url('assets/css/fmla.css');?>">
<main role="main" class="<?=isset($pd) ? 'container' : '';?>">
    <section class="sheet padding-10mm" id="js-preview">
        <div class="form-wrp">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  text-center">
                    <h2 style="margin-top: 0;">Designation Notice</h2>
                    <p style="font-size: 22px;">(Family and Medical Leave Act)</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">
                    <h2 style="margin-top: 0;">U.S. Department of Labor</h2>
                    <p style="">Wage and Hour Division</p>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                    <img id="imge" src="<?php echo base_url("assets/images/fmlalogo.png");?>" style="max-width: 100%;" class="fmla1_h1_right"></h1>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <p style="text-align-last: end">OMB Control Number: 1545-0074</p>
                    <strong style="float: right;">Expires: 8/31/2021</strong>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Leave covered under the Family and Medical Leave Act (FMLA) must be designated as FMLA-protected and the employer must inform the employee of the
                            amount of leave that will be counted against the employee’s FMLA leave entitlement. In order to determine whether leave is covered under the FMLA, the
                            employer may request that the leave be supported by a certification. If the certification is incomplete or insufficient, the employer must state in writing what
                            additional information is necessary to make the certification complete and sufficient. While use of this form by employers is optional, a fully completed Form
                            WH-382 provides an easy method of providing employees with the written information required by 29 C.F.R. §§ 825.300(c), 825.301, and 825.305(c). .
                        </strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>To :<span class="cs_required_icon"> *</span></label>
                                        <input type="text" value="<?php echo !empty($pre_form['notice_receiver'])>0 ? $pre_form['notice_receiver']: ''?>" name="notice_receiver" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label>Date :<span class="cs_required_icon"> *</span></label>
                                        <input type="text" value="<?php echo !empty($pre_form['sending_date']) ? $pre_form['sending_date']: ''?>" name="sending_date" class="form-control datepicker" readonly/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <p>We have reviewed your request for leave under the FMLA and any supporting documentation that you have provided.We received your most recent information on</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <input type="text" name="received_date" value="<?php  echo !empty($pre_form["received_info_date"]) ? $pre_form["received_info_date"] : ''  ;?>" readonly class="form-control datepicker" readonly>
                                    </div>
                                </div>
                               <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                  <div class="form-group autoheight">
                                     <p>and decided:</p>
                                  </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            Your FMLA leave request is approved. All leave taken for this reason will be designated as FMLA leave.
                                            <input type="checkbox" name="FMLA_leave_approved" value="FMLA_leave_approved" <?php echo !empty($pre_form['FMLA_leave_approved']) && $pre_form['FMLA_leave_approved'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <h4><b>The FMLA requires that you notify us as soon as practicable if dates of scheduled leave change or are extended, or were
                                        initially unknown. Based on the information you have provided to date, we are providing the following information about the
                                        amount of time that will be counted against your leave entitlement: </b></h4>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                              <p class="cs_label_setting">Provided there is no deviation from your anticipated leave schedule, the following number of hours, days, or weeks will be counted against your leave entitlement:</p>
                                            <input type="checkbox" name="no_deviation_frm_leave" value="no_deviation_frm_leave" <?php echo !empty($pre_form['no_deviation_frm_leave']) && $pre_form['no_deviation_frm_leave'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div style="padding-left:30px;">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight">
                                            <input type="text" class="form-control" name="days_counted_against_leave" value="<?php echo !empty($pre_form["days_counted_against_leave"]) ? $pre_form["days_counted_against_leave"] : '';?>" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            <p class="cs_label_setting">Because the leave you will need will be unscheduled, it is not possible to provide the hours, days, or weeks that will be counted
                                            against your FMLA entitlement at this time. You have the right to request this information once in a 30-day period (if leave
                                            was taken in the 30-day period).</p> 
                                            <input type="checkbox" name="leave_info_in_30_days" value="leave_info_in_30_days" <?php echo !empty($pre_form['leave_info_in_30_days']) && $pre_form['leave_info_in_30_days'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <p style="font-size: 16px;"><b>Please be advised (check if applicable):</b></p>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                           <p class="cs_label_setting"> You have requested to use paid leave during your FMLA leave. Any paid leave taken for this reason will count against your
                                            FMLA leave entitlement. </p>
                                            <input type="checkbox" name="requested_paid_leave" value="requested_paid_leave" <?php echo !empty($pre_form['requested_paid_leave']) && $pre_form['requested_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            <p class="cs_label_setting">We are requiring you to substitute or use paid leave during your FMLA leave. </p>
                                            <input type="checkbox" name="use_paid_leave" value="use_paid_leave" <?php echo !empty($pre_form['use_paid_leave']) && $pre_form['use_paid_leave'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group autoheight">
                                        <label class="control control--checkbox">
                                            <p class="cs_label_setting">You will be required to present a fitness-for-duty certificate to be restored to employment. If such certification is not timely
                                            received, your return to work may be delayed until certification is provided. A list of the essential functions of your position </p>
                                            <input type="checkbox" name="fitness_for_duty_certificate" value="fitness_for_duty_certificate" <?php echo !empty($pre_form['fitness_for_duty_certificate']) && $pre_form['fitness_for_duty_certificate'] == 1 ? 'checked="checked"': ''?>>
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                                <div style="padding:30px;">
                                    <div class="col-lg-1 col-md-1 col-xs-3 col-sm-2">
                                        <div class="form-group autoheight" >
                                            <label class="control control--checkbox">
                                                 is
                                                 <input type="checkbox" name="fitness_certification" value="fitness_certification_attached" <?php echo !empty($pre_form['fitness_certification_attached']) && $pre_form['fitness_certification_attached'] == 1 ? 'checked="checked"': ''?>>
                                                 <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-5 col-sm-2">
                                        <div class="form-group autoheight" >
                                            <label class="control control--checkbox">
                                                 is not
                                                <input type="checkbox" name="fitness_certification" value="fitness_certification_not_attached" <?php echo !empty($pre_form['fitness_certification_not_attached']) && $pre_form['fitness_certification_not_attached'] == 1 ? 'checked="checked"': ''?>>
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                                        <div class="form-group autoheight" >
                                        <p class="cs_label_setting"> attached. If attached, the fitness-for-duty certification must address your ability to perform these functions.</p>
                                        </div> 
                                    </div>
                                </div>      
                            </div> 
                         </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                              <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                  <div class="form-group autoheight">
                                      <label class="control control--checkbox">
                                        Additional information is needed to determine if your FMLA leave request can be approved: 
                                        <input type="checkbox" name="additional_information" value="additional_information" <?php echo !empty($pre_form['additional_information']) && $pre_form['additional_information'] == 1 ? 'checked="checked"': ''?>>
                                        <div class="control__indicator"></div>
                                        </label>
                                  </div>
                              </div>
                              <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                  <div class="form-group autoheight">
                                      <label class="control control--checkbox">
                                         <p class="cs_label_setting">The certification you have provided is not complete and sufficient to determine whether the FMLA applies to your leave request.</p>   
                                         <input type="checkbox" name="certification_provided" value="certification_provided" <?php echo !empty($pre_form['certification_provided']) && $pre_form['certification_provided'] == 1 ? 'checked="checked"': ''?>>
                                         <div class="control__indicator"></div>
                                       </label>
                                   </div>
                              </div>
                                <div style="padding-left:30px">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">   
                                                <p class="cs_label_setting"> You must provide the following information no later than </p>
                                            </div>
                                    </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <input readonly="true" type="text" class="form-control datepicker" name="date_to_provide_info" >
                                            </div>
                                        </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <p class="cs_label_setting">, unless it is not </p>
                                            </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <p class="cs_label_setting">practicable under the particular circumstances despite your diligent good faith efforts, or your leave may be denied. </p>
                                            </div>
                                    </div>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-bottom:10px">
                            <div class="form-group autoheight" >
                                <textarea placeholder="(Specify information needed to make the certification complete and sufficient)" class="form-control" name="specify_info"><?php echo !empty($pre_form['specify_info']) ? $pre_form['specify_info']: ''?></textarea>
                            </div>
                          </div>
                           <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                  <div class="form-group autoheight">
                                      <label class="control control--checkbox">
                                       <p class="cs_label_setting">We are exercising our right to have you obtain a second or third opinion medical certification at our expense, and we will
                                         . provide further details at a later time.</p>   
                                        <input type="checkbox" name="medical_certification" value="medical_certification" <?php echo !empty($pre_form['medical_certification']) && $pre_form['medical_certification'] == 1 ? 'checked="checked"': ''?>>
                                      <div class="control__indicator"></div>
                                   </label>
                              </div>
                           </div>
                         </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    <p class="cs_label_setting"> Your FMLA Leave request is Not Approved.</p> 
                                                    <input type="checkbox" name="FMLA_leave_not_approved" value="sick" <?php echo !empty($pre_form['FMLA_leave_not_approved']) && $pre_form['FMLA_leave_not_approved'] == 1 ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    <p class="cs_label_setting"> The FMLA does not apply to your leave request. </p>
                                                    <input type="checkbox" name="FMLA_cannot_be_applied" value="FMLA_cannot_be_applied" <?php echo !empty($pre_form['FMLA_cannot_be_applied']) && $pre_form['FMLA_cannot_be_applied'] == 1 ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="form-group autoheight">
                                                <label class="control control--checkbox">
                                                    <p class="cs_label_setting"> You have exhausted your FMLA leave entitlement in the applicable 12-month period. </p>
                                                    <input type="checkbox" name="exhausted_FMLA_leave" value="sick" <?php echo !empty($pre_form['exhausted_FMLA_leave']) && $pre_form['exhausted_FMLA_leave'] == 1 ? 'checked="checked"': ''?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>      
                </div> 
            </div>
            <div class="row">   
                 <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <h3 class="text-center">
                        PAPERWORK REDUCTION ACT NOTICE AND PUBLIC BURDEN STATEMENT
                    </h3>
                    <p>
                      It is mandatory for employers to inform employees in writing whether leave requested under the FMLA has been determined to be covered under the FMLA. 29 U.S.C.
                        § 2617; 29 C.F.R. §§ 825.300(d), (e). It is mandatory for employers to retain a copy of this disclosure in their records for three years. 29 U.S.C. § 2616; 29 C.F.R. §
                        825.500. Persons are not required to respond to this collection of information unless it displays a currently valid OMB control number. The Department of Labor
                        estimates that it will take an average of 10 – 30 minutes for respondents to complete this collection of information, including the time for reviewing instructions,
                        searching existing data sources, gathering and maintaining the data needed, and completing and reviewing the collection of information. If you have any comments
                        regarding this burden estimate or any other aspect of this collection information, including suggestions for reducing this burden, send them to the Administrator, Wage
                        and Hour Division, U.S. Department of Labor, Room S-3502, 200 Constitution Ave., NW, Washington, DC 20210.  <strong>DO NOT SEND THE COMPLETED FORM;TO THE WAGE AND HOUR DIVISION. </strong>
                    </p>
                 </div>
                  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                  <p style="float:right"><b>Form WH-382 January 2009</b></p>
                  </div>
            </div>
        </div>
    </section>
</main>
<script>
    $(function(){
        $('#js-preview').find('input, textarea, select').addClass('disabled').prop('disabled', true);
    })
</script>