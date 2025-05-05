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
                            <form action="<?= current_url(); ?>" type="GET">
                                <div class="panel-collapse collapse" id="collapseOne">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="keyword" class="text-medium">Keyword</label>
                                                    <input type="text" class="form-control" name="keyword"
                                                        placeholder="Keywords" required />
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="status" class="text-medium">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="-1">All</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Archived</option>
                                                        <option value="draft">Draft</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer text-right">
                                        <button type="button" class="btn btn-black">
                                            <i class="fa fa-times-circle"></i>
                                            Clear
                                        </button>
                                        <button type="submit" class="btn btn-orange">
                                            <i class="fa fa-search"></i>
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>