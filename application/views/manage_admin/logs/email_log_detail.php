<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i>Log Detail</h1>
                                    </div>
                                    <!-- Products Start -->
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="20%"><b>Username</b></td>
                                                        <td><?= $log["username"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="20%"><b>Email</b></td>
                                                        <td><?= $log["email"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Date</b></td>
                                                        <td><?= date_with_time($log["date"]) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Subject</b>
                                                        <td> <?= $log["subject"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Message</b>
                                                        <td> <?= $log["message"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p class="msg_setting">
                                                                <a class="hr-edit-btn" title="Back" href="<?= base_url('manage_admin/email_enquiries') ?>">Back</a>
                                                            </p>
                                                        </td>

                                                    </tr>


                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <!-- Products End -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/products/product_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Promotion?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {

                                if (action == "delete") {
                                    alertify.success('Selected promotion have been ' + action + 'd.');
                                    $("#parent_" + id).remove();
                                }
                                else {
                                    location.reload();
                                }
                            });

                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>