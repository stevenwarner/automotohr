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
                        <span class="page-heading down-arrow"><?php echo $title ?></span>
                    </div>
                    <div class="message-action">
                        <div class="row">                         
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?= base_url('my_referral_network') ?>" >Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="compose-message">
                        <div class="hr-promotions table-responsive">
                            <form method="post">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td><b>Date</b></td>
                                            <td><?=reset_datetime(array('datetime' => $referral_info['referred_date'], '_this' => $this)); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Referred To</b></td>
                                            <td><?php echo $referral_info['referred_to']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Reference Email</b></td>
                                            <td><?php echo $referral_info['reference_email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Job Title</b></td>
                                            <td><?php echo $referral_info['job_title']; ?></td>
                                        </tr>
                                        <tr>
                                             <td><b>Message</b></td>
                                            <td>
                                                <?php echo $referral_info['personal_message']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>private_messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {
                                alertify.success('Selected message have been ' + action + 'd.');
                                $("#parent_" + id).remove();
                            });

                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>