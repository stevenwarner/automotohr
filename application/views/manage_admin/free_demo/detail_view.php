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
                                        <h1 class="page-title"><i class="glyphicon glyphicon-credit-card"></i><?php echo $page_title;?></h1>
                                    </div>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post" class="private-msg">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Name</th>
                                                        <th>Number</th>
                                                        <th>Exp Month</th>
                                                        <th>Exp Year</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td><?php echo $cc_type; ?></td>
                                                    <td><?php echo $cc_holder_name; ?></td> 
                                                    <td><?php echo $cc_number; ?></td>
                                                    <td><?php echo $cc_expiration_month; ?></td>
                                                    <td><?php echo $cc_expiration_year; ?></td>
                                                </tbody>
                                            </table>
                                            <?php //echo( $links ); ?>
                                        </form>
                                    </div>
                                    <div class="add-new-promotions">
                                        <a class="site-btn" href="<?php echo site_url('manage_admin/old_dashboard');?>">Back to Listing</a>  
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