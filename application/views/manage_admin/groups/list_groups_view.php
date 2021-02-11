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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>                                    
                                    <?php if(check_access_permissions_for_view($security_details, 'create_admin_groups')){ ?>
                                        <div class="add-new-promotions">
                                            <a href="<?php echo site_url('manage_admin/groups/create'); ?>" class="site-btn">Add New Group</a>
                                        </div>
                                    <?php } ?>
                                    <!-- Group Start -->
                                    <div class="hr-promotions table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Group Name</th>
                                                    <th>Group Description</th>
                                                    <?php if(check_access_permissions_for_view($security_details, 'create_admin_groups')){ ?>
                                                        <th width="1%" class="actions" colspan="4">Actions</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php   if (!empty($groups)) {
                                                                foreach ($groups as $group) {
                                                                    echo '<tr id="parent_'. $group->id.'">';
                                                                    echo '<td>' . $group->id . '</td>
                                                                          <td>' . $group->name . '</td>
                                                                          <td>' . $group->description . '</td>';
                                                                    if(check_access_permissions_for_view($security_details, 'create_admin_groups')){
                                                                        if (!in_array($group->name, array('admin'))){
                                                                            echo '<td>' . anchor('manage_admin/groups/edit/' . $group->id, '<span class="hr-edit-btn">Edit</span>') . '</td>';                                                                     
                                                                            echo '<td><button class="hr-delete-btn" id="' . $group->id . '" onclick="deletegroup(' . $group->id . ')">Delete</button></td>';
                                                                        } else {
                                                                            echo'<td></td>';
                                                                            echo'<td></td>';
                                                                        }
                                                                    }
                                                                    echo '</tr>';
                                                                    echo '</td>';
                                                                    echo '</tr>';
                                                                }
                                                        } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Groups End -->
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
    function deletegroup(id) {
        url = "<?= base_url() ?>manage_admin/groups/delete";
        alertify.confirm('Confirmation', "Are you sure you want to delete this Group ?",
        function () {
            $.post(url, {action: 'delete', sid: id})
            .done(function (data) {
                //                               
                $("#parent_"+id).remove();
                alertify.success('Selected company have been Deleted.');            
            });  
        },
        function () {
            alertify.error('Canceled');
        });
    }
</script>