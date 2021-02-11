
<?php echo $this->session->flashdata('message'); ?>
<div class="dash-inner-block">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            <div class="heading-title page-title">
                <h1 class="page-title"><i class="fa fa-shopping-cart"></i>Products</h1>
            </div>
            <div class="add-new-promotions">
                <a class="site-btn" href="<?php echo base_url('admin/products/add_new_product'); ?>">Add New Product</a>
            </div>
            <!-- Products Start -->
            <div class="hr-promotions table-responsive">
                <form method="post">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>User Group</th>
                                <th>Price</th>
                                <th>Subscribed Users</th>
                                <th>Listings Posted</th>
                                <th>Status</th>
                                <th width="1%" class="actions" colspan="4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                           foreach($db_products as $product)
                           {
                               if($product->product_active!=1)
                                   {
                                   $product_status="Not Active"; 
                                  
                                   $del_btn=anchor('admin/products/delete/'.$product->product_id,'<span>Delete</span>',array('class'=>'hr-delete-btn', 'onclick'=>"if (!confirm('Are you sure?')) return false;"));
                                   
                                 $active_btn=anchor('admin/products/activate/'.$product->product_id,'<span>Activate</span>',array('class'=>'hr-edit-btn'));
                                   }
                               else{
                                   $product_status="Active";
                                   $active_btn=anchor('admin/products/deactivate/'.$product->product_id,'<span>deactivate</span>',array('class'=>'hr-delete-btn'));
                                   }
                               
                       echo '<tr>
                                <td>'.$product->product_id.'</td>
                                <td><strong>'.$product->product_name.'</strong></td>
                                <td>Mixed Product</td>
                                <td>'.$product->product_user_group.'</td>
                                <td>$'.$product->product_price.'</td>
                                <td>75</td>
                                <td>1000</td>
                                <td>'.$product_status.'</td>
                                <td>'.@$active_btn.'</td>
                                <td><a class="hr-edit-btn" title="Edit" href="'.  base_url('admin/products/edit/'.$product->product_id).'">Edit</a></td>
                                <td>'.@$del_btn.'</td>
                                <td>'.anchor('admin/products/clone_product/'.$product->product_id,'<span>Clone</span>',array('class'=>'hr-edit-btn')).'</td>
                            </tr>';
                       
                       $del_btn='';
                            }?>
                             
                        </tbody>
                    </table>
                </form>
            </div>
            <!-- Products End -->
        </div>
    </div>
</div>