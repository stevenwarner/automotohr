<div class="main">
    <div class="contrainer">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="form-wrp">

                    <div class="row">
                        <div class="col-lg-6">
                            <h2 style="margin-top: 0;">Certification of Health Care Provider for Employee’s Serious Health Condition</h2>
                            <p>(Family and Medical Leave Act)</p>
                        </div>
                        <div class="col-lg-4 text-center">
                            <h2 style="margin-top: 0;">U.S. Department of Labor</h2>
                            <p>Wage and Hour Division</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                            <img id="imge" src="<?php echo base_url("assets/images/fmlalogo.png");?>" style="max-width: 100%;" class="fmla1_h1_right"></h1>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-8">
                            <strong>DO NOT SEND COMPLETED FORM TO THE DEPARTMENT OF LABOR; RETURN TO THE PATIENT</strong>
                        </div>
                        <div class="col-lg-4 text-center">
                            <p>OMB Control Number: 1545-0074</p>
                            <strong>Expires: 8/31/2021</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong>SECTION II: For Completion by the EMPLOYEE <br>INSTRUCTIONS to the EMPLOYEE:</strong> Please complete Section II before giving this form to your medical provider. The FMLA permits an employer to require that you submit a timely, complete, and sufficient medical certification to support a request for FMLA leave due to your own serious health condition. If requested by your employer, your response is required to obtain or retain the benefit of FMLA protections. 29 U.S.C. §§ 2613, 2614(c)(3). Failure to provide a complete and sufficient medical certification may result in a denial of your FMLA request. 29 C.F.R. § 825.313. Your employer must give you at least 15 calendar days to return this form. 29 C.F.R. § 825.305(b).
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group autoheight">
                                                <label>First Name: <span class="cs-required">*</span></label>
                                                <input type="text" id="js-fmla-health-employee-firstname" name="first_name" class="form-control js-fmla-employee-firstname" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group autoheight">
                                                <label>Middle Name:</label>
                                                <input type="text" id="js-fmla-health-employee-middlename" name="middle_name" class="form-control js-fmla-employee-middlename" />
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group autoheight">
                                                <label>Last Name: <span class="cs-required">*</span></label>
                                                <input type="text" id="js-fmla-health-employee-lastname" name="last_name" class="form-control js-fmla-employee-lastname" />
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <h3 class="text-center">
                                PAPERWORK REDUCTION ACT NOTICE AND PUBLIC BURDEN STATEMENT
                            </h3>
                            <p>
                                If submitted, it is mandatory for employers to retain a copy of this disclosure in their records for three years. 29 U.S.C. § 2616; 29 C.F.R. § 825.500. Persons are not required to respond to this collection of information unless it displays a currently valid OMB control number. The Department of Labor estimates that it will take an average of 20 minutes for respondents to complete this collection of information, including the time for reviewing instructions, searching existing data sources, gathering and maintaining the data needed, and completing and reviewing the collection of information. If you have any comments regarding this burden estimate or any other aspect of this collection information, including suggestions for reducing this burden, send them to the Administrator, Wage and Hour Division, U.S. Department of Labor, Room S-3502, 200 Constitution Ave., NW, Washington, DC 20210. <strong>DO NOT SEND COMPLETED FORM TO THE DEPARTMENT OF LABOR; RETURN TO THE PATIENT.</strong>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                    	<div class="col-sm-12 text-center">
                    		<button class="btn btn-info btn-lg btn-5 js-fmla-save-button" data-type="health">Save</button>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>