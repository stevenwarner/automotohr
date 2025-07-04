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
                                        <h1 class="page-title"><i class="fa fa-shopping-cart"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'add_new_product')){ ?>
                                        <div class="add-new-promotions">
                                            <a class="site-btn" href="<?php echo base_url('manage_admin/products/add_new_product'); ?>">Add New Product</a>
                                        </div>
                                    <?php } ?>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Charge Per Day</th>
                                                        <th>Expiry Days</th>
                                                        <th>Price</th>
                                                        <th>Cost</th>
                                                        <th>Listings Posted</th>
                                                        <th>Status</th>
                                                        <th>Sort Order</th>
                                                        <?php $function_names = array('add_new_product', 'edit_product', 'clone_product', 'activate_deactivate_products'); ?>
                                                        <?php if(check_access_permissions_for_view($security_details, $function_names)){ ?>
                                                            <th width="1%" class="actions" colspan="4">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody><?php   foreach ($db_products as $product) {
                                                                    $data = array('value' => 'true', 'content' => 'Delete', 'class' => 'hr-delete-btn', 'id' => $product->sid, 'onclick' => "return todo('delete', this.id);");
                                                                    $delete_btn = form_button($data);
                                                                    
                                                                    if ($product->active != 1) {
                                                                        $product_status = "Not Active";
                                                                        $data = array('value' => 'true', 'content' => 'Activate', 'class' => 'hr-edit-btn', 'id' => $product->sid, 'onclick' => "return todo('activate', this.id);");
                                                                        $active_btn = form_button($data);
                                                                    } else {
                                                                        $product_status = "Active";
                                                                        $data = array('value' => 'true', 'content' => 'Deactivate', 'class' => 'hr-delete-btn', 'id' => $product->sid, 'onclick' => "return todo('deactivate', this.id);");
                                                                        $active_btn = form_button($data);
                                                                    }

                                                                    echo '<tr id="parent_' . $product->sid . '">';
                                                                    echo '<td>' . $product->sid . '</td>
                                                                          <td><strong>' . $product->name . '</strong></td>
                                                                          <td>' . ($product->daily == 1 ? 'Yes' : 'No') . '</td>
                                                                          <td>' . $product->expiry_days . '</td>
                                                                          <td>$' . $product->price . '</td>
                                                                          <td>$';
                                                                    echo ($product->cost_price == NULL || $product->cost_price == '') ? '0' : $product->cost_price;
                                                                    echo '</td>
                                                                          <td>' . $product->number_of_postings . '</td>
                                                                          <td>' . $product_status . '</td>' .
                                                                         '<td>' . $product->sort_order . '</td>';
                                                                    
                                                                    if(check_access_permissions_for_view($security_details, 'activate_deactivate_products')){
                                                                        echo '<td>' . @$active_btn . '</td>';
                                                                    }
                                                                    
                                                                    if(check_access_permissions_for_view($security_details, 'edit_product')){
                                                                        echo '<td><a class="hr-edit-btn" title="Edit" href="' . base_url('manage_admin/products/edit/' . $product->sid) . '">Edit</a></td>';
                                                                    }
                                                                    
                                                                    if(check_access_permissions_for_view($security_details, 'clone_product')){
                                                                        echo '<td>' . anchor('manage_admin/products/clone_product/' . $product->sid, '<span>Clone</span>', array('class' => 'hr-edit-btn')) . '</td>';
                                                                    }
                                                                    echo '</tr>';
                                                                    $del_btn = '';
                                                                } ?>
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
        url = "<?= base_url() ?>manage_admin/products/product_task";
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