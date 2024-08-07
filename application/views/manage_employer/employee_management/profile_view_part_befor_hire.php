<div class="form-title-section">
    <h2>Before Hire Empployee Information</h2>
    <span class="pull-right">
        <button class="btn btn-cancel csW" id="jsBackToPrimaryBox">Cancel</button>
    </span>
</div>

<div class="clearfix"></div>

<?php
//
$extra_info = unserialize($EmployeeBeforeHireData['extra_info']);
$field_phone = 'PhoneNumber';
$field_sphone = 'secondary_PhoneNumber';
$field_ophone = 'other_PhoneNumber';
//
$is_regex = 0;
$input_group_start = $input_group_end = '';
$primary_phone_number_cc = $primary_phone_number = $EmployeeBeforeHireData[$field_phone];
//
if (isset($phone_pattern_enable) && $phone_pattern_enable == 1) {
    $is_regex = 1;
    $input_group_start = '<div class="input-group"><div class="input-group-addon"><span class="input-group-text" id="basic-addon1">+1</span></div>';
    $input_group_end   = '</div>';
    $primary_phone_number = phonenumber_format($EmployeeBeforeHireData[$field_phone], true);
    $primary_phone_number_cc = phonenumber_format($EmployeeBeforeHireData[$field_phone]);
} else {
    if ($primary_phone_number === '+1') $primary_phone_number = '';
    if ($primary_phone_number_cc === '+1') $primary_phone_number_cc = 'N/A';
}

