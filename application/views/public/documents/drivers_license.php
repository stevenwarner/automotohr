<?php 
    if(!isset($license[0])){
        $license = [];
        $license['sid'] = 0;
        $license['users_sid'] = '';
        $license['users_type'] = '';
        $license['license_type'] = '';
        $license['license_details'] = [
            'license_type' => '',
            'license_authority' => '',
            'license_class' => '',
            'license_number' => '',
            'license_issue_date' => '',
            'license_expiration_date' => '',
            'license_indefinite' => '',
            'license_notes' => '',
        ];
        $license['license_file'] = '';

    } else {
        $license = $license[0];
        $license['license_details'] = @unserialize($license['license_details']);
    }
?>

<!--  -->
<link rel="stylesheet" href="<?=base_url("assets/mFileUploader/index.css");?>">
<script src="<?=base_url("assets/mFileUploader/index.js");?>"></script>

<style>
    .csModalFooterLoader{ z-index: 1; }
    .csModalFooterLoader i{ font-size: 50px; }
</style>

<div id="dependents">
    <form action="">
        <div class="csModalFooterLoader jsDLicenseLoader">
            <i class="fa fa-spinner fa-spin"></i>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Type</label>
                    <div>
                        <select class="jsSelect" id="jsType">
                            <option value="Sales License">Sales License </option>
                            <option value="Commercial Driver’s License">Commercial Driver’s License</option>
                            <option value="Non-commercial Driver’s License">Non-commercial Driver’s License</option>
                            <option value="Restricted Driver’s License">Restricted Driver’s License</option>
                            <option value="Basic Driver’s License">Basic Driver’s License</option>
                            <option value="Identification Card">Identification Card</option>
                            <option value="College Diploma">College Diploma</option>
                            <option value="Training">Training</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>License Authority</label>
                    <div>
                        <input type="text" class="form-control" id="jsAuthority" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Class</label>
                    <div>
                        <select class="jsSelect" id="jsClass">
                            <option value="None">None</option>
                            <option value="Class A">Class A</option>
                            <option value="Class B">Class B</option>
                            <option value="Class C">Class C</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Number</label>
                    <div>
                        <input type="text" class="form-control" id="jsNumber" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Issue Date</label>
                    <div>
                        <input type="text" class="form-control jsDatePicker" id="jsIssueDate" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Date Of Birth</label>
                    <div>
                        <input type="text" class="form-control jsDatePicker" id="jsDOB" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Expiration Date</label>
                    <div>
                        <input type="text" class="form-control jsDatePicker" id="jsExpirationDate" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <br />
                    <label class="control control--checkbox">
                        Indefinite
                        <input type="checkbox" id="jsIndefinite" />
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <label>License Notes</label>
                    <div>
                        <textarea rows="10" class="form-control" id="jsNotes"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <label>Upload Scanned License Image</label>
                    <div>
                        <input type="file" class="form-control hidden" id="jsUploadImage" />
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(!empty($license['license_file'])): ?>
        <div class="form-group jsFileViewer">
            <div class="row">
                <div class="col-sm-12">
                    <label>Filename: <?=$license['license_file'];?></label>
                    <div>
                        <?= getFilePathForIframe($license['license_file'], true, ['style="width: 50%"']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    <a href="javascript:void(0)" class="btn btn-info jsUpdateBtn">Save</a>
                </div>
            </div>
        </div>
    </form>
</div>


<!--  -->
<script>
    $(function(){
        //
        let 
        userType = "<?=$users_type;?>",
        userSid = "<?=$users_sid;?>",
        companySid = "<?=$company_sid;?>",
        license = <?=json_encode($license);?>;
        //
        $('.jsSelect').select2();
        $('.jsDatePicker').datepicker({
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
        //
        setData();
        //
        function setData(){
            $('#jsType').select2('val', license['license_details']['license_type']);
            $('#jsAuthority').val(license['license_details']['license_authority']);
            $('#jsClass').select2('val', license['license_details']['license_class']);
            $('#jsNumber').val(license['license_details']['license_number']);
            $('#jsDOB').val(license['license_details']['dob']);
            $('#jsIssueDate').val(license['license_details']['license_issue_date']);
            $('#jsExpirationDate').val(license['license_details']['license_expiration_date']);
            $('#jsIndefinite').prop('checked', (license['license_details']['license_indefinite'] == null || license['license_details']['license_indefinite'] == 'off' ? false : true));
            $('#jsNotes').val(license['license_details']['license_notes'].trim());
        }
        //
        $(document).on('change', '#jsCountry', function(){
            let l = states[$(this).val()];
            //
            if(l === undefined){
                $('#jsState').html('<option value="0">Select Country First</option>');
                return;
            }
            //
            let options = '';
            //
            $.each(l, (i, v) => { options += `<option value="${i}">${v.Name}</option>`; });
            $('#jsState').html(options);
        });
        //
        $('.jsUpdateBtn').click(function(e){
            //
            e.preventDefault();
            //
            let obj = {};
            //
            obj.type = $('#jsType').val();
            obj.authority = $('#jsAuthority').val().trim();
            obj.class = $('#jsClass').val();
            obj.number = $('#jsNumber').val().trim();
            obj.issueDate = $('#jsIssueDate').val();
            obj.dob = $('#jsDOB').val();
            obj.expirationDate = $('#jsExpirationDate').val();
            obj.indefinite = $('#jsIndefinite').prop('checked') === true ? 'on' : 'off';
            obj.notes = $('#jsNotes').val();
            obj.file = $('#jsUploadImage').mFileUploader('get');
            //
            if(obj.file.hasError !== undefined && obj.file.hasError === true){
                obj.file = null;
            }
            //
            obj.userSid = userSid;
            obj.userType = userType;
            obj.companySid = companySid;
            obj.ctype = 'drivers';
            obj.sid = license['sid'];
            //
            let formData = new FormData();
            //
            $.each(obj, (i, v) => { formData.append(i, v); });
            //
            $('.jsDLicenseLoader').show();
            //
            $.ajax({
                url: "<?=base_url('onboarding/license_edit');?>",
                type: "POST",
                contentType: false,
                processData: false,
                data: formData,
            }).done((resp) => {
                $('.jsDLicenseLoader').hide();
                alertify.alert('SUCCESS!', 'You have succesfully edited drivers license.', () => {
                    window.location.reload();
                });
            });
        });

        // File uploader with drag n drop functionality
        $('#jsUploadImage').mFileUploader({
            fileLimit: '10MB',
            placeholderImage: '',
            allowedTypes: ["jpg","jpeg","png","jpe", "docx", "doc", "ppt", "pptx", "rtf", "xls", "xlsx", "pdf"]
        });

    });
</script>