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
                                         <a href="<?php echo base_url('manage_admin/companies/add_approver/' . $companySid); ?>" class="site-btn pull-right" style="font-size: 14px;"><i class="fa fa-plus"></i>Add Approver</a>
                                    </div>
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?= $flag ? "styles='display:block'" : "" ?>>
                                        <form method="GET" action="">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Approver</label>
                                                    <div>
                                                        <select id="js-filter-approvers">
                                                            <option value="all">All</option>
                                                            <?php 
                                                                if(sizeof($employees)){
                                                                    foreach ($employees as $k => $v) {
                                                                        echo '<option '.( $approver == $v['sid'] ? 'selected="true"' : '' ).' value="'.( $v['sid'] ).'">'.( $v['first_name'].' '.$v['last_name'] ).( $v['job_title'] != '' && $v['job_title'] != null ? ' ('.$v['job_title'].')' : '' ).' ['.( remakeAccessLevel($v) ).']'.'</option>';
                                                                    }
                                                                }
                                                             ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <label>Status</label>
                                                    <div>
                                                        <select name="status" id="js-filter-status">
                                                            <option <?= $status == 'all' ? 'selected="true"' : '';?> value="all">All</option>
                                                            <option <?= $status == '0' ? 'selected="true"' : '';?> value="0">Active</option>
                                                            <option <?= $status == '1' ? 'selected="true"' : '';?> value="1">Deativated</option>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                               
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                                                    <a href="javascript:;" id="btn_apply_filters" class="btn btn-success">Search</a>
                                                    <a href="<?php echo base_url('manage_admin/companies/timeoff_approvers'); ?>/<?=$companySid;?>" class="btn btn-success">Clear</a>
                                                </div>
                                            
                                        </form>
                                    </div>
                                    <!-- Pagination -->
                                    <form name="users_form" method="post">
                                        <div class="hr-box-header">
                                            <div class="hr-items-count">
                                                <strong><?php echo $total; ?></strong> Approvers
                                            </div>
                                            
                                            <?php echo $links; ?>
                                        </div>
                                    </form>
                                    <!-- Data Table -->
                                    <div class="table-responsive table-outer">
                                        <div class="hr-displayResultsTable">
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Approver Name</th>
                                                            <th>Department</th>
                                                            <th>Created At</th>
                                                            <th>Status</th>
                                                            <th class="text-center" colspan="4">Actions</th>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            if(sizeof($companies)){
                                                                foreach ($companies as $k => $v) {
                                                                    echo '<tr data-id="'.( $v['approver_id'] ).'">';
                                                                    echo '  <td>'.( $v['approver_id'] ).'</td>';
                                                                    echo '  <td>'.( $v['full_name'] ).'</td>';
                                                                    echo '  <td>'.( $v['department_name'] == null ? 'All Departments' : $v['department_name'] ).'</td>';
                                                                    echo '  <td>'.( $v['created_at'] ).'</td>';
                                                                    echo '  <td class="js-status '.( $v['is_archived'] == 1 ? 'text-danger' : 'text-success' ).'">'.( $v['is_archived'] == 1 ? '<strong>Deactivated</strong>' : '<strong>Activated</strong>' ).'</td>';
                                                                    echo '  <td>';
                                                                    if($v['is_archived'] == 0){
                                                                        echo '  <a href="javascript:void(0)" class="btn btn-warning js-deactivate-approver" title="Deactivate approver"><i class="fa fa-shield"></i></a>';
                                                                    } else
                                                                        echo '  <a href="javascript:void(0)" class="btn btn-success js-activate-approver" title="Activate approver"><i class="fa fa-shield"></i></a>';
                                                                    echo '      <a href="'.( base_url('manage_admin/companies/edit_approver/'.( $companySid ).'/'.( $v['approver_id'] ).'') ).'" class="btn btn-success"><i class="fa fa-pencil"></i></a>';
                                                                    echo '  </td>';
                                                                    echo '</tr>';
                                                                }
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="execute" value="multiple_action">
                                                <input type="hidden" id="type" name="type" value="company">
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Pagination -->
                                    <form name="users_form" method="post">
                                        <div class="hr-box-header hr-box-footer">
                                            <div class="hr-items-count">
                                                <strong><?php echo $total; ?></strong> Approvers
                                            </div>
                                            <!-- Pagination Start -->
                                            <?php echo $links; ?>
                                            <!-- Pagination End -->
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
<script type="text/javascript">
    function generate_search_url() {
        var approver = $("#js-filter-approvers").val();
        var status = $("#js-filter-status").val();

        status = encodeURIComponent(status) ;
        approver = encodeURIComponent(approver) ;
       
        var url = '<?php echo base_url('manage_admin/companies/timeoff_approvers'); ?>/<?=$companySid;?>' + '/' + approver + '/' + status;

        $('#btn_apply_filters').attr('href', url);
    }
    $(document).ready(function(){
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
       
        $("#js-filter-status, #js-filter-approvers").change(generate_search_url);
    });
</script>


<script>
    $(function(){
        $('#js-filter-approvers').select2();
        $('#js-filter-status').select2();

        //
        $(document).on('click', '.js-activate-approver', function(e){
            e.preventDefault();
            var sid = $(this).closest('tr').data('id');
            var _this = $(this);
           
            alertify.confirm('Do you really want to Activate this approver?', function(){
                $.post("<?=base_url('manage_admin/companies/activate_approver');?>", {
                    approverSid: sid 
                }, function(resp) {
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response);
                    //
                    _this.closest('tr')
                    .find('a.js-activate-approver')
                    .removeClass('btn-success')
                    .removeClass('js-activate-approver')
                    .addClass('btn-warning')
                    .addClass('js-deactivate-approver')
                    .prop('title', 'Deactivate Approver');
                    //
                    _this.closest('tr')
                    .find('td.js-status')
                    .removeClass('text-danger')
                    .addClass('text-success')
                    .html('<strong>Activated</strong>');
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        //
        $(document).on('click', '.js-deactivate-approver', function(e){
            e.preventDefault();
            var sid = $(this).closest('tr').data('id');
            var _this = $(this);
           
            alertify.confirm('Do you really want to Deactivate this approver?', function(){
                $.post("<?=base_url('manage_admin/companies/deactivate_approver');?>", {
                    approverSid: sid 
                }, function(resp) {
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response);
                    //
                    _this.closest('tr')
                    .find('a.js-deactivate-approver')
                    .removeClass('btn-warning')
                    .removeClass('js-deactivate-approver')
                    .addClass('btn-success')
                    .addClass('js-activate-approver')
                    .prop('title', 'Activate Approver');
                    //
                    _this.closest('tr')
                    .find('td.js-status')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html('<strong>Deactivated</strong>');
                });
            });
        }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    })
</script>

<style>
    .select2-container, .select2-drop, .select2-search, .select2-search input{ width: 100%; }
</style>