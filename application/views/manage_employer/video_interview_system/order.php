<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?php echo $title; ?>
                        </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="row" style="margin-top: 10px;">
                            <!-- table -->
                            <div class="table-responsive table-outer">
                                <?php if (!empty($questions)) { ?>
                                <form action="" method="POST" id="send_questions_form" name="send_questions_form">
                                        <div class="table-wrp mylistings-wrp border-none">
                                            <table class="table table-bordered table-stripped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-9">Question</th>
                                                        <th class="text-center">Display Order</th>
                                                    </tr> 
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($questions as $question) { ?>
                                                    <tr>
                                                        <input type="hidden" name="question_sid_<?php echo $question['sid'];?>" id="question_sid_<?php echo $question['sid'];?>" value="<?php echo $question['sid'];?>" />
                                                        <td>
                                                            <?php if($question['question_type'] == 'video') { ?>
                                                                <?php echo $question['video_title']; ?>
                                                            <?php } else { ?>
                                                                <?php echo $question['question_text']; ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="number" value="0" name="question_order_<?php echo $question['sid'];?>" id="question_order_<?php echo $question['sid'];?>" min="0" class="invoice-fields col-sm-12">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>                                            
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" name="order" id="order" value="order" />
                                        <input type="submit" value="Send" class="submit-btn" id="send_question_submit" name="send_question_submit" style="float:right;" onclick="return validate_form();">
                                    </form>
                                <?php } else { ?>
                                    <div class="no-job-found">
                                        <ul>
                                            <li>
                                                <h3 style="text-align: center;">No Video Questions found! </h3>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>                        
                            </div>
                            <!-- table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script>
    function validate_form() { 
        $("#send_questions_form").validate({
            ignore: [],
            rules: {
            <?php foreach ($questions as $question) { ?>
                <?php echo 'question_order_' . $question['sid']; ?>: {
                        required: true,
                        number: true,
                        min: 0
                    },
            <?php } ?>
            },
            messages: {
            <?php foreach ($questions as $question) { ?>
                <?php echo 'question_order_' . $question['sid']; ?>: {
                    required: 'Order is required',
                    number: 'Please enter a valid number',
                    min: 'Please enter a number above or equal to 0'
                },
            <?php } ?>
            }
        });
    }
</script>