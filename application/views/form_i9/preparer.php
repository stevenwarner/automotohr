<?php
for ($i = 1; $i <= 4; $i++) : ?>

    <div class="row <?= $i % 2 === 0 ? 'csBG5 p10' : '' ?>">
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>
                    Signature of Preparer or Translator
                    &nbsp;<strong class="text-danger">*</strong>
                    <i class="fa fa-question-circle-o modalShow" src="section_41_signature_of_preparer"></i>
                </label>
                <?php if (isset($preparerArray[$i]) && $pre_form['user_consent'] == 1) : ?>
                    <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>" alt="" src="<?= $preparerArray[$i]['signature']; ?>" class="prepare_signature_img_<?= $i; ?>" />
                <?php endif; ?>
                <?php if ($pre_form['user_consent'] != 1) : ?>
                    <!-- the below loaded view add e-signature -->
                    <a class="btn blue-button btn-sm jsSetPrepareSignature jsSetPrepareSignature_<?= $i; ?>" data-key="<?= $i; ?>">
                        Create E-Signature
                    </a>
                    <div class=" img-full">
                        <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>" alt="" class="prepare_signature_img_<?= $i; ?>" />
                    </div>
                    <input type="hidden" id="section1_preparer_signature_<?= $i; ?>" name="section1_preparer_signature_<?= $i; ?>" />
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>Today's Date (mm/dd/yyyy)
                    &nbsp;<strong class="text-danger">*</strong>  
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_today_date"></i></label>
                <input type="text" value="<?= $preparerArray[$i]['today_date'] ? formatDateToDB($preparerArray[$i]['today_date'], DB_DATE, 'm-d-Y') : ''; ?>" name="section1_preparer_today_date_<?= $i; ?>" id="section1_preparer_today_date_<?= $i; ?>" class="form-control date_picker input-grey input-lg" readonly />
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label>Last Name (Family Name)
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_last_name"></i></label>
                <input autocomplete="nope" type="text" name="section1_preparer_last_name_<?= $i; ?>" value="<?= $preparerArray[$i]['last_name'] ?? ''; ?>" id="section1_preparer_last_name_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label>First Name (Given Name)
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_first_name"></i></label>
                <input autocomplete="nope" type="text" name="section1_preparer_first_name_<?= $i; ?>" value="<?= $preparerArray[$i]['first_name'] ?? ''; ?>" id="section1_preparer_first_name_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label>Middle initial (If any)
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_middle_initial"></i></label>
                <input autocomplete="nope" type="text" name="section1_preparer_middle_initial_<?= $i; ?>" value="<?= $preparerArray[$i]['middle_initial'] ?? ''; ?>" id="section1_preparer_middle_initial_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>Address (Street Number and Name)
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_address"></i></label>
                <input autocomplete="nope" type="text" name="section1_preparer_address_<?= $i; ?>" value="<?= $preparerArray[$i]['address'] ?? ''; ?>" id="section1_preparer_address_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>City or Town
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_city_or_town"></i></label>
                <input autocomplete="nope" type="text" name="section1_preparer_city_town_<?= $i; ?>" value="<?= $preparerArray[$i]['city'] ?? ''; ?>" id="section1_preparer_city_town_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>State
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_state"></i></label>
                <div class="select">
                    <select name="section1_preparer_state_<?= $i; ?>" id="section1_preparer_state_<?= $i; ?>" class="form-control input-grey input-lg jsStateDropDown">
                        <?php
                        foreach ($states as $state) {
                            echo '<option value="' . $state['state_code'] . '" ' . ($state['state_code'] == ($preparerArray[$i]['state'] ??  '') ? 'selected' : '') . '>' . $state['state_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label>ZIP Code
                    &nbsp;<strong class="text-danger">*</strong>
                    &nbsp;<i class="fa fa-question-circle-o modalShow" src="section_41_zip_code"></i></label>
                <input autocomplete="nope" type="text" value="<?= $preparerArray[$i]['zip_code'] ?? ''; ?>" name="section1_preparer_zip_code_<?= $i; ?>" id="section1_preparer_zip_code_<?= $i; ?>" class="form-control input-grey input-lg" />
            </div>
        </div>
    </div>
    <br>
<?php endfor; ?>