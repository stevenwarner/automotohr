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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>contact us inquiry log</h1>
                                    </div>
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <!-- Search Table Start -->
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <form action="<?= base_url('manage_admin/logs/contactus_enquiries') ?>" method="GET">
                                            <ul>
                                                <li>
                                                    <label>Date:</label>
                                                    <div class="hr-fields-wrap registration-date">
                                                        <div class="hr-register-date">
                                                            <input type="text" value="<?= empty($search['start']) ? "" : $search['start']; ?>" name="start" class="hr-form-fileds" id="startdate"  readonly>
                                                            <button type="button"  class="ui-datepicker-trigger"><i class="fa fa-calendar"></i></button>To
                                                        </div>
                                                        <div class="hr-register-date">
                                                            <input type="text" value="<?= empty($search['end']) ? "" : $search['end']; ?>" name="end" class="hr-form-fileds" id="enddate"  readonly>
                                                            <button type="button" class="ui-datepicker-trigger"><i class="fa fa-calendar"></i></button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Message:</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text" value="<?= empty($search['message']) ? "" : $search['message']; ?>" name="message" class="hr-form-fileds">
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Email:</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="email"  value="<?= empty($search['email']) ? "" : $search['email']; ?>" name="email" class="hr-form-fileds">
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Company name:</label>
                                                    <div class="hr-fields-wrap">
                                                        <input type="text"  value="<?= empty($search['company_name']) ? "" : $search['company_name']; ?>" name="company_name" class="hr-form-fileds">
                                                    </div>
                                                </li>

                                                <li><input type="submit" class="site-btn" value="Search"></li>
                                            </ul>
                                        </form>
                                    </div>
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <form name="users_form" method="post">
                                        <div class="hr-box-header">
                                            <!-- Pagination Start -->
                                            <!--                                                        <nav class="hr-pagination">
                                                                                                        <ul>
                                                                                                            <li class="previous"><a href="#"><i class="fa fa-angle-left"></i></a></li>
                                                                                                            <li><a href="#">1</a></li>
                                                                                                            <li><a class="active" href="#">2</a></li>
                                                                                                            <li><a href="#">3</a></li>
                                                                                                            <li><a href="#">4</a></li>
                                                                                                            <li><a href="#">5</a></li>
                                                                                                            <li><a href="#">6</a></li>
                                                                                                            <li class="next"><a href="#"><i class="fa fa-angle-right"></i></a></li>
                                                                                                        </ul>
                                                                                                    </nav>
                                                                                                     Pagination End 
                                                                                                    <div class="hr-numberPerPage">
                                                                                                        per page:
                                                                                                        <select class="hr-perPage" >
                                                                                                            <option selected="" value="10">10</option>
                                                                                                            <option value="20">20</option>
                                                                                                            <option value="50">50</option>
                                                                                                            <option value="100">100</option>
                                                                                                        </select>
                                                                                                    </div>-->
                                        </div>
                                        <div class="table-responsive table-outer">
                                            <div class="hr-displayResultsTable">
                                                <table>
                                                    <thead>
                                                        <tr>
<!--                                                                        <th><input type="checkbox"></th>-->
                                                            <th>Date</th>
                                                            <th>Message</th>
                                                            <th>Email</th>
                                                            <th>Company Name</th>
                                                            <th>Status</th>
                                                            <th width="1%" colspan="1">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($logs as $log) { ?>
                                                            <tr>
    <!--                                                                        <td><input type="checkbox"></td>-->
                                                                <td><?= date('m-d-Y', strtotime($log["date"])); ?></td>
                                                                <td style="text-decoration: underline"><a href="<?= base_url('manage_admin/log_detail') ?>/<?= $log["sid"] ?>" ><?php echo substr($log["message"], 0, 74); ?>...</a></td>
                                                                <td><?= $log["email"] ?></td>
                                                                <td><?= $log["company_name"] ?></td>
                                                                <td><?= $log["status"] ?></td>
                                                                <td><a class="hr-edit-btn" title="View" href="<?= base_url('manage_admin/log_detail') ?>/<?= $log["sid"] ?>">View</a></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="hr-box-header hr-box-footer">
                                        </div>
                                    </form>
                                    <!-- Email Logs End -->
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