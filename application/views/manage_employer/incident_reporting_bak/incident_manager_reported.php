<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <p>Incident Name: <b><?php echo $incident_name; ?></b></p>
                            <p>Reported by:    <b><?php echo $reported_by; ?></b></p>
                            <p>Reported Date: <b><?php echo  date_format (new DateTime($reported_on), 'M d Y h:m a'); ?></b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('incident_reported'); ?>">
                                        <i class="fa fa-chevron-left"></i>
                                        Back
                                    </a>
                                    <?php echo $title; ?>                               
                                </span>
                            </div>
                            <form method="post" action="" id="inc-form" enctype="multipart/form-data" accept-charset="utf-8">
                                <input type="hidden" name="incident_reporting_sid" value="<?php echo $incident_reporting_sid; ?>">
                                <input type="hidden" name="incident_name" value="<?php echo $incident_name; ?>">
                                <input type="hidden" name="reported_by_sid" value="<?php echo $reported_by_sid; ?>">
                                <input type="hidden" name="report_sid" value="<?php echo $report_sid; ?>">
                                <div class="form-wrp">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 abcde">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                </div>

                                <?php if (sizeof($questions) > 0) { ?>
                                    <div id="digital_form">
                                        <?php foreach ($questions as $question) {
                                        echo '<div class="row"><div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><div class="form-group autoheight">';

                                        if ($question['question_type'] == 'textarea') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']); ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']); ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('text_' . $question['id']); ?></textarea>
                                        <?php } elseif ($question['question_type'] == 'text') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <?php $required = $question['is_required'] ? "required" : "";
                                            echo form_input(strip_tags($question['label']), set_value(strip_tags($question['label'])), 'class="form-control" ' . $required); ?>
                                        <?php } elseif ($question['question_type'] == 'time') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <?php $required = $question['is_required'] ? "required" : ""; ?>
                                            <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="12:00AM" class="form-control start_time"  aria-invalid="false" required="<?php echo $required; ?>">
                                        <?php } elseif ($question['question_type'] == 'date') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <?php $required = $question['is_required'] ? "required" : ""; ?>
                                            <input type="text" name="<?php echo strip_tags($question['label']) ?>" value="" class="form-control start_date"  aria-invalid="false" required="<?php echo $required; ?>" autocomplete="off">
                                        <?php } elseif ($question['question_type'] == 'signature') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <?php $required = $question['is_required'] ? "required" : ""; ?>
                                            <textarea class="form-control textarea" name="<?php echo strip_tags($question['label']) ?>" rows="8" cols="60" <?php echo $question['is_required'] ? "required" : "" ?>><?php echo set_value('signature_' . $question['id']); ?></textarea>
                                        <?php } elseif ($question['question_type'] == 'radio') { ?>
                                            <label><?php echo strip_tags($question['label']) ?>: <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                                    <label class="control control--radio">
                                                    Yes<input type="radio" name="<?php echo strip_tags($question['label']) ?>" value="yes" style="position: relative;" <?php echo $question['is_required'] ? "required" : "" ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3 col-md-6 col-xs-12 col-sm-6">
                                                    <label class="control control--radio">
                                                    No<input type="radio" name="<?php echo strip_tags($question['label']) ?>" value="no" style="position: relative;" <?php echo $question['is_required'] ? "required" : "" ?>>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php } elseif ($question['question_type'] == 'single select') { ?>
                                            <label class="auto-height"><?php echo strip_tags($question['label']) ?> : <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <div class="hr-select-dropdown">
                                                <select name="<?php echo strip_tags($question['label']) ?>" class="form-control" <?php if ($question['is_required'] == 1) { ?> required <?php } ?>>
                                                    <option value="">-- Please Select --</option>
                                                    <?php $options = explode(',', $question['options']);
                                                        foreach ($options as $option) { ?>
                                                            <option value="<?php echo $option; ?>"> <?php echo ucfirst($option); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } elseif ($question['question_type'] == 'multi select') { ?>
                                            <label class="multi-checkbox auto-height" data-attr="<?php echo $question['is_required'] ?>" data-key="<?php echo $question['id']; ?>" data-value="<?php echo $question['label'] ?>"><?php echo strip_tags($question['label']); ?> <span class="required"><?php echo $question['is_required'] ? '*' : '' ?></span></label>
                                            <div class="row">
                                                <?php $options = explode(',', $question['options']); ?>
                                                <?php foreach ($options as $option) { ?>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                        <label class="control control--checkbox">
                                                            <?php echo $option; ?>
                                                            <input type="checkbox" name="<?php echo strip_tags($question['label']) ?>[]" value="<?php echo $option; ?>" style="position: relative;">
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } echo '</div> </div> </div>'; } ?>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="form-col-100 autoheight text-right">
                                                    <input type="submit" value="Submit Response" name="submit" class="btn btn-success">
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                <?php } else {
                                    echo "<span class='no-data'>No Questions Scheduled For This Type</span>";
                                } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.start_time').datetimepicker({
            datepicker: false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step: 15
        });

        $('.start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    }); 
</script>