if (isset($EmployeeBeforeHireData["dob"]) && $EmployeeBeforeHireData["dob"] != '' && $EmployeeBeforeHireData["dob"] != '0000-00-00') $dob = DateTime::createFromFormat('Y-m-d', $EmployeeBeforeHireData['dob'])->format('m-d-Y');
else $dob = '';
//
if ($_ssv) {
    //
    $EmployeeBeforeHireData['ssn'] = ssvReplace($EmployeeBeforeHireData["ssn"]);
    //
    if ($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d XXXX, D');
} else {
    if ($dob != '') $dob = DateTime::createFromFormat('m-d-Y', $dob)->format('M d Y, D');
}

?>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="pt0 pb0"><strong><?= ucwords($EmployeeBeforeHireData['first_name'] . ' ' . $EmployeeBeforeHireData['last_name']); ?></strong>
                    <span class="pull-right">
                        <i aria-hidden="false" data-toggle="collapse" href="#collapseExample<?= $EmployeeBeforeHireData['sid']; ?>" role="button" aria-expanded="true" aria-controls="collapseExample<?= $EmployeeBeforeHireData['sid']; ?>"></i>
                    </span>
                </h4>
            </div>
            <div class="panel-body" id="collapseExample<?= $EmployeeBeforeHireData['sid']; ?>">
                <!--  -->
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <label class="csF16">First Name</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["first_name"]); ?></p>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label class="csF16">Nick Name</label>
                        <p class="dummy-invoice-fields"><?= isset($EmployeeBeforeHireData["nick_name"]) ? GetVal($EmployeeBeforeHireData["nick_name"]) : 'Not Specified'; ?></p>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label class="csF16">Middle Name / Initial</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["middle_name"]); ?></p>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <label class="csF16">Last Name</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["last_name"]); ?></p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Email</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["email"]); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Primary Number</label>
                        <p class="dummy-invoice-fields"><?= GetVal($primary_phone_number_cc); 
                                                        ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Gender</label>
                        <p class="dummy-invoice-fields"><?= GetVal(ucfirst($EmployeeBeforeHireData["gender"])); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Job Title</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["job_title"]); ?></p>
                    </div>

                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16">Address</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireDatame["Location_Address"]); ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">City</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["Location_City"]); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Zipcode</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["Location_ZipCode"]); ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">State</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["state_name"]); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Country</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["country_name"]); ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Social Security Number</label>
                        <p class="dummy-invoice-fields"><?= _secret(GetVal($EmployeeBeforeHireData["ssn"]), false, true); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Employee Number</label>
                        <p class="dummy-invoice-fields"><?= GetVal($EmployeeBeforeHireData["employee_number"]); ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Office Location</label>
                        <p class="dummy-invoice-fields"><?= GetVal($extra_info['office_location']); ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Employment Type</label>
                        <p class="dummy-invoice-fields">
                            <?= GetVal(isset($employment_types[$EmployeeBeforeHireData["employee_type"]]) ? $employment_types[$EmployeeBeforeHireData["employee_type"]] : $employment_types['fulltime']); 
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6 col-xs-12 dn">
                        <label class="csF16">Employee Status</label>
                        <p class="dummy-invoice-fields">
                            <?= GetVal(isset($employment_statuses[$EmployeeBeforeHireData["employee_status"]]) ? $employment_statuses[$EmployeeBeforeHireData["employee_status"]] : ''); 
                            ?>
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Union Member</label>
                        <p class="dummy-invoice-fields" <?= $EmployeeBeforeHireData['union_member'] ? "style='height: 100px'" : '' ?>><?= $EmployeeBeforeHireData['union_member'] ? 'Yes <br><br>' . $EmployeeBeforeHireData['union_name'] : 'No'; ?></p>
                    </div>
                </div>
                <br>
                <!--  -->
                <?php if (IS_TIMEZONE_ACTIVE && $show_timezone != '') { ?>
                    <div class="row">
                        <!-- TimeZone -->
                        <div class="col-md-12 col-xs-12">
                            <label class="csF16">Timezone</label>
                            <p class="dummy-invoice-fields">
                                <?= ($EmployeeBeforeHireData["timezone"] == '' || $EmployeeBeforeHireData["timezone"] == null) || (!preg_match('/^[A-Z]/', $EmployeeBeforeHireData['timezone'])) ? 'Not Specified' : get_timezones($EmployeeBeforeHireDatame["timezone"], 'name'); ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Secondary Email</label>
                        <?php
                         $secondaryEmail = isset($extra_info["secondary_email"]) && !empty($extra_info["secondary_email"]) ? $extra_info["secondary_email"] : $EmployeeBeforeHireData["alternative_email"];
                        ?>
                        <p class="dummy-invoice-fields"><?= GetVal($secondaryEmail); 
                                                        ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Secondary Mobile Number</label>
                        <p class="dummy-invoice-fields"><?= GetVal($extra_info['secondary_PhoneNumber']); 
                                                        ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Other Email</label>
                        <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_email']); 
                                                        ?></p>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label class="csF16">Other Phone Number</label>
                        <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_PhoneNumber']); 
                                                        ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16">Linkedin Profile URL</label>
                        <?php if (isset($EmployeeBeforeHireData["linkedin_profile_url"])) { ?>
                            <p class="dummy-invoice-fields"><a href="<?= $EmployeeBeforeHireData["linkedin_profile_url"]; ?>" target="_blank"><?= $EmployeeBeforeHireData["linkedin_profile_url"]; ?></a>
                            </p>
                        <?php } else { ?>
                            <p class="dummy-invoice-fields"><?= GetVal($extra_info['other_PhoneNumber']); 
                                                            ?></p>
                        <?php } ?>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16">Interests</label>
                        <p class="dummy-invoice-fields"><?= GetVal(isset($extra_info["interests"]) ? $extra_info["interests"] : ''); ?></p>
                    </div>
                </div>
                <!--  -->
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16">Short Bio</label>
                        <p class="dummy-invoice-fields"><?= GetVal(isset($extra_info["short_bio"]) ? $extra_info["short_bio"] : ''); ?></p>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</div>
<script>
    $('#jsBackToPrimaryBox').click(function(e) {
        //
        e.preventDefault();
        $('#jsBeforHireBox').hide();
        $('#jsPrimaryEmployeeBox').show();
    });
</script>