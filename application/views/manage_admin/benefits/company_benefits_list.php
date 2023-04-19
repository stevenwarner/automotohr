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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><?php echo $page_title; ?></h1>
                                    </div>
                                    <div>

                                        <div class="hr-promotions table-responsive">
                                            <div class="heading-title page-title">
                                                <div class="add-new-promotions">
                                                    <a class="site-btn pull-right" href="<?php echo base_url('manage_admin/benefits/add/'); ?>">Add New</a>
                                                </div>
                                            </div>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="col-xs-8">Name</th>
                                                        <th class="col-xs-8">Category</th>
                                                        <th class="col-xs-8">Type Number</th>
                                                        <th class="col-xs-8">Pretax</th>
                                                        <th class="col-xs-8">Posttax</th>
                                                        <th class="col-xs-8">Imputed</th>
                                                        <th class="col-xs-8">Health Care</th>
                                                        <th class="col-xs-8">Retirement</th>
                                                        <th class="col-xs-8">Yearly Limit</th>
                                                        <th width="1%" colspan="3" class="actions">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($benifits as $benifit) { ?>
                                                        <tr>
                                                            <td><?php echo $benifit['name'] ?></td>
                                                            <td><?php echo $benifit['category'] ?></td>
                                                            <td><?php echo $benifit['benefit_type'] ?></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['pretax'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['posttax'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['imputed'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['healthcare'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['retirement'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span></td>
                                                            <td class="text-center"><span class="<?php echo $benifit['yearly_limit'] == '1' ? ' glyphicon glyphicon-ok alert-success ' : ' glyphicon glyphicon-remove alert-danger ' ?>"> </span> </td>
                                                            <td>
                                                                <a href="<?php echo base_url('manage_admin/benefits/edit') . '/' . $benifit['sid']; ?>" class="hr-edit-btn">EDIT</a>
                                                            </td>
                                                            <td>
                                                                <form method="post" action="<?php echo base_url('manage_admin/benefits/add') ?>" id="form_delete_benefit_<?php echo $benifit['sid']; ?>">
                                                                    <input type="hidden" id="sid" name="sid" value="<?php echo $benifit['sid']; ?>" />
                                                                    <input type="hidden" id="status" name="status" value="<?php echo $benifit['status']; ?>" />
                                                                    <input type="hidden" id="action" name="action" value="enable_disable_benefit" />
                                                                </form>
                                                                <input type="button" id="btn-delete" onclick="fChangeStatus(this);" class=" <?php echo $benifit['status'] == 1 ? ' hr-delete-btn ' : ' hr-edit-btn ' ?>" value="<?php echo $benifit['status'] == 1 ? 'DISABLE' : 'ENABLE' ?>" data-sid="<?php echo $benifit['sid']; ?>" />
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
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
    </div>
    <script>
        function fChangeStatus(source) {
            var sid = $(source).attr('data-sid');
            alertify.confirm(
                'Are you Sure!',
                'Are you sure you want to change this status?',
                function() {
                    //yes
                    $('#form_delete_benefit_' + sid).submit();
                },
                function() {
                    //no
                }
            ).set({
                labels: {
                    'ok': 'Yes',
                    'cancel': 'No'
                }
            });
        }
    </script>