<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!--  -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                </div>
                            <?php } ?>


                            <form action="" method="post">
                                <div class="form-group">
                                    <label>
                                        Prompt
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <textarea name="prompt" id="" rows="20"
                                        class="form-control"><?= $result["prompt"]; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i>
                                        Save Changes
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