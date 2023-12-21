<div class="main jsmaincontent" style="background: #fff;">
    <div class="container">
        <form action="javascript:void(0)" autocomplete="off" id="jsStateFormW4EmployerSection">
            <!-- text -->
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            First Name
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="first_name" value="<?php echo $formData['first_name']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            Last Name
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="last_name" value="<?php echo $formData['last_name']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>
                            Federal Employer ID Number (FEIN)  
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="number" name="ssn" value="<?php echo $formData['ssn']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Address
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="street_1" value="<?php echo $formData['street_1']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            City
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="city" value="<?php echo $formData['city']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            State
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="text" name="state" value="<?php echo $formData['state']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>
                            Zip code
                            <strong class="text-danger">*</strong>
                        </label>
                        <input type="number" name="zip_code" value="<?php echo $formData['zip_code']; ?>" class="form-control" <?php echo $input; ?> />
                    </div>
                </div>
            </div>
            <hr>

            <?php $this->load->view('v1/forms/2020_w4_mn_employer_help_section'); ?>

          
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-info csRadius5   ">
                            Save and Consent
                        </button>
                    </div>
                </div>   
        </form>
    </div>
</div>