       <?php
        $input = $formInfo["is_employer_completed"] ? 'disabled' : "";
        $companySid = $this->session->userdata('logged_in')['company_detail']['sid'];
        $employerPrefill = getDataForEmployerPrefill($companySid);
        ?>

       <form action="javascript:void(0)" autocomplete="off" id="jsStateFormW4EmployerSection">
           <!-- text -->
           <div class="row">
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>
                           Employer's name
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="text" name="first_name" value="<?php echo $formInfo["employer_json"]['first_name'] != '' ? $formInfo["employer_json"]['first_name'] : $employerPrefill['company_corp_name']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>
                           Minnesota Tax ID Number
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="number" name="mn_tax_number" value="<?php echo $formInfo["employer_json"]['mn_tax_number']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>
                           Federal Employer ID Number (FEIN)
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="number" name="ssn" value="<?php echo $formInfo["employer_json"]['ssn'] != '' ? $formInfo["employer_json"]['ssn'] : $employerPrefill['ssn']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
           </div>
           <div class="row">
               <div class="col-sm-6">
                   <div class="form-group">
                       <label>
                           Street 1
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="text" name="street_1" value="<?php echo $formInfo["employer_json"]['street_1'] != '' ? $formInfo["employer_json"]['street_1'] : $employerPrefill['Location_Address']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-6">
                   <div class="form-group">
                       <label>
                           Street 2
                       </label>
                       <input type="text" name="street_2" value="<?php echo $formInfo["employer_json"]['street_2'] != '' ? $formInfo["employer_json"]['street_2'] : $employerPrefill['Location_Address_2']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>

           </div>
           <div class="row">
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>
                           City
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="text" name="city" value="<?php echo $formInfo["employer_json"]['city'] != '' ? $formInfo["employer_json"]['city'] : $employerPrefill['Location_City']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>
                           State
                           <strong class="text-danger">*</strong>
                       </label>
                       <select name="state" class="form-control" <?= $input; ?>>
                           <?php foreach ($states as $state) {
                                $stateId = $formInfo["employer_json"]["state"] ? $formInfo["employer_json"]["state"] : $employerPrefill['Location_State'];
                            ?>
                               <option value="<?= $state["sid"]; ?>" <?= $state["sid"] == $stateId ? "selected" : ""; ?>><?= $state["state_name"]; ?> (<?= $state["state_code"]; ?>)</option>
                           <?php } ?>
                       </select>
                   </div>
               </div>
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>
                           Zip code
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="number" name="zip_code" value="<?php echo $formInfo["employer_json"]['zip_code'] != '' ? $formInfo["employer_json"]['zip_code'] : $employerPrefill['Location_ZipCode']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
           </div>

           <?php if (!$formInfo["is_employer_completed"]) { ?>
               <hr>

               <div class="row">
                   <div class="col-sm-12 text-center jsStateFormW4EmployerSectionBtn">
                       <button class="btn btn-info csRadius5 ">
                           Save and Consent
                       </button>
                   </div>
               </div>
           <?php } ?>
       </form>


       <?php $this->load->view('v1/forms/2020_w4_mn_employer_help_section'); ?>