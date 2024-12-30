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
                                                    <div class="hr-promotions table-responsive">
                                                        <label>Module Security </label>
                                                        <?php echo form_open('', array('class' => 'form-horizontal')); ?>
                                                        <?php foreach ($modules as $key => $module) {  ?>
                                                            <div class="hr-promotions table-responsive">
                                                                <table>
                                                                    <thead>
                                                                        <tr style="height:50px;">
                                                                            <th style="background: #81b431; color: white;" colspan="4">
                                                                                <?php echo $module['module_heading'] ?>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <?php if (!empty($module['module_chiled'])) {
                                                                        foreach ($module['module_chiled'] as $childRow) {
                                                                    ?>
                                                                            <tr style="background: white;">
                                                                                <td width="10%">
                                                                                    <div class="hr-register-date">
                                                                                        <input type="checkbox" name="module_name[]" value="<?php echo $childRow['slug']; ?>" class="hr-form-fileds" <?php if ($childRow['status']) { ?> checked="checked" <?php }; ?> />
                                                                                    </div>
                                                                                </td>
                                                                                <td width="40%"><?php echo $childRow['heading']; ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    } ?>
                                                                </table>
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <div class="add-new-promotions" style="text-align: right;">
                                                        <a href="<?= base_url() ?>manage_admin/companies/manage_company/<?php echo $companySid; ?>" class="site-btn">Back</a>
                                                        <input type="hidden" name="action" value="true">
                                                        <?php echo form_submit('submit', 'Save', 'class="site-btn"'); ?>
                                                    </div>
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