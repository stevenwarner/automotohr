<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>My Jobs</span>
                    </div>
                    <div class="applicant-filter">
                        <div class="row">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">  
                                <div class="filter-form-wrp">
                                    <span>Search Jobs:</span>
                                    <div class="tracking-filter">
                                        <form method="GET" id="jobs_filter" name="jobs_filter" action="<?= base_url() ?>application_tracking_system/active/all/all/all/all">
                                            <input type="text" class="invoice-fields search-job" placeholder="Search job">  
                                            <input class="form-btn" type="submit" value="Search">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                                <a href="<?= base_url() ?>manual_candidate" class="page-heading">+ Create New Job</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-outer">
                        <form method="POST" id="multiple_actions_employer" name="multiple_actions">
                            <div class="table-wrp">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="check_all"></th>
                                            <th width="40%">Job Title</th>
                                            <th>Posted On</th>
                                            <th>Active</th>
                                            <th>Job Views</th>
                                            <th width="1%" colspan="4" class="last-col">Actions</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <!--All records-->                                      <tr>
                                            <td><input type="checkbox"></td>
                                            <td width="40%">Retail Clothing Sales Specialist - TEAM LATUS MOTORS HARLEY-DAVIDSON Gladstone OR</td>
                                            <td>12-07-2015</td>
                                            <td>Yes</td>
                                            <td>6</td>
                                            <td>
                                                <a class="action-btn" href="javascript:;">
                                                    <i class="fa fa-pencil"></i>
                                                    <span class="btn-tooltip">Edit Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn remove" href="javascript:;">
                                                    <i class="fa fa-remove"></i>
                                                    <span class="btn-tooltip">Delete Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn activate" href="javascript:;">
                                                    <i class="fa fa-ban"></i>
                                                    <span class="btn-tooltip">Deactivate Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn clone-job" href="javascript:;">
                                                    <i class="fa fa-copy"></i>
                                                    <span class="btn-tooltip">Clone Job</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td width="40%">Retail Clothing Sales Specialist - TEAM LATUS MOTORS HARLEY-DAVIDSON Gladstone OR</td>
                                            <td>12-07-2015</td>
                                            <td>Yes</td>
                                            <td>6</td>
                                            <td>
                                                <a class="action-btn" href="javascript:;">
                                                    <i class="fa fa-pencil"></i>
                                                    <span class="btn-tooltip">Edit Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn remove" href="javascript:;">
                                                    <i class="fa fa-remove"></i>
                                                    <span class="btn-tooltip">Delete Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn deactivate" href="javascript:;">
                                                    <i class="fa fa-check"></i>
                                                    <span class="btn-tooltip">Activate</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn clone-job" href="javascript:;">
                                                    <i class="fa fa-copy"></i>
                                                    <span class="btn-tooltip">Clone Job</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td width="40%">Retail Clothing Sales Specialist - TEAM LATUS MOTORS HARLEY-DAVIDSON Gladstone OR</td>
                                            <td>12-07-2015</td>
                                            <td>Yes</td>
                                            <td>6</td>
                                            <td>
                                                <a class="action-btn" href="javascript:;">
                                                    <i class="fa fa-pencil"></i>
                                                    <span class="btn-tooltip">Edit Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn remove" href="javascript:;">
                                                    <i class="fa fa-remove"></i>
                                                    <span class="btn-tooltip">Delete Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn activate" href="javascript:;">
                                                    <i class="fa fa-ban"></i>
                                                    <span class="btn-tooltip">Deactivate Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn clone-job" href="javascript:;">
                                                    <i class="fa fa-copy"></i>
                                                    <span class="btn-tooltip">Clone Job</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td width="40%">Retail Clothing Sales Specialist - TEAM LATUS MOTORS HARLEY-DAVIDSON Gladstone OR</td>
                                            <td>12-07-2015</td>
                                            <td>Yes</td>
                                            <td>6</td>
                                            <td>
                                                <a class="action-btn" href="javascript:;">
                                                    <i class="fa fa-pencil"></i>
                                                    <span class="btn-tooltip">Edit Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn remove" href="javascript:;">
                                                    <i class="fa fa-remove"></i>
                                                    <span class="btn-tooltip">Delete Job</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn deactivate" href="javascript:;">
                                                    <i class="fa fa-check"></i>
                                                    <span class="btn-tooltip">Activate</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="action-btn clone-job" href="javascript:;">
                                                    <i class="fa fa-copy"></i>
                                                    <span class="btn-tooltip">Clone Job</span>
                                                </a>
                                            </td>
                                        </tr>                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="btn-panel">                                
                                <a class="delete-all-btn" href="javascript:;">Delete Selected</a>
                                <a class="delete-all-btn active-btn" href="javascript:;">ACTIVATE</a>
                                <a class="delete-all-btn deactive-btn" href="javascript:;">DEACTIVATE</a>                                
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>