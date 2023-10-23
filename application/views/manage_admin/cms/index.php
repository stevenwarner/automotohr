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
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-3 text-center">Name</th>
                                                                    <th class="col-xs-2 text-center">Slug</th>
                                                                    <th class="col-xs-3 text-center">Created At</th>
                                                                    <th class="col-xs-3 text-center">Updated At</th>
                                                                    <th class="col-xs-1 text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($pages_data as $page) { ?>
                                                                    <tr>
                                                                        <td><?php echo $page['title']; ?></td>
                                                                        <td><?php echo $page['slug']; ?></td>
                                                                        <td>
                                                                            <?php echo  date_with_time($page['created_at']); ?>
                                                                        </td>
                                                                        <td> <?php echo  date_with_time($page['updated_at']); ?>
                                                                        </td>
                                                                        <td><button class="btn btn-success" onclick="window.location.href = '<?php echo base_url('manage_admin/edit_page/' . $page['sid']) ?>' ">Edit</button>
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