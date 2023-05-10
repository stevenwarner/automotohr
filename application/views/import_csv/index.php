<?php 
    $importColumnsArray = [];
    $importColumnsArray[] = 'First Name';
    $importColumnsArray[] = 'Nick Name';
    $importColumnsArray[] = 'Middle Initial';
    $importColumnsArray[] = 'Last Name';
    $importColumnsArray[] = 'Email';
    $importColumnsArray[] = 'Primary Number';
    $importColumnsArray[] = 'Street Address';
    $importColumnsArray[] = 'City';
    $importColumnsArray[] = 'Zipcode';
    $importColumnsArray[] = 'State';
    $importColumnsArray[] = 'Country';
    $importColumnsArray[] = 'Gender';
    $importColumnsArray[] = 'Date Of Birth';
    $importColumnsArray[] = 'Social Security Number';
    $importColumnsArray[] = 'Employee Number';
    $importColumnsArray[] = 'Profile Picture URL';
    $importColumnsArray[] = 'Access Level';
    $importColumnsArray[] = 'Job Title';
    $importColumnsArray[] = 'Timezone';
    $importColumnsArray[] = 'Resume URL';
    $importColumnsArray[] = 'Cover Letter URL';
    $importColumnsArray[] = 'Secondary Email';
    $importColumnsArray[] = 'Secondary Phone Number';
    $importColumnsArray[] = 'Other Email';
    $importColumnsArray[] = 'Other Phone Number';
    $importColumnsArray[] = 'Office Location';
    $importColumnsArray[] = 'Linkedin URL';
    $importColumnsArray[] = 'Department';
    $importColumnsArray[] = 'Team';
    $importColumnsArray[] = 'Joining Date';
    $importColumnsArray[] = 'Shift Hours';
    $importColumnsArray[] = 'Shift Minutes';
    $importColumnsArray[] = 'Notified By';
    $importColumnsArray[] = 'Status';
    $importColumnsArray[] = 'Position';
    $importColumnsArray[] = 'Employment Type';
    $importColumnsArray[] = 'Rehire Date';
    $importColumnsArray[] = 'Rehire Reason';
    $importColumnsArray[] = 'Termination Date';
    $importColumnsArray[] = 'Termination Reason';

    $importColumnsArray[] = 'EEOC Code';
    $importColumnsArray[] = 'Salary Benefits';
    $importColumnsArray[] = 'Workers Compensation Code';

    //
    $importColumnsArray[] = 'Marital Status';
    $importColumnsArray[] = 'License Type';
    $importColumnsArray[] = 'License Authority';
    $importColumnsArray[] = 'License Class';
    $importColumnsArray[] = 'License Number';
    $importColumnsArray[] = 'License Issue Date';
    $importColumnsArray[] = 'DOB';
    $importColumnsArray[] = 'License Expiration Date';
    $importColumnsArray[] = 'License Notes';

    //
    $importValueArray = '';
    $importValueArray .= 'Jason, josi, K, Snow, email@abc.com, +1234567892, 123 Street, California, 90001, CA, United States, Male, 05/05/1984, 111-22-2222, 12365478, https://yourwebsite.com/images/profile_picture.png, Admin, General Manager, PST, https://yourwebsite.com/files/resume.pdf, https://yourwebsite.com/files/cover_letter.pdf, second@email.com, +1234567891, other@mail.com, +1234567899, Office Location, https://yourwebsite.com/linkedIn, Sales, Outbound, 3/7/2016, 8, 0, email, Active, Full-time, 03/08/2019, Rehired reason goes here, , ,<br/>';
    $importValueArray .= 'Albert, king, J, King, email@abc.com, +1234567892, 133 Street, California, 90001, CA, United States, Male, 05/05/1984, 111-22-2223, 12365478, https://yourwebsite.com/images/profile_picture.png, Admin, General Manager, PST, https://yourwebsite.com/files/resume.pdf, https://yourwebsite.com/files/cover_letter.pdf, second@email.com, +1234567891, other@mail.com, +1234567899, Office Location, https://yourwebsite.com/linkedIn, Sales, Outbound, 3/7/2016, 8, 0, email, Active, Full-time, , , , ,<br/>';
    $importValueArray .= 'Jason, , K, Snow, email@abc.com, +1234567892, 123 Street, California, 90001, CA, United States, Male, 05/05/1984, 111-22-2222, 12365478, https://yourwebsite.com/images/profile_picture.png, Admin, General Manager, PST, https://yourwebsite.com/files/resume.pdf, https://yourwebsite.com/files/cover_letter.pdf, second@email.com, +1234567891, other@mail.com, +1234567899, Office Location, https://yourwebsite.com/linkedIn, Sales, Outbound, 3/7/2016, 8, 0, email, Active, Full-time, 03/08/2019, Rehired reason goes here, , ,<br/>';
    $importValueArray .= 'Nathan, Natu, , Quite, email@abc.com, +1234567892, 133 Street, California, 90001, CA, United States, Male, 05/05/1984, 111-22-2223, 12365478, https://yourwebsite.com/images/profile_picture.png, Employee, Technician, PST, https://yourwebsite.com/files/resume.pdf, https://yourwebsite.com/files/cover_letter.pdf, second@email.com, +1234567891, other@mail.com, +1234567899, Office Location, https://yourwebsite.com/linkedIn, Sales, Outbound, 3/7/2016, 8, 0, email, Active, Full-time, , , , ,<br/>';
    $importValueArray .= 'Jack, Joki, , Brown, email@abc.com, +1234567892, 133 Street, California, 90001, CA, United States, Male, 05/05/1984, 111-22-2223, 12365478, https://yourwebsite.com/images/profile_picture.png, Employee, Technician, PST, https://yourwebsite.com/files/resume.pdf, https://yourwebsite.com/files/cover_letter.pdf, second@email.com, +1234567891, other@mail.com, +1234567899, Office Location, https://yourwebsite.com/linkedIn, Sales, Outbound, 3/7/2016, 8, 0, email, Terminated, Part-time, , , 05/09/2020, Termination reason goes here.<br/>';
