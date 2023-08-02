<style>
    label.control {
        font-weight: normal;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="header-logo">
            <img src="<?php echo base_url('assets/images/eeoc_logo.jpg') ?>" alt="" />
        </div>
    </div>
</div>
<br />
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <h2>EQUAL EMPLOYMENT OPPORTUNITY COMMISSION</h2>
    </div>
</div>
<br />
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <p><strong>EQUAL EMPLOYMENT OPPORTUNITY FORM</strong><br />We are an equal opportunity employer and we give consideration for employment to qualified applicants without regard to race, color, religion, sex, sexual orientation, gender identity, national origin, disability, or protected veteran status.</p>
        <p>If you'd like more information about your EEO rights as an applicant under the law, please visit:<br><strong>http://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf.</strong></p>
        <p>Federal law requires employers to provide reasonable accommodation to qualified individuals with disabilities. In the event you require reasonable accommodation to apply for this job, please contact our company and appropriate assistance will be provided.</p>
    </div>
</div>
<!--  -->
<?php if ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
    <hr />
    <div class="row">
        <div class="col-sm-3">
            <a class="btn btn-warning btn-block" href="javascript:;">Save EEOC</a>
        </div>
        <div class="col-sm-9">
            <p>By clicking the "Save EEOC" button will solely update the EEOC data.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <a class="btn btn-success btn-block" href="javascript:;">Consent EEOC</a>
        </div>
        <div class="col-sm-9">
            <p>By clicking the "Consent EEOC" button, you can save the EEOC form on behalf of the employee.</p>
        </div>
    </div>
<?php } ?>
<!--  -->
<?php 
$eeo_form_info=syncEeocData($eeo_form_info['application_sid'], $eeo_form_info);
?>

<div class="row">
    <br>
    <div class="col-sm-12">
        <div class="bg-gray p10">
            <?php 
                $required_label = '';
                
                if ($dl_citizen == 1) {
                    $required_label = '<span style="color: red; font-size: 16px;"> * </span>';
                }
            ?>
            <strong>I am a U.S. citizen or permanent resident <?php echo $required_label; ?></strong>
        </div>
        <label class="control control--radio">
            <input type="radio" name="citizen" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'Yes' ? 'checked="checked"' : ''; ?> value="Yes" <?php echo $is_readonly; ?>> Yes
            <div class="control__indicator"></div>
        </label>
        &nbsp;
        <label class="control control--radio">
            <input type="radio" name="citizen" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'No' ? 'checked="checked"' : ''; ?> value="No" <?php echo $is_readonly; ?>> NO
            <div class="control__indicator"></div>
        </label>
        <?php if (!empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'no') { ?>
            <strong>Visa Status :</strong> <?php echo !empty($eeo_form_info['visa_status']) ? $eeo_form_info['visa_status'] : 'No Status Found'; ?>
        <?php } ?>
    </div>
</div>

<!--  -->
<div class="row">
    <br>
    <div class="col-sm-12">
        <div class="bg-gray p10">
            <strong>1. GROUP STATUS (PLEASE CHECK ONE) </strong>
        </div>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="Hispanic or Latino" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Hispanic or Latino' ? 'checked="checked"' : ''; ?>> Hispanic or Latino - A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.
            <div class="control__indicator"></div>
        </label> <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="White" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'White' ? 'checked="checked"' : ''; ?>> White (Not Hispanic or Latino) - A person having origins in any of the original peoples of Europe, the Middle East or North Africa.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="Black or African American" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Black or African American' ? 'checked="checked"' : ''; ?>> Black or African American (Not Hispanic or Latino) - A person having origins in any of the black racial groups of Africa.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="Native Hawaiian or Other Pacific Islander" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Native Hawaiian or Other Pacific Islander' ? 'checked="checked"' : ''; ?>> Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) - A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="Asian" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Asian' ? 'checked="checked"' : ''; ?>> Asian (Not Hispanic or Latino) - A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="American Indian or Alaska Native" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'American Indian or Alaska Native' ? 'checked="checked"' : ''; ?>> American Indian or Alaska Native (Not Hispanic or Latino) - A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="group" value="Two or More Races" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Two or More Races' ? 'checked="checked"' : ''; ?>> Two or More Races (Not Hispanic or Latino) - All persons who identify with more than one of the above five races.
            <div class="control__indicator"></div>
        </label>
    </div>
</div>

<!--  -->
<div class="row">
    <br>
    <div class="col-sm-12">
        <div class="bg-gray p10">
            <strong>2. VETERAN </strong>
        </div>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="veteran" value="Disabled Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Disabled Veteran' ? 'checked="checked"' : ''; ?>> Disabled Veteran: A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="veteran" value="Recently Separated Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == "Recently Separated Veteran"  ? 'checked="checked"' : ''; ?>> Recently Separated Veteran: A "recently separated veteran" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="veteran" value="Active Duty Wartime or Campaign Badge Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Active Duty Wartime or Campaign Badge Veteran' ? 'checked="checked"' : ''; ?>> Active Duty Wartime or Campaign Badge Veteran: An "active duty wartime or campaign badge veteran" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="veteran" value="Armed Forces Service Medal Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Armed Forces Service Medal Veteran' ? 'checked="checked"' : ''; ?>> Armed Forces Service Medal Veteran: An "Armed forces service medal veteran" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985.
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="veteran" value="I Am Not a Protected Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'I Am Not a Protected Veteran' ? 'checked="checked"' : ''; ?>> I Am Not a Protected Veteran
            <div class="control__indicator"></div>
        </label>
    </div>
