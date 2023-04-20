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
                                                        <?php echo form_open('manage_admin/company_benefits/add/' . $companySid); ?>
                                                        <input type="hidden" id="sid" name="sid" value="<?php echo $benifit['sid']; ?>" />
                                                        <input type="hidden" id="action" name="action" value="save_benifit" />
                                                        <!--  -->
                                                        <div class="form-group">
                                                            <label>Name<strong class="text-danger">*</strong></label>
                                                            <p><i>Benefits names are displayed on employee pay stubs and are for your records</i></p>
                                                            <input type="text" class="form-control" name="benifitname" value="<?php echo $benifit['name'] ?>" />
                                                            <?php echo form_error('benifitname'); ?>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Category<strong class="text-danger">*</strong></label>
                                                            <p><i>The name of the category.</i></p>
                                                            <select name="categoryname" id="categoryname" class="form-control">
                                                                <option value="">Please select a category </option>
                                                                <?php if (!empty($defaultCategories)) {
                                                                    foreach ($defaultCategories as $catRow) {
                                                                ?>
                                                                        <option value="<?php echo $catRow['category']; ?>"><?php echo $catRow['category']; ?></option>
                                                                <?php }
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php echo form_error('category'); ?>


                                                        </div>


                                                        <div class="form-group">
                                                            <label>Employee deduction per pay period </label>
                                                            <p><i>This amount is deducted from the employee each pay period to pay for this benefit. this can be changed for each employee later.</i></p>
                                                            <input type="text" class="form-control" name="employeededuction" value="<?php echo $benifit['benefit_type'] ?>" placeholder="0.00" />
                                                        </div>


                                                        <div class="form-group">
                                                            <label>Company contribution per pay pefiod</label>
                                                            <p><i>This Amount is paid by the company each pay period byond what was deducted from the employee's pay .</i></p>
                                                            <input type="text" class="form-control" name="companycontribution" value="<?php echo $benifit['description'] ?>" placeholder="0.00" />
                                                        </div>
                                                        <br>

                                                        <ul>
                                                            <li>
                                                                <a href="<?php echo base_url('manage_admin/company_benefits/' . $companySid); ?>" class="site-btn"><i class="fa fa-reply"></i>&nbsp;Back</a>
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