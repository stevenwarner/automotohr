<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-sm-3 col-xs-12">
                <?php $this->load->view('payroll/sidebar'); ?>
            </div>
            <!-- Main Content Area -->
            <div class="col-sm-9 col-xs-12">
                <div class="hr-box">
                    <div class="hr-box-header bg-header-green">
                        <span class="hr-registered pull-left">
                            <span>Payroll Settings</span>
                        </span>
                    </div>
                    <!--  -->
                    <div class="hr-box-body hr-innerpadding">
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="pull-right">
                                    <button class="btn btn-success">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Bank Account
                                    </button>
                                </span>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <br>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped table-bordered">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Account #</th>
                                            <th scope="col">Account Type</th>
                                            <th scope="col">Routing Number</th>
                                            <th scope="col">Account Number</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align: middle;">1263eae5-4411-48d9-bd6d-18ed93082e65</td>
                                            <td style="vertical-align: middle;">Checking</td>
                                            <td style="vertical-align: middle;">851070439</td>
                                            <td style="vertical-align: middle;">XXXX4087</td>
                                            <td style="vertical-align: middle;" class="text-danger">
                                                Not Verfied
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <button class="btn btn-success">Verify Account</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle;">1263eae5-4411-48d9-bd6d-18ed93082e65</td>
                                            <td style="vertical-align: middle;">Checking</td>
                                            <td style="vertical-align: middle;">851070439</td>
                                            <td style="vertical-align: middle;">XXXX4087</td>
                                            <td style="vertical-align: middle;" class="text-success">
                                                Verfied
                                            </td>
                                            <td style="vertical-align: middle;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle;">1263eae5-4411-48d9-bd6d-18ed93082e65</td>
                                            <td style="vertical-align: middle;">Checking</td>
                                            <td style="vertical-align: middle;">851070439</td>
                                            <td style="vertical-align: middle;">XXXX4087</td>
                                            <td style="vertical-align: middle;" class="text-success">
                                                Verfied
                                            </td>
                                            <td style="vertical-align: middle;">
                                            </td>
                                        </tr>
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