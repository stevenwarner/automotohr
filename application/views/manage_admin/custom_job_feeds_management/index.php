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
                                        <h1 class="page-title"><i class="fa fa-list"></i>Job Feeds Management</h1>
                                    </div>
                                    <br />
                                    <br />
                                    <br />
                                    <div style="min-height: 790px;">
                                        <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <a href="<?php echo base_url('manage_admin/custom_job_feeds_management/add_feed'); ?>" class="btn btn-success btn-block">Add Custom Feeds</a>
                                                </div>
                                        </div>

                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <strong style="color: #ffffff;">Default Feeds</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th>Site</th>
                                                                    <th>Title</th>
                                                                    <th>Type</th>
                                                                    <th>Last Read</th>
                                                                    <th>URL</th>
                                                                    <th colspan="2" class="text-center" >Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if(sizeof($default_feed_list) || sizeof($default_feed_accept_url)) {
                                                                    if (sizeof($default_feed_list)) {
                                                                        foreach ($default_feed_list as $feed) { ?>
                                                                            <tr>
                                                                                <td><?= $feed['site'] ?></td>
                                                                                <td><?= $feed['title'] ?></td>
                                                                                <td><?= $feed['type'] ?></td>
                                                                                <td>
                                                                                    <a href="<?php echo $feed['url'] ?>"><?php echo $feed['url'] ?></a>
                                                                                </td>
                                                                                <td>
                                                                                    <?=empty($feed['last_read']) ? '-' : DateTime::createfromformat('Y-m-d H:i:s', $feed['last_read'])->format('M d Y, D H:i');?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if(sizeof($feed['url'])) { ?>
                                                                                       <a class="btn btn-default btn-block" href="<?php echo base_url("manage_admin/custom_job_feeds_management/companies_listing/".$feed['sid']); ?>">Companies</a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                 <td>
                                                                                    <a href="javascript:;"
                                                                                       data-attr="<?= $feed['sid'] ?>"
                                                                                       class="btn <?= $feed['status'] ? 'btn-danger disable' : 'btn-primary enable'?> btn-block"><?= $feed['status'] ? 'Disable' : 'Enable'?></a>
                                                                                    
                                                                                </td>
                                                                               
                                                                            </tr>
                                                                        <?php }
                                                                    }
                                                                    if (sizeof($default_feed_accept_url)) { ?>
                                                                        <tr class="bg-success">
                                                                            <td colspan="6">Applicant Accept URLs</td>
                                                                        </tr>
                                                                        <?php foreach ($default_feed_accept_url as $feed) { ?>
                                                                            <tr>
                                                                                <td><?= $feed['site'] ?></td>
                                                                                <td><?= $feed['title'] ?></td>
                                                                                <td><?= $feed['type'] ?></td>
                                                                                <td>
                                                                                    <?=empty($feed['last_read']) ? '-' : DateTime::createfromformat('Y-m-d H:i:s', $feed['last_read'])->format('M d Y, D H:i');?>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="<?php echo $feed['url'] ?>" target="_blank"><?php echo $feed['url'] ?></a>
                                                                                </td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                    }
                                                                }else{?>
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No Default Feeds Found</td>
                                                                    </tr>
                                                                <?php }?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />


                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header bg-header-green">
                                                        <strong style="color: #ffffff;">Custom Feeds</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                                <thead>
                                                                <tr>
                                                                    <th>Title</th>
                                                                    <th>Type</th>
                                                                    <th>URL</th>
                                                                    <th>Last Hit On</th>
                                                                    <th class="text-center" colspan="3">Actions</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php if(sizeof($custom_feed_list) || sizeof($custom_feed_accept_url)) {
                                                                    if (sizeof($custom_feed_list)) {
                                                                        foreach ($custom_feed_list as $feed) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><?= $feed['title'] ?></td>
                                                                                <td><?= $feed['type'] ?></td>
                                                                                <td><a href="<?= $feed['url'] ?>" target="_blank"><?= $feed['url'] ?></a></td>
                                                                                <td>
                                                                                    <?=empty($feed['last_read']) ? '-' : DateTime::createfromformat('Y-m-d H:i:s', $feed['last_read'])->format('M d Y, D H:i');?>
                                                                                </td>
                                                                                <td>
                                                                                   <?php if(sizeof($feed['url'])) { ?>
                                                                                        <a class="btn btn-default btn-block" href="<?php echo base_url("manage_admin/custom_job_feeds_management/companies_listing/".$feed['sid']); ?>">Companies</a>
                                                                                   <?php }?>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="<?php echo base_url('manage_admin/custom_job_feeds_management/edit_job_feed') . '/' . $feed['sid'] ?>"
                                                                                       class="btn btn-success btn-block">Edit </a>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:;"
                                                                                       data-attr="<?= $feed['sid'] ?>"
                                                                                       class="btn <?= $feed['status'] ? 'btn-danger disable' : 'btn-primary enable'?> btn-block"><?= $feed['status'] ? 'Disable' : 'Enable'?> </a>
                                                                                       
                                                                                </td>
                                                                                
                                                                            </tr>
                                                                        <?php }
                                                                    }
                                                                    if (sizeof($custom_feed_accept_url)) { ?>
                                                                        <tr class="bg-success">
                                                                            <td colspan="4">Applicant Accept URLs</td>
                                                                        </tr>
                                                                        <?php foreach ($custom_feed_accept_url as $feed) { ?>
                                                                            <tr>
                                                                                <td><?= $feed['title'] ?></td>
                                                                                <td><?= $feed['type'] ?></td>
                                                                                <td><?= $feed['url'] ?></td>
                                                                                <td>
                                                                                    <?=empty($feed['last_read']) ? '-' : DateTime::createfromformat('Y-m-d H:i:s', $feed['last_read'])->format('M d Y, D H:i');?>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="<?php echo base_url('manage_admin/custom_job_feeds_management/edit_job_feed') . '/' . $feed['sid'] ?>"
                                                                                       class="btn btn-success btn-block">Edit </a>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:;"
                                                                                       data-attr="<?= $feed['sid'] ?>"
                                                                                       class="btn <?= $feed['status'] ? 'btn-danger disable' : 'btn-primary enable'?> btn-block"><?= $feed['status'] ? 'Disable' : 'Enable'?></a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php }
                                                                    }
                                                                }else{?>
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No Custom Feeds Found</td>
                                                                    </tr>
                                                                <?php }?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />
                                        <div class="row">
                                            <div class="col-xs-12">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','.disable',function(){
        var attr = $(this).attr('data-attr');
        var _this = this;
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to disable this feed?',
            function () {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('manage_admin/custom_job_feeds_management/ajax_handler')?>',
                    data: {
                        id: attr,
                        status: 0
                    },
                    success: function(resp){
                        $(_this).removeClass('btn-danger');
                        $(_this).removeClass('disable');
                        $(_this).addClass('btn-primary');
                        $(_this).addClass('enable');
                        $(_this).html('Enable');
                    },
                    error: function(){

                    }
                });
            },
            function () {
            });
    });

    $(document).on('click','.enable',function(){
        var attr = $(this).attr('data-attr');
        var _this = this;
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to enable this feed?',
            function () {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('manage_admin/custom_job_feeds_management/ajax_handler')?>',
                    data: {
                        id: attr,
                        status: 1
                    },
                    success: function(resp){
                        $(_this).removeClass('btn-primary');
                        $(_this).removeClass('enable');
                        $(_this).addClass('btn-danger');
                        $(_this).addClass('disable');
                        $(_this).html('Disable');
                    },
                    error: function(){

                    }
                });
            },
            function () {
            });
    });

    function func_refund_product(pending_job_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to refund this product?',
            function () {
                $('#form_refund_product_' + pending_job_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }

    function func_activate_job_on_feed(pending_job_sid) {
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to activate this Job on Feed?',
            function () {
                $('#form_activate_job_on_feed_' + pending_job_sid).submit();
            },
            function () {
                alertify.error('Cancelled!');
            });
    }
</script>
