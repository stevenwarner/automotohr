<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                <?php $this->load->view('manage_employer/company_logo_name'); ?>

                                    <?php if($user_type == 'applicant'){ ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/'.$user_sid.'/'.$job_list_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    <?php } else { ?>
                                        <a class="dashboard-link-btn" href="<?php echo base_url('employee_profile/'.$user_sid); ?>"><i aria-hidden="true" class="fa fa-chevron-left"></i>Employee Profile</a>
                                    <?php }
                                    echo $title; ?>
                                </span>
                            </div>
                            <?php if (!empty($eeo_form_info)) { ?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <?php if ($eeo_form_info["status"] == 1) { ?>
                                            <?php if ($eeo_form_info["is_expired"] == 1) { ?>
                                                <div class="col-lg-3 pull-right" style="padding-right: 0px;">
                                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_eeoc_form/download'. '/' . $user_sid . '/' . $user_type); ?>" class="btn btn-success btn-block" title="Download EEOC Form" placement="top">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                        Download PDF
                                                    </a>
                                                </div>
                                                <div class="col-lg-3 pull-right">
                                                    <a target="_blank" href="<?php echo base_url('hr_documents_management/print_eeoc_form/print'. '/' . $user_sid . '/' . $user_type); ?>" class="btn btn-success btn-block" title="Print EEOC Form" placement="top">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                        Print PDF
                                                    </a>
                                                </div>
                                            <?php } ?>
                                            <div class="col-lg-3 pull-right">
                                                <button onclick="func_remove_EEOC('deactive');" class="btn btn-danger btn-block">Revoke</button>
                                            </div>   
                                            <?php if ($eeo_form_info["is_expired"] != 1) { ?> 
                                            <div class="col-lg-3 pull-right">
                                                <a class="btn btn-success btn-block jsResendEEOC" ref="javascript:void(0);" title="Send EEOC form to <?=ucwords($user_name);?>" placement="top">
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                    Send Email Reminder
                                                </a>
                                            </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="col-lg-3 pull-right">
                                                <button onclick="func_assign_EEOC('active');" class="btn btn-warning btn-block">Re-Assign</button>
                                            </div>
                                        <?php } ?> 
                                        <?php if (!empty($track_history)) { ?>
                                            <div class="col-lg-3 pull-right">
                                                <button onclick="show_document_track('eeoc', <?=$eeo_form_info['sid'];?>);" class="btn btn-success btn-block" title="View action trail for EEOC form" placement="top">EEOC Trail</button>
                                            </div>
                                        <?php } ?> 
                                    </div>
                                </div>
                                <?php if ($eeo_form_info["status"] == 1) { ?>     
                                <hr>  
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <span>
                                            Assigned On : 
                                            <strong>
                                                <?php echo !empty($eeo_form_info['last_sent_at']) ? DateTime::createfromformat('Y-m-d H:i:s', $eeo_form_info['last_sent_at'])->format('M d Y, D H:i:s') : ""; ?>
                                            </strong>
                                        </span>
                                        <br>
                                        <span>
                                            Assigned By : 
                                            <strong>
                                                <?php echo getUserNameBySID($eeo_form_info['last_assigned_by']); ?>
                                            </strong>
                                        </span>
                                    </div>
                                </div>  
                                <?php } ?>
                                <hr>  
                                <?php if ($eeo_form_info["status"] == 1) { ?>    
                                    <section class="sheet padding-10mm">
                                        <article class="sheet-header">
                                            <div class="header-logo"><img src="<?php echo base_url('assets/images/eeoc_logo.jpg')?>"></div>
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
                                                        <strong>I am a U.S. citizen or permanent resident </strong>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'Yes' ? 'checked="checked"' : ''; ?> onclick="return false;"> Yes
                                                        <input type="checkbox" name="" <?php echo !empty($eeo_form_info['us_citizen']) && $eeo_form_info['us_citizen'] == 'No' ? 'checked="checked"' : ''; ?> onclick="return false;"> NO
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
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Hispanic or Latino' ? 'checked="checked"' : ''; ?>> Hispanic or Latino - A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'White' ? 'checked="checked"' : ''; ?>> White (Not Hispanic or Latino) - A person having origins in any of the original peoples of Europe, the Middle East or North Africa. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Black or African American' ? 'checked="checked"' : ''; ?>> Black or African American (Not Hispanic or Latino) - A person having origins in any of the black racial groups of Africa.  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Native Hawaiian or Other Pacific Islander' ? 'checked="checked"' : ''; ?>> Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) - A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Asian' ? 'checked="checked"' : ''; ?>> Asian (Not Hispanic or Latino) - A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'American Indian or Alaska Native' ? 'checked="checked"' : ''; ?>> American Indian or Alaska Native (Not Hispanic or Latino) - A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['group_status']) && $eeo_form_info['group_status'] == 'Two or More Races' ? 'checked="checked"' : ''; ?>> Two or More Races (Not Hispanic or Latino) - All persons who identify with more than one of the above five races.  
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Disabled Veteran' ? 'checked="checked"' : ''; ?>> Disabled Veteran: A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == "Recently Separated Veteran"  ? 'checked="checked"' : ''; ?>> Recently Separated Veteran: A "recently separated veteran" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Active Duty Wartime or Campaign Badge Veteran' ? 'checked="checked"' : ''; ?>> Active Duty Wartime or Campaign Badge Veteran: An "active duty wartime or campaign badge veteran" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense.  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'Armed Forces Service Medal Veteran' ? 'checked="checked"' : ''; ?>> Armed Forces Service Medal Veteran: An "Armed forces service medal veteran" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985. 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['veteran']) && $eeo_form_info['veteran'] == 'I Am Not a Protected Veteran' ? 'checked="checked"' : ''; ?>> I Am Not a Protected Veteran 
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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

                                                        <strong>How do I know if I have a disability?</strong><p>
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
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "YES, I HAVE A DISABILITY" ? 'checked="checked"' : ''; ?>> YES, I HAVE A DISABILITY (or previously had a disability)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "NO, I DON'T HAVE A DISABILITY" ? 'checked="checked"' : ''; ?>> NO, I DON'T HAVE A DISABILITY   
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['disability']) && $eeo_form_info['disability'] == "I DON'T WISH TO ANSWER" ? 'checked="checked"' : ''; ?>> I DON'T WISH TO ANSWER  
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Male' ? 'checked="checked"' : ''; ?>> Male
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="" onclick="return false;" <?php echo !empty($eeo_form_info['gender']) && $eeo_form_info['gender'] == 'Female' ? 'checked="checked"' : ''; ?>> Female
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </section>
                                <?php } else { ?>
                                    <h2 class="text-center">
                                        The <?php echo $user_type == 'applicant' ? 'applicant' : 'employee'; ?> has not completed the EEOC form. 
                                    </h2>
                                <?php } ?>
                            <?php } else { ?>
                                <h2 class="text-center">
                                    The <?php echo $user_type == 'applicant' ? 'applicant' : 'employee'; ?> has not completed the EEOC form. 
                                    <br>
                                    <a class="btn btn-success jsResendEEOC" ref="javascript:void(0);" title="Assign EEOC form to <?=ucwords($user_name);?>" placement="top">Assign</a>
                                </h2>
                            <?php } ?>
                            
                            <?php if (!empty($verification_documents_history)) { ?>
                                <br>
                                <br>
                                <?php $this->load->view('hr_documents_management/verification_documents_history'); ?>
                            <?php } ?>    
                        </div>    
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?> 
            </div>
        </div>
    </div>
