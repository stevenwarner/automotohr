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
                                            &nbsp;Schedule Demo Popup
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
                            <!-- Meta -->
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection0" aria-expanded="true" aria-controls="collapseOne">
                                    <h4>
                                        <strong>
                                            Schedule Your Demo
                                        </strong>
                                    </h4>
                                </div>
                                <div id="jsSection0" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <form action="javascript:void(0)" id="jsSection1Form">
                                        <div class="panel-body">

                                            <div class=" form-group">
                                                <label>
                                                    Main heading&nbsp;
                                                    <strong class="text-danger">*</strong>
                                                </label>
                                                <input type="text" class="form-control" name="heading" value="<?=$pageContent["page"]["sections"]["pageDetails"]["heading"];?>" />
                                            </div>
                                            <div class=" form-group">
                                                <label>
                                                    Sub heading&nbsp;
                                                    <strong class="text-danger">*</strong>
                                                </label>
                                                <input type="text" class="form-control" name="subHeading" value="<?=$pageContent["page"]["sections"]["pageDetails"]["subHeading"];?>" />
                                            </div>
                                            <div class=" form-group">
                                                <label>
                                                    Button text&nbsp;
                                                    <strong class="text-danger">*</strong>
                                                </label>
                                                <input type="text" class="form-control" name="buttonText" value="<?=$pageContent["page"]["sections"]["pageDetails"]["buttonText"];?>" />
                                            </div>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <button class="btn btn-success jsSection1Btn">
                                                <i class="fa fa-save" aria-hidden="true"></i>
                                                &nbsp;Update
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