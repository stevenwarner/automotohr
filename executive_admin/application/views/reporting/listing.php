<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title">
                        <i class="fa fa-dashboard"></i>
                        Reporting
                    </h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard'); ?>">
                        <i class="fa fa-long-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
                <!-- reports list table -->
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <h1 class="hr-registered pull-left">
                            <span class="text-success">Reporting</span>
                        </h1>
                    </div>
                    <div class="table-responsive hr-innerpadding">
                        <table class="table table-stripped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left col-xs-10">Title</th>
                                    <th class="text-center col-xs-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Export Employees</td>
                                    <td class="text-center">
                                        <a class="btn btn-success btn-block" href="<?php echo base_url() . 'reports/export_employees/'; ?>">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- reports list table -->
            </div>
        </div>
    </div>
</div>