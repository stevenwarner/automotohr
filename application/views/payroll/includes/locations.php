<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu -->
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">				
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow csF16 csB7">Company Addresses</span>
                        </div>
                    </div>
                </div>    
                 <!-- Main -->
                 <div class="mainContent">
                    <div class="csPR">
                        <?php $this->load->view('loader_new', ['id' => 'company_locations']); ?>
                        <!--  -->
                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-right">
                                <button class="btn btn-success csF16 csB7 jsLocationAdd"><i class="fa fa-eye csF16" aria-hidden="true"></i>&nbsp;Add A Location</button>
                            </div>
                        </div>
                        <!-- -->
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th class="csBG1 csF16 csB7" scope="col">Id</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Country</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">State</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">City</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Zipcode</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Street 1</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Street 2</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Phone Number</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Last Modified</th>
                                            <th class="csBG1 csF16 csB7 text-right" scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsLocationBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.API_URL = "<?=getAPIUrl('locations');?>"; 
    window.API_KEY = "<?=getAPIKey();?>"; 
</script>