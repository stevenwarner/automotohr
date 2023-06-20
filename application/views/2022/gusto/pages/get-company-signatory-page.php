<!--  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery-ui-datepicker-custom.css">
<script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "sign_documents", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Company Signatory
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-sm-12">
                        <p class="csF16">
                            The company signatory is responsible for electronically signing all government forms Gusto creates and is generally a member of the partnership/business. Each time a signatory is added or updated, we’ll file a new Form 8655 with the IRS—your signatory must be authorized by the IRS-<a href="https://www.irs.gov/pub/irs-pdf/p1474.pdf" target="_blank"><b>your signatory must be authorized by the IRS</b></a> to give Gusto the “Reporting Agent Authorization” we need to file and pay your taxes.
                        </p>
                        <h1 class="csF18 csB7">
                            Signatory Personal Details
                        </h1>
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
                        <p class="csF16">
                            Their official, legal name.
                        </p>
                        <input type="text" class="form-control jsFirstName" value="<?=!empty($signatory_info) ? $signatory_info['first_name'] : '';?>" placeholder="John" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Middle initial
                        </label>
                        <p class="csF16">
                            Initial only.
                        </p>
                        <input type="text" class="form-control jsMiddleName" value="<?=!empty($signatory_info) ? $signatory_info['middle_name'] : '';?>" placeholder="Optional" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Last name <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsLastName" value="<?=!empty($signatory_info) ? $signatory_info['last_name'] : '';?>" placeholder="Doe" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Personal email address <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsEmail" value="<?=!empty($signatory_info) ? $signatory_info['email'] : '';?>" placeholder="We may use this email address to send them critical information about payroll" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Title <span class="csRequired"></span>
                        </label>
                        <p class="csF16">
                            An authorized signatory will commonly have one of these titles.
                        </p>
                        <select class="form-control jsTitle">
                            <option value="0">[Select]</option>
                            <option value="Owner" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Owner" ? 'selected="selected"' : '';?>>Owner</option>
                            <option value="President" <?=!empty($signatory_info) &&  $signatory_info['title'] === "President" ? 'selected="selected"' : '';?>>President</option>
                            <option value="Vice President" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Vice President" ? 'selected="selected"' : '';?>>Vice President</option>
                            <option value="Treasurer" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Treasurer" ? 'selected="selected"' : '';?>>Treasurer</option>
                            <option value="Corporate Officer" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Corporate Officer" ? 'selected="selected"' : '';?>>Corporate Officer</option>
                            <option value="Partner" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Partner" ? 'selected="selected"' : '';?>>Partner</option>
                            <option value="Member" <?=!empty($signatory_info) &&  $signatory_info['title'] === "Member" ? 'selected="selected"' : '';?>>Member</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Birth Date <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsSignatoryDOB jsDatePicker" value="<?=!empty($signatory_info) ? date('m/d/Y',strtotime($signatory_info['dob'])) : '';?>" placeholder="MM/DD/YYYY" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Phone number
                        </label>
                        <input type="text" class="form-control jsPhone" value="<?=!empty($signatory_info) ? $signatory_info['phone'] : '';?>" placeholder="Optional" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Social Security Number <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsSignatorySSN" value="<?=!empty($signatory_info) ? $signatory_info['ssn'] : '';?>" placeholder="123456789" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Signatory Home Address
                        </h1>
                        <p class="csF16">
                            To verify their identity, please enter their home address. If you’re unsure what address to use, use the one listed on their driver’s license
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Street 1 <span class="csRequired"></span>
                        </label>
                        <input type="text" class="form-control jsStreet1" value="<?=!empty($signatory_info) ? $signatory_info['street1'] : '';?>" placeholder="e.g. 425 2nd Street" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Street 2
                        </label>
                        <input type="text" class="form-control jsStreet2" value="<?=!empty($signatory_info["street2"])  ? $signatory_info['street2'] : '';?>" placeholder="Optional" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            City <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsCity"
                            value="<?=!empty($signatory_info) ? $signatory_info['city'] : '';?>" placeholder="e.g. San Francisco" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            State <span class="csRequired"></span>
                        </label>
                        <select class="form-control jsState">
                            <?php foreach($states as $state): ?>
                                <option value="<?=$state['state_code'];?>" <?=!empty($signatory_info) &&  $signatory_info['state'] === $state['state_code'] ? 'selected="selected"' : '';?>><?=$state['state_name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label class="csF16 csB7">
                            Zip <span class="csRequired"></span>
                        </label>
                        <input type="email" class="form-control jsZip" value="<?=!empty($signatory_info) ? $signatory_info['zipCode'] : '';?>" placeholder="e.g. 94107" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 text-right">
                    <button class="btn btn-black csF16 csB7 jsAddSignatoryCancel">
                            <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp;
                            Back
                        </button>
                        <button class="btn btn-orange csF16 csB7 jsPayrollSaveCompanySignatory">
                            <i class="fa fa-save" aria-hidden="true"></i>&nbsp;
                            <span id="jsSaveBtnTxt">Save & continue</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
