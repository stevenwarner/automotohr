<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>CMS</h1>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Pages</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption></caption>
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-3">Name</th>
                                                                    <th class="col-xs-2">Slug</th>
                                                                    <th class="col-xs-2">Last updated at</th>
                                                                    <th class="col-xs-1">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($pages_data as $page) { ?>
                                                                    <tr>
                                                                        <td style="vertical-align: middle;"><?php echo $page['title']; ?></td>
                                                                        <td style="vertical-align: middle;">
                                                                            <a href="<?php echo $page['slug']; ?>" target="_blank">
                                                                                <?php echo $page['slug']; ?>
                                                                            </a>
                                                                        </td>
                                                                        <td style="vertical-align: middle;">
                                                                            <?= formatDateToDB(
                                                                                $page["updated_at"],
                                                                                DB_DATE_WITH_TIME,
                                                                                DATE_WITH_TIME
                                                                            ); ?>
                                                                        </td>
                                                                        <td style="vertical-align: middle;">
                                                                            <a href="<?php echo base_url('manage_admin/edit_page/' . $page['sid']) ?>" class="btn btn-warning">
                                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                                                &nbsp;Edit Page
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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