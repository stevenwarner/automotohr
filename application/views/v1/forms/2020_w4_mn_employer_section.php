       <?php
        $input = $formInfo["is_employer_completed"] ? 'disabled' : "";
        ?>

       <form action="javascript:void(0)" autocomplete="off" id="jsStateFormW4EmployerSection">
           <!-- text -->
           <div class="row">
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>
                           First Name
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="text" name="first_name" value="<?php echo $formInfo["employer_json"]['first_name']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-3">
                   <div class="form-group">
                       <label>
                           Last Name
                           <strong class="text-danger">*</strong>
                       </label>
                       <input type="text" name="last_name" value="<?php echo $formInfo["employer_json"]['last_name']; ?>" class="form-control" <?php echo $input; ?> />
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
                       <input type="number" name="ssn" value="<?php echo $formInfo["employer_json"]['ssn']; ?>" class="form-control" <?php echo $input; ?> />
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
                       <input type="text" name="street_1" value="<?php echo $formInfo["employer_json"]['street_1']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-6">
                   <div class="form-group">
                       <label>
                           Street 2
                       </label>
                       <input type="text" name="street_2" value="<?php echo $formInfo["employer_json"]['street_2']; ?>" class="form-control" <?php echo $input; ?> />
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
                       <input type="text" name="city" value="<?php echo $formInfo["employer_json"]['city']; ?>" class="form-control" <?php echo $input; ?> />
                   </div>
               </div>
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>
                           State
                           <strong class="text-danger">*</strong>
                       </label>
                       <select name="state" class="form-control" <?= $input; ?>>
                           <?php foreach ($states as $state) { ?>
                               <option value="<?= $state["sid"]; ?>" <?= $state["sid"] == $formInfo["employer_json"]["state"] ? "selected" : ""; ?>><?= $state["state_name"]; ?> (<?= $state["state_code"]; ?>)</option>
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
                       <input type="number" name="zip_code" value="<?php echo $formInfo["employer_json"]['zip_code']; ?>" class="form-control" <?php echo $input; ?> />
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