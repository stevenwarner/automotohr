
<?php if(!empty($external_participants)) { ?>
    <?php foreach($external_participants as $key => $external_participant) { ?>
        <?php if($key == 0) { ?>

            <div id="external_participant_<?php echo $key; ?>" class="row external_participants">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="external_participants[<?php echo $key; ?>][name]"
                               id="external_participants_<?php echo $key; ?>_name"
                               type="text"
                               value="<?php echo $external_participant['name']; ?>"
                               class="form-control external_participants_name" />
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="external_participants[<?php echo $key; ?>][email]"
                               id="external_participants_<?php echo $key; ?>_email"
                               type="email"
                               value="<?php echo $external_participant['email']; ?>"
                               data-rule-email="true"
                               class="form-control external_participants_email" />
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label class="control control--checkbox show-email">
                            Show Email
                            <input name="external_participants[<?php echo $key; ?>][show_email]"
                                   id="external_participants_<?php echo $key; ?>_show_email"
                                   class="external_participants_show_email"
                                   value="1"
                                   <?php echo $external_participant['show_email'] == 1 ? 'checked="checked"' : ''; ?>
                                   type="checkbox" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                    <button id="btn_add_participant"
                            type="button"
                            class="btn btn-success btn-equalizer btn-block btn_add_participant">
                        <i class="fa fa-plus-square"></i></button>
                </div>
            </div>

        <?php } else { ?>

            <div id="external_participant_<?php echo $key; ?>" class="row external_participants" data-id="<?php echo $key; ?>">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input name="external_participants[<?php echo $key; ?>][name]"
                               id="external_participants_<?php echo $key; ?>_name"
                               type="text"
                               value="<?php echo $external_participant['name']; ?>"
                               class="form-control external_participants_name" />
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input name="external_participants[<?php echo $key; ?>][email]"
                               id="external_participants_<?php echo $key; ?>_email"
                               type="email"
                               value="<?php echo $external_participant['email']; ?>"
                               data-rule-email="true"
                               class="form-control external_participants_email" />
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label class="control control--checkbox show-email">
                            Show Email
                            <input name="external_participants[<?php echo $key; ?>][show_email]"
                                   id="external_participants_<?php echo $key; ?>_show_email"
                                   class="external_participants_show_email"
                                   value="1"
                                    <?php echo $external_participant['show_email'] == 1 ? 'checked="checked"' : ''; ?>
                                   type="checkbox" />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                    <button type="button"
                            class="btn btn-danger btn-equalizer btn-block btn_remove_participant"
                            data-id="<?php echo $key; ?>">
                        <i class="fa fa-trash"></i></button>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <div id="external_participant_0" class="row">
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label>Name</label>
                <input name="external_participants[0][name]" id="external_participants_0_name" type="text" class="form-control external_participants_name" />
            </div>
        </div>
        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-4">
            <div class="form-group">
                <label>Email</label>
                <input data-rule-email="true" name="external_participants[0][email]" id="external_participants_0_email" type="email" class="form-control external_participants_email" />
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-4">
            <div class="form-group">
                <label class="control control--checkbox show-email">
                    Show Email
                    <input name="external_participants[0][show_email]" id="external_participants_0_show_email" class="external_participants_show_email" value="1"  type="checkbox" />
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
            <button id="btn_add_participant" type="button" class="btn btn-success btn-equalizer btn_add_participant btn-block"><i class="fa fa-plus-square"></i></button>
        </div>
    </div>
<?php } ?>

