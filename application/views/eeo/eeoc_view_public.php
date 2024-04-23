<style>
    label.control {
        font-size: 14px !important;
        font-weight: normal;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css') ?>">
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body>
    <div class="container">
        <?php if ($eeo_form_status == 0) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success text-center" style="padding-top: 100px;padding-bottom: 100px;">
                        <h1>Sorry!</h1>
                        <h4>The company disable its EEOC module.</h4>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php if ($eeo_form_info['is_expired'] == 0) {

                $eeocFormOptions = get_eeoc_options_status($company_sid);

            ?>
                <?php if ($eeo_form_info["is_opt_out"] == 1) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <br>
                            <p class="alert alert-info text-center">
                                You have Opt-out from the EEOC.
                            </p>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-orange csW jsEEOCOptOut" title="You will be opt-out of the EEOC form.">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                Opt-out
                            </button>
                        </div>
                    </div>
                    <br>
                    <div>
                        <section class="sheet padding-10mm">
                            <article class="sheet-header">
                                <div class="header-logo"><img src="<?php echo base_url('assets/images/eeoc_logo.jpg') ?>"></div>
                                <div class="center-col">
                                    <h2>EQUAL EMPLOYMENT OPPORTUNITY COMMISSION</h2>
                                </div>
                            </article>
                            <p><strong>EQUAL EMPLOYMENT OPPORTUNITY FORM<br></strong> We are an equal opportunity employer and we give consideration for employment to qualified applicants without regard to race, color, religion, sex, sexual orientation, gender identity, national origin, disability, or protected veteran status.</p>
                            <p>If you'd like more information about your EEO rights as an applicant under the law, please visit:<br><strong>http://www.dol.gov/ofccp/regs/compliance/posters/pdf/eeopost.pdf.</strong></p>
                            <p>Federal law requires employers to provide reasonable accommodation to qualified individuals with disabilities. In the event you require reasonable accommodation to apply for this job, please contact our company and appropriate assistance will be provided.</p>
                            <table class="i9-table">
                                <thead>
                                    <tr class="bg-gray">
                                        <th colspan="5">
                                            <?php
                                            $required_label = '';

                                            if ($dl_citizen == 1) {
                                                $required_label = '<span style="color: red; font-size: 16px;"> * </span>';
                                            }
                                            ?>
                                            <strong>I am a U.S. citizen or permanent resident <?php echo $required_label; ?></strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="citizen" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'Yes' ? 'checked="checked"' : ''; ?> value="Yes"> Yes
                                                <div class="control__indicator"></div>
                                            </label>
                                            &nbsp;
                                            <label class="control control--radio">
                                                <input type="radio" name="citizen" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'No' ? 'checked="checked"' : ''; ?> value="No"> NO
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <?php if (!empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'no') { ?>
                                        <tr>
                                            <td>
                                                <strong>Visa Status :</strong> <?php echo !empty($eeo_form_info['visa_status']) ? $eeo_form_info['visa_status'] : 'No Status Found'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <table class="i9-table">
                                <thead>
                                    <tr class="bg-gray">
                                        <th colspan="5">
                                            <strong>1. GROUP STATUS (PLEASE CHECK ONE)</strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="Hispanic or Latino" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Hispanic or Latino' ? 'checked="checked"' : ''; ?>> Hispanic or Latino - A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="White" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'White' ? 'checked="checked"' : ''; ?>> White (Not Hispanic or Latino) - A person having origins in any of the original peoples of Europe, the Middle East or North Africa.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="Black or African American" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Black or African American' ? 'checked="checked"' : ''; ?>> Black or African American (Not Hispanic or Latino) - A person having origins in any of the black racial groups of Africa.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="Native Hawaiian or Other Pacific Islander" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Native Hawaiian or Other Pacific Islander' ? 'checked="checked"' : ''; ?>> Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) - A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="Asian" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Asian' ? 'checked="checked"' : ''; ?>> Asian (Not Hispanic or Latino) - A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="American Indian or Alaska Native" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'American Indian or Alaska Native' ? 'checked="checked"' : ''; ?>> American Indian or Alaska Native (Not Hispanic or Latino) - A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="control control--radio">
                                                <input type="radio" name="group" value="Two or More Races" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Two or More Races' ? 'checked="checked"' : ''; ?>> Two or More Races (Not Hispanic or Latino) - All persons who identify with more than one of the above five races.
                                                <div class="control__indicator"></div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if ($eeocFormOptions['dl_vet'] == 1) { ?>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="5">
                                                <strong>2. VETERAN </strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="veteran" value="Disabled Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Disabled Veteran' ? 'checked="checked"' : ''; ?>> Disabled Veteran: A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="veteran" value="Recently Separated Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == "Recently Separated Veteran"  ? 'checked="checked"' : ''; ?>> Recently Separated Veteran: A "recently separated veteran" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="veteran" value="Active Duty Wartime or Campaign Badge Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Active Duty Wartime or Campaign Badge Veteran' ? 'checked="checked"' : ''; ?>> Active Duty Wartime or Campaign Badge Veteran: An "active duty wartime or campaign badge veteran" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense.
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="veteran" value="Armed Forces Service Medal Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Armed Forces Service Medal Veteran' ? 'checked="checked"' : ''; ?>> Armed Forces Service Medal Veteran: An "Armed forces service medal veteran" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985.
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="veteran" value="I Am Not a Protected Veteran" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'I Am Not a Protected Veteran' ? 'checked="checked"' : ''; ?>> I Am Not a Protected Veteran
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>

                            <?php if ($eeocFormOptions['dl_vol'] == 1) { ?>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="5">
                                                <strong>3. VOLUNTARY SELF-IDENTIFICATION OF DISABILITY</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Why are you being asked to complete this form?</strong>
                                                <p>
                                                    Because we do business with the government, we must reach out to, hire, and provide equal opportunity to qualified people with disabilities.i To help us measure how well we are doing, we are asking you to tell us if you have a disability or if you ever had a disability. Completing this form is voluntary, but we hope that you will choose to fill it out. If you are applying for a job, any answer you give will be kept private and will not be used against you in any way.</p>

                                                <p>If you already work for us, your answer will not be used against you in any way. Because a person may become disabled at any time, we are required to ask all of our employees to update their information every five years. You may voluntarily self-identify as having a disability on this form without fear of any punishment because you did not identify as having a disability earlier.</p>

                                                <strong>How do I know if I have a disability?</strong>
                                                <p>
                                                    You are considered to have a disability if you have a physical or mental impairment or medical condition that substantially limits a major life activity, or if you have a history or record of such an impairment or medical condition.</p>

                                                <p> Disabilities include, but are not limited to:</p>
                                                <div style="width: 100%; display: block;">
                                                    <div style="width: 25%; float: left;">
                                                        <ul>
                                                            <li>Blindness</li>
                                                            <li>Deafness</li>
                                                            <li>Cancer</li>
                                                            <li>Diabetes</li>
                                                            <li>Epilepsy</li>
                                                        </ul>
                                                    </div>
                                                    <div style="width: 25%; float: left;">
                                                        <ul>
                                                            <li>Autism</li>
                                                            <li>Cerebral palsy</li>
                                                            <li>HIV/AIDS</li>
                                                            <li>Schizophrenia</li>
                                                            <li>Muscular dystrophy</li>
                                                        </ul>
                                                    </div>
                                                    <div style="width: 25%; float: left;">
                                                        <ul>
                                                            <li>Bipolar disorder</li>
                                                            <li>Major depression</li>
                                                            <li>Multiple sclerosis (MS)</li>
                                                            <li>Missing limbs or partially missing limbs</li>
                                                        </ul>
                                                    </div>
                                                    <div style="width: 25%; float: left;">
                                                        <ul>
                                                            <li>Post-traumatic stress disorder (PTSD)</li>
                                                            <li>Obsessive compulsive disorder</li>
                                                            <li>Impairments requiring the use of a wheelchair</li>
                                                            <li>Intellectual disability (previously called mental retardation)</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <h3>Please check one of the boxes below:</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="disability" value="YES, I HAVE A DISABILITY" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "YES, I HAVE A DISABILITY" ? 'checked="checked"' : ''; ?>> YES, I HAVE A DISABILITY (or previously had a disability)
                                                    <div class=" control__indicator">
                                                    </div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="disability" value="NO, I DON'T HAVE A DISABILITY" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "NO, I DON'T HAVE A DISABILITY" ? 'checked="checked"' : ''; ?>> NO, I DON'T HAVE A DISABILITY
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="disability" value="I DON'T WISH TO ANSWER" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "I DON'T WISH TO ANSWER" ? 'checked="checked"' : ''; ?>> I DON'T WISH TO ANSWER
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>

                            <?php if ($eeocFormOptions['dl_gen'] == 1) { ?>
                                <table class="i9-table">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th colspan="5">
                                                <strong>4. GENDER (PLEASE CHECK ONE)</strong>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="gender" value="Male" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Male' ? 'checked="checked"' : ''; ?>> Male
                                                    <div class="control__indicator"></div>
                                                </label>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="gender" value="Female" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Female' ? 'checked="checked"' : ''; ?>> Female
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="control control--radio">
                                                    <input type="radio" name="gender" value="Other" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Other' ? 'checked="checked"' : ''; ?>> Other
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            <?php } ?>
                            <!--  -->
                            <hr />
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary form-control jsSaveEEOC" style="background-color: #3554dc;">I Consent EEOC</button>
                                </div>
                            </div>
                        </section>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success text-center" style="padding-top: 100px;padding-bottom: 100px;">
                            <h1>Thank You!</h1>
                            <h4>You have successfully submitted the EEOC form.</h4>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
<!--  -->
<?php if ($eeo_form_status == 1 && $eeo_form_info['is_expired'] == 0) { ?>
    <script>
        $(function() {

            if (typeof baseUrl === "undefined") {
                /**
                 * get the base url
                 *
                 * @param {string} appendUrl
                 * @returns
                 */
                function baseUrl(appendUrl = "") {
                    appendUrl = appendUrl.replace(/^\//g, "");
                    // return the url
                    return window.location.origin + "/" + appendUrl;
                }
            }

            $(".jsEEOCOptOut").click(function(event) {
                event.preventDefault();
                alertify.confirm(
                    "Do you really want to 'Opt-out' of the EEOC form?",
                    startOptOutProcess
                );
            });

            function startOptOutProcess() {

                $.ajax({
                        url: baseUrl("eeoc/<?= $eeo_form_info["sid"]; ?>/opt_out"),
                        method: "PUT",
                    })
                    .always(function() {})
                    .success(function(resp) {
                        alertify.alert(
                            "Success",
                            resp.message,
                            window.location.refresh
                        )
                    });
            }
            //
            $('.jsSaveEEOC').click(function() {
                //
                var citizenFlag = <?php echo $dl_citizen; ?>
                //
                var obj = {
                    id: <?= $id; ?>,
                    citizen: $('input[name="citizen"]:checked').val(),
                    group: $('input[name="group"]:checked').val(),
                    veteran: $('input[name="veteran"]:checked').val(),
                    disability: $('input[name="disability"]:checked').val(),
                    gender: $('input[name="gender"]:checked').val(),
                    eeoc_action: 'consent'
                };
                //
                const errorArray = [];
                //
                if (citizenFlag == 1 && obj.citizen === undefined) {
                    errorArray.push('Please, select a citizen.');
                }
                //
                if ($("input[name='citizen'][value='Yes']").prop("checked")) {
                    //
                    if (obj.group === undefined) {
                        errorArray.push('Please, select a group status.');
                    }

                    //
                    <?php if ($eeocFormOptions['dl_vet'] == 1) { ?>
                        if (obj.veteran === undefined) {
                            errorArray.push('Please, select veteran.');
                        }
                    <?php } ?>

                    //
                    <?php if ($eeocFormOptions['dl_vol'] == 1) { ?>
                        if (obj.disability === undefined) {
                            errorArray.push('Please, select disability.');
                        }
                    <?php } ?>

                    //
                    <?php if ($eeocFormOptions['dl_gen'] == 1) { ?>
                        if (obj.gender === undefined) {
                            errorArray.push('Please, select gender.');
                        }
                    <?php } ?>
                }
                //
                if (errorArray.length) {
                    //
                    return alertify.alert(
                        "ERROR!",
                        getErrorsStringFromArray(errorArray),
                        CB
                    );
                }
                //
                $.post(
                    "<?= base_url("eeoc_form_submit"); ?>",
                    obj
                ).done(function(resp) {
                    //
                    if (resp === 'success') {
                        alertify.alert('Success!', 'You have successfully submitted the EEOC form.', function() {
                            window.location.reload();
                        });
                        return;
                    }
                    //
                    alertify.alert('Success!', 'You have successfully submitted the EEOC form.');
                });
            });

            if (typeof getErrorsStringFromArray === "undefined") {
                /**
                 * Error message
                 *
                 * @param {*} errorArray
                 * @param {*} errorMessage
                 * @returns
                 */
                function getErrorsStringFromArray(errorArray, errorMessage) {
                    return (
                        "<strong><p>" +
                        (errorMessage ?
                            errorMessage :
                            "Please, resolve the following errors") +
                        "</p></strong><br >" +
                        errorArray.join("<br />")
                    );
                }
            }
        });
    </script>
<?php } ?>