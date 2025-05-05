<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <!--  -->
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('survey/employer_sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                Survey Templates
                            </span>
                        </div>

                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                                    <i class="fa fa-arrow-left"></i>
                                    Dashboard
                                </a>
                                <button type="button" class="btn btn-orange jsAddSurveyTemplateBtn">
                                    <i class="fa fa-plus-circle"></i>
                                    Add Survey Template
                                </button>
                            </div>
                        </div>
                        <br />

                        <!-- Filter-->
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion"
                                href="#collapseOne" aria-expanded="false">
                                <h1 class="panel-heading-text text-medium">
                                    <strong>
                                        <i class="fa fa-filter text-orange"></i>
                                        Filter
                                    </strong>
                                </h1>
                            </div>
                            <div class="panel-collapse collapse" id="collapseOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="keyword" class="text-medium">Keyword</label>
                                                <input type="text" class="form-control jsFilterKeyword" name="keyword"
                                                    placeholder="Keywords" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer text-right">
                                    <button type="button" class="btn btn-black jsFilterClearBtn">
                                        <i class="fa fa-times-circle"></i>
                                        Clear
                                    </button>
                                    <button type="submit" class="btn btn-orange jsFilterSearchBtn">
                                        <i class="fa fa-search"></i>
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-heading-text text-medium">
                                    <strong>
                                        <i class="fa fa-list text-orange"></i>
                                        Templates
                                    </strong>
                                </h1>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="bg-black">Template</th>
                                                <th class="bg-black"># Of Questions</th>
                                                <th class="bg-black">Recur</th>
                                                <th class="bg-black text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jsTemplatesArea">
                                            <tr>
                                                <td colspan="4">
                                                    <div class="alert text-center">
                                                        <i class="fa fa-spinner fa-spin text-large"></i>
                                                    </div>
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
</div>