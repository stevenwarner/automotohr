<div class="row">
    <div class="col-xs-12">
        <div class="hr-box">
            <div class="hr-box-header">
                <strong>Automatically assign document after:</strong>
            </div>
            <div class="hr-innerpadding">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="">
                            <div class="">
                                <label class="control control--radio">
                                    Days
                                    <input type="radio" name="assign_type" value="days" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Months
                                    <input type="radio" name="assign_type" value="months" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <br />

                <div class="row">
                    <div class="col-xs-6 js-type-days js-type">
                        <div class="universal-form-style-v2">
                            <div class="input-group pto-time-off-margin-custom">
                                <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-days">
                                <span class="input-group-addon">Days</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 js-type-months js-type">
                        <div class="universal-form-style-v2">
                            <div class="input-group pto-time-off-margin-custom">
                                <input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-months">
                                <span class="input-group-addon">Months</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>