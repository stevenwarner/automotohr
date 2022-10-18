<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Heading -->
                            <div class="heading-title page-title">
                                <h1 class="page-title" style="width: 100%;"><i class="fa fa-users" aria-hidden="true"></i><?php echo $page_title; ?></h1>
                            </div>

                            <div class="clearfix"></div>

                            <br />
                            <!-- Filter -->
                            <div class="jsFilter">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <label>Select Companies <span class="text-danger">*</span></label>
                                        <select id="jsParentCompany" style="width: 100%" multiple>
                                            <option value="-1">All</option>
                                            <?php if ($companies) :
                                                foreach ($companies as $company) : ?>
                                                    <option value="<?= $company['sid']; ?>">
                                                        <?= $company['CompanyName']; ?>
                                                    </option>
                                            <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select id="jsStatusCompany" style="width: 100%">
                                            <option value="-1">All</option>
                                            <option value="0">Disabled</option>
                                            <option value="1">Enabled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 text-right">
                                        <br>
                                        <button class="btn btn-success">Apply Search</button>
                                        <button class="btn btn-default">Reset Search</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Details -->
                            <div class="jsCompanies">
                                <br>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4 style="margin: 0;"><strong>Companies</strong> - <small>50 Found</small></h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Company</th>
                                                                <th scope="col">ComplyNet Company</th>
                                                                <th scope="col">Onboard Status</th>
                                                                <th scope="col">Employee Status</th>
                                                                <th scope="col">Status</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="csVm">
                                                                    <strong>AutomotoHR</strong> <br>
                                                                    <span>Id: 1234</span> <br>
                                                                </td>
                                                                <td class="csVm">
                                                                    <strong>AutomotoHR</strong> <br>
                                                                    <span>Id: 1234-456789-78798-7989</span>
                                                                </td>
                                                                <td class="csVm">
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-center">60% Completed</p>
                                                                </td>
                                                                <td class="csVm">
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-center">20% Completed</p>
                                                                </td>
                                                                <td class="csVm">
                                                                    <strong class="text-success">ENABLED</strong>
                                                                </td>
                                                                <td class="csVm">
                                                                    <button class="btn btn-success">View</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="csVm">
                                                                    <strong>Glockner</strong> <br>
                                                                    <span>Id: 1234</span> <br>
                                                                </td>
                                                                <td class="csVm">
                                                                    <strong>Glockner</strong> <br>
                                                                    <span>Id: 1234-456789-78798-7989</span>
                                                                </td>
                                                                <td class="csVm">
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-center">100% Completed</p>
                                                                </td>
                                                                <td class="csVm">
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-center">100% Completed</p>
                                                                </td>
                                                                <td class="csVm">
                                                                    <strong class="text-success">ENABLED</strong>
                                                                </td>
                                                                <td class="csVm">
                                                                    <button class="btn btn-success">View</button>
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
                            <!-- Loader -->
                            <?php $this->load->view('loader', ['props' => 'id="jsReportComplyNet"']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .csVm {
        vertical-align: middle !important;
    }
</style>
<!--  -->
<script src="<?= base_url(_m('assets/2022/js/complynet/report', 'js', time())); ?>"></script>