</div>

<!--  -->
<div class="row">
    <br>
    <div class="col-sm-12">
        <div class="bg-gray p10">
            <strong>3. VOLUNTARY SELF-IDENTIFICATION OF DISABILITY </strong>
        </div>
        <strong>Why are you being asked to complete this form?</strong>
        <p>Because we do business with the government, we must reach out to, hire, and provide equal opportunity to qualified people with disabilities.i To help us measure how well we are doing, we are asking you to tell us if you have a disability or if you ever had a disability. Completing this form is voluntary, but we hope that you will choose to fill it out. If you are applying for a job, any answer you give will be kept private and will not be used against you in any way.</p>
        <p>If you already work for us, your answer will not be used against you in any way. Because a person may become disabled at any time, we are required to ask all of our employees to update their information every five years. You may voluntarily self-identify as having a disability on this form without fear of any punishment because you did not identify as having a disability earlier.</p>
        <strong>How do I know if I have a disability?</strong>
        <p>You are considered to have a disability if you have a physical or mental impairment or medical condition that substantially limits a major life activity, or if you have a history or record of such an impairment or medical condition.</p>
        <p> Disabilities include, but are not limited to:</p>

        <div class="row">
            <div class="col-sm-3 col-md-3 col-xs-12">
                <ul>
                    <li>Blindness</li>
                    <li>Deafness</li>
                    <li>Cancer</li>
                    <li>Diabetes</li>
                    <li>Epilepsy</li>
                </ul>
            </div>
            <div class="col-sm-3 col-md-3 col-xs-12">
                <ul>
                    <li>Autism</li>
                    <li>Cerebral palsy</li>
                    <li>HIV/AIDS</li>
                    <li>Schizophrenia</li>
                    <li>Muscular dystrophy</li>
                </ul>
            </div>
            <div class="col-sm-3 col-md-3 col-xs-12">
                <ul>
                    <li>Bipolar disorder</li>
                    <li>Major depression</li>
                    <li>Multiple sclerosis (MS)</li>
                    <li>Missing limbs or partially missing limbs</li>
                </ul>
            </div>
            <div class="col-sm-3 col-md-3 col-xs-12">
                <ul>
                    <li>Post-traumatic stress disorder (PTSD)</li>
                    <li>Obsessive compulsive disorder</li>
                    <li>Impairments requiring the use of a wheelchair</li>
                    <li>Intellectual disability (previously called mental retardation)</li>
                </ul>
            </div>
        </div>
        <br>
        <h3>Please check one of the boxes below:</h3>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="disability" value="YES, I HAVE A DISABILITY" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "YES, I HAVE A DISABILITY" ? 'checked="checked"' : ''; ?> /> YES, I HAVE A DISABILITY (or previously had a disability)
            <div class=" control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="disability" value="NO, I DON'T HAVE A DISABILITY" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "NO, I DON'T HAVE A DISABILITY" ? 'checked="checked"' : ''; ?> /> NO, I DON'T HAVE A DISABILITY
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="disability" value="I DON'T WISH TO ANSWER" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "I DON'T WISH TO ANSWER" ? 'checked="checked"' : ''; ?> /> I DON'T WISH TO ANSWER
            <div class="control__indicator"></div>
        </label>
    </div>
</div>

<!--  -->
<div class="row">
    <br>
    <div class="col-sm-12">
        <div class="bg-gray p10">
            <strong>4. GENDER (PLEASE CHECK ONE)</strong>
        </div>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="gender" value="Male" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Male' ? 'checked="checked"' : ''; ?>> Male
            <div class=" control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="gender" value="Female" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Female' ? 'checked="checked"' : ''; ?>> Female
            <div class="control__indicator"></div>
        </label>
        <br>
        <label class="control control--radio">
            <input type="radio" <?php echo $is_readonly; ?> name="gender" value="Other" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Other' ? 'checked="checked"' : ''; ?>> Other
            <div class="control__indicator"></div>
        </label>
    </div>
</div>


<?php if ($session['employer_detail']['access_level_plus'] == 1 || $session['employer_detail']['pay_plan_flag'] == 1) { ?>
    <hr />
    <div class="row">
        <div class="col-sm-6">
            <button class="btn btn-warning form-control jsSaveEEOC">Save EEOC</button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-success form-control jsConsentEEOC">Consent EEOC</button>
        </div>
    </div>
<?php } ?>