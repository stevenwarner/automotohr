<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="add-new-promotions">
                                        <p>Promotions allow you to offer discounts to users for certain products and services</p>
                                        <?php if(in_array('full_access', $security_details) || in_array('add_new_promotion', $security_details)){ ?>
                                            <a class="site-btn" href="<?php echo site_url('manage_admin/promotions/add_new_promotion'); ?>">Add a New Promotion Code</a>
                                        <?php } ?>
                                    </div>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Promotion Code</th>
                                                        <th>Discount</th>
                                                        <!--<th>Uses</th>-->
                                                        <th>Start Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Status</th>
                                                        <?php $function_names = array('edit_promotion', 'activate_deactivate_promotion', 'delete_promotion'); ?>
                                                        <?php if(check_access_permissions_for_view($security_details, $function_names)){ ?>
                                                            <th class="actions" colspan="4">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data as $key => $value) { ?>
                                                        <tr id="parent_<?= $value['sid'] ?>">
                                                            <td><?php echo $value['code']; ?></td>
                                                            <td>
                                                                <?php   if ($value['type'] == 'fixed') {
                                                                            echo "$" . $value['discount'];
                                                                        } else {
                                                                            echo $value['discount'] . "%";
                                                                        } ?>
                                                            </td>
                                                            <!-- <td><?php //echo $value['maximum_uses']; ?></td>-->
                                                            <td>
                                                                <?php   if ($value['start_date'] != '0000-00-00 00:00:00') {
                                                                            echo date_with_time($value['start_date']);
                                                                        } else {
                                                                            echo '---------------';
                                                                        } ?>
                                                            </td>
                                                            <td>
                                                                <?php   if ($value['end_date'] != '0000-00-00 00:00:00') {
                                                                            echo date_with_time($value['end_date']);
                                                                        } else {
                                                                            echo '---------------';
                                                                        } ?>
                                                            </td>
                                                            <td>
                                                                <?php   if ($value['active'] == 0) {
                                                                            echo "Not Active";
                                                                        } else {
                                                                            echo "Active";
                                                                        } ?>
                                                            </td>
                                                            <?php if(check_access_permissions_for_view($security_details, 'activate_deactivate_promotion')){ ?>
                                                                <td class="text-center">
                                                                    <?php if ($value['active'] == 0) { ?>
                                                                                <input class="hr-edit-btn" type="button"  id="<?= $value["sid"] ?>" onclick="return todo('activate', this.id)"  value="Activate">
                                                                    <?php } else { ?>
                                                                                <input class="hr-delete-btn" type="button"  id="<?= $value["sid"] ?>" onclick="return todo('deactivate', this.id)"  value="Deactivate">
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if(check_access_permissions_for_view($security_details, 'edit_promotion')){ ?>
                                                                <td class="text-center">
                                                                    <?php echo anchor('manage_admin/promotions/edit_promotion/' . $value['sid'], '<input class="hr-edit-btn" type="button" value="Edit">'); ?>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if(check_access_permissions_for_view($security_details, 'delete_promotion')){ ?>
                                                                <td class="text-center">
                                                                    <input class="hr-delete-btn" type="button"  value="Delete" id="<?= $value['sid'] ?>" onclick="return todo('delete', this.id)" name="button">
                                                                </td>
                                                            <?php } ?>
                                                            <!--<td><input class="hr-edit-btn" type="button"  value="View Log"></td>-->
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
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
</div>
<script>
    function todo(action, id) {
        url = "<?= base_url() ?>manage_admin/promotions/promotion_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Promotion?",
                function () {
                    $.post(url, {action: action, sid: id})
                            .done(function (data) {

                                if (action == "delete") {
                                    alertify.success('Selected promotion have been ' + action + 'd.');
                                    $("#parent_" + id).remove();
                                }
                                else {
                                    location.reload();
                                }
                            });

                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>