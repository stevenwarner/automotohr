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
                                        <h1 class="page-title"><i class="glyphicon glyphicon-envelope"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post" class="private-msg">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <th>Authorization Date</th>
                                                        <th width="1%" class="actions" colspan="5">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($all_enquiries)) { ?>
                                                        <?php foreach ($all_enquiries as $data) { ?>
                                                            <tr>
                                                                <td><?php echo $data["CompanyName"]; ?></td>
                                                                <td><?php echo my_date_format($data["authorization_date"]); ?></td> 
                                                                <td><a class="hr-edit-btn" title="View" href="<?= base_url('manage_admin/old_dashboard/info') ?>/<?= $data["verification_key"] ?>">View</a></td>                                                            
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                            <tr>
                                                                <td colspan="3">No Record Found!</td>                  
                                                            </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <?php //echo( $links ); ?>
                                        </form>
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