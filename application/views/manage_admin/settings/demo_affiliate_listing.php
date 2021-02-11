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
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <br />

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-6">Title</th>
                                                            <th class="col-xs-2">Video Status</th>
                                                            <th class="col-xs-2">Video Source</th>
                                                            <?php if (check_access_permissions_for_view($security_details, 'edit_affiliate_config')) { ?>
                                                                <th class="col-xs-2" colspan="2">Action</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($configurations)) { ?>
                                                            <?php foreach($configurations as $configurate) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $configurate['page_name']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($configurate['status'] == '0'){ ?> 
                                                                            Disable
                                                                        <?php } elseif ($configurate['status'] == '1'){ ?>
                                                                            Enable
                                                                        <?php } ?>   
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($configurate['video_source'] == 'youtube_video'){ ?> 
                                                                            YouTube Video
                                                                        <?php } elseif ($configurate['video_source'] == 'vimeo_video'){ ?>
                                                                            Vimeo Video
                                                                        <?php } elseif ($configurate['video_source'] == 'uploaded_video'){ ?>
                                                                            Uploaded Video
                                                                        <?php } ?>   
                                                                    </td>
                                                                    <?php if (check_access_permissions_for_view($security_details, 'edit_affiliate_config')) { ?>
                                                                        <td>
                                                                            <a href="<?php echo base_url('manage_admin/settings/edit_demo_affiliate_configurations/' . $configurate['sid']);?>" class="btn btn-success btn-sm btn-block">Edit</a>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="4">
                                                                    <span class="no-data">No Page Configurations</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
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
