<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Top -->
                            <div class="row">
                                <div class="heading-title page-title">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                        <h1 class="page-title">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                            &nbsp;Create a new page
                                        </h1>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="<?= base_url("manage_admin/cms"); ?>" class="black-btn">
                                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                            &nbsp;
                                            Back To CMS
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <!-- Page details  -->
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsMeta" aria-expanded="true" aria-controls="collapseOne">
                                    <h4>
                                        <strong>
                                            Page details
                                        </strong>
                                    </h4>
                                </div>
                                <div id="jsMeta" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <form action="javascript:void(0)" id="jsCreatePageForm">
                                        <div class="panel-body">
                                            <div class=" form-group">
                                                <label>Page name</label>
                                                <input type="text" class="form-control jsToSlug" data-target="slug" name="title" />
                                            </div>
                                            <div class=" form-group">
                                                <label>Page slug</label>
                                                <input type="text" class="form-control" name="slug" />
                                            </div>

                                            <div class="form-group">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" name="is_footer_link" id="jsSection2Status" /> Is Footer Link?
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>

                                              <div class="form-group">
                                                <label class="control control--checkbox">
                                                    <input type="checkbox" name="is_default" id="is_defaultpage" /> Is Default?
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>

                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-success jsCreatePageBtn">
                                                <i class="fa fa-save" aria-hidden="true"></i>
                                                &nbsp;Create page
                                            </button>
                                        </div>
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