?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                        <div class="dashboard-conetnt-wrp">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h4>The Provided CSV File must be in Following Format</h4>
                                </div>
                                <div class="panel-body"><pre>
<strong><?=implode(', ', $importColumnsArray);?></strong><br>
<?=$importValueArray;?>
                                </pre></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="universal-form-style-v2">
                            <ul>
                                <form method="post" action="javascript:void(0)" id="js-import-form" enctype="multipart/form-data">
                                    <input type="hidden" value="upload_file" name="action" id="action" />
                                    <div>
                                        <label>Upload CSV File</label>
                                        <input type="file" id="userfile" style="display: none;" />
                                    </div>
                                    <br />
                                    <div>
                                        <input type="submit" class="btn btn-success js-submit-btn disabled" value="Import Employees" disabled="true" />
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loader -->
<div id="my_loader" class="text-center js-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
        <div class="loader-icon-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;" aria-hidden="true"></i>
            <div class="loader-text js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>
<script src="<?=base_url('assets/lodash/loadash.min.js');?>"></script>
<link rel="stylesheet" href="<?=base_url('assets/alertifyjs/css/alertify.min.css');?>">
<script src="<?=base_url('assets/alertifyjs/alertify.min.js');?>"></script>


<link rel="stylesheet" href="<?=base_url('assets/mFileUploader/index.css');?>" />
<script src="<?=base_url('assets/mFileUploader/index.js');?>"></script>

