<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                    <div class="dashboard-content">
                                        <div class="dash-inner-block">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                                    </div>
                                                    <div class="hr-setting-page">
                                                        <?php echo form_open('manage_admin/benefits/add'); ?>
                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $benifit['sid']; ?>" />
                                                        <input type="hidden" id="action" name="action" value="save_benifit" />
                                                        <!--  -->
                                                        <div class="form-group">
                                                            <label>Name<strong class="text-danger">*</strong></label>
                                                            <p><i>The name of the benefit.</i></p>
                                                            <input type="text" class="form-control" name="benifitname" value="<?php echo $benifit['name'] ?>" />
                                                            <?php echo form_error('benifitname'); ?>
                                                        </div>


                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <p><i>The name of the benefit.</i></p>
                                                            <input type="text" class="form-control" name="categoryname" value="<?php echo $benifit['category'] ?>" />
                                                        </div>


                                                        <div class="form-group">
                                                            <label>Type Number</label>
                                                            <p><i>The benefit type in Gusto.</i></p>
                                                            <input type="text" class="form-control" name="benefittype" value="<?php echo $benifit['benefit_type'] ?>" />
                                                        </div>


                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <p><i>The description of the benefit.</i></p>
                                                            <input type="text" class="form-control" name="benefittype" value="<?php echo $benifit['description'] ?>" />
                                                        </div>
                                                        <br>


                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="pretax" value="1" <?php echo  $benifit['pretax'] ? 'checked' : '' ?>> Pretax <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit is deducted before tax calculations, thus reducing one’s taxable income</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                        <br>

                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="posttax" value="1" <?php echo  $benifit['posttax'] ? 'checked' : '' ?>> Posttax <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit is deducted after tax calculations.</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="imputed" value="1" <?php echo  $benifit['imputed'] ? 'checked' : '' ?>> Imputed <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit is considered imputed income.</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>




                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="healthcare" value="1" <?php echo  $benifit['healthcare'] ? 'checked' : '' ?>> Health Care <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit is healthcare related.</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="retirement" value="1" <?php echo  $benifit['retirement'] ? 'checked' : '' ?>> Retirement <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit is associated with retirement planning.</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" name="yearlylimit" value="1" <?php echo  $benifit['yearly_limit'] ? 'checked' : '' ?>> Yearly Limit <strong class="text-danger">*</strong>
                                                                <p style="font-weight: normal;"><i>Whether the benefit has a government mandated yearly limit.</i></p>
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>


                                                        <ul>

                                                            <li>
                                                                <a href="<?php echo base_url('manage_admin/benefits'); ?>" class="site-btn"><i class="fa fa-reply"></i>&nbsp;Back</a>
                                                                <?php echo form_submit('setting_submit', 'Save', array('class' => 'site-btn')); ?>
                                                            </li>
                                                        </ul>
                                                        <?php echo form_close(); ?>
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
            </div>
        </div>
    </div>
</div>