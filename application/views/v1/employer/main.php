<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
        
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <!-- Company details header -->
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <!--  -->
                            <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                <i class="fa fa-chevron-left"></i>Dashboard
                            </a>
                        </span>
                    </div>
                </div>
            </div>        

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">    
                    <a href="<?php echo base_url('/dashboard'); ?>" class="btn btn-black">
                        <i class="fa fa-th"></i> Dashboard
                    </a>
                    
                    <a href="<?php echo base_url('attendance/dashboard'); ?>" class="btn btn-orange <?= preg_match('/attendance\/dashboard/im', uri_string()) ? 'active-link' : ''; ?>">
                        <i class="fa fa-dashboard"></i> Overview
                    </a>

                    <a href="<?php echo base_url('attendance/timesheet'); ?>"  class="btn btn-orange <?= preg_match('/attendance\/timesheet/im', uri_string()) ? 'active-link' : ''; ?>">
                        <i class="fa fa-file"></i> TimeSheet
                    </a>

                    <a href="<?php echo base_url('attendance/employees/timesheets'); ?>" class="btn btn-orange <?= preg_match('/attendance\/employees\/timesheets/im', uri_string()) ? 'active-link' : ''; ?>">
                        <i class="fa fa-files-o"></i> Employees timesheets
                    </a>

                    <a href="<?php echo base_url('attendance/employees/locations'); ?>" class="btn btn-orange <?= preg_match('/attendance\/employees\/locations/im', uri_string()) ? 'active-link' : ''; ?>">
                        <i class="fa fa-map-marker"></i> Employees locations
                    </a>
                </div>
            </div>
            
            <br>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                    <?php $this->load->view($mainContentPath); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (isset($user_sid)) { ?>
    <script>
        const profileUserInfo = {
            userId: <?= $user_sid; ?>
        };
    </script>
<?php } ?>