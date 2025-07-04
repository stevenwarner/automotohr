<?php if ($reference_status == 'active') { ?>
    <div class="universal-form-style-v2">
        <?php if ($this->uri->segment(1) == 'reference_questionnaire') { ?>
            <h2><?php echo ucwords($users_type) . ' Details' ?></h2>
            <hr />
            <ul>
                <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <label><?php echo ucwords($users_type . ' Name'); ?></label>
                    <p><?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?></p>
                </li>
                <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <label>Job Title</label>
                    <p><?php echo ucwords($employer['job_title']); ?></p>
                </li>
                <li class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <label>Primary Number</label>
                    <p><?php echo ucwords($employer['PhoneNumber']); ?></p>
                </li>
            </ul>
            <div class="clear"></div>

        <?php } else { ?>
            <ul>
                <li class="form-col-50-left">
                    <label>Name</label>
                    <p><?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?></p>
                </li>
                <li class="form-col-50-right">
                    <label>Job Title</label>
                    <p><?php echo ucwords($employer['job_title']); ?></p>
                </li>
                <li class="form-col-50-left">
                    <label>Reference Name</label>
                    <p><?php echo ucwords($reference['reference_name']); ?></p>
                </li>
                <li class="form-col-50-right">
                    <label>Reference Title</label>
                    <p><?php echo ucwords($reference['reference_title']); ?></p>
                </li>
                <li class="form-col-50-left">
                    <label>Reference Email</label>
                    <p><?php echo ucwords($reference['reference_email']); ?></p>
                </li>
                <li class="form-col-50-right">
                    <label>Reference Phone</label>
                    <p><?php echo ucwords($reference['reference_phone']); ?></p>
                </li>
            </ul>
        <?php } ?>
        <h2>Please Fill in the Following Details:</h2>
        <hr />
        <?php if ($this->uri->segment(1) == 'reference_questionnaire') { ?>
            <div class="well well-sm">
                <h4 class="text-justify">Your responses to these questions are personal and do not reflect the views of any company/organization to which you belong now or to which you have belonged in the past.</h4>
            </div>
        <?php } ?>
        <ul>
            <?php if ($reference['reference_type'] == 'work') { ?>
                <form id="form_work_reference_questionnaire" method="post">
                    <input type="hidden" id="perform_action" name="perform_action" value="save_work_reference_questionnaire_information" />
                    <input type="hidden" id="conducted_by" name="conducted_by" value="<?php echo (isset($session) ? $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'] : $reference['reference_name']); ?>" />
                    <input type="hidden" id="conducted_date" name="conducted_date" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                    <li class="form-col-100 autoheight">
                        <label for="position">1. What position did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" hold with your company?<span class="staric">*</span></label>
                        <input class="invoice-fields" type="text" id="position" name="position" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['position'] : ''); ?>" />
                    </li>
                    <li class="form-col-100 autoheight">
                        <label>2. What were the dates of employment?</label>
                        <div class="form-col-50-left autoheight">
                            <label for="work_period_start">From Date<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="work_period_start" name="work_period_start" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['work_period_start'] : ''); ?>" />
                        </div>
                        <div class="form-col-50-right autoheight">
                            <label for="work_period_end">To Date</label>
                            <input class="invoice-fields" type="text" id="work_period_end" name="work_period_end" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['work_period_end'] : ''); ?>" />
                        </div>
                    </li>
                    <!--                    <li class="form-col-100 autoheight">
                        <label for="final_salary">3. What was the final salary?<span class="staric">*</span></label>
                        <input class="invoice-fields" type="text" id="final_salary" name="final_salary" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['final_salary'] : ''); ?>" />                       
                    </li>-->
                    <input type="hidden" id="final_salary" name="final_salary" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['final_salary'] : ''); ?>" />
                    <li class="form-col-100 autoheight">
                        <label for="duties_description">3. Describe the duties "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" performed in this position.</label>
                        <textarea class="invoice-fields-textarea" id="duties_description" rows="5" name="duties_description"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['duties_description'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="performance">4. How would you describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s overall performance?</label>
                        <textarea class="invoice-fields-textarea" id="performance" rows="5" name="performance"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['performance'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="late_or_absent">5. Approximately how many times in a 12 month period was "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" late or absent from work, excluding FMLA time and any approved time such as vacation and paid sick time?</label>
                        <textarea class="invoice-fields-textarea" id="late_or_absent" rows="5" name="late_or_absent"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['late_or_absent'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="teamwork">6. How well did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" get along with coworkers (i.e., teamwork)?</label>
                        <textarea class="invoice-fields-textarea" id="teamwork" rows="5" name="teamwork"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['teamwork'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="follow_directions">7. How well did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" follow direction?</label>
                        <textarea class="invoice-fields-textarea" id="follow_directions" rows="5" name="follow_directions"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['follow_directions'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="assignments_performance">8. How well did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" perform assignments?</label>
                        <textarea class="invoice-fields-textarea" id="assignments_performance" rows="5" name="assignments_performance"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['assignments_performance'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="assignments_performance_timely">9. Did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" follow-through on assignments in a timely manner? Please describe.</label>
                        <textarea class="invoice-fields-textarea" id="assignments_performance_timely" rows="5" name="assignments_performance_timely"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['assignments_performance_timely'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="decision_making_and_work_independently">10. How was <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s decision making ability and ability to work independently?</label>
                        <textarea class="invoice-fields-textarea" id="decision_making_and_work_independently" rows="5" name="decision_making_and_work_independently"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['decision_making_and_work_independently'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="written_and_verbal_communication">11. Describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s written and verbal communication skills (i.e., the ability to verbally communicate with others, type vs. Draft memos or correspondence). (Ask only if relevant to job.)</label>
                        <textarea class="invoice-fields-textarea" id="written_and_verbal_communication" rows="5" name="written_and_verbal_communication"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['written_and_verbal_communication'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="duties_best_performed">12. What duties did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" perform the best?</label>
                        <textarea class="invoice-fields-textarea" id="duties_best_performed" rows="5" name="duties_best_performed"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['duties_best_performed'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="areas_of_improvement">13. What areas could have been improved?</label>
                        <textarea class="invoice-fields-textarea" id="areas_of_improvement" rows="5" name="areas_of_improvement"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['areas_of_improvement'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="disciplinary_record">14. Did "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" have a disciplinary record? If so, please briefly describe the nature of that record and dates of discipline.</label>
                        <textarea class="invoice-fields-textarea" id="disciplinary_record" rows="5" name="disciplinary_record"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['disciplinary_record'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="dishonesty_insubordination">15. Were there any incidents of dishonesty, insubordination or threatening behavior? Please describe.</label>
                        <textarea class="invoice-fields-textarea" id="dishonesty_insubordination" rows="5" name="dishonesty_insubordination"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['dishonesty_insubordination'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="reason_for_leaving">16. What was the reason for leaving?</label>
                        <textarea class="invoice-fields-textarea" id="reason_for_leaving" rows="5" name="reason_for_leaving"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['reason_for_leaving'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="would_re_employ">17. Would you re-employ, and if not, why?</label>
                        <textarea class="invoice-fields-textarea" id="would_re_employ" rows="5" name="would_re_employ"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['would_re_employ'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="questionair_radio_container">
                            <label for="should_accept">18. Would you recommend that we accept "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" in our Organization? </label>
                            <input type="radio" id="" name="should_accept" style="visibility: hidden;" />
                        </div>

                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_yes" value="yes" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'yes'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_yes">
                                Yes
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_no" value="no" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'no'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_no">
                                No
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_dont_know" value="dont_know" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'dont_know'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_dont_know">
                                Don't Know
                            </label>
                        </div>
                    </li>
                    <!--
                    <li class="form-col-100 autoheight">
                        <label>Reference Check conducted by:</label>
                        <div class="form-col-50-left autoheight">
                            <label for="referee_name">Name<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="referee_name" name="referee_name" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['referee_name'] : ''); ?>" />
                        </div>
                        <div class="form-col-50-right autoheight">
                            <label for="referee_title">Title<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="referee_title" name="referee_title" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['referee_title'] : ''); ?>" />
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-50-left autoheight">
                            <label for="conducted_date">Conducted On Date<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="conducted_date" name="conducted_date" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['conducted_date'] : ''); ?>" />
                        </div>
                        <div class="form-col-50-right autoheight">
                        </div>
                    </li>
                    -->
                    <li class="form-col-100 autoheight">
                        <button class="btn btn-lg btn-success" onclick="fSaveWorkReferenceQuestionnaireInformation();"><i class="glyphicon glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                    </li>
                </form>
            <?php } else if ($reference['reference_type'] == 'personal') { ?>
                <form class="form_personal_reference_questionnaire" id="form_personal_reference_questionnaire" method="post">
                    <input type="hidden" id="perform_action" name="perform_action" value="save_personal_reference_questionnaire_information" />
                    <input type="hidden" id="conducted_by" name="conducted_by" value="<?php echo (isset($session) ? $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'] : $reference['reference_name']); ?>" />
                    <input type="hidden" id="conducted_date" name="conducted_date" value="<?php echo date("Y-m-d H:i:s"); ?>" />
                    <li class="form-col-100 autoheight">
                        <label for="period_known">1. How Long Have You Known "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>"?<span class="staric">*</span></label>
                        <input class="invoice-fields" type="text" id="period_known" name="period_known" value="<?php echo (!empty($questionnaire_information) ? $questionnaire_information['period_known'] : ''); ?>" />
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="personal_setting">2. In What Types of Personal/Social Settings Have You Known "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>"?<span class="staric">*</span></label>
                        <textarea class="invoice-fields-textarea" type="text" id="personal_setting" name="personal_setting"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['personal_setting'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-100">
                            <label for="how_well_you_know">3. How Well Do You Know "<?php echo ucwords($employer['first_name']); ?>" (Check where appropriate)<span class="staric">*</span></label>
                            <input type="radio" id="" name="how_well_you_know" value="" style="visibility: hidden;" />
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="by_name_sight" name="how_well_you_know" value="by_name_sight" <?php echo (!empty($questionnaire_information) && $questionnaire_information['how_well_you_know'] == 'by_name_sight'  ? 'checked="checked" ' : ''); ?> />
                            <label for="by_name_sight">
                                By Name / Sight
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="very_well" name="how_well_you_know" value="very_well" <?php echo (!empty($questionnaire_information) && $questionnaire_information['how_well_you_know'] == 'very_well'  ? 'checked="checked" ' : ''); ?> />
                            <label for="very_well">
                                Very Well / Numerous Personal Contacts
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="casually" name="how_well_you_know" value="casually" <?php echo (!empty($questionnaire_information) && $questionnaire_information['how_well_you_know'] == 'casually'  ? 'checked="checked" ' : ''); ?> />
                            <label for="casually">
                                Casually / Few Personal Contacts
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="quite_well" name="how_well_you_know" value="quite_well" <?php echo (!empty($questionnaire_information) && $questionnaire_information['how_well_you_know'] == 'quite_well'  ? 'checked="checked" ' : ''); ?> />
                            <label for="quite_well">
                                Know "<?php echo ucwords($employer['first_name']); ?>" Quite Well
                            </label>
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="brief_description_of_success">4. Briefly Describe Why You Believe "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" Will Succeed In A Professional Career.</label>
                        <textarea class="invoice-fields-textarea" type="text" id="brief_description_of_success" name="brief_description_of_success"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['brief_description_of_success'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="strengths_and_weaknesses">5. Describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s strengths and weaknesses.</label>
                        <textarea class="invoice-fields-textarea" type="text" id="strengths_and_weaknesses" name="strengths_and_weaknesses"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['strengths_and_weaknesses'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-100">
                            <label for="writing_skills">6. How would you describe the writing skills of the applicant:</label>
                            <input type="radio" id="" name="writing_skills" style="visibility: hidden;" />
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="stream_of_consciousness" value="stream_of_consciousness" name="writing_skills" <?php echo (!empty($questionnaire_information) && $questionnaire_information['writing_skills'] == 'stream_of_consciousness'  ? 'checked="checked" ' : ''); ?> />
                            <label for="stream_of_consciousness">
                                Stream of Consciousness
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="well_constructed" value="well_constructed" name="writing_skills" <?php echo (!empty($questionnaire_information) && $questionnaire_information['writing_skills'] == 'well_constructed'  ? 'checked="checked" ' : ''); ?> />
                            <label for="well_constructed">
                                Well Constructed, Supported, Organized
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="thoughtful" value="thoughtful" name="writing_skills" <?php echo (!empty($questionnaire_information) && $questionnaire_information['writing_skills'] == 'thoughtful'  ? 'checked="checked" ' : ''); ?> />
                            <label for="thoughtful">
                                Thoughtful, But Lacks Organization
                            </label>
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="leadership">7. How Would You Describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s Institutional And Personnel Leadership Skills?</label>
                        <textarea class="invoice-fields-textarea" id="leadership" name="leadership"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['leadership'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-100">
                            <label for="punctual">8. Is "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" punctual?</label>
                            <input type="radio" id="" name="punctual" style="visibility: hidden;" />
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="punctual_yes" value="yes" name="punctual" <?php echo (!empty($questionnaire_information) && $questionnaire_information['punctual'] == 'yes'  ? 'checked="checked" ' : ''); ?> />
                            <label for="punctual_yes">
                                Yes
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="punctual_no" value="no" name="punctual" <?php echo (!empty($questionnaire_information) && $questionnaire_information['punctual'] == 'no'  ? 'checked="checked" ' : ''); ?> />
                            <label for="punctual_no">
                                No
                            </label>
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="work_attitude">9. Please Describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s Attitude Toward Work.</label>
                        <textarea class="invoice-fields-textarea" id="work_attitude" name="work_attitude"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['work_attitude'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="outstanding_abilities">10. Please describe <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s outstanding abilities or talents.</label>
                        <textarea class="invoice-fields-textarea" id="outstanding_abilities" name="outstanding_abilities"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['outstanding_abilities'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="follow_instructions">11. How well does "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" follow instructions?</label>
                        <textarea class="invoice-fields-textarea" id="follow_instructions" name="follow_instructions"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['follow_instructions'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="self_starter_or_motivated_by_others">12. Would you describe "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" as a self-starter or one who needs to be motivated by others?</label>
                        <textarea class="invoice-fields-textarea" id="self_starter_or_motivated_by_others" name="self_starter_or_motivated_by_others"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['self_starter_or_motivated_by_others'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="stressful_situations">13. In stressful situations, describe how "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" reacted. Be specific.</label>
                        <textarea class="invoice-fields-textarea" id="stressful_situations" name="stressful_situations"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['stressful_situations'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="difficult_people">14. Additionally, how does "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" handle difficult people? What is his/her conflict resolution protocol?</label>
                        <textarea class="invoice-fields-textarea" id="difficult_people" name="difficult_people"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['difficult_people'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="tactful_manner">15. Does "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" always conduct his/her dealings with others in a tactful manner? Explain.</label>
                        <textarea class="invoice-fields-textarea" id="tactful_manner" name="tactful_manner"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['tactful_manner'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="accomplishments">16. What Are Some of <?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>'s Key Accomplishments?</label>
                        <textarea class="invoice-fields-textarea" id="accomplishments" name="accomplishments"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['accomplishments'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="development_areas">17. What area of development could "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" focus on?</label>
                        <textarea class="invoice-fields-textarea" id="development_areas" name="development_areas"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['development_areas'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <label for="advice">18. If you were going to provide advice on how to best guide "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>", what would it be?</label>
                        <textarea class="invoice-fields-textarea" id="advice" name="advice"><?php echo (!empty($questionnaire_information) ? $questionnaire_information['advice'] : ''); ?></textarea>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-100">
                            <label for="should_accept">19. Would you recommend that we accept "<?php echo ucwords($employer['first_name'] . ' ' . $employer['last_name']); ?>" in our Organization? </label>
                            <input type="radio" id="" name="should_accept" style="visibility: hidden;" />
                        </div>

                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_yes" value="yes" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'yes'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_yes">
                                Yes
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_no" value="no" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'no'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_no">
                                No
                            </label>
                        </div>
                        <div class="questionair_radio_container">
                            <input type="radio" id="should_accept_dont_know" value="dont_know" name="should_accept" <?php echo (!empty($questionnaire_information) && $questionnaire_information['should_accept'] == 'dont_know'  ? 'checked="checked" ' : ''); ?> />
                            <label for="should_accept_dont_know">
                                Don't Know
                            </label>
                        </div>
                    </li>
                    <!--
                    <li class="form-col-100 autoheight">
                        <label>Reference Check conducted by:</label>
                        <div class="form-col-50-left autoheight">
                            <label for="referee_name">Name<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="referee_name" name="referee_name" value="<?php //echo ( !empty($questionnaire_information) ? $questionnaire_information['referee_name'] : '' );
                                                                                                                    ?>" />
                        </div>
                        <div class="form-col-50-right autoheight">
                            <label for="referee_title">Title<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="referee_title" name="referee_title" value="<?php //echo ( !empty($questionnaire_information) ? $questionnaire_information['referee_title'] : '' );
                                                                                                                        ?>" />
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-50-left autoheight">
                            <label for="conducted_date">Conducted On Date<span class="staric">*</span></label>
                            <input class="invoice-fields" type="text" id="conducted_date" name="conducted_date" value="<?php //echo ( !empty($questionnaire_information) ? $questionnaire_information['conducted_date'] : '' );
                                                                                                                        ?>" />
                        </div>
                        <div class="form-col-50-right autoheight">
                        </div>
                    </li>
                    -->
                    <li class="form-col-100 autoheight">
                        <button class="btn btn-lg btn-success" onclick="fSavepersonalReferenceQuestionnaireInformation();"><i class="glyphicon glyphicon glyphicon-floppy-disk"></i>&nbsp;Save</button>
                    </li>
                </form>
            <?php } ?>
        </ul>
    </div>



    <script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.validate.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets'); ?>/js/additional-methods.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            fEnableDatePickerAndSetDateLimits('work_period_start', 'work_period_end');
            fEnableDatePicker('conducted_date');


        });


        function fEnableDatePicker(dateInputId) {
            $('#' + dateInputId).datepicker({
                changeYear: true,
                dateFormat: 'mm-dd-yy'
            }).on('focusin', function() {
                $(this).prop('readonly', true);
            }).on('focusout', function() {
                $(this).prop('readonly', false);
            });
        }

        function fEnableDatePickerAndSetDateLimits(startDateInputId, endDateInputId) {
            $('#' + startDateInputId).datepicker({

                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                onSelect: function(selected) {
                    var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                    console.log(dt);
                    dt.setDate(dt.getDate() + 1);
                    $('#' + endDateInputId).datepicker("option", "minDate", dt);
                }
            }).on('focusin', function() {
                $(this).prop('readonly', true);
            }).on('focusout', function() {
                $(this).prop('readonly', false);
            });

            $('#' + endDateInputId).datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
                setDate: new Date(),
                onSelect: function(selected) {
                    var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                    console.log(dt);
                    dt.setDate(dt.getDate() - 1);
                    $('#' + startDateInputId).datepicker("option", "maxDate", dt);
                }
            }).on('focusin', function() {
                $(this).prop('readonly', true);
            }).on('focusout', function() {
                $(this).prop('readonly', false);
            });
        }


        <?php if ($reference['reference_type'] == 'work') { ?>

            function fValidateWorkReferenceQuestionnairInformation() {
                $('#form_work_reference_questionnaire').validate({
                    rules: {
                        position: {
                            required: true,
                            minlength: 2,
                            maxlength: 100,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        work_period_start: {
                            required: true
                        },
                        duties_description: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        performance: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        late_or_absent: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        teamwork: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        follow_directions: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        assignments_performance: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        assignments_performance_timely: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        decision_making_and_work_independently: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        written_and_verbal_communication: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        duties_best_performed: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        areas_of_improvement: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        disciplinary_record: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        dishonesty_insubordination: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        reason_for_leaving: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        would_re_employ: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        referee_name: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        referee_title: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        conducted_date: {
                            required: true
                        },
                        should_accept: {
                            required: true
                        }
                    }
                });
            }

            function fSaveWorkReferenceQuestionnaireInformation() {
                fValidateWorkReferenceQuestionnairInformation();
                if ($('#form_work_reference_questionnaire').valid()) {
                    $('#form_work_reference_questionnaire').submit();
                }
            }
        <?php } else if ($reference['reference_type'] == 'personal') { ?>

            function fValidatepersonalReferenceQuestionnairInformation() {
                $('#form_personal_reference_questionnaire').validate({
                    rules: {
                        period_known: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        personal_setting: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        how_well_you_know: {
                            required: true
                        },
                        writing_skills: {
                            required: true
                        },
                        punctual: {
                            required: true
                        },
                        should_accept: {
                            required: true
                        },
                        brief_description_of_success: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        strengths_and_weaknesses: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        leadership: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        work_attitude: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        outstanding_abilities: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        follow_instructions: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        self_starter_or_motivated_by_others: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        stressful_situations: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        difficult_people: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        tactful_manner: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        accomplishments: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        development_areas: {
                            minlength: 2,
                            maxlength: 800,
                            //pattern: /^[A-Za-z\s\.']+$/
                        },
                        referee_name: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        referee_title: {
                            required: true,
                            minlength: 2,
                            maxlength: 150,
                            //pattern: /^[A-Za-z\s\.,:;']+$/
                        },
                        conducted_date: {
                            required: true
                        }
                    }
                });
            }

            function fSavepersonalReferenceQuestionnaireInformation() {
                fValidatepersonalReferenceQuestionnairInformation();
                if ($('#form_personal_reference_questionnaire').valid()) {
                    $('#form_personal_reference_questionnaire').submit();
                }
            }
        <?php } ?>
    </script>

<?php } else { ?>

    <div class="no-job-found">
        <ul>
            <li>
                <h3 style="text-align: center;">Reference Not Found! </h3>
            </li>
        </ul>
    </div>

<?php } ?>