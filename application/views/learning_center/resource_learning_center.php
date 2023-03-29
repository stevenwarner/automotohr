<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('learning_center'); ?>"><i class="fa fa-chevron-left"></i>Learning Management System</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="hr-innerpadding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th class="col-lg-10">Menu Name</th>
                                        <th class="col-lg-2 text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($resource_category as $cat){ ?>
                                            <tr>
                                                <td><?= $cat['title'];?></td>
                                                <td>
<!--                                                    <a href="--><?//= base_url('learning_center/resource_learning_center/learning/'.$cat['sub_url_code']);?><!--" class="btn btn-success btn-sm btn-block">View</a>                                                              </td>-->
                                                    <a href="javascript:;" class="btn btn-success btn-sm btn-block">View</a>                                                              </td>
                                                </td>
                                            </tr>

                                        <?php }?>
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