<script>

    function check_file(val) {
        $('.js-submit-btn').addClass('disabled').prop('disabled', true);
        var fileName = $("#" + val).val();
        
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            
            if (val == 'userfile') {
                if (ext != "csv") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid File format.");
                    $('#name_' + val).html('<p class="red">Only ( .csv ) allowed!</p>');
                    return false;
                } else{
                    $('.js-submit-btn').removeClass('disabled').prop('disabled', false);
                    return true;
                }
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    //
    $(function importCSV(){
        var 
        file = [],
        jobTitles = <?=json_encode(array('jobtitle', 'job'));?>,
        cityTitles = <?=json_encode(array('city', 'employeecity'));?>,
        stateTitles = <?=json_encode(array('state','employeestate'));?>,
        resumeTitles = <?=json_encode(array('resume', 'cv', 'resumeurl'));?>,
        countryTitles = <?=json_encode(array('country', 'employeecountry'));?>,
        addressTitles = <?=json_encode(array('address','streetaddress', 'employeeaddressline1', 'addressline1', 'employeeaddress'));?>,
        zipCodeTitles = <?=json_encode(array('zipcode', 'zip', 'employeezip'));?>,
        timezoneTitles = <?=json_encode(array('timezone', 'zone'));?>,
        lastNameTitles = <?=json_encode(array('lastname', 'lname'));?>,
        firstNameTitles = <?=json_encode(array('firstname', 'fname'));?>,
        coverLetterTitles = <?=json_encode(array('coverletter', 'coverletterurl'));?>,
        phoneNumberTitles = <?=json_encode(array('phonenumber','contactnumber', 'contact', 'employeetelephonenumber', 'telephonenumber'));?>,
        accessLevelTitles = <?=json_encode(array('accesslevel'));?>,
        emailAddressTitles = <?=json_encode(array('email', 'emailaddress', 'personalemail', 'e-mail'));?>,
        secondaryemailaddressTitles = <?=json_encode(array('secondaryemail', 'secondaryemailaddress'));?>,
        otheremailaddressTitles = <?=json_encode(array('otheremail', 'otheremailaddress'));?>,
        secondaryphoneTitles = <?=json_encode(array('secondaryphonenumber', 'secondaryphone'));?>,
        otherphoneTitles = <?=json_encode(array('otherphonenumber', 'otherphone'));?>, //telephonenumber
        officelocationTitles = <?=json_encode(array('office','officelocation'));?>,
        linkedinurlTitles = <?=json_encode(array('linkedin', 'linkedinurl'));?>,
        employeenumberTitles = <?=json_encode(array('employeenumber','employee','emp'));?>,
        socialsecuritynumberTitles = <?=json_encode(array('socialsecurity','socialsecuritynumber','socialsecurity#','ssn','ss','ss#'));?>,
        dateofbirthTitles = <?=json_encode(array('dateofbirth','birthdate','dob'));?>,
        departmentTitles = <?=json_encode(array('departmentname','department','homedepartmentdescription'));?>,
        teamTitles = <?=json_encode(array('teamname','team'));?>,
        joiningdateTitles = <?=json_encode(array('joining','joiningdate','hiringdate','hiredate'));?>,
        hoursTitles = <?=json_encode(array('shifthours','hours'));?>,
        officeTitles = <?=json_encode(array('officelocation','worklocation'));?>,
        minutesTitles = <?=json_encode(array('shiftminutes','minutes'));?>,
        notifyTitles = <?=json_encode(array('notify','notifyby','notifiedby'));?>,
        profilePictureTitles = <?=json_encode(array('profile', 'profilepicture', 'profilepictureurl'));?>;
        genderTitles = <?=json_encode(array('gender', 'sex'));?>;
        statusTitles = <?=json_encode(array('status', 'employeestatus'));?>;
        positionTitles = <?=json_encode(array('position'));?>;
        rehireDateTitles = <?=json_encode(array('rehiredate', 'rehireddate'));?>;
        rehireReasonTitles = <?=json_encode(array('rehirereason', 'rehiredreason'));?>;
        terminationDateTitles = <?=json_encode(array('terminationdate', 'terminateddate'));?>;
        terminationReasonTitles = <?=json_encode(array('terminationreason', 'terminatedreason'));?>;
        employmentTitles = <?=json_encode(array('employmenttype', 'employment', 'employmentstatus'));?>;
        middlenameTitles = <?=json_encode(array('middlename', 'middleinitial', 'middlenameinitial'));?>;
        nicknameTitles = <?=json_encode(array('nick_name', 'nickname'));?>;

        eeoccodeTitles = <?=json_encode(array('eeoc_code', 'eeoccode'));?>;
        salarybenefitsTitles = <?=json_encode(array('salary_benefits', 'salarybenefits'));?>;
        workerscompensationcodeTitles = <?=json_encode(array('workers_compensation_code', 'workerscompensationcode'));?>;


       //
       maritalstatusTitles = <?=json_encode(array('marital_status', 'maritalstatus'));?>;
       licensetypeTitles = <?=json_encode(array('license_type', 'licensetype'));?>;
       licenseauthorityTitles = <?=json_encode(array('license_authority', 'licenseauthority'));?>;
       licenseclassitles = <?=json_encode(array('license_class', 'licenseclass'));?>;
       licensenumberTitles = <?=json_encode(array('license_number', 'licensenumber'));?>;
       licenseissuedateTitles = <?=json_encode(array('license_issue_date', 'licenseissuedate'));?>;
       dobTitles = <?=json_encode(array('dob', 'dateofbirth'));?>;
       licenseexpirationdateTitles = <?=json_encode(array('license_expiration_date', 'licenseexpirationdate'));?>;
       licensenotesTitles = <?=json_encode(array('license_notes', 'licensenotes'));?>;


        loader('hide');
        // 
        // $('#userfile').change(fileChanged);
        //
        $('#js-import-form').submit(formHandler);
        //
        function readFile(f){
            //
            $('.js-submit-btn').prop('disabled', true).addClass('disabled');
            //
            if(Object.keys(f).length === 0){
                alertify.alert('WARNING!', 'Please, select a file.', () => {});
                return;
            }
            //
            if(f.hasError === true){
                alertify.alert('WARNING!', 'Invalid file is uploaded.', () => {});
                return;
            }
            //
            $('.js-submit-btn').removeAttr('disabled').removeClass('disabled');
            //
            var fileReader = new FileReader();
            fileReader.onload = fileLoaded;
            fileReader.readAsText(f);
        }
        
        //
        $('#userfile').mFileUploader({
            fileLimit: -1,
            allowedTypes: ['csv'],
            onSuccess: readFile,
            onError: () => {
                $('.js-submit-btn').prop('disabled', true).addClass('disabled');
            },
            onClear: () => {
                $('.js-submit-btn').prop('disabled', true).addClass('disabled');
            }
        });

        //
        function fileLoaded(e){ file = e; }

        //
        function formHandler(e){
            e.preventDefault();
            var fileData = file.target.result.split(/\r\n|\n/g);
            //Check if is it right format
            var format_index = fileData[0].toLowerCase().replace(/[^a-z]/g, '').trim();
            if(!format_index.includes("firstname") && !format_index.includes("first-name") && !format_index.includes("fname") && !format_index.includes("first_name")){
                alertify.alert('Not a valid format');
                return false;
            }
            // Get header
            var indexes  = fileData[0].split(',');
            // Reset index
            indexes = indexes.map(function(v, i){
                var index = in_array(v.toLowerCase().replace(/[^a-z]/g, '').trim());
                return index === -1 ? 'extra' : index;
            });
            // Remove head
            fileData.splice(0,1);
            //
            var errorRows = "";
            //
            fileData.map((row, index) => {
                var er = row.split(',');
                //
                if(er.length != indexes.length && er.length !== 1){
                    errorRows += '<p>';
                    errorRows += '  <strong>Row #: </strong>'+(index+2)+'<br />';
                    errorRows += '  <strong>Name: </strong>'+(er[0] + er[1])+'';
                    errorRows += '</p>';
                }
            });
            //
            if(errorRows.length){
                //
                return alertify.alert(
                    "Error!",
                    "<p>Please make sure there are no <strong>','</strong> in values.</p><br />"+
                    "<p><strong>For instance </strong>422 Street, 3rd floor -> 422 Street 3rd floor</p><br />"+
                    errorRows,
                    function(){}
                );
            }
            //
            var records = [];
            //
            fileData.map(function(v){
                var tmp = v.split(','),
                record = new Object(),
                i = 0,
                len = tmp.length;
                record['extra2'] = [];
                if(len > 2){
                    //
                    for(i; i < len; i++) {
                        if(indexes[i] === undefined){
                            record['extra2'].push(tmp[i]);
                        } else{
                            record[indexes[i]] = tmp[i].trim();
                        }
                    }
                    //
                    if(record.first_name != '' && record.first_name != null){
                        records.push(record);
                    }
                }
            });
            //
            if(records.length == 0){
                alertify.alert('WARNING!', 'Uploaded file doesn\'t contain employees', function(){ return; });
                return;
            }
            //
            uploadRecords(records);
        }

        //
        function in_array(index){
            var i, len, array;
            // Reset start and length
            i = 0; len = officeTitles.length; array = officeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'office_location';
            // Reset start and length
            i = 0; len = firstNameTitles.length; array = firstNameTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'first_name';
            // Reset start and length
            i = 0; len = lastNameTitles.length; array = lastNameTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'last_name';
            // Reset start and length
            i = 0; len = phoneNumberTitles.length; array = phoneNumberTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'PhoneNumber';
            // Reset start and length
            i = 0; len = emailAddressTitles.length; array = emailAddressTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'email';
            // Reset start and length
            i = 0; len = addressTitles.length; array = addressTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'Location_Address';
            // Reset start and length
            i = 0; len = countryTitles.length; array = countryTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'Location_Country';
            // Reset start and length
            i = 0; len = cityTitles.length; array = cityTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'Location_City';
            // Reset start and length
            i = 0; len = stateTitles.length; array = stateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'Location_State';
            // Reset start and length
            i = 0; len = zipCodeTitles.length; array = zipCodeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'Location_ZipCode';
            // Reset start and length
            i = 0; len = jobTitles.length; array = jobTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'job_title';
            // Reset start and length
            i = 0; len = accessLevelTitles.length; array = accessLevelTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'access_level';
            // Reset start and length
            i = 0; len = profilePictureTitles.length; array = profilePictureTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'profile_picture';
            // Reset start and length
            i = 0; len = resumeTitles.length; array = resumeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'resume';
            // Reset start and length
            i = 0; len = coverLetterTitles.length; array = coverLetterTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'cover_letter';
            // Reset start and length
            i = 0; len = timezoneTitles.length; array = timezoneTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'timezone';
            // New flow
            // Reset start and length
            i = 0; len = secondaryemailaddressTitles.length; array = secondaryemailaddressTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'secondary_email';
            // Reset start and length
            i = 0; len = otheremailaddressTitles.length; array = otheremailaddressTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'other_email';
            // Reset start and length
            i = 0; len = secondaryphoneTitles.length; array = secondaryphoneTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'secondary_PhoneNumber';
            // Reset start and length
            i = 0; len = otherphoneTitles.length; array = otherphoneTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'other_PhoneNumber';
            // Reset start and length
            i = 0; len = officelocationTitles.length; array = officelocationTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'office_location';
            // Reset start and length
            i = 0; len = linkedinurlTitles.length; array = linkedinurlTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'linkedin_profile_url';
            // Reset start and length
            i = 0; len = employeenumberTitles.length; array = employeenumberTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'employee_number';
            // Reset start and length
            i = 0; len = socialsecuritynumberTitles.length; array = socialsecuritynumberTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'ssn';
            // Reset start and length
            i = 0; len = dateofbirthTitles.length; array = dateofbirthTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'dob';
            // Reset start and length
            i = 0; len = departmentTitles.length; array = departmentTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'department';
            // Reset start and length
            i = 0; len = teamTitles.length; array = teamTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'team';
            // Reset start and length
            i = 0; len = joiningdateTitles.length; array = joiningdateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'joined_at';
            // Reset start and length
            i = 0; len = hoursTitles.length; array = hoursTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'user_shift_hours';
            // Reset start and length
            i = 0; len = minutesTitles.length; array = minutesTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'user_shift_minutes';
            // Reset start and length
            i = 0; len = notifyTitles.length; array = notifyTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'notified_by';
            // Reset start and length
            i = 0; len = genderTitles.length; array = genderTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'gender';
            // Reset start and length
            i = 0; len = statusTitles.length; array = statusTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'status';
            // Reset start and length
            i = 0; len = positionTitles.length; array = positionTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'position';
            // Reset start and length
            i = 0; len = rehireDateTitles.length; array = rehireDateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'rehire_date';
            // Reset start and length
            i = 0; len = rehireReasonTitles.length; array = rehireReasonTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'rehire_reason';
            // Reset start and length
            i = 0; len = terminationDateTitles.length; array = terminationDateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'termination_date';
            // Reset start and length
            i = 0; len = terminationReasonTitles.length; array = terminationReasonTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'termination_reason';
            // Reset start and length
            i = 0; len = employmentTitles.length; array = employmentTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'employment_type';
            // Reset start and length
            i = 0; len = middlenameTitles.length; array = middlenameTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'middle_name';
            i = 0; len = nicknameTitles.length; array = nicknameTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'nick_name';
            
            i = 0; len = eeoccodeTitles.length; array = eeoccodeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'eeoc_code';
            i = 0; len = salarybenefitsTitles.length; array = salarybenefitsTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'salary_benefits';
            i = 0; len = workerscompensationcodeTitles.length; array = workerscompensationcodeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'workers_compensation_code';
            
            //
            i = 0; len = maritalstatusTitles.length; array = maritalstatusTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'marital_status';
            i = 0; len = licensetypeTitles.length; array = licensetypeTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_type';
            i = 0; len = licenseauthorityTitles.length; array = licenseauthorityTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_authority';
            i = 0; len = licenseclassitles.length; array = licenseclassitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_class';
            i = 0; len = licensenumberTitles.length; array = licensenumberTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_number';
            i = 0; len = licenseissuedateTitles.length; array = licenseissuedateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_issue_date';
            i = 0; len = dobTitles.length; array = dobTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'dob';
            i = 0; len = licenseexpirationdateTitles.length; array = licenseexpirationdateTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_expiration_date';
            i = 0; len = licensenotesTitles.length; array = licensenotesTitles;
            for(i; i < len; i++) if(index == array[i].trim()) return 'license_notes';


            return -1;
        }


    
        //
        var chunkOBJ = {
            current: 0,
            // chunkSize: 2,
            chunkSize: 50,
            loaded: 0,
            records: [],
            totalChunks: 0,
            recordsLength: 0
        };
        var failedCount = 0, insertedCount = 0, existedCount = 0;

        //
        function uploadRecords(records){
            chunkOBJ.records = [];
            //
            chunkOBJ.recordsLength = records.length;
            //
            chunkOBJ.totalChunks = Math.ceil(chunkOBJ.recordsLength / Math.ceil(chunkOBJ.recordsLength / chunkOBJ.chunkSize));
            //
            if(chunkOBJ.totalChunks != 1)
            chunkOBJ.records = _.chunk(records, chunkOBJ.totalChunks);
            else chunkOBJ.records.push(records);
            //
            uploadChunk();
        }

        //
        function uploadChunk(){
            if(chunkOBJ.records[chunkOBJ.current] === undefined){
                // TODO
                console.log('All chunks are being uploaded..');
                return;
            }
            startAddProcess(chunkOBJ.records[chunkOBJ.current]);
        }

        var xhr = null;
        //
        function startAddProcess(chunk){
            //
            if(xhr !== null) return;
            //
            if( chunkOBJ.records[chunkOBJ.current] === undefined){
                loader('hide');
                alertify.alert('SUCCESS!', 'You have successfully imported <b>'+( insertedCount )+'</b> new employees, <b>'+( existedCount )+'</b> employees already exists and <b>'+( failedCount )+'</b> employees failed to add.');
                failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                return;
            }
            //
            chunkOBJ.loaded += chunk.length;
            var 
            messageRow  = "Please, be patient as we import employees.<br />";
            messageRow += "It may take a few minutes<br />";
            messageRow += 'Importing '+(chunkOBJ.loaded)+' of '+(chunkOBJ.recordsLength)+' employees.';
            //
            loader('show', messageRow);
            xhr = $.post("<?=base_url('import-csv/handler');?>", {
                action: 'add_employees',
                employees: chunk
            }, function(resp) {
                xhr = null;
                if(resp.Status === false){
                    alertify.alert('ERROR!', 'Something went wrong');
                    loader('hide');
                    return;
                }
                failedCount += parseInt(resp.Failed);
                existedCount += parseInt(resp.Existed);
                insertedCount += parseInt(resp.Inserted);
                // Update current and hit uploadChunk function
                chunkOBJ.current++;
                if(chunkOBJ.current >= chunkOBJ.records.length) {
                    loader('hide');
                    alertify.alert('SUCCESS!', 'You have successfully imported <b>'+( insertedCount )+'</b> new employees, <b>'+( existedCount )+'</b> employees already exists and <b>'+( failedCount )+'</b> employees failed to add.', function(){
                        window.location = "<?=base_url('employee_management?employee_type=offline');?>";
                    });
                    failedCount = existedCount = insertedCount = chunkOBJ.loaded = chunkOBJ.current = chunkOBJ.totalChunks = chunkOBJ.recordsLength = 0;
                    return;
                }
                uploadChunk();
            });
        }

        //
        function loader(show_it, msg){
            show = show_it === undefined || show_it === true || show_it === 'show' ? 'show' : show_it;
            msg  = msg === undefined ? '' : msg;
            if(show_it == 'show'){
                $('.js-loader-text').html(msg);
                $('.js-loader').show();
            }else{
                $('.js-loader-text').html('');
                $('.js-loader').fadeOut(150);
            }
        }
    })
</script>