<!-- Main Start -->
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>My HR Docs</span>
                        </div>
                        <div class="btn-panel">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <a class="page-heading" href="javascript:;">+ Add Document</a>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                    <a class="page-heading" href="javascript:;">Archived Document</a>
                                </div>
                            </div>
                        </div>
                        <div class="create-job-wrap">
                            <div class="table-responsive">
                                <div class="hr-document-list">
                                    <table class="hr-doc-list-table">
                                        <thead>
                                            <tr>                                                
                                                <th width="30%">Document Name</th>
                                                <th width="20%">Type&nbsp;[?]</th>
                                                <th width="15%">Included In <br>Onboarding&nbsp;[?]</th>
                                                <th width="15%">Action <br>Required&nbsp;[?]</th>
                                                <th width="20%"></th>    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo STORE_NAME; ?></td>
                                                <td>Word Doc</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch-on-boarding" checked>
                                                        <label class="onoffswitch-label" for="switch-on-boarding">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="action-switch">
                                                        <label class="onoffswitch-label" for="action-switch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="archive-btn-wrp">
                                                        <a class="action-btn more-action-btn" href="javascript:;">more</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="javascript:;"><i class="fa fa-trash"></i> Archive</a></li>
                                                        </ul>
                                                        <a href="javascript:;" class="action-btn">
                                                            <i class="fa fa-download"></i>
                                                            <span class="btn-tooltip">Download</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo STORE_NAME; ?></td>
                                                <td>Word Doc</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch-on-boarding" checked>
                                                        <label class="onoffswitch-label" for="switch-on-boarding">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="action-switch">
                                                        <label class="onoffswitch-label" for="action-switch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="archive-btn-wrp">
                                                        <a class="action-btn more-action-btn" href="javascript:;">more</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="javascript:;"><i class="fa fa-trash"></i> Archive</a></li>
                                                        </ul>
                                                        <a href="javascript:;" class="action-btn">
                                                            <i class="fa fa-download"></i>
                                                            <span class="btn-tooltip">Download</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo STORE_NAME; ?></td>
                                                <td>Word Doc</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch-on-boarding" checked>
                                                        <label class="onoffswitch-label" for="switch-on-boarding">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="action-switch">
                                                        <label class="onoffswitch-label" for="action-switch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="archive-btn-wrp">
                                                        <a class="action-btn more-action-btn" href="javascript:;">more</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="javascript:;"><i class="fa fa-trash"></i> Archive</a></li>
                                                        </ul>
                                                        <a href="javascript:;" class="action-btn">
                                                            <i class="fa fa-download"></i>
                                                            <span class="btn-tooltip">Download</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo STORE_NAME; ?></td>
                                                <td>Word Doc</td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch-on-boarding" checked>
                                                        <label class="onoffswitch-label" for="switch-on-boarding">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="action-switch">
                                                        <label class="onoffswitch-label" for="action-switch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="archive-btn-wrp">
                                                        <a class="action-btn more-action-btn" href="javascript:;">more</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="javascript:;"><i class="fa fa-trash"></i> Archive</a></li>
                                                        </ul>
                                                        <a href="javascript:;" class="action-btn">
                                                            <i class="fa fa-download"></i>
                                                            <span class="btn-tooltip">Download</span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tick-list-box width-100">
                                <h2>About HR Docs</h2>
                                <ul>
                                    <li>Automated HR document distribution</li>
                                    <li>Tracking of receipt/acknowledgment</li>
                                    <li>Upload docs for new hires and employees</li>
                                    <li>Unlimited document storage</li>
                                    <li>Revokes access for terminated employees</li>
                                    <li>Supports Adobe PDF&reg; and Microsoft Word&reg;</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.more-action-btn').click(function(){
            $(this).next().slideToggle();
        });
    });
</script>