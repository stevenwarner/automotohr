<!--  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
<script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee", "subIndex" =>"employee_profile"]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Employee profile
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Personal details
                        </h1>
                        <p class="csF16">
                            This information will be used for payroll, taxes, and benefits, so double-check that it's accurate.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            Fields marked with asterisk (<span class="csRequired"></span>) are mendatory.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            First name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsFirstName" value="<?=!empty($employee_info) ? $employee_info['first_name'] : '';?>" placeholder="John" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Middle name / initial 
                        </label>
                        <input type="text" class="form-control jsMiddleName" value="<?=!empty($employee_info) ? $employee_info['middle_name'] : '';?>" placeholder="B" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Last name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsLastName" value="<?=!empty($employee_info) ? $employee_info['last_name'] : '';?>" placeholder="Doe" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Start date <span class="csRequired"></span>
                        </label>
                        <?php 
                            $start_date = "";
                            if(isset($employee_info)){
                                if (!empty($employee_info['joined_at'])) {
                                    $start_date = date('m/d/Y',strtotime($employee_info['joined_at']));
                                } else if (!empty($employee_info['registration_date'])) {
                                    $start_date = date('m/d/Y',strtotime($employee_info['registration_date']));
                                }
                            }
                        ?>
                        <input type="text" class="form-control jsStartDate jsDatePicker" value="<?php echo $start_date; ?>" placeholder="MM/DD/YYYY" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Work address
                        </label>
                        <select class="form-control jsEWD">
                            <option value="0">[Select]</option>
                            <?php foreach($locations as $location): ?>
                                <option value="<?=$location['sid'];?>"><?php echo $location['street_1'].', '.$location['city'].', '.$location['state'].' - '.$location['zip']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Personal email address <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmail" value="<?=!empty($employee_info) ? $employee_info['email'] : '';?>" placeholder="We may use this email address to send them critical information about payroll" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Employee's Social Security number <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmployeeSSN" value="<?=!empty($employee_info) ? $employee_info['ssn'] : '';?>" placeholder="123456789" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Birthday <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEDOB jsDatePicker" value="<?=!empty($employee_info) ? date('m/d/Y',strtotime($employee_info['dob'])) : '';?>" placeholder="MM/DD/YYYY" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                    <button class="btn btn-black csF16 csB7 jsAddEmployeeCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveCompanyEmployee">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