</div>

<?php if (!empty($eeo_form_info)) { ?>
    <?php $this->load->view('hr_documents_management/document_track'); ?>
<?php } ?> 

<script>
    $('[data-toggle="tooltip"]').tooltip({
        trigger: "hover"
    });
    //
    function func_remove_EEOC(status) {
        alertify.confirm(
        'Are you sure?',
        'Are you sure you want to revoke this document?',
        function () {
            $.post(
                "<?=base_url('change_form_status');?>", {
                    userId: <?=$user_sid;?>,
                    userType: "<?=$user_type;?>",
                    action: status
                }
            ).done(function(resp){
                //
                if(resp == 'success'){
                    alertify.alert('Success!','Document Successfully Revoked!', function(){
                        window.location.reload();
                    });
                } else {
                   //
                    alertify.alert('Error!', 'Something went wrong while resending the EEOC form.') 
                }
            });
        },
        function () {
            alertify.alert("Warning", 'Cancelled!');
        });
    }
    //
    function func_assign_EEOC(status) {
        alertify.confirm(
        'Are you sure?',
        'Are you sure you want to assign this document?',
        function () {
            $.post(
                "<?=base_url('change_form_status');?>", {
                    userId: <?=$user_sid;?>,
                    userType: "<?=$user_type;?>",
                    action: status
                }
            ).done(function(resp){
                //
                if(resp == 'success'){
                    alertify.alert('Success!','Document Successfully Assigned!', function(){
                        window.location.reload();
                    });
                } else {
                   //
                    alertify.alert('Error!', 'Something went wrong while resending the EEOC form.') 
                }
            });
        },
        function () {
            alertify.alert("Warning", 'Cancelled!');
        });
    }
    //
    $(function(e){
        //
        $('.jsResendEEOC').click(function(event){
            //
            event.preventDefault();
            //
            alertify.confirm(
                "Are you sure you want to send EEOC form?",
                function(){
                    //
                    $.post(
                        "<?=base_url('send_eeoc_form');?>", {
                            userId: <?=$user_sid;?>,
                            userType: "<?=$user_type;?>",
                            userJobId: "<?=$job_list_sid;?>",
                            userLocation: "EEOC Form"
                        }
                    ).done(function(resp){
                        //
                        if(resp == 'success'){
                            alertify.alert('Success!','EEOC form has been sent.', function(){
                                window.location.reload();
                            });
                        } else {
                            //
                            alertify.alert('Error!', resp)
                        }
                    });
                }
            ).setHeader('Confirm!');
        });
    });